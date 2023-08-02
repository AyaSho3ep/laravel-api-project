<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use App\Jobs\SendNewPostNotification;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    public function index(){
        try{
            $this->authorize('admin-supervisors-Privilege');
            $posts = Post::orderBy('id', 'desc')->with('categories:id,category_name', 'PostClassifications:id,post_type', 'users:id,f_name,l_name,job', 'comments')->paginate(10);
            if($posts){
                return response()->json([
                    'success' =>true,
                    'posts' =>$posts
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
            $this->authorize('admin-supervisors-Privilege');
            $validation = Validator::make($request->all(),[
                'title' => ['required', 'string', 'max:100','min:3', 'unique:posts'],
                'content' => ['required', 'string', 'max:1000', 'min:10'],
                'sources' => ['required', 'string'],
                'evaluation' => ['required', 'string'],
                'proof' => ['required', 'string'],
                'category_id' => ['required'],
                'postClassification_id' => ['required'],
                'media' => ['required'],
                'trending' => ['required'],
            ]);
            if($validation->fails()){
                return response()->json([
                    'success' => false,
                    'message' => $validation->errors()->all()
                ]);
            }else{
                $filename = '';
                if($request->file('media')){
                    $filename = $request->file('media')->store('posts','public');
                }else{
                    $filename = 'null';
                }

                $post = Post::create([
                    'title' => $request->title,
                    'content' => $request->content,
                    'sources' => $request->sources,
                    'evaluation' => $request->evaluation,
                    'proof' => $request->proof,
                    'media' => $filename,
                    'category_id' =>$request->category_id,
                    'postClassification_id' =>$request->postClassification_id,
                    'user_id' =>auth('sanctum')->user()->id,
                    'views' => 0,
                    'trending' => $request->trending == TRUE ? '1' : '0'
                ]);

                if($post){
                    dispatch(new SendNewPostNotification($post));
                    return response()->json([
                        'success' =>true,
                        'message' =>'Post added successfully'
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
            $this->authorize('admin-supervisors-Privilege');
            $posts = Post::findOrFail($id);

            return response()->json([
                'success' =>true,
                'posts' =>$posts
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
            $this->authorize('admin-supervisors-Privilege');
            $posts = Post::findOrFail($id);
            $validation = Validator::make($request->all(),[
                'title' => ['required', 'string', 'max:100','min:3'],
                'content' => ['required', 'string', 'max:1000', 'min:10'],
                'sources' => ['required', 'string', 'max:500', 'min:1'],
                'evaluation' => ['required', 'string', 'max:10', 'min:1'],
                'proof' => ['required', 'string', 'max:500', 'min:1'],
                'category_id' => ['required'],
                'postClassification_id' => ['required'],
                'trending' => ['required']
            ]);
            if($validation->fails()){
                return response()->json([
                    'success' =>false,
                    'message' =>$validation->errors()->all()
                ]);
            }else{
                $filename = '';
                $location = public_path('storage\\'. $posts->media);
                if($request->file('media')){
                    if(File::exists($location)){
                        File::delete($location);
                    }
                    $filename = $request->file('media')->store('posts','public');
                }
                $posts->title = $request->title;
                $posts->content = $request->content;
                $posts->sources = $request->sources;
                $posts->evaluation = $request->evaluation;
                $posts->proof = $request->proof;
                $posts->Category_id = $request->category_id;
                $posts->postClassification_id = $request->postClassification_id;
                $posts->trending = $request->trending == TRUE ? '1' : '0';
                $posts->user_id = auth('sanctum')->user()->id;
                $posts->media = $filename;
                $result = $posts->save();
            }if($result){
                return response()->json([
                    'success' =>true,
                    'message' =>'Post updated successfully'
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

    public function delete($id){
        try{
            $this->authorize('admin-supervisors-Privilege');
            $posts = Post::findOrFail($id);
            $location = public_path('storage\\'. $posts->media);

            if(File::exists($location)){
                File::delete($location);
            }

            $result = $posts->delete();
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

    public function search($search)
    {
        try {
            $posts = Post::with('categories:id,category_name', 'PostClassifications:id,post_type', 'users:id,f_name,l_name,job', 'comments')->where('title', 'LIKE', '%' . $search . '%')->orderBy('id', 'desc')->get();
            return response()->json([
                'success' => true,
                'posts' => $posts
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid request'
            ]);
        }
    }
}
