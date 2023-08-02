<?php

namespace App\Http\Controllers\Frontend;

use Exception;
use App\Models\Comment;
use App\Events\CommentAdded;
use Illuminate\Http\Request;
use App\Events\CommentDeleted;
use App\Events\CommentUpdated;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    public function index($post_id){
        try{
            $comments = Comment::orderBy('id','desc')->where('post_id', $post_id)->with('users:id,f_name,l_name,image', 'posts:id,title')->get();
            if($comments){
                return response()->json([
                    'success' =>true,
                    'comments' =>$comments
                ]);
            }
        }catch(Exception $e){
            return response()->json([
                'success' =>false,
                'messgae' =>'Invalid request'
            ]);
        }
    }

    public function store(Request $request){
        try{
        $validation = Validator::make($request->all(),[
            'comment' => ['required', 'string'],
            'post_id' => ['required', 'exists:posts,id']
        ]);
        if($validation->fails()){
            return response()->json([
                'success' => false,
                'message' => $validation->errors()->all()
            ]);
        }else{
            $comment = Comment::create([
                'comment' => $request->comment,
                'user_id' =>auth()->user()->id,
                'post_id' =>$request->post_id
            ]);
            if($comment){
                event(new CommentAdded($comment));
                return response()->json([
                    'success' =>true,
                    'message' =>'Your comment is added successfully',
                    'comment' =>$comment
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
            $comment = Comment::findOrFail($id);
            if (Auth::user()->id !== $comment->user_id) {
                return response()->json([
                    'success' =>false,
                    'message' => 'Unauthorized'
                ], 401);
            }
            return response()->json([
                'success' =>true,
                'comment' =>$comment
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
            $comment = Comment::findOrFail($id);
            if (Auth::user()->id !== $comment->user_id) {
                return response()->json([
                    'success' =>false,
                    'message' => 'Unauthorized'
                ], 401);
            }
            $validation = Validator::make($request->all(),[
                'comment' => ['required', 'string']
            ]);
            if($validation->fails()){
                return response()->json([
                    'success' =>false,
                    'message' =>$validation->errors()->all()
                ]);
            }else{
                $comment->update($request->only(['comment']));
                event(new CommentUpdated($comment));
                    return response()->json([
                        'success' =>true,
                        'comment' =>$comment
                    ]);
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
            $comment = Comment::findOrFail($id);
            if (Auth::user()->id !== $comment->user_id && !$this->authorize('admin-supervisors-Privilege')) {
                return response()->json([
                    'success' =>false,
                    'message' => 'Unauthorized'
                ], 401);
            }else{
                $comment->delete();
                event(new CommentDeleted($comment));
                return response()->json([
                    'success' =>true,
                    'message' =>'Comment deleted successfully'
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
