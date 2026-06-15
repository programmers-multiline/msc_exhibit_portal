<?php

namespace App\Http\Controllers;


use App\Models\Participants;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\ParticipantImage;
use App\Models\ParticipantsUpdate;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Models\ExternalUser;
use App\Models\AssignedAgent;
use App\Models\AssignedAgentLog;

use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ParticipantsImport;
use Illuminate\Support\Facades\Auth;

use App\Mail\ParticipantBrochureMail;
use App\Models\Company;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\ParticipantFile;
use App\Models\product_list;




class ParticipantController extends Controller
{

  /*  public function index(Request $request)
    {
       
        if ($request->ajax()) {

            return DataTables::of(Participants::with(['images', 'company'])->orderBy('id', 'desc'))
            //Participants::with(['images', 'company'])->orderBy('id', 'desc')

               ->addColumn('checkbox', function($row){
                    // ✅ If may assigned PSC → display PSC name
                    if(!empty($row->assigned_psc)){
                        return '<span class="badge bg-success text-white">
                                    PSC: '.$row->assigned_psc.'
                                </span>';
                    }

                    // ❌ If wala pa → show checkbox
                    return '<input type="checkbox"
                            class="participant_checkbox"
                            value="'.$row->company_id.'">';
                })

                ->addColumn('participant_photo', function($row){

                    if($row->images->count() > 0){

                        $img = $row->images->first();

                        return '<img
                        src="'.asset('storage/participants/'.$img->image_name).'"
                        width="30"
                        height="30"
                        class="img-thumbnail viewImages"
                        data-id="'.$row->id.'"
                        style="cursor:pointer">';

                    }

                    return 'No Image';

                })

               ->addColumn('name_position', function($row){
    return $row->participant_name . '<br>' . $row->participant_position;
})

              ->addColumn('action', function($row){

                    $status      = $row->status ?? 'N/A';
                    $description = $row->description ?? 'N/A';
                    $lastUpdate  = $row->last_update_date
                                    ? date('Y-m-d H:i', strtotime($row->last_update_date))
                                    : 'N/A';

                        return '
                            <div style="min-width:250px">
                                <div>
                                    <strong>Status:</strong>
                                    <span class="badge bg-info text-white">'.$status.'</span>
                                </div>
                                <div>
                                    <strong>Description:</strong>
                                    <span>'.$description.'</span>
                                </div>
                                <div>
                                    <strong>Last Update:</strong>
                                    <span>'.$lastUpdate.'</span>
                                </div>

                                <hr>

                                <button
                                    class="btn btn-sm btn-primary btnUpdateStatus"
                                    data-id="'.$row->id.'">
                                    Update Status
                                </button>

                            </div>
                        ';
                    })
              
               ->rawColumns(['name_position','checkbox','participant_photo','action'])
                ->make(true);
        }
  $users = ExternalUser::getUsersWithCompanyAndDepartment();

    return view('participants.index', compact('users'));
       // return view('participants.index');
    } */

