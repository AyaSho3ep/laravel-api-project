<?php
namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Exception;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    // use HttpResponses;

    public function register(Request $request){
        try{
            $validation = Validator::make($request->all(),[
                'f_name' => ['required', 'string', 'min:3', 'max:255'],
                'l_name' => ['required', 'string', 'min:3', 'max:255'],
                'job' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'max:255', 'unique:users', 'email'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
                'phone' => ['nullable', 'unique:users'],
                'national_id' => ['required', 'unique:users']
            ]);

            if($validation->fails()){
                return response()->json([
                    'success' =>false,
                    'message' =>$validation->errors()->all()
                ], 401);
            }
            $user = User::create([
                'f_name' =>$request->f_name,
                'l_name' =>$request->l_name,
                'job' =>$request->job,
                'email' =>$request->email,
                'password' =>Hash::make($request->password),
                'phone' =>$request->phone,
                'national_id' =>$request->national_id,
            ]);
            if($user){
                if($user->id == 1){
                    $user->role_id = 1;
                    $user->save();
                }
                return response()->json([
                    'success' =>true,
                    'message' =>'User created successfully',
                    'token' => $user->createToken('myapptoken'. $user->f_name)->plainTextToken
                ], 200);
            }else{
                return response()->json([
                'success' =>false,
                'message' =>'Something went wrong'
                ], 401);
            }
        }catch(Exception $e){
            return response()->json([
                'success' =>false,
                'message' =>'Invalid request'
            ], 500);
        }
    }

    public function login(Request $request){
        try{
            $validation = Validator::make($request->all(),[
                'email' => 'required|email',
                'password' => 'required'
            ]);

            if($validation->fails()){
                return response()->json([
                    'success' =>false,
                    'message' =>$validation->errors()->all()
                ], 401);
            }

            if(!Auth::attempt($request->only(['email','password']))){
                return response()->json([
                    'success' =>false,
                    'message' =>'Credentials do not match'
                ], 401);
            }

            $user = User::where('email', $request->email)->first();

            return response()->json([
                'success' =>true,
                'message' => 'User logged in successfully',
                'token' => $user->createToken('myapptoken'. $user->f_name)->plainTextToken
            ], 200);
        }catch(Exception $e){
            return response()->json([
                'success' =>false,
                'message' =>'Invalid request'
            ], 500);
        }
    }

    public function logout(Request $request){

        auth()->user()->tokens()->delete();

        return response()->json([
            'success' =>true,
            'message' => 'Logged out successsfully'
        ], 200);
    }


}
