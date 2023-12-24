<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\MailForgotPassword;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    public function forgotPass(Request $request)
    {
        if ($request->isMethod('post')) {
            $email = User::whereEmail($request->email)->first();
            if (!$email) {
                return response()->json('Email not found !');
            } else {
                $data = [
                    'email' => $request->email,
                    'token' => Str::random(15),
                    'created_at' => now()
                ];
                $findEmail = DB::table('password_reset_tokens')
                    ->where('email', $data['email']);
                if (!$findEmail->first()) {
                    $findEmail->insert($data);
                }
                $mailData = [
                    'token' => $findEmail->first()->token,
                    'email' => $findEmail->first()->email
                ];
                Mail::to($mailData['email'])->send(new MailForgotPassword($mailData));
                return response()->json('success');
            }
        }
        return view('auth.forgot-password');
    }
}
