<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Webform;
use App\Models\Question;
use Illuminate\Support\Facades\Config;

class SurveyFormController extends MyController
{
    //
    public function index($form_id){
        
        $title = __('Survey') . " " . __('Form');
        
        // get Company ID
        $my_code = $this->user['tree_code'];
        $company_id = explode('.', $my_code)[0];
        

        $form_info = Webform::where('id', $form_id)->where('company_id', $company_id)->first();
        if(!$form_info){
            abort(403);
            exit;
        }
            
        $questions = Question::where('form_id', $form_id)->get();
        $answer_types = Config::get('constants.answer_type');

        if(count($questions) > 0){
            for($index = 0; $index < count($questions); $index++){
                $item = $questions[$index];
                if($item['answer_type'] == "Option"){
                    $options = json_decode($item['question_option']);
                    $questions[$index]['options'] = $options;
                }
            }
        }
        

        return view('surveyForm', [
            'title' => $title,
            'user' => $this->user, 
            'form_info' => $form_info,
            'questions' => $questions,
            'answer_types' => $answer_types
        ]);
    }

    
    public function addUpdateQuestion(Request $request){

        $question = Question::updateOrCreate(['id' => $request->id], [
            'form_id' => $request->form_id,
            'question' => $request->question,
            'question_option' => $request->question_option,
            'is_require' => $request->is_require,
            'answer_type' => $request->answer_type
        ]);

        return response()->json(['code'=>200, 'message'=>'Successfully','data' => $question], 200);
    }

    public function getQuestion($question_id){
        $question = Question::where('id', $question_id)->first();
        return response()->json(['code'=>200, 'message'=>'Successfully','data' => $question], 200);
    }

    public function deleteQuestion($question_id){
        Question::where('id', $question_id)->delete();

        return response()->json(['code'=>200, 'message'=>__('Successfully removed')], 200);
    }
}
