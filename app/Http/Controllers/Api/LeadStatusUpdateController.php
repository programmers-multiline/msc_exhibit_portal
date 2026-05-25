<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LeadStatusUpdate;

class LeadStatusUpdateController extends Controller
{
    public function index()
    {
        $LeadStatusUpdate = LeadStatusUpdate::where('line_status', 'active')
                    ->get();

        return response()->json($LeadStatusUpdate);
    }
}
