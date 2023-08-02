<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\ResetPassword;
use App\Mail\ResetPasswordCode;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */


    public function forgotPassword(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'email' => 'required'|'email'|'exists:users'
        ]);

        if(User::where('email', $request->email)->doesntExist()){
            return response()->json([
                'success' =>false,
                'message' =>'This email does not exists!'
            ],401);
        }

        ResetPassword::where('email', $request->email)->delete();

        $code = mt_rand(100000, 999999);

        $newdata = ResetPassword::create([
            'email' => $request->email,
            'code' => $code
        ]);

        Mail::to($request->email)->send(new ResetPasswordCode($newdata));

        return response()->json([
            'success' =>true,
            'message' => trans('passwords.sent')
        ], 200);
    }


}
