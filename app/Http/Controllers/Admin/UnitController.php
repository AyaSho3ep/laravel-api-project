<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Unit;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class UnitController extends Controller
{
    public function index(){
        try{
            $units = Unit::orderBy('id','asc')->get();
            if($units){
                return response()->json([
                    'success' =>true,
                    'units' =>$units
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
                'title' => ['required'],
                'duration' => ['required'],
                'objectives' => ['required'],
                'strategies' => ['required'],
                'tools' => ['required'],
            ]);
            if($validation->fails()){
                return response()->json([
                    'success' =>false,
                    'message' =>$validation->errors()->all()
                ]);
            }else{
                $result = Unit::create([
                    'title' => $request->title,
                    'duration' => $request->duration,
                    'objectives' => $request->objectives,
                    'strategies' => $request->strategies,
                    'tools' => $request->tools,
                ]);
                if($result){
                    return response()->json([
                        'success' =>true,
                        'message' => 'Unit added successfully'
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
            $unit = Unit::findOrFail($id);

            return response()->json([
                'success' =>true,
                'units' =>$unit
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
            $unit = Unit::findOrFail($id);
            $validation = Validator::make($request->all(),[
                'title' => ['required'],
                'duration' => ['required'],
                'objectives' => ['required'],
                'strategies' => ['required'],
                'tools' => ['required'],
            ]);
            if($validation->fails()){
                return response()->json([
                    'success' =>false,
                    'message' =>$validation->errors()->all()
                ]);
            }else{
                $unit->title = $request->title;
                $unit->duration = $request->duration;
                $unit->objectives = $request->objectives;
                $unit->strategies = $request->strategies;
                $unit->tools = $request->tools;
                $result = $unit->save();
                if($result){
                    return response()->json([
                        'success' =>true,
                        'message' =>'Unit updated successfully'
                    ]);
                }
            }
        }catch(Exception $e){
            return response()->json([
                'success' =>false,
                'message' => 'Invalid request'
            ]);
        }
    }

    public function delete($id){
        $this->authorize('admin-Privilege');
        $result = Unit::findOrFail($id)->delete();
        if ($result) {
            return response()->json([
                'success' => true,
                'message' => 'Unit deleted Successfully'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong'
            ]);
        }
    }
}
