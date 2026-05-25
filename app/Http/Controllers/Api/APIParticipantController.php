<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Participants;
use App\Models\ParticipantImage;

class APIParticipantController extends Controller
{
    //
     public function attendee(Request $request)
    {
        try {

            // SAVE PARTICIPANT
            $participant = Participants::create([
                'exhibit_name'        => $request->exhibit_name,
                'entry_by'            => $request->entry_by,
                'day_num'             => $request->day_num,
                'participant_name'    => $request->participant_name,
                'participant_email'   => $request->participant_email,
                'participant_company' => $request->participant_company,
                'participant_contact' => $request->participant_contact,
                'participant_address' => $request->participant_address,
                'participant_remarks' => $request->participant_remarks,
                'company_id'          => $request->company_id,
               // 'sales_manager'        => $request->sales_manager,
               //  'participant_source'   => $request->participant_source,
                //  'participant_position' => $request->participant_position,
            ]);

            // SAVE IMAGES
            $uploadedImages = [];

            if ($request->hasFile('participant_photo')) {

                foreach ($request->file('participant_photo') as $file) {

                    $path = $file->store('participants', 'public');

                    $filename = basename($path);

                    $image = ParticipantImage::create([
                        'participant_id' => $participant->id,
                        'image_name'     => $filename
                    ]);

                    $uploadedImages[] = $image;
                }
            }

            return response()->json([
                'success'     => true,
                'message'     => 'Participant added successfully!',
                'participant' => $participant,
                'images'      => $uploadedImages
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getImages($id)
    {
        $images = ParticipantImage::where('participant_id', $id)->get();

        return response()->json([
            'success' => true,
            'data' => $images
        ]);
    }
}
