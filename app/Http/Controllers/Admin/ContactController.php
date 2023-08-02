<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Contact;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ContactController extends Controller
{
    public function getContacts(){
        try{
            $this->authorize('admin-supervisors-Privilege');
            $contacts = Contact::orderBy('id','desc')->get();
            return response()->json([
                'success' =>true,
                'contacts' =>$contacts
            ]);
        }catch(Exception $e){
            return response()->json([
                'success' =>false,
                'contacts'=>'Invalid request'
            ]);
        }
    }

    public function delete($id)
    {
        $this->authorize('admin-supervisors-Privilege');
        $result = Contact::findOrFail($id)->delete();
        if ($result) {
            return response()->json([
                'success' => true,
                'message' => 'Message deleted Successfully'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong'
            ]);
        }
    }
}
