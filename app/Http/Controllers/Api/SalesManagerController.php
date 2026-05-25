<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class SalesManagerController extends Controller
{
    //
     public function getSalesManager()
    {
        $users = User::whereIn('position_id', [197, 198, 13])
                    ->where('company_id', 3)
                    ->where('status',1)
                    ->get();

        return response()->json([
            'success' => true,
            'count'   => $users->count(),
            'data'    => $users
        ]);
    }
}
