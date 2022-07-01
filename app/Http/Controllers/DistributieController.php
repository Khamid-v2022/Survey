<?php

namespace App\Http\Controllers;

use App\Models\Webform;
use App\Models\User;
use Illuminate\Http\Request;

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
}
