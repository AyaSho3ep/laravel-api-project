<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    public function index(){
        try{
            $settings = Setting::orderBy('id', 'desc')->get();
            return response()->json([
                'success' =>true,
                'settings' =>$settings
            ]);
        }catch(Exception $e){
            return response()->json([
                'success' =>false,
                'message' =>'Invalid request'
            ]);
        }
    }

    public function store(Request $request){
        try{
            $this->authorize('admin-Privilege');
            $validation = Validator::make($request->all(),[
                'media' =>['required'],
                'email' => ['nullable', 'email'],
                'phone' => ['nullable'],
                'linkedin' => ['nullable'],
                'twitter' => ['nullable'],
                'facebook' => ['nullable'],
                'instagram' => ['nullable'],
                'youtube' => ['nullable'],
            ]);
            if($validation->fails()){
                return response()->json([
                    'success' => false,
                    'message' => $validation->errors()->all()
                ]);
            }else{
                $filename = '';
                if($request->file('media')){
                    $filename = $request->file('media')->store('settings','public');
                }else{
                    $filename = 'null';
                }

                $result = Setting::create([
                    'media' =>$filename,
                    'email' =>$request->email,
                    'phone' => $request->phone,
                    'linkedin' => $request->linkedin,
                    'twitter' => $request->twitter,
                    'facebook' => $request->facebook,
                    'instagram' => $request->instagram,
                    'youtube' => $request->youtube,
                ]);
                if($result){
                    return response()->json([
                        'success' =>true,
                        'message' =>'Media added successfully'
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

    public function updateHomeMedia(Request $request,$id){
        try{
            $this->authorize('admin-Privilege');
            $settings = Setting::findOrFail($id);
            $validation = Validator::make($request->all(),[
                'media' => ['required'],
            ]);

        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validation->errors()->all(),
            ]);
        }
            $filename = '';
            $location = public_path('storage\\'. $settings->media);
            if($request->file('media')){
                if(File::exists($location)){
                    File::delete($location);
                }
                $filename = $request->file('media')->store('settings','public');
            }
            $settings->media = $filename;
            $result = $settings->save();
            if($result){
                return response()->json([
                    'success' =>true,
                    'message' =>'Media updated successfully'
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
                'message' => 'Invalid request'
            ]);
        }
    }

    public function editSocialLinks($id){
        try{
            $this->authorize('admin-Privilege');
            $settings = Setting::findOrFail($id);
            return response()->json([
                'success' =>true,
                'settings' =>$settings
            ]);
        }catch(Exception $e){
            return response()->json([
                'success' =>false,
                'message' =>'Invalid request'
            ]);
        }
    }

    public function updateSocialLinks(Request $request, $id)
    {
        $this->authorize('admin-Privilege');
        $validation = Validator::make($request->all(),
            [
                'email' => ['nullable', 'email'],
                'phone' => ['nullable'],
                'linkedin' => ['nullable'],
                'twitter' => ['nullable'],
                'facebook' => ['nullable'],
                'instagram' => ['nullable'],
                'youtube' => ['nullable'],
            ]
        );

        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validation->errors()->all(),
            ]);
        } else {
            $result = Setting::findOrFail($id)->update(
                [
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'linkedin' => $request->linkedin,
                    'twitter' => $request->twitter,
                    'facebook' => $request->facebook,
                    'instagram' => $request->instagram,
                    'youtube' => $request->youtube,
                ]
            );
            if ($result) {
                return response()->json([
                    'success' => true,
                    'message' => "Social links Update Successfully",
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => "Some Problem",
                ]);
            }
        }
    }
}
