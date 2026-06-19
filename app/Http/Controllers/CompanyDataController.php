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
        return view('assigned.index');
    
        }








}
