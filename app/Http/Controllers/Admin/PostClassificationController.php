<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PostClassification;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostClassificationController extends Controller
{
    public function index(){
        try{
            $classifications = PostClassification::orderBy('id', 'desc')->get();
            if($classifications){
                return response()->json([
                    'success' => true,
                    'classifications' => $classifications
                ]);
            }
        }catch(Exception $e){
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
                'post_type' => ['required', 'string']
            ]);

            if ($validation->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validation->errors()->all()
                ]);
            } else {
                $result = PostClassification::create([
                    'post_type' => $request->post_type
                ]);
                if ($result) {
                    return response()->json([
                        'success' => true,
                        'message' => "New post classification added successfully"
                    ]);
                } else {
                    return response()->json([
                        'success' => true,
                        'message' => "something went wrong"
                    ]);
                }
            }
        }catch(Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Invalid request'
            ]);
        }
    }

    public function edit($id)
    {
        try {
            $classification = PostClassification::findOrFail($id);
            if ($classification) {
                return response()->json([
                    'success' => true,
                    'classification' => $classification
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid request'
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $this->authorize('admin-supervisors-Privilege');
            $classification = PostClassification::findOrFail($id);
            $validation = validator::make($request->all(), [
                'post_type' => ['required', 'string']
            ]);
            if ($validation->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validation->errors()->all()
                ]);
            } else {
                $classification->post_type = $request->post_type;
                $result = $classification->save();

                if ($result) {
                    return response()->json([
                        'success' => true,
                        'message' => "post classification updated successfully"
                    ]);
                } else {
                    return response()->json([
                        'success' => true,
                        'message' => "something went wrong"
                    ]);
                }
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
            $result = PostClassification::findOrFail($id)->delete();
            if ($result) {
                return response()->json([
                    'success' => true,
                    'message' => "post classification deleted successfully"
                ]);
            } else {
                return response()->json([
                    'success' => true,
                    'message' => "something went wrong"
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

}
