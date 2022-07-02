<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User_Form;
use App\Models\User;
use App\Models\Webform;

class SurveyController extends Controller
{


    public function index($unique_str){
        $form_info = User_Form::where('unique_str', $unique_str)->first();
        if(!$form_info){
            abort(404);
            return;
        }
        $user = User::where('id', $form_info['user_id'])->first();
        $form = Webform::where('id', $form_info['form_id'])->first();

        return view('public_survey.survey', [
            'user' => $user,
            'form' => $form,
        ]);
    }
}
