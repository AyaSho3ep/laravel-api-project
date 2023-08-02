<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function index(){
        try{
            $this->authorize('admin-supervisors-Privilege');
            $comments = Comment::orderBy('id','desc')->with('users:id,f_name,l_name,image', 'posts:id,title')->get();
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

    public function delete($id){
        try{
            $this->authorize('admin-supervisors-Privilege');
            $comment = Comment::findOrFail($id);
                $comment->delete();
                return response()->json([
                    'success' =>true,
                    'message' =>'Comment deleted successfully'
                ]);

        }catch(Exception $e){
            return response()->json([
                'success' =>false,
                'message' =>'Invalid request'
            ]);
        }
    }

}