       public function index(Request $request)
{
    if ($request->ajax()) {

        $participants = Participants::select(
        'participants.*',
        'company_list.company_name as company_name',
        'users.name as entry_by_name',
        'assigned_agent.psc_name  as psc_name'
    )
            ->leftJoin('company_list', 'participants.company_id', '=', 'company_list.id')
            ->leftJoin('users', 'participants.entry_by', '=', 'users.emp_id')
            ->leftJoin('assigned_agent','participants.id','=','assigned_agent.company_id')
            ->with('images')
            ->orderBy('participants.id', 'desc');

        return DataTables::of($participants)



        ->addColumn('checkbox', function($row){

                // 1. Check kung may assigned PSC
                if (!empty($row->psc_name)) {
                    return '<span class="badge bg-success text-white">
                                PSC: '.$row->psc_name.'
                            </span>';
                }

                // 2. Check kung ang position_id ng naka-login na user ay 13
                else if (in_array(auth()->user()->position_id, [13, 237])) {
                    return '<input type="checkbox"
                            class="participant_checkbox"
                            value="'.$row->company_id.'">';
                }

                // 3. Default kapag hindi pumasa sa mga condition sa itaas
                else {
                    return '--';
                }

                })

            ->addColumn('participant_photo', function($row){

                if ($row->images->count() > 0) {

                    $img = $row->images->first();

                    return '<img
                        src="'.asset('storage/participants/'.$img->image_name).'"
                        width="30"
                        height="30"
                        class="img-thumbnail viewImages"
                        data-id="'.$row->id.'"
                        style="cursor:pointer">';
                }

                return 'No Image';
            })

            ->addColumn('name_position', function($row){
                return $row->participant_name . '<br>' . $row->participant_position;
            })

            // ✅ NEW COLUMN: COMPANY NAME
            ->addColumn('company_name', function($row){
                return $row->company_name ?? 'No Company';
            })

            ->addColumn('action', function($row){

                $status      = $row->status ?? 'N/A';
                $description = $row->description ?? 'N/A';
                $lastUpdate  = $row->last_update_date
                    ? date('Y-m-d H:i', strtotime($row->last_update_date))
                    : 'N/A';

                return '
                    <div style="min-width:250px">
                        <div>
                            <strong>Status:</strong>
                            <span class="badge bg-info text-white">'.$status.'</span>
                        </div>

                        <div>
                            <strong>Description:</strong>
                            <span>'.$description.'</span>
                        </div>

                        <div>
                            <strong>Last Update:</strong>
                            <span>'.$lastUpdate.'</span>
                        </div>

                        <hr>

                        <button
                            class="btn btn-sm btn-primary btnUpdateStatus"
                            data-id="'.$row->id.'">
                            Update Status
                        </button>
                    </div>
                ';
            })

            ->rawColumns([
                'checkbox',
                'participant_photo',
                'name_position',
                'company_name',
                'action'
            ])
            ->make(true);
    }

    $users = ExternalUser::getUsersWithCompanyAndDepartment();

    return view('participants.index', compact('users'));
}

public function create(Request $request)
{
 $user = Auth::user();

    if(!$user){
        return redirect('/login');
    }
    $companies = \App\Models\Company::orderBy('company_name')->get();

      // $companies = Company::all();
    $users = User::all();


    $addresses = DB::table('ph_address')
    ->whereIn('geographic_level', ['City', 'Prov'])
    ->orderBy('address_name', 'ASC')
    ->get();

  

    $selected_company_id   = $request->company_id;
    $selected_company_name = $request->company_name;

    return view('participants.create', compact('companies','users','selected_company_id','addresses','selected_company_name'));
}

public function add_participant(Request $request)
{
 $user = Auth::user();

    if(!$user){
        return redirect('/login');
    }
    $companies = \App\Models\Company::orderBy('company_name')->get();

      // $companies = Company::all();
    $users = User::all();


    $addresses = DB::table('ph_address')
    ->whereIn('geographic_level', ['City', 'Prov'])
    ->orderBy('address_name', 'ASC')
    ->get();


      //For Product List Display
$products = \App\Models\ProductList::orderBy('name')->get();
    //Ending Product List Display

    $selected_company_id   = $request->company_id;
    $selected_company_name = $request->company_name;
//dd($products);
    return view('participants.add_participant', compact('companies','users','selected_company_id','addresses','selected_company_name','products'));
}


public function attendee(Request $request)
{
    // 1️⃣ Save participant first
    $participant = Participants::create([
        'exhibit_name'         => $request->exhibit_name,
        'entry_by'             => $request->entry_by,
        'agent_company'        => $request->agent_company,
        'sales_manager'        => $request->sales_manager,
        'day_num'              => $request->day_num,
        'participant_name'     => $request->participant_name,
        'participant_email'    => $request->participant_email,
        'participant_company'  => $request->participant_company,
        'participant_position' => $request->participant_position,
        'participant_contact'  => $request->participant_contact,
        'participant_source'   => $request->participant_source,
        'participant_address'  => $request->participant_address,
        'participant_remarks'  => $request->participant_remarks,
    ]);

    // 2️⃣ Save uploaded images
    if ($request->hasFile('participant_photo')) {

        foreach ($request->file('participant_photo') as $file) {

        $path     = $file->store('participants', 'public');
        $filename = basename($path);

            ParticipantImage::create([
                'participant_id' => $participant->id,
                'image_name'     => $filename
            ]);
        }
    }

    return redirect('/participants')
           ->with('success', 'Participant added successfully!');
}

public function getImages($id)
{
    return ParticipantImage::where('participant_id',$id)->get();
}

//Use to update the Status of Exhibit Attendee
// Uploading ng file on the same table
    public function updateStatus(Request $request, $id)
{
    //Nilabas ko ito for better error return result
     $request->validate([
                        'status'        => 'required',
                        'customer_code' => 'required_if:status,10',
                        'files'         => 'required_if:status,9',
                        'files.*'       => 'file|mimes:pdf,jpg,png,jpeg|max:2048',
                    ]);

    try {

        DB::beginTransaction();

        $participant = Participants::findOrFail($id);
        $user        = Auth::user();

        ParticipantsUpdate::create([
            'participant_id' => $id,
            'status'         => $request->status,
            'description'    => $request->description,
            'updated_by'     => $user->emp_id,
            'update_date'    => now()
        ]);

        $participant->update([
            'last_update_date' => now(),
            'status'           => $request->status,
            'assigned_psc'     => $user->emp_id,
            'description'      => $request->description
        ]);

        // 🔥 ONLY RUN IF STATUS = 9
        if ((int)$request->status === 9) {

            /* if (!$request->hasFile('files')) {
                throw new \Exception("Signed proposal file is required.");
            } */

            foreach ($request->file('files') as $file) {

                $path = $file->store('participant_files', 'public');

                ParticipantFile::create([
                    'participant_id' => $id,
                    'file_path'      => $path,
                    'file_name'      => $file->getClientOriginalName(),
                    'file_type'      => $file->getClientMimeType(),
                    'uploaded_by'    => $user->emp_id,
                    'uploaded_at'    => now()
                ]);
            }
        }

         if ((int)$request->status === 10) {

           if (!$participant->company) {
                    throw new \Exception("No company linked to this participant.");
                }

              $participant->company()->update([
                        'customer_code' => $request->customer_code,
                        'updated_at'    => now()
                    ]);
            
            }

        DB::commit();

        return response()->json(['success' => true]);

    } catch (\Exception $e) {

        DB::rollback();

        return response()->json([
            'error' => $e->getMessage()
        ], 500);
    }
}


//Use to Assigned PSC
public function bulkAssign(Request $request)
{
    $request->validate([
        'participants' => 'required|array',
        'psc_id'       => 'required'
    ]);

    DB::beginTransaction();

    try {

        $user = Auth::user();

        // 🔥 Get PSC info
        $psc = User::where('emp_id', $request->psc_id)->first();

        if (!$psc) {
            throw new \Exception('PSC not found');
        }

        $psc_name = $psc->first_name . ' ' . $psc->last_name;

        foreach ($request->participants as $company_id) {

            $existing = AssignedAgent::where('company_id', $company_id)->first();

            if ($existing) {

                // ✅ LOG muna bago update
                AssignedAgentLog::create([
                    'company_id'      => $company_id,
                    'old_psc_emp_id'  => $existing->psc_emp_id,
                    'old_psc_name'    => $existing->psc_name,
                    'new_psc_emp_id'  => $request->psc_id,
                    'new_psc_name'    => $psc_name,
                    'changed_by'      => $user->emp_id,
                    'created_at'      => now()
                ]);

                // ✅ UPDATE existing
                $existing->update([
                    'psc_emp_id'  => $request->psc_id,
                    'psc_name'    => $psc_name,
                    'assigned_by' => $user->emp_id,
                    'company_id'  => $company_id,
                    'updated_at'  => now()
                ]);
              // dd($request->psc_id); 

              //Update assigned PSC
            $participantIds = $request->participants;
            $pscId          = $request->psc_id;
                Participants::whereIn('id', $participantIds)
                ->update([
                    'assigned_psc' => $pscId 
                        ]);

            } else {

                //dd($company_id);
                // ✅ INSERT new
                AssignedAgent::create([
                    'company_id'  => $company_id,
                    'psc_emp_id'  => $request->psc_id,
                    'psc_name'    => $psc_name,
                    'assigned_by' => $user->emp_id,
                    'created_at'  => now(),
                    'updated_at'  => now()
                ]);
            }
        }

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'PSC Assigned/Updated Successfully'
        ]);

    } catch (\Exception $e) {

        DB::rollback();

        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}


