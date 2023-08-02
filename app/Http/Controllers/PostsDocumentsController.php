<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Models\PostsDocuments;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class PostsDocumentsController extends Controller
{
    public function index(){
        try{
            $this->authorize('admin-supervisors-Privilege');
            $postsDocuments = PostsDocuments::orderBy('id', 'desc')->get();
            if($postsDocuments){
                return response()->json([
                    'success' =>true,
                    'posts' =>$postsDocuments
                ]);
            }
        } catch(Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Invalid request'
            ]);
        }
    }

    public function store(Request $request){
        try{
            $validation = Validator::make($request->all(),[
                'name' => ['required', 'string', 'min:3', 'max:255'],
                'email' => ['required', 'string', 'max:255', 'email'],
                'national_id' => ['required'],
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
                    $filename = $request->file('media')->store('postsDocuments','public');
                }else{
                    $filename = 'null';
                }

                $result = PostsDocuments::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'national_id' => $request->national_id,
                    'media' => $filename,
                ]);

                if($result){
                    return response()->json([
                        'success' =>true,
                        'message' =>'Thanks for your help, we will contact you soon'
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
            $postDocs = PostsDocuments::findOrFail($id);
            $location = public_path('storage\\'. $postDocs->media);

            if(File::exists($location)){
                File::delete($location);
            }

            $result = $postDocs->delete();
            if($result){
                return response()->json([
                    'success' =>true,
                    'message' =>'Post document deleted successfully'
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
