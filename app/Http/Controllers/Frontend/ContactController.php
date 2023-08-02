<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Contact;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    public function store(Request $request){
        $validation = Validator::make($request->all(),[
            'name' => ['required'],
            'email' =>['required','email'],
            'subject' => ['required'],
            'message' =>['required']
        ]);
        if($validation->fails()){
            return response()->json([
                'success' =>false,
                'message' =>$validation->errors()->all()
            ]);
        }else{
            $result = Contact::create([
                'name' => $request->name,
                'email' => $request->email,
                'subject' => $request->subject,
                'message' => $request->message,
            ]);
        }
        if($result){
            return response()->json([
                'success' =>true,
                'message' => 'your message sent successfully, we will contact you soon'
            ]);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong'
            ]);
        }
    }
}
