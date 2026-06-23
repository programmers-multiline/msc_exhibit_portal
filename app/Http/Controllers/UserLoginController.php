<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserLogin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserLoginController extends Controller
{
    //
    public function showLogin()
    {
        return view('auth.external-login');
    }
   
    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');
       
       // dd($credentials);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

           // $user = Auth::user();

           // dd($user);
 
            return redirect('/viewcontacts');
        }

        return back()->withErrors([
            'error' => 'Invalid Credentials'
            
        ]);
    }


    public function login_via_oms(Request $request)
    {
        // $user_id = $request->user_id;

        // $user = User::where('id', $user_id)->first();

        // if ($user) {

        //     Auth::login($user);

        //     $request->session()->regenerate(); // IMPORTANT

        //     $link = route('participants.index');

        //     return response()->json([
        //         'message' => 'User found, login link generated',
        //         'login_link' => $link,
        //     ], 201);
        // }
        // return response()->json(['message' => 'User not found'], 401);

        $user = User::find($request->user_id);

       // dd($user);

        if (!$user) {
            abort(403);
        }

        Auth::login($user);

        $request->session()->regenerate();
   

        return redirect()->route('viewcontacts');
    }
    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
}
