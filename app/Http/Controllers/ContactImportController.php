<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
use App\Models\ContactDuplicate;
use Carbon\Carbon;
use App\Models\ExhibitName;
use App\Http\Controllers\UserLoginController;
use Illuminate\Support\Facades\Auth;


class ContactImportController extends Controller
{

    //
      // Ipapalabas ang upload form
    public function showForm()
    {
   
        return view('contacts/import');
    }

public function import(Request $request)
{
    $user = Auth::user();

    $request->validate([
        'file' => 'required|mimes:csv,txt|max:10240'
    ]);

    // 🟢 1. KUNIN ANG AKTIBONG EXHIBIT NAME
    // Palitan ang 'status' o 'is_active' depende sa eksaktong column name mo sa table
    $activeExhibit = ExhibitName::where('exhibit_status', 'Active')->first();
    
    // Tiyaking may active exhibit para maiwasan ang error, o magtakda ng default value
    $exhibitName = $activeExhibit ? $activeExhibit->exhibit_name : 'No Active Exhibit';

    $file = $request->file('file');
    $handle = fopen($file->getRealPath(), 'r');

    // Basahin at linisin ang headers
    $headers = fgetcsv($handle);
    if (!$headers) {
        fclose($handle);
        return response()->json(['status' => 'error', 'message' => 'Walang makuhang header sa CSV.'], 422);
    }
    $headers = array_map(function($header) {
        return strtolower(trim($header));
    }, $headers);

    $dateIndex = array_search('date', $headers);
    $timeIndex = array_search('time', $headers);

    $textIndex = array_search('text', $headers);
    if ($textIndex === false) {
        $textIndex = array_search('codecontent', $headers);
    }

    if ($textIndex === false) {
        fclose($handle);
        return response()->json(['status' => 'error', 'message' => 'Hindi nahanap ang vCard column.'], 422);
    }

    $totalUploaded = 0;
    $totalNew = 0;
    $totalDuplicate = 0;
    $totalSkipped = 0;

    while (($row = fgetcsv($handle)) !== FALSE) {
        if (empty($row) || !isset($row[$textIndex])) {
            continue;
        }

        $rawDate = ($dateIndex !== false && isset($row[$dateIndex])) ? trim($row[$dateIndex]) : null;
        $time = ($timeIndex !== false && isset($row[$timeIndex])) ? trim($row[$timeIndex]) : null;
        $vcardText = trim($row[$textIndex]);

        $date = null;

        if (!empty($rawDate)) {
            if (empty($time) && str_contains($rawDate, ' ')) {
                $dateTimeParts = explode(' ', $rawDate);
                $rawDate = $dateTimeParts[0];
                $time = $dateTimeParts[1] ?? null;
            }

            try {
                $date = Carbon::parse($rawDate)->format('Y-m-d');
            } catch (\Exception $e) {
                $date = null; 
            }
        }

        $parsedVcard = $this->parseVcard($vcardText);
        $name  = $parsedVcard['FN'] ?? null;
        $phone = $parsedVcard['CELL'] ?? null;

        if (empty($name) || empty($phone)) {
            $totalSkipped++;
            continue;
        }

        // 🟢 2. ISAMA ANG EXHIBIT NAME SA DATA ARRAY
        $contactData = [
            'entry_by'     => $user->emp_id,
            'exhibit_name' => $exhibitName,                    // <--- DITO IPAPASOK ANG AKTIBONG EXHIBIT
            'date'         => $date,
            'time'         => $time,
            'name'         => $name,
            'company'      => $parsedVcard['ORG'] ?? null,
            'title'        => $parsedVcard['TITLE'] ?? null,
            'phone'        => $phone,
            'email'        => $parsedVcard['EMAIL'] ?? null,
        ];

        // Suriin ang duplicate gamit ang Name, Phone, AT Exhibit Name para mas accurate
        $isDuplicate = Contact::where('name', $name)
                              ->where('phone', $phone)
                             // ->where('exhibit_name', $exhibitName) // <--- Opsyonal: I-check kung duplicate sa mismong exhibit na ito
                              ->exists();

        if ($isDuplicate) {
            ContactDuplicate::create($contactData);
            $totalDuplicate++;
        } else {
            Contact::create($contactData);
            $totalNew++;
        }

        $totalUploaded++;
    }

    fclose($handle);

    return response()->json([
        'status'          => 'success',
        'total_uploaded'  => $totalUploaded,
        'total_new'       => $totalNew,
        'total_duplicate' => $totalDuplicate,
        'total_skipped'   => $totalSkipped,
        'message'         => 'Matagumpay na natapos ang pagproseso sa iyong CSV file!'
    ], 200);
}




    private function parseVcard($vcardString)
    {
        $data = [];
        $lines = explode("\n", str_replace("\r", "", $vcardString));

        foreach ($lines as $line) {
            $line = trim($line);

            if (str_starts_with($line, 'FN:')) {
                $data['FN'] = trim(substr($line, 3));
            } elseif (str_starts_with($line, 'ORG:')) {
                $data['ORG'] = trim(substr($line, 4));
            } elseif (str_starts_with($line, 'TITLE:')) {
                $data['TITLE'] = trim(substr($line, 6));
            } elseif (str_contains($line, 'TEL;') && str_contains($line, 'cell:')) {
                $parts = explode(':', $line);
                $data['CELL'] = trim(end($parts));
            } elseif (str_contains($line, 'EMAIL;')) {
                $parts = explode(':', $line);
                $data['EMAIL'] = trim(end($parts));
            }
        }

        return $data;
    }
}
