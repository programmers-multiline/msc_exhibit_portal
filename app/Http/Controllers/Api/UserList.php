<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserList extends Controller
{
    //
       public function getUserList()
    {
        $users = User::where('status',1)->get();

        return response()->json([
            'success' => true,
            'count'   => $users->count(),
            'data'    => $users
        ]);
    }
}
