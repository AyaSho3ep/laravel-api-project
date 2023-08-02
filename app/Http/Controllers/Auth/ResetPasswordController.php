<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\ResetPassword;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\ResetsPasswords;

class ResetPasswordController extends Controller
{
    public function resetPassword(Request $request)
    {
        $request->validate([
            'code' => 'required|string|exists:reset_passwords',
            'password' => 'required|string|min:8|confirmed'
        ]);

        $resetPassword = ResetPassword::firstWhere('code', $request->code);

        if($resetPassword->created_at > now()->addHour()){
            $resetPassword->delete();
            return response()->json([
                'success' =>false,
                'message' =>trans('passwords.code_is_expire')
            ], 422);
        }

        $user = User::firstWhere('email', $resetPassword->email);
        $user->update(['password'=>Hash::make($request->password)]);
            // $request->only('password'));
        $resetPassword->delete();

        return response()->json([
            'success' =>true,
            'message' =>'Password updated successfully'
        ], 200);
    }
}
