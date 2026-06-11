<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
  

public function login(Request $request)
{
    $user = DB::table('users')
        ->where('username', $request->username)
        ->first();

        //dd($user);

    if($user && $user->password == $request->password){

        session([
            'user'     => $user,
            'emp_id'   => $user->emp_id,
            'username' => $user->username
        ]);

        return redirect('/participant/create');
    }

    return back()->with('error','Invalid Credentials');
}

            public function logout()
        {
            session()->forget('user');   // remove session
            session()->flush();          // optional: clear all session

            return redirect('/');
        }
}