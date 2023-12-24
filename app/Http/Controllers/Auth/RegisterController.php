<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    public function register(Request $request){
        if ($request->isMethod('post')){
            DB::beginTransaction();
            try {
                $email = User::query()->whereEmail($request->email)->first();
                if (!$email){
                    $user = new User();
                    $user->name = $request->name;
                    $user->email = $request->email;
                    $user->password = password_hash($request->password,PASSWORD_DEFAULT);
                    $user->save();
                    DB::commit();
                    return response()->json('success');
                }else{
                    return response()->json('Email has been register');
                }

            }catch (\Throwable $throwable){
                DB::rollBack();
                return  response()->json($throwable->getMessage());
            }
        }
        return view('auth.register');
    }
}
