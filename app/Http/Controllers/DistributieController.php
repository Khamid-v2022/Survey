<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Webform;
use App\Models\User;
use App\Models\User_Form;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\SurveyMail;


use Hash;
use DateTime;
use Carbon\Carbon;

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
        $users = User::where('users.active', 'active')
                ->where('users.role', 'trainee')
                ->leftJoin('user_forms', 'users.id', '=', 'user_forms.user_id')
                ->leftjoin('webforms', 'user_forms.form_id', '=', 'webforms.id')
                ->select('users.*', 'webforms.form_name', 'user_forms.progress_status', 'user_forms.id AS survey_id', 'started_at', 'ended_at')
                ->get();
        
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
        $queue = [];

        foreach($request->tranee_ids as $trainee_id){
            // check if registred already or not
            $exist = User_Form::where('user_id', $trainee_id)
                ->where('form_id', $request->form_id)
                ->where('progress_status', '!=', 'end')->get();
            


            $date = new DateTime();
            $timestamp = $date->getTimestamp();

            if(count($exist) == 0){
                $user_form = User_Form::create([
                    'user_id' => $trainee_id, 
                    'form_id' => $request->form_id,
                    'unique_str' => base64_encode(Hash::make($request->form_id . '.' . $trainee_id . '.' . $timestamp)),
                    'progress_status' => 'start',
                    'active' => 'active',
                ]);

                $queue[] = $user_form;

            }
                
        }

        $email_success_flag = true;
        
        // email send
        foreach($queue as $item){
            // get user info
            $user = User::where('id', $item['user_id'])->first();
            $survey_url = route('survey', ['unique_str' => $item['unique_str']]);

            $details = [
                'title' => 'Coachingsupport Enquete',
                'body' => 'Beste ' . $user['first_name'] . '<br/><br/>' . 'Gelieve de enquete in te vullen via deze link:<br/>' 
                            . '<a href="' . $survey_url . '" target="_black">' . $survey_url . '</a>'
            ];
            
            try {
                Mail::to($user['email'])->send(new SurveyMail($details));
                
                // update emilled field
                $user_form = User_Form::where('id', $item['id'])->first();
                $user_form->emailled_at =  Carbon::now();
                $user_form->save();

            } catch (Exception $e) {
                if (count(Mail::failures()) > 0) {
                    $email_success_flag = false;
                }
            }
        }

        if(!$email_success_flag)
            return response()->json(['code'=>202, 'message'=>'Kan e-mail niet verzenden'], 200);

        return response()->json(['code'=>200, 'message'=>count($queue) . ' e-mails succesvol verzonden'], 200);
    }

    public function deleteSurveyItem(Request $request){
        $user = User_Form::where('id', $request->survey_id)->delete();
   
        return response()->json(['code'=>200, 'message'=>'Succesvol verwijderd'], 200);
    }
}
