<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Hash;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use App\Mail\SurveyMail;

class AdminUserManagementController extends MyController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // admin role for register company
    public function index()
    {
        $title = __('Registration Coachingsupport');
        $org_types = Config::get('constants.org_type');

        $companies = User::where('role', '=', 'company')->orderBy('name', 'asc')->get();
        return view('adminDashboard', [
            'title' => $title,
            'user' => $this->user, 
            'companies' => $companies,
            'org_types' => $org_types
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        // $user = User::updateOrCreate(['id' => $request->id], [
        //             'active' => $request->active
        //         ]);

        $user = User::where('id', $request->id)->first();
        
         // send email
        if($request->active == 'active'){
            $details = [
                'title' => __('Welcome to coaching support'),
                'body' => __('Your account has been activated.')
            ];
        }else{
            $details = [
                'title' => 'Oope!',
                'body' => __('Your account has been deactivated.')
            ];
        }

        try {
            Mail::to($user['email'])->send(new SurveyMail($details));
        } catch (Exception $e) {
            if (count(Mail::failures()) > 0) {
                return response()->json(['code'=>202, 'message'=>__('Unable to send email')], 200);
            }
        }
        
        $user->active = $request->active;
        $user->save();

        return response()->json(['code'=>200, 'message'=>__('An email has been sent to that user.'), 'data' => $user], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $company = User::where('id', $id)->first();
        return response()->json(['code'=>200, 'message'=>"", 'data' => $company], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // check email has used or not
        $user = new User;
        if(isset($request->id)){
            $exist = User::where('id', '!=', $request->id)->where('email', strtolower($request->email))->get();
        }else{
            $exist = User::where('email', strtolower($request->email))->get();
        }
        if(count($exist) > 0){
            return response()->json(['code'=>422, 'message'=>__('The email address you entered is already in use by another user.')], 200);
        }
 
        $user = User::updateOrCreate(['id' => $request->id], [
            'org_type' => $request->org_type,
            'name' => $request->company_name,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'chamber_commerce' => $request->chamber_commerce,
            'city' => $request->city,
            'email' => strtolower($request->email),
            'tel' => $request->tel,
            'role' => 'Company',
            'parent_id' => 0                // company: parent 0
        ]);

        // tree_code update
        // company tree_code is own id
        User::where('id', $user['id'])->update(['tree_code' => $user['id']]);

       
        if($request->action_type == "Add"){
            // set password
            User::where('id', $user['id'])->update(['password' => Hash::make($request->password), 'active' => 'active']);
            // send Email
            $this->send_signup_email($user);
        }
            

        return response()->json(['code'=>200, 'message'=>'Met succes geregistreerd'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // delete all of member in this company
        $company = User::where('id', '=', $id)->first();
        User::where('tree_code', 'LIKE', $company['tree_code'] . '%')->delete();

        $user = User::where('id', $id)->delete();
   
        return response()->json(['code'=>200, 'message'=>'Succesvol verwijderd'], 200);
    }

    private function send_signup_email($info){
        // get SuperAdmin Email
        $admin = User::where('role', 'Admin')->where('active', 'active')->first();

        $info_body_html = '';
        $info_body_html .= '<br/>' . __('Organisation Type') . ': <b>' . $info['org_type'] . '</b>';
        $info_body_html .= '<br/>' . __('Company Name') . ': <b>' . $info['name'] . '</b>';
        $info_body_html .= '<br/>' . __('First name') . ': <b>' . $info['first_name'] . '</b>';
        $info_body_html .= '<br/>' . __('Last name') . ': <b>' . $info['last_name'] . '</b>';
        $info_body_html .= '<br/>KvK#: <b>' . $info['chamber_commerce'] . '</b>';
        $info_body_html .= '<br/>' . __('City') . ': <b>' . $info['city'] . '</b>';
        $info_body_html .= '<br/>' . __('Email') . ': <b>' . $info['email'] . '</b>';
        $info_body_html .= '<br/>' . __('Tel') . ': <b>' . $info['tel'] . '</b><br/>';

        $details = [
            'title' => __('Registration Coachingsupport'),
            'body' => __('A new company has registered') . ': ' . $info_body_html
        ];
        
        $resonse = Mail::to($admin['email'])->send(new SurveyMail($details));
        
        return $resonse;
    }

    public function user_manage_page(){
        
        $title = __('User Management');

        $roles = Config::get('constants.roles.user');
        
        $users = User::where('role', '!=', 'Admin')->orderBy('tree_code', 'asc')
                        ->get();
        $companies = User::where('role', '=', 'Company')->orderBy('name', 'asc')
                        ->get();
        
        $user_info = $this->user;
        
        if(count($users) > 0){
            for($i = 0; $i < count($users); $i++){
                $item = $users[$i];
                
                // get Company Info
                $company_code = explode('.', $item['tree_code'])[0];
                
                $company = User::where('tree_code', '=', $company_code)->first();
                $users[$i]['company_name'] = $company['name'];
            }
        }
        
        return view('adminUserManagement', [
            'title' => $title,
            'user' => $user_info, 
            'users' => $users,
            'roles' => $roles,
            'companies' => $companies
        ]);
    }

    public function getUserTreeByRole(Request $request){
        $roles = Config::get('constants.roles.user');

        // get Company Tree code
        $company = User::find($request->selected_company);

        // register target_role : trainee => coach, trainer
        // register target_role : coach or trainer => program
        if($request->role == 'Trainee'){
            $users = User::where('role', 'Coach')
            ->where('tree_code', 'LIKE', $company['tree_code'] . '%')->orderBy('first_name', 'asc')->get();
            
            // get Department, program info
            for($index = 0; $index < count($users); $index++){
                $codes = explode('.', $users[$index]['tree_code']);
                
                $department_code = array_slice($codes, 0, 2);
                $the_code = implode('.', $department_code); 
                $department = User::where('tree_code', 'LIKE', $the_code)->first();
                $users[$index]['department_name'] = $department['name'];

                $program_code = array_slice($codes, 0, 3);
                $the_code = implode('.', $program_code);
                $program = User::where('tree_code', 'LIKE', $the_code)->first();
                $users[$index]['program_name'] = $program['name'];

            }

            $trainers = User::where('role', 'Trainer')
            ->where('tree_code', 'LIKE', $company['tree_code'] . '%')->orderBy('first_name', 'asc')->get();

        }else if($request->role == 'Coach' || $request->role == 'Trainer'){
            $users = User::where('role', 'Program')
            ->where('tree_code', 'LIKE', $company['tree_code'] . '%')->orderBy('name', 'asc')->get();

            // get program info
            for($index = 0; $index < count($users); $index++){
                $codes = explode('.', $users[$index]['tree_code']);
                
                $department_code = array_slice($codes, 0, 2);
                $the_code = implode('.', $department_code); 
                $department = User::where('tree_code', 'LIKE', $the_code)->first();
                $users[$index]['department_name'] = $department['name'];
            }
        }else{
            $index = array_search($request->role, $roles);
        
            $target_role = $roles[$index - 1];
            $users = User::where('role', $target_role)
            ->where('tree_code', 'LIKE', $company['tree_code'] . '%')->orderBy('name', 'asc')->get();
        }

        if(count($users) > 0)
            return response()->json(['code'=>200, 'message'=>__('Successfully removed'), 'data'=>$users, 'trainers' => isset($trainers)?$trainers:[]], 200);
        else
            return response()->json(['code'=>201, 'message'=>'No user'], 200);
    }

    public function addUpdateUser(Request $request){

        if(isset($request->id)){
            $exist = User::where('id', '!=', $request->id)->where('email', strtolower($request->email))->get();
        }else{
            $exist = User::where('email', strtolower($request->email))->get();
        }
        if(count($exist) > 0){
            return response()->json(['code'=>422, 'message'=>__('The email address you entered is already in use by another user.')], 200);
        }

        $parent_user = User::find($request->parent_id);

        if($request->trainer_id_for_trainee == -1)
            $request->trainer_id_for_trainee = NULL;
        $user = User::updateOrCreate(['id' => $request->id], [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => strtolower($request->email),
            'name' => $request->name,
            'address' => $request->address,
            'post_code' => $request->post_code,
            'city' => $request->city,
            'num_add' => $request->num_add,
            'tel' => $request->tel,
            'role' => $request->role,
            'parent_id' => $request->parent_id,
            'trainer_id_for_trainee' => $request->trainer_id_for_trainee,
            'active' => 'active'
        ]);


        // tree code update
        $new_tree_code = $parent_user['tree_code'] . '.' . $user['id'];
        $user->tree_code = $new_tree_code;
        $user->save();

        if($request->action_type == "Add"){
            // $user->password = Hash::make('123456!');
            $password = $this->randomPassword();
            $user->password = Hash::make($password); 
            
            // send email
            $details = [
                'title' => '',
                'body' => __('Your account has been activated.') . '<br/>' .  __('Please login with this password') . ': ' . $password
            ];
            
            try {
                Mail::to($user['email'])->send(new SurveyMail($details));
                // success set random password
                $user->save();
            } catch (Exception $e) {
                if (count(Mail::failures()) > 0) {
                    // sending email failed then set default password as 123456!
                    $user->password = Hash::make('123456!'); 
                    $user->save();
                    return response()->json(['code'=>202, 'message'=>__('Unable to send email')], 200);
                }
            }
        }

        return response()->json(['code'=>200, 'message'=>'Successfully','data' => $user], 200);
    }

    private function randomPassword() {
	    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
	    $pass = array();
	    $alphaLength = strlen($alphabet) - 1;
	    for ($i = 0; $i < 8; $i++) {
	        $n = rand(0, $alphaLength);
	        $pass[] = $alphabet[$n];
	    }
	    return implode($pass);
	}

    public function userInfo($id){
        $user = User::find($id);
        $user['company_id'] = explode('.', $user['tree_code'])[0];

        return response()->json(['code'=>200, 'message'=>'Success','data' => $user], 200);
    }

    public function changeActive(Request $request){
        User::where('id', $request->id)->update([
            'active' => $request->active
        ]);

        $user = User::where('id', $request->id)->first();
       
        if($request->active == 'active'){
            $password = $this->randomPassword();
            $user->password = Hash::make($password);

            $details = [
                'title' => '',
                'body' => __('Your account has been activated.') . '<br/>' .  __('Please login with this password') . ': ' . $password
            ];
        }else{
            $details = [
                'title' => 'Oope!',
                'body' => __('Your account has been deactivated.')
            ];
        }

        try {
            Mail::to($user['email'])->send(new SurveyMail($details));
        } catch (Exception $e) {
            if (count(Mail::failures()) > 0) {
                return response()->json(['code'=>202, 'message'=>__('Unable to send email')], 200);
            }
        }

        $user->save();
        return response()->json(['code'=>200, 'message'=>__('Status changed successfully'), 'data' => $user], 200);
    }

    public function deleteUser($id){
        // delete only selected user
        // $user = User::where('id', $id)->delete();

        // delete all of lower level users
        $user = User::find($id);
        User::where('tree_code', 'LIKE', $user['tree_code'] . '%')->delete();
   
        return response()->json(['code'=>200, 'message'=>__('Successfully removed')], 200);
    }


    // Trainee management
    public function trainee_manage_page(){
        $title = "Trainee";
        $companies = User::where('role', '=', 'Company')->orderBy('name', 'asc')->get();
        $user_info = $this->user;

        
        $my_role = $this->user['role'];
       
        $users = User::where('users.role', 'Trainee')
        ->leftjoin('users AS u2', 'users.trainer_id_for_trainee', '=', 'u2.id')
        ->orderBy('tree_code', 'asc')
        ->select('users.*', 'u2.first_name AS trainee_first', 'u2.last_name AS trainee_last')
        ->get();
       
      
        // get Coach, Company
        if(count($users) > 0){
            for($i = 0; $i < count($users); $i++){
                $item = $users[$i];
                
                // get Parent tree code (coach)
                $last_dot_position = strrpos($item['tree_code'], ".");
                $parent_tree_code = substr($item['tree_code'], 0, $last_dot_position);

                $parentUser = User::where('tree_code', $parent_tree_code)->first();
                $users[$i]['parent_name'] = '';
                $users[$i]['parent_email'] = '';
                if($parentUser){
                    $users[$i]['parent_name'] = $parentUser['first_name'] . ' ' . $parentUser['last_name'];
                    $users[$i]['parent_email'] = $parentUser['email'];
                }

                // get Company Info
                $company_code = explode('.', $item['tree_code'])[0];
                $company = User::where('tree_code', '=', $company_code)->first();
                $users[$i]['company_name'] = $company['name'];
            }
        }

        

        $roles = Config::get('constants.roles.user');

        // $index = array_search($my_role, $roles);
        // if($my_role == 'Coach')
        //     $index++;
        // $include_roles = array_slice($roles, $index + 1);
        
        // // get higher level ogrs
        // // if user role = trainer or coarch  need to get company, department, program
        // $codes = explode('.', $this->user['tree_code']);
        
        // $company_code = array_slice($codes, 0, 1);
        // $the_code = implode('.', $company_code);
        // $company = User::where('tree_code', 'LIKE', $the_code)->first();
        // $user_info['company_name'] = $company['name'];
        // $user_info['org_type'] = $company['org_type'];

        // if($my_role == 'Coach' || $my_role == 'Trainer' || $my_role == 'Department' || $my_role == 'Program'){
            
        //     $department_code = array_slice($codes, 0, 2);
        //     $the_code = implode('.', $department_code); 
        //     $department = User::where('tree_code', 'LIKE', $the_code)->first();
        //     $user_info['department_name'] = $department['name'];
           
        //     if($my_role == 'Coach' || $my_role == 'Trainer' || $my_role == 'Program') {
        //         $program_code = array_slice($codes, 0, 3);
        //         $the_code = implode('.', $program_code);
        //         $program = User::where('tree_code', 'LIKE', $the_code)->first();
        //         $user_info['program_name'] = $program['name'];
        //     }       
        // }


        // sort($include_roles);

        return view('adminTreineeManagement', [
            'title' => $title,
            'user' => $user_info,
            'users' => $users,
            'companies' => $companies,
            'roles' => $roles
        ]);
    }

}