public function importFromGoogleSheet()
{
    $url = "https://docs.google.com/spreadsheets/d/e/2PACX-1vQj6TJXC4jzfnTzSwa1FhvTugOq5QIwZ-uD6uSyc_ybKgnCbeXvPDWXLrWQCvyvFu9tLtxNkWsOq2Ha/pub?gid=916668348&single=true&output=csv";

    // Kunin ang CSV
    $response = Http::get($url);

    if (!$response->successful()) {
        return "Failed to fetch Google Sheet.";
    }

    $rows = array_map('str_getcsv', explode("\n", $response->body()));

    DB::beginTransaction();

    try {

        foreach (array_slice($rows, 1) as $row) {

            if(count($row) < 3) continue; // skip empty rows

            Participants::updateOrCreate(
                //['participant_email' => $row[6]], // avoid duplicate by email
                [
                    'exhibit_name'         => 'WorldBex' ?? null,
                    'entry_by'             => $row[1] ?? null,
                    'agent_company'        => $row[2] ?? null,
                    'sales_manager'        => $row[3] ?? null,
                    'day_num'              => $row[4] ?? null,
                    'participant_name'     => $row[5] ?? null,
                    'participant_email'    => $row[6] ?? null,
                    'participant_company'  => $row[7] ?? null,
                    'participant_position' => $row[8] ?? null,
                    'participant_contact'  => $row[9] ?? null,
                    'participant_source'   => $row[10] ?? null,
                    'participant_address'  => $row[11] ?? null,
                    'participant_remarks'  => $row[12] ?? null,
                ]
            );
        }

        DB::commit();
        return "Import Successful!";

    } catch (\Exception $e) {

        DB::rollBack();
        return $e->getMessage();
    }
}


