<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResetPasswordController extends Controller
{
    public function resetPass(Request $request)
    {
        $findEmailAndToken = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();
        session()->put('email', $request->email);
        if ($findEmailAndToken) {
            return view('auth.reset-password');
        } else {
            abort(404);
        }
    }

    public function changePassword(Request $request)
    {
        User::query()->where('email', session('email'))->update([
            'password' => password_hash($request->password,PASSWORD_DEFAULT)
        ]);
        session()->forget('email');
        return response()->json('success');
    }

}
