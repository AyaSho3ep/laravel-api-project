<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ResetPassword;

class CheckCodeController extends Controller
{
    public function verifyCode(Request $request)
    {
        $request->validate([
            'code' =>'required|string|exists:reset_passwords'
        ]);

        $resetPassword = ResetPassword::firstWhere('code', $request->code);

        if($resetPassword->created_at > now()->addHour()){
            $resetPassword->delete();
            return response()->json([
                'success' =>false,
                'message' =>trans('passwords.code_is_expire')
            ], 422);
        }

        return response()->json([
            'success' =>true,
            'message' =>trans('passwords.code_is_valid')
        ], 200);
    }
}