//Import function
public function importExcel(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:xlsx,csv,xls'
    ]);

    $import = new ParticipantsImport;

    Excel::import($import, $request->file('file'));

    return response()->json([
        'success' => true,
        'count' => $import->rowsCount
    ]);
}


//Use to display contacts or participants in a separate page
/* public function contacts()
{
    $contacts = Participants::select(
        'exhibit_name',
        'entry_by',
        'agent_company',
        'day_num',
        'participant_name',
        'participant_email',
        'participant_company',
         'participant_position',
        'participant_contact',
        'participant_address',
        'participant_photo'
    )
    ->orderBy('participant_name', 'asc')
    ->get();

    return view('contacts.index', compact('contacts'));
} */

public function contacts()
{
    $contacts = Participants::select(
            'participants.id',
            'participants.exhibit_name',
            'participants.entry_by',
            'participants.day_num',
            'participants.participant_name',
            'participants.participant_email',
            'participants.participant_company',
            'participants.participant_position',
            'participants.participant_contact',
            'participants.participant_address',
            'participant_images.image_name'
        )
        ->leftJoin('participant_images', function ($join) {
            $join->on('participants.id', '=', 'participant_images.participant_id')
                ->whereRaw('participant_images.created_at = (
                    SELECT MAX(created_at)
                    FROM participant_images
                    WHERE participant_images.participant_id = participants.id
                )');
        })
        ->orderBy('participants.participant_name', 'asc')
        ->get();

    return view('contacts.index', compact('contacts'));
}

public function search(Request $request)
{
    $search = $request->search;

$contacts = Participants::query()
    ->when($search, function($query) use ($search) {
        $query->where(function($q) use ($search) {
            $q->where('participant_name', 'like', "%$search%")
              ->orWhere('participant_email', 'like', "%$search%")
              ->orWhere('participant_company', 'like', "%$search%")
              ->orWhere('assigned_psc', 'like', "%$search%");
        });
    })
    ->orderBy('participant_name')
    ->limit(50)
    ->get();


    return response()->json($contacts);
}



