<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
Use Alert;

class AuthController extends Controller
{

    public function loginPage()
    {
        return view('auth.login');    
    }

    public function login(Request $request)
    {
        $request->validate([
            'username'  => 'required',
            'password'  => 'required',
        ]);

        $credential = $request->only('username', 'password');
        if (Auth::attempt($credential)) {
            return redirect()->intended('/');
        }

        Alert::error('Login Failed', 'Username or Password Wrong');
        return redirect()->route('login.page')->withInput();
    }

    public function logout(Request $request)
    {
       $request->session()->flush();
       Auth::logout();
       return Redirect()->route('login.page');
    }
}
