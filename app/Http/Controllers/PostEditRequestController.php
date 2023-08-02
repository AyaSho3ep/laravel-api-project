<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Models\PostEditRequest;
use Illuminate\Support\Facades\Validator;

class PostEditRequestController extends Controller
{
    public function index()
    {
        try {
            $this->authorize('admin-supervisors-Privilege');
            $PostEditRequest = PostEditRequest::orderBy('id', 'desc')->get();
            if ($PostEditRequest) {
                return response()->json([
                    'success' => true,
                    'PostEditRequest' => $PostEditRequest
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid request'
            ]);
        }
    }

    public function store(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'post_id' => ['required'],
                'evaluation' => ['required'],
                'type' => ['required'],
                'content' => ['required']
            ]);
            if ($validation->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validation->errors()->all()
                ]);
            } else {
                $result = PostEditRequest::create([
                    'post_id' => $request->post_id,
                    'user_id' => auth('sanctum')->user()->id,
                    'evaluation' => $request->content == TRUE ? '1' : '0',
                    'type' => $request->type,
                    'content' => $request->content,
                ]);
            }
            if ($result) {
                return response()->json([
                    'success' => true,
                    'message' => 'your request sent successfully'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Something went wrong'
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid request'
            ]);
        }
    }

    public function delete($id)
    {
        try {
            $this->authorize('admin-supervisors-Privilege');
            $result = PostEditRequest::findOrFail($id)->delete();
            if ($result) {
                return response()->json([
                    'success' => true,
                    'message' => 'deleted Successfully'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Something went wrong'
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid request'
            ]);
        }
    }
}
