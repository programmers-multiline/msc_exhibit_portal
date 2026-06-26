<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    //
    public function index()
    {
       $reports = DB::table('attendance')
            ->selectRaw("
                YEAR(date) as year_per_participant,
                COUNT(DISTINCT CASE WHEN exhibit_name = 'WorldBex' THEN attendance.company_id END) as worldbex,
                COUNT(DISTINCT CASE WHEN exhibit_name = 'PHILCONSTRUCT' THEN attendance.company_id END) as philconstruct,
                COUNT(DISTINCT CASE WHEN exhibit_name = 'PHA' THEN attendance.company_id END) as pha,
                COUNT(DISTINCT attendance.company_id) as total_leads
            ")
            ->leftJoin('contacts_update', 'contacts_update.company_id', '=', 'attendance.company_id')
            ->leftJoin('lead_agent_status', 'lead_agent_status.id', '=', 'contacts_update.status')
            ->groupBy(DB::raw("YEAR(date)"))
            ->orderBy('year_per_participant', 'desc')
            ->get();


       // return view('reports.index', compact('reports'));

          $reports_per_WorldBex = DB::table('attendance')
            ->selectRaw("
                YEAR(date) AS year_per_exhibit,
                COUNT(DISTINCT CASE WHEN exhibit_name = 'WorldBex' THEN attendance.company_id END) AS worldbex_attendees,
                COUNT(DISTINCT CASE WHEN exhibit_name = 'WorldBex' AND lead_status='New Lead' THEN attendance.company_id END) AS 'New_Lead',
                COUNT(DISTINCT CASE WHEN exhibit_name = 'WorldBex' AND lead_status<>'New Lead' AND lead_status<>'Converted' THEN attendance.company_id END) AS 'Active_Leads',
                COUNT(DISTINCT CASE WHEN exhibit_name = 'WorldBex' AND lead_status='Converted' THEN attendance.company_id END) AS 'Converted',
                COUNT(DISTINCT CASE WHEN exhibit_name = 'WorldBex' THEN attendance.company_id END) AS total_leads
            ")
            ->leftJoin('contacts_update', 'contacts_update.company_id', '=', 'attendance.company_id')
            ->leftJoin('lead_agent_status', 'lead_agent_status.id', '=', 'contacts_update.status')
            ->groupBy(DB::raw("YEAR(date)"))
            ->orderBy('year_per_exhibit', 'desc')
            ->get();




          $reports_per_PhilConstruct = DB::table('attendance')
            ->selectRaw("
                YEAR(date) AS year_per_exhibit,
                COUNT(DISTINCT CASE WHEN exhibit_name = 'PhilConstruct' THEN attendance.company_id END) AS PhilConstruct_attendees,
                COUNT(DISTINCT CASE WHEN exhibit_name = 'PhilConstruct' AND lead_status='New Lead' THEN attendance.company_id END) AS 'New_Lead',
                COUNT(DISTINCT CASE WHEN exhibit_name = 'PhilConstruct' AND lead_status<>'New Lead' AND lead_status<>'Converted' THEN attendance.company_id END) AS 'Active_Leads',
                COUNT(DISTINCT CASE WHEN exhibit_name = 'PhilConstruct' AND lead_status='Converted' THEN attendance.company_id END) AS 'Converted',
                COUNT(DISTINCT CASE WHEN exhibit_name = 'PhilConstruct' THEN attendance.company_id END) AS total_leads
            ")
            ->leftJoin('contacts_update', 'contacts_update.company_id', '=', 'attendance.company_id')
            ->leftJoin('lead_agent_status', 'lead_agent_status.id', '=', 'contacts_update.status')
            ->groupBy(DB::raw("YEAR(date)"))
            ->orderBy('year_per_exhibit', 'desc')
            ->get();



             $reports_per_PHA = DB::table('attendance')
            ->selectRaw("
                YEAR(date) AS year_per_exhibit,
                COUNT(DISTINCT CASE WHEN exhibit_name = 'PHA' THEN attendance.company_id END) AS PHA_attendees,
                COUNT(DISTINCT CASE WHEN exhibit_name = 'PHA' AND lead_status='New Lead' THEN attendance.company_id END) AS 'New_Lead',
                COUNT(DISTINCT CASE WHEN exhibit_name = 'PHA' AND lead_status<>'New Lead' AND lead_status<>'Converted' THEN attendance.company_id END) AS 'Active_Leads',
                COUNT(DISTINCT CASE WHEN exhibit_name = 'PHA' AND lead_status='Converted' THEN attendance.company_id END) AS 'Converted',
                COUNT(DISTINCT CASE WHEN exhibit_name = 'PHA' THEN attendance.company_id END) AS total_leads
            ")
            ->leftJoin('contacts_update', 'contacts_update.company_id', '=', 'attendance.company_id')
            ->leftJoin('lead_agent_status', 'lead_agent_status.id', '=', 'contacts_update.status')
            ->groupBy(DB::raw("YEAR(date)"))
            ->orderBy('year_per_exhibit', 'desc')
            ->get();

            


       return view('reports.index', compact('reports', 'reports_per_WorldBex', 'reports_per_PhilConstruct','reports_per_PHA' ));
    }

}
