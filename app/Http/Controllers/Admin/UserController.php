<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    public function index(){

        try{
            $this->authorize('admin-Privilege');
            $users = User::orderBy('id', 'desc')->with('roles:id,name')->get();
            if($users){
                return response()->json([
                    'success' =>true,
                    'posts' =>$users
                ]);
            }
        } catch(Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Invalid request'
            ]);
        }
    }

    public function userToSupervisor($id){
        $this->authorize('admin-Privilege');
        $user = User::findOrFail($id);
        $user->role_id = 2;
        $result = $user->save();
        if($result){
            return response()->json([
                'success' =>true,
                'message' =>'supervisor Privilege is added'
            ]);
        }
    }

    public function DeleteSupervisorPrivilege($id){
        $this->authorize('admin-Privilege');
        $user = User::findOrFail($id);
        $user->role_id = 3;
        $result = $user->save();
        if($result){
            return response()->json([
                'success' =>true,
                'message' =>'supervisor Privilege is removed'
            ]);
        }
    }

    public function userToAdmin($id){
        $this->authorize('admin-Privilege');
        $user = User::findOrFail($id);
        $user->role_id = 1;
        $result = $user->save();
        if($result){
            return response()->json([
                'success' =>true,
                'message' =>'Admin Privilege is added'
            ]);
        }
    }

    public function DeleteAdminPrivilege($id){
        $this->authorize('admin-Privilege');
        $user = User::findOrFail($id);
        $user->role_id = 3;
        $result = $user->save();
        if($result){
            return response()->json([
                'success' =>true,
                'message' =>'supervisor Privilege is removed'
            ]);
        }
    }

    public function teamWork(){
        try{
            $teamwork = User::where('role_id','<=',2)->orderBy('id', 'asc')->get(['id','f_name','l_name','job']);
            if($teamwork){
                return response()->json([
                    'success' =>true,
                    'teamwork' =>$teamwork
                ]);

            }
        }catch(Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Invalid request'
            ]);
        }
    }
}
