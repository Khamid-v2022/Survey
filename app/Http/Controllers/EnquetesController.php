<?php

namespace App\Http\Controllers;

use App\Models\Webform;
use App\Models\User_Form;
use Illuminate\Http\Request;
use Hash;

class EnquetesController extends MyController
{
    //
    public function index(){
        
        $title = __('Surveys');
        
        // get Company ID
        // company id = first digit part of tree code by separate "."
        $my_code = $this->user['tree_code'];
        $company_id = explode('.', $my_code)[0];

        $forms = Webform::select('webforms.*', 'users.first_name', 'users.last_name', 'users.role')
            ->join('users', 'webforms.created_id', '=', 'users.id')
            ->where('company_id', $company_id)->get();

        if(count($forms) > 0){
            for($index = 0; $index < count($forms); $index++){
                $total = User_Form::where('form_id', $forms[$index]['id'])->count();
                $sumitted = User_Form::where('form_id', $forms[$index]['id'])->where('progress_status', 'submitted')->count();
                $forms[$index]['total_sent'] = $total;
                $forms[$index]['sumitted'] = $sumitted;
            }
        }
        
        

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

        return response()->json(['code'=>200, 'message'=>__('Successfully removed')], 200);
    }

    public function exportCSV($id){
        $form_info = Webform::where('id', $id)->first();
        $users = User_Form::select("users.*", "user_forms.unique_str", "user_forms.progress_status", "user_forms.ended_at")
            ->leftjoin('users', 'user_forms.user_id', '=', 'users.id')    
            ->where('user_forms.form_id', $id)
            ->get();

        $fileName = $form_info['form_name'] . '.csv';
        
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array(__('First name'), __('Last name'), __('Status'), 'Unique URL');

        $callback = function() use($users, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($users as $user) {
                $row['first_name']  = $user->first_name;
                $row['last_name']   = $user->last_name;
                $row['url']         = route('survey', ['unique_str' => $user['unique_str']]);
                $row['status'] = __('Pending');
                if($user['progress_status'] == "submitted")
                    $row['status'] = __('Submitted');

                fputcsv($file, array($row['first_name'], $row['last_name'], $row['status'], $row['url']));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
