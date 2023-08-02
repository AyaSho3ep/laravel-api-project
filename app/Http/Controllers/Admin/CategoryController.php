<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;



class CategoryController extends Controller
{
    public function index()
    {
        try {
            $categories = Category::orderBy('id', 'desc')->get();
            if ($categories) {
                return response()->json([
                    'success' => true,
                    'categories' => $categories
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
            $this->authorize('admin-supervisors-Privilege');
            $validation = Validator::make($request->all(), [
                'category_name' => ['required', 'string', 'max:20', 'min:2', 'unique:categories']
            ]);
            if ($validation->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validation->errors()->all()
                ]);
            } else {
                $result = Category::create([
                    'category_name' => $request->category_name
                ]);
                if ($result) {
                    return response()->json([
                        'success' => true,
                        'message' => "Category added successfully"
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

    public function edit($id)
    {
        try {
            $this->authorize('admin-supervisors-Privilege');
            $categories = Category::findOrFail($id);
            if ($categories) {
                return response()->json([
                    'success' => true,
                    'categories' => $categories
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'categories' => 'Invalid request'
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $this->authorize('admin-supervisors-Privilege');
            $categories = Category::findOrFail($id);
            $validation = validator::make($request->all(), [
                'category_name' => ['required', 'string', 'max:20', 'min:2', 'unique:categories']
            ]);
            if ($validation->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validation->errors()->all()
                ]);
            } else {
                $categories->category_name = $request->category_name;
                $result = $categories->save();

                if ($result) {
                    return response()->json([
                        'success' => true,
                        'message' => "Category updated successfully"
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
                'categories' => 'Invalid request'
            ]);
        }
    }

    public function delete($id)
    {
        try {
            $this->authorize('admin-supervisors-Privilege');
            $result = Category::findOrFail($id)->delete();
            if ($result) {
                return response()->json([
                    'success' => true,
                    'message' => "Category deleted successfully"
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
                'message' => 'Invalid request'
            ]);
        }
    }

    public function search($search){
        try{
            $categories = Category::where('category_name', 'like','%'.$search.'%')->orderBy('id', 'desc')->get();
            if ($categories) {
                return response()->json([
                    'success' => true,
                    'categories' => $categories
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
