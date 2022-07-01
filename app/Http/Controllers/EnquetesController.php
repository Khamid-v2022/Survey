<?php

namespace App\Http\Controllers;

use App\Models\Webform;
use Illuminate\Http\Request;
use Hash;

class EnquetesController extends MyController
{
    //
    public function index(){
        
        $title = "Enquetes";
        
        // get Company ID
        // company id = first digit part of tree code by separate "."
        $my_code = $this->user['tree_code'];
        $company_id = explode('.', $my_code)[0];

        $forms = Webform::select('webforms.*', 'users.first_name', 'users.last_name', 'users.role')
            ->join('users', 'webforms.created_id', '=', 'users.id')
            ->where('company_id', $company_id)->get();

        return view('enquetes', [
            'title' => $title,
            'user' => $this->user, 
            'forms' => $forms
        ]);
    }


    public function addUpdateForm(Request $request){
        // get Company ID
        // company id = first digit part of tree code by separate "."
        $my_code = $this->user['tree_code'];
        $company_id = explode('.', $my_code)[0];

        $form = Webform::updateOrCreate(['id' => $request->id], [
            'form_name' => $request->form_name,
            'created_id' => $this->user['id'],
            'company_id' => $company_id,
            'active' => $request->active
        ]);

        // update qnique_str        
        if($request->action_type == "Add"){
            $form->unique_str = Hash::make($form['id']); 
        }
            
        $form->save();

        return response()->json(['code'=>200, 'message'=>'Successfully','data' => $form], 200);
    }

    public function changeActive(Request $request){
        $form = Webform::where('id', $request->id)->update([
            'active' => $request->active
        ]);

        return response()->json(['code'=>200, 'message'=>'Successfully changed satus','data' => $form], 200);
    }

    public function deleteForm($id){
        $form = Webform::where('id', $id)->delete();

        return response()->json(['code'=>200, 'message'=>'Successfully deleted'], 200);
    }
}
