<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Models\AddingPostRequests;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class AddingPostRequestsController extends Controller
{
    public function index(){
        try{
            $this->authorize('admin-supervisors-Privilege');
            $postRequests = AddingPostRequests::orderBy('id', 'desc')->get();
            if($postRequests){
                return response()->json([
                    'success' =>true,
                    'postRequests' =>$postRequests
                ]);
            }
        }catch(Exception $e){
            return response()->json([
                'success' =>false,
                'message' =>'Invalid request'
            ]);
        }
    }

    public function store(Request $request){
        try{
            $validation = Validator::make($request->all(),[
                'name' => ['required', 'string', 'min:3', 'max:255'],
                'email' => ['required', 'string', 'max:255', 'email'],
                'url' => ['required', 'url'],
                'description' => ['required', 'string', 'min:3','max:500'],
                'media' => ['required'],
            ]);

            if($validation->fails()){
                return response()->json([
                    'success' => false,
                    'message' => $validation->errors()->all()
                ]);
            }else{
                $filename = '';
                if($request->file('media')){
                    $filename = $request->file('media')->store('postRequests','public');
                }else{
                    $filename = 'null';
                }

                $result = AddingPostRequests::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'url' => $request->url,
                    'description' => $request->description,
                    'media' => $filename,
                ]);

                if($result){
                    return response()->json([
                        'success' => true,
                        'message' => 'your request sent successfully'
                    ]);
                }else{
                    return response()->json([
                        'success' =>false,
                        'message' =>'Something went wrong'
                    ]);
                }
            }

        }catch(Exception $e){
            return response()->json([
                'success' =>false,
                'message' =>'Invalid request'
            ]);
        }
    }

    public function delete($id){
        try{
            $this->authorize('admin-supervisors-Privilege');
            $postRequest = AddingPostRequests::findOrFail($id);
            $location = public_path('storage\\'. $postRequest->media);

            if(File::exists($location)){
                File::delete($location);
            }

            $result = $postRequest->delete();
            if($result){
                return response()->json([
                    'success' =>true,
                    'message' =>'Post deleted successfully'
                ]);
            }else{
                return response()->json([
                    'success' =>false,
                    'message' =>'Something went wrong'
                ]);
            }
        }catch(Exception $e){
            return response()->json([
                'success' =>false,
                'message' =>'Invalid request'
            ]);
        }
    }

}
