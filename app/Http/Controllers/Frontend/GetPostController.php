<?php
namespace App\Http\Controllers\Frontend;

use Exception;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GetPostController extends Controller
{
    public function index()
    {
        try {
            $posts = Post::with('categories:id,category_name', 'PostClassifications:id,post_type', 'users:id,f_name,l_name,job', 'comments')->orderBy('id', 'desc')->get();
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

       public function viewPosts()
       {
           try {
               $posts = Post::with('categories:id,category_name', 'PostClassifications:id,post_type', 'users:id,f_name,l_name,job', 'comments')->where('views', '>', 0)->orderBy('id', 'desc')->get();
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


       public function getPostById($id)
       {
           try {
               $posts = Post::with('categories:id,category_name', 'PostClassifications:id,post_type', 'users:id,f_name,l_name,job', 'comments')->findOrFail($id);
               $posts->views = $posts->views + 1;
               $posts->save();
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

       public function getPostByCategory($id)
       {
           try {
               $posts = Post::with('categories:id,category_name', 'PostClassifications:id,post_type', 'users:id,f_name,l_name,job', 'comments')->where('category_id', $id)->orderBy('id', 'desc')->get();
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

       public function getPostByClassification($id)
       {
           try {
               $posts = Post::with('categories:id,category_name', 'PostClassifications:id,post_type', 'users:id,f_name,l_name,job', 'comments')->where('postClassification_id', $id)->orderBy('id', 'desc')->get();
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

       public function getPostByTrending()
       {
           try {
               $posts = Post::with('categories:id,category_name', 'PostClassifications:id,post_type', 'users:id,f_name,l_name,job', 'comments')->where('trending', '=', 1)->orderBy('id', 'desc')->get();
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

       public function searchPost($search)
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
