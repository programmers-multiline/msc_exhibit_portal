<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class SalesUserController extends Controller
{
    //
      public function getSalesUser()
    {
        $users = User::whereIn('position_id', [157,159])
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