public function storeAjax(Request $request)
{
    $request->validate([
            'participant_type' => 'required',
            'participant_name' => 'required',
            'company_id'       => 'nullable|required_if:participant_type,Company'
    ]);

    $user = Auth::user();

    DB::beginTransaction();

    try {
             //Get active exhibit name
                $activeExhibit = DB::table('exhibit_names')
                 ->where('exhibit_status', 'Active')
                 ->first();
            //ending of Exhibit name


        if ($request->p_id) {

    // UPDATE
    $participant = \App\Models\Participants::find($request->p_id);

    if ($participant) {
        $participant->update([
            'company_id'           => $request->company_id,
            'entry_by'             => $user->emp_id,
            'day_num'              => now()->setTimezone('Asia/Manila')->format('Y-m-d'),
            'participant_type'     => $request->participant_type,
            'participant_name'     => $request->participant_name,
            'participant_email'    => $request->email,
            'participant_contact'  => $request->contact,
            'number_type'          => $request->number_type,
            'participant_position' => $request->participant_position,
            'participant_source'   => $request->participant_source,
            'participant_address'  => $request->address,
            'participant_remarks'  => $request->participant_remarks,
            'exhibit_name'         => $activeExhibit?->exhibit_name,
            'level_type'           => $request->level_type,
            'entry_from'           => 'Online'
        ]);
    }

} else {

    // INSERT
    $participant = \App\Models\Participants::create([
        'company_id'           => $request->company_id,
        'entry_by'             => $user->emp_id,
        'day_num'              => now()->setTimezone('Asia/Manila')->format('Y-m-d'),
        'participant_type'     => $request->participant_type,
        'participant_name'     => $request->participant_name,
        'participant_email'    => $request->email,
        'participant_contact'  => $request->contact,
        'number_type'          => $request->number_type,
        'participant_position' => $request->participant_position,
        'participant_source'   => $request->participant_source,
        'participant_address'  => $request->address,
        'participant_remarks'  => $request->participant_remarks,
        'exhibit_name'         => $activeExhibit?->exhibit_name,
        'level_type'           => $request->level_type,
        'entry_from'           => 'Online'
    ]);
}
//dd($request->all());
        //dd($request->product_inquiry);
    $productInquiry = collect($request->product_inquiry ?? [])->implode(', ');

        // 2. Insert attendees_list
        DB::table('attendees_list')->insert([
            'company_id'       => $request->company_id,
            'participant_id'   => $participant->id,
            'exhibit_name'     => $activeExhibit?->exhibit_name,
            'year_attended'    => now()->format('Y-m-d'),
            'product_inquiry'  => $productInquiry,
            'encoded_by'       => $user->emp_id,
            'agent_company_id' => $user->company_id,
            'created_at'       => now(),
            'updated_at'       => now(),
        ]);

        DB::table('company_list')
        ->where('id', $request->company_id)
        ->update([
            'level_type'         => $request->level_type,
            'city_province_code' => $request->city_province,
            'address'            => $request->address,
            'encoded_by'         => $user->emp_id,
            'created_at'         => now(),
            'updated_at'         => now(),
        ]);

        // 3. Upload images
        $uploadCount = 0;

        if ($request->hasFile('participant_photo')) {

            foreach ($request->file('participant_photo') as $file) {

                if ($file->isValid()) {

                    $path     = $file->store('participants', 'public');
                    $filename = basename($path);

                    $image = ParticipantImage::create([
                        'participant_id' => $participant->id,
                        'image_name'     => $filename
                    ]);

                    if ($image) {
                        $uploadCount++;
                    }

                } else {
                    throw new \Exception("Invalid image upload");
                }

            }

        }

        // ✅ Commit if all good
        DB::commit();

    } catch (\Exception $e) {

        // ❌ Rollback lahat
        DB::rollBack();

        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ], 500);
    }

    // ✅ Outside transaction (non-critical)
    try {
        Mail::to($request->email)
            ->queue(new ParticipantBrochureMail($participant));

        $emailStatus = "Email queued successfully";

    } catch (\Exception $e) {
        $emailStatus = "Email failed: " . $e->getMessage();
    }

    $UploadStatus = $uploadCount > 0
        ? $uploadCount . " image(s) uploaded successfully"
        : "No image uploaded";

    return response()->json([
        'success'      => true,
        'email_status' => $emailStatus,
        'UploadStatus' => $UploadStatus,
        'message'      => 'Participant saved successfully'
    ]);
}

public function checkDuplicate(Request $request)
{
    $duplicate = [];

    if($request->email){
        $emailExists = \App\Models\Participants::where('participant_email',$request->email)->exists();

        if($emailExists){
            $duplicate[] = 'Email';
        }
    }

    if($request->contact){
        $contactExists = \App\Models\Participants::where('participant_contact',$request->contact)->exists();

        if($contactExists){
            $duplicate[] = 'Contact Number';
        }
    }

    return response()->json([
        'exists' => count($duplicate) > 0,
        'fields' => $duplicate
    ]);
}

}
