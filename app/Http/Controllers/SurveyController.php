<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User_Form;
use App\Models\User;
use App\Models\Webform;
use App\Models\Question;
use App\Models\Answer;


class SurveyController extends Controller
{

    public function index($unique_str){
        $form_info = User_Form::where('unique_str', $unique_str)->first();
        if(!$form_info){
            abort(404);
            return;
        }
        if($form_info['progress_status'] == 'submitted'){
            return redirect('/thanks');
            return;
        }

        $user = User::where('id', $form_info['user_id'])->first();
        $form = Webform::where('id', $form_info['form_id'])->first();
        $questions = Question::where('form_id', $form_info['form_id'])->get();
        if(count($questions) > 0){
            for($index = 0; $index < count($questions); $index++){
                $item = $questions[$index];
                if($item['answer_type'] == "Option"){
                    $options = json_decode($item['question_option']);
                    $questions[$index]['options'] = $options;
                }
            }
        }

        return view('public_survey.survey', [
            'user' => $user,
            'form' => $form,
            'questions' => $questions
        ]);
    }

    public function postAnswer(Request $request){
        $form_id = $request->form_id;
        $trainee_id = $request->trainee_id;
        foreach($request->data as $item){
            Answer::updateOrCreate(['form_id' => $form_id, 'trainee_id' => $trainee_id, 'question_id' => $item['question_id']], [
                'form_id' => $form_id ,
                'question_id' => $item['question_id'],
                'trainee_id' => $trainee_id,
                'answer' => $item['answer']
            ]);
        }

        // update user-form to complate state
        $form_info = User_Form::where('user_id', $trainee_id)->where('form_id', $form_id)->first();
        $form_info->progress_status = 'submitted';
        $form_info->ended_at = date("Y-m-d H:i:s");
        $form_info->save();

        return response()->json(['code'=>200, 'message'=>'Successfully',], 200);
    }

    public function thanks(){
        return view('public_survey.thanks');
    }
}
