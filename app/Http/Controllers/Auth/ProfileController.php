<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function user(){
        return response()->json([
            'message'=>'User successfully fetched',
            'user'=>auth()->user()
        ],200);
    }

    public function changePassword(Request $request){
        $validation = Validator::make($request->all(),[
            'old_password' =>['required'],
            'new_password' =>['required', 'min:8', 'confirmed'],
        ]);
        if($validation->fails()){
            return response()->json([
                'success' =>false,
                'message' =>$validation->errors()->all()
            ]);
        }
        $user = $request->user();
        if(Hash::check($request->old_password, $user->password)){
            $user->update([
                'password' =>Hash::make($request->new_password)
            ]);
            return response()->json([
                'success' =>true,
                'message' =>'Password updated successfully'
            ]);
        }else{
            return response()->json([
                'success' =>false,
                'message' =>'Old password does not match'
            ]);
        }
    }

    public function updateProfile(Request $request){
        $validation = Validator::make($request->all(),[
            'f_name' => ['required', 'string', 'min:3', 'max:255'],
            'l_name' => ['required', 'string', 'min:3', 'max:255'],
            'job' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'unique:users'],
            'national_id' => ['required'],
            'image' =>['required', 'image', 'mimes:jpg,jpeg,png']
        ]);
        if($validation->fails()){
            return response()->json([
                'success' =>false,
                'message' =>$validation->errors()->all()
            ], 401);
        }

        $user = $request->user();
        $filename = '';
        $location = public_path('storage\\'. $user->image);
        if($request->file('image')){
            if($user->image){
                if(File::exists($location)){
                    File::delete($location);
                }
            }
            $filename = $request->file('image')->store('profile','public');
        }
        $result = $user->update([
            'f_name' => $request->f_name,
            'l_name' => $request->l_name,
            'job' => $request->job,
            'phone' =>$request->phone,
            'national_id' =>$request->national_id,
            'image' =>$filename
        ]);
        if($result){
            return response()->json([
                'success' =>true,
                'message' =>'Profile updated successfully'
            ]);
        }else{
            return response()->json([
                'success' =>false,
                'message' =>'Something went wrong'
            ]);
        }
    }
}
