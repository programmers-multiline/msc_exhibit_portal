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




class AssignedController extends Controller
{


       public function index(Request $request)
        {
         $user = Auth::user();
         $rawResults = DB::select("SELECT 
                                cm.id as company_id,
                                cm.company_name,
                                cm.address,
                                c.id as Contactid,
                                c.name as ContactPerson,
                                c.phone as ContactPhone,
                                c.email as ContactEmail,
                                a.psc_name as AgentName,
                                cu.status as ContactUpdate,
                                cu.description as UpdateRemarks,
                                cu.created_at as UpdateTime,
                                l.lead_status,
                                l.status_percentage

                            FROM company_list cm
                            LEFT JOIN contacts c ON c.company_id = cm.id 
                            LEFT JOIN contacts_update cu ON cu.company_id = cm.id
                            LEFT JOIN assigned_agent a ON a.company_id = cm.id
                            LEFT JOIN lead_agent_status l ON l.id = cu.status
                          WHERE a.psc_name IS NOT NULL OR a.psc_name<>''
                            ");

                             /*  --WHERE cm.company_name = 'ERGOTECH'  -- O gamitin ang dynamic filter mo */

// I-group ang mga contact persons sa loob ng iisang object ng kompanya
$companies = collect($rawResults)->groupBy('company_id')->map(function ($rows) {
    $first = $rows->first();
    //dd($first->UpdateRemarks);
    return [
        'company_id'        => $first->company_id,
        'company_name'      => $first->company_name,
        'address'           => $first->address ?? 'No Address',
        'AgentName'         => $first->AgentName ?? 'No Agent Assigned',
        'ContactUpdate'     => $first->ContactUpdate ?? 'No Update Yet',
        'lead_status'       => $first->lead_status ?? 'No Update Yet',
        'status_percentage' => $first->status_percentage ?? 'No Percent',
        'UpdateRemarks'     => $first->UpdateRemarks ?? 'No Remarks Available',
        'UpdateTime'        => $first->UpdateTime ?? '--',
        'contacts'          => $rows->map(function($row) {
            return [
                'id'    => $row->Contactid,
                'name'  => $row->ContactPerson,
                'phone' => $row->ContactPhone,
                'email' => $row->ContactEmail
            ];
        })->filter(fn($c) => !empty($c['name']))->values()
    ];
}); // 💡 TINANGGAL ANG ->first() DITO PARA MAKUHA LAHAT NG KOMPANYA

 $lead_agent_status = DB::table('lead_agent_status')->get(); 

return view('assigned.index', compact('companies','lead_agent_status'));
   
       // return view('assigned.index');
    
        }

       









}
