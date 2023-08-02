<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Lesson;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class LessonController extends Controller
{
    public function index(){
        try{
            $lessons = Lesson::orderBy('id','asc')->with('units:id,title')->get();
            if($lessons){
                return response()->json([
                    'success' =>true,
                    'lessons' =>$lessons
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
            $this->authorize('admin-Privilege');
            $validation = Validator::make($request->all(),[
                'name' =>['required'],
                'media' =>['required'],
                'unit_id' =>['required']
            ]);
            if($validation->fails()){
                return response()->json([
                    'success' =>false,
                    'message' =>$validation->errors()->all()
                ]);
            }else{
                $filename = '';
                if($request->file('media')){
                    $filename = $request->file('media')->store('lessons','public');
                }else{
                    $filename = 'null';
                }

                $result = Lesson::create([
                    'name' => $request->name,
                    'media' => $filename,
                    'unit_id' => $request->unit_id
                ]);

                if($result){
                    return response()->json([
                        'success' =>true,
                        'message' =>'Lesson added successfully'
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

    public function edit($id){
        try{
            $this->authorize('admin-Privilege');
            $lesson = Lesson::findOrFail($id);
            return response()->json([
                'success' =>true,
                'lesson' =>$lesson
            ]);
        }catch(Exception $e){
            return response()->json([
                'success' =>false,
                'message' =>'Invalid request'
            ]);
        }
    }

    public function update(Request $request,$id){
        try{
            $this->authorize('admin-Privilege');
            $lesson = Lesson::findOrFail($id);
            $validation = Validator::make($request->all(),[
                'name' =>['required'],
                'unit_id' =>['required']
            ]);
            if($validation->fails()){
                return response()->json([
                    'success' =>false,
                    'message' =>$validation->errors()->all()
                ]);
            }else{
                $filename = '';
                $location = public_path('storage\\'.$lesson->media);
                if($request->file('media')){
                    if(File::exists($location)){
                        File::delete($location);
                    }
                    $filename = $request->file('media')->store('lessons','public');
                }
                $lesson->name = $request->name;
                $lesson->unit_id = $request->unit_id;
                $lesson->media = $filename;
                $result = $lesson->save();
                if($result){
                    return response()->json([
                        'success' =>true,
                        'message'  =>'Lesson updated successfully'
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
        $this->authorize('admin-Privilege');
        $result = Lesson::findOrFail($id)->delete();
        if ($result) {
            return response()->json([
                'success' => true,
                'message' => 'Lesson deleted Successfully'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong'
            ]);
        }
    }
}
