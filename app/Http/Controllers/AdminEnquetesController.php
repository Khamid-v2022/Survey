<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Webform;
use App\Models\User_Form;
use App\Models\Question;
use Illuminate\Http\Request;
use Hash;

class AdminEnquetesController extends MyController
{
    //
    public function index(){
        
        $title = __('Surveys');
        
        $companies = User::where('role', '=', 'Company')->get();

        $forms = Webform::select('webforms.*', 'users.first_name', 'users.last_name', 'users.role', 'company.name AS company_name')
            ->join('users', 'webforms.created_id', '=', 'users.id')
            ->join('users AS company', 'webforms.company_id', '=', 'company.id')
            ->get();

        if(count($forms) > 0){
            for($index = 0; $index < count($forms); $index++){
                $total = User_Form::where('form_id', $forms[$index]['id'])->count();
                $sumitted = User_Form::where('form_id', $forms[$index]['id'])->where('progress_status', 'submitted')->count();
                $forms[$index]['total_sent'] = $total;
                $forms[$index]['sumitted'] = $sumitted;
            }
        }
        
        return view('adminEnquetes', [
            'title' => $title,
            'user' => $this->user, 
            'forms' => $forms,
            'companies' => $companies
        ]);
    }


    public function addUpdateForm(Request $request){
        // get Company ID
        // company id = first digit part of tree code by separate "."
        $my_code = $this->user['tree_code'];

        $form = Webform::updateOrCreate(['id' => $request->id], [
            'form_name' => $request->form_name,
            'created_id' => $this->user['id'],
            'company_id' => $request->company_id,
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

        return response()->json(['code'=>200, 'message'=>__('Successfully removed')], 200);
    }

    public function exportCSV($id){
        $form_info = Webform::where('id', $id)->first();
        $question_info = Question::where('form_id', $id)->get();
        
        $users = User_Form::select("users.*", "user_forms.unique_str", "user_forms.progress_status", "user_forms.ended_at", 'answers.question_id', 'answers.answer')
            ->leftjoin('users', 'user_forms.user_id', '=', 'users.id')
            ->leftjoin('answers', function($join){
                $join->on('answers.form_id', '=', 'user_forms.form_id')
                    ->on('answers.trainee_id', '=', 'user_forms.user_id');
            })
            ->where('user_forms.form_id', $id)
            ->orderby('users.id', 'ASC')
            ->orderby('answers.question_id', 'ASC')
            ->get();
        $fileName = $form_info['form_name'] . '.csv';
        
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );
        
        $columns = array(__('First name'), __('Last name'));
        
        foreach($question_info as $question){
            array_push($columns, $question['question']);
        }

        $callback = function() use($users, $columns, $question_info) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            $prev_user_id = 0;
            $first_flag = true;

            foreach ($users as $user) {
                if($prev_user_id != $user['id']){
                    if(!$first_flag){
                        fputcsv($file, $row);
                    }

                    $row = array();
                    array_push($row, $user->first_name);
                    array_push($row, $user->last_name);
                    $prev_user_id = $user['id'];
                    $first_flag = false;
                }
                
                foreach($question_info as $question){
                    if($question['id'] == $user['question_id'])
                        array_push($row, $user->answer);
                }
                
                // $row['url']         = route('survey', ['unique_str' => $user['unique_str']]);
                // $row['status'] = __('Pending');
                // if($user['progress_status'] == "submitted")
                //     $row['status'] = __('Submitted');

                // fputcsv($file, array($row['first_name'], $row['last_name'], $row['status'], $row['url']));
                
                
            }
            fputcsv($file, $row);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
