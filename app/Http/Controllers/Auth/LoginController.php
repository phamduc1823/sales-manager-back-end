<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        if ($request->isMethod('post')) {
            $credential = $request->only('email', 'password');
            if (auth()->attempt($credential) && auth()->user()->hasAnyRole('admin', 'super-admin') && auth()->user()->status == 1) {
                $user = auth()->user();
                auth()->login($user, true);
                return response()->json('success');
            } else {
                return response()->json('fail');
            }
        }
        return view('auth.login');
    }

    public function logout()
    {
        auth()->logout();
        return redirect()->route('login');
    }
}
