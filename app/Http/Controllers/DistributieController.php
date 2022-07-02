<?php

namespace App\Http\Controllers;

use App\Models\Webform;
use App\Models\User;
use App\Models\User_Form;
use Illuminate\Http\Request;

use Hash;
use DateTime;

class DistributieController extends MyController
{
    //
    public function index(){
        $title = "Distributie";
        
        // get Company ID
        // company id = first digit part of tree code by separate "."
        $my_code = $this->user['tree_code'];
        $company_id = explode('.', $my_code)[0];

        // get WebForms for our company
        $forms = Webform::select('webforms.*', 'users.first_name', 'users.last_name', 'users.role')
            ->join('users', 'webforms.created_id', '=', 'users.id')
            ->where('company_id', $company_id)->get();

        // get Tranees for out company
        $users = User::where('active', 'active')->where('role', 'trainee')->get();

        $our_trainee = [];
        foreach($users as $item){
            $user_company = explode('.', $item['tree_code'])[0];
            if($company_id == $user_company){
                array_push($our_trainee, $item);
            }
        }

        // get coach/trainer for each trainee
        $i = 0;
        if(count($our_trainee) > 0){
            for($i == 0; $i < count($our_trainee); $i++){
                $item = $our_trainee[$i];
                
                // get Parent tree code (coach/trainer)
                $last_dot_position = strrpos($item['tree_code'], ".");
                $parent_tree_code = substr($item['tree_code'], 0, $last_dot_position);

                $parentUser = User::where('tree_code', $parent_tree_code)->first();
                $our_trainee[$i]['parent_name'] = '';
                $our_trainee[$i]['parent_role'] = '';
                $our_trainee[$i]['parent_email'] = '';
                if($parentUser){
                    $our_trainee[$i]['parent_name'] = $parentUser['first_name'] . ' ' . $parentUser['last_name'];
                    $our_trainee[$i]['parent_role'] = $parentUser['role'];
                    $our_trainee[$i]['parent_email'] = $parentUser['email'];
                }
            }
        }

        return view('distributie', [
            'title' => $title,
            'user' => $this->user,
            'trainees' => $our_trainee,
            'forms' => $forms,
        ]);
    }

    public function sendFormToTranees(Request $request){
        // get webform info
        $form = Webform::where('id', $request->form_id)->first();

        foreach($request->tranee_ids as $trainee_id){
            // check if registred already or not
            $exist = User_Form::where('user_id', $trainee_id)
                ->where('form_id', $request->form_id)
                ->where('progress_status', '!=', 'end')->get();
            


            $date = new DateTime();
            $timestamp = $date->getTimestamp();

            if(count($exist) == 0){
                User_Form::create([
                    'user_id' => $trainee_id, 
                    'form_id' => $request->form_id,
                    // 'unique_str' => $form['unique_str'],
                    'unique_str' => base64_encode(Hash::make($request->form_id . '.' . $trainee_id . '.' . $timestamp)),
                    'progress_status' => 'start',
                    'active' => 'active'
                ]);
            }
                
        }

        // email send to user_id

        return response()->json(['code'=>200, 'message'=>'Is succesvol verzonden', 'data'=>$form], 200);
    }
}
