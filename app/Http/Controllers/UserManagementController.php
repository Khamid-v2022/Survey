<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Hash;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use App\Mail\SurveyMail;

class UserManagementController extends MyController
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

        $companies = User::where('role', 'company')->get();
        return view('adminDashboard', [
            'title' => $title,
            'user' => $this->user, 
            'companies' => $companies
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::where('id', $id)->delete();
   
        return response()->json(['code'=>200, 'message'=>'Succesvol verwijderd'], 200);
    }



    // User side
    public function user_manage_page(){
        
        $title = __('User Management');
        
        $my_role = $this->user['role'];
        $roles = Config::get('constants.roles.user');

        $index = array_search($my_role, $roles);
        if($my_role == 'coach')
            $index++;
        $include_roles = array_slice($roles, $index + 1);
        
        $users = User::wherein('role', $include_roles)
                        ->where('tree_code', 'LIKE', $this->user['tree_code'] . '%')
                        ->orderBy('tree_code', 'asc')
                        ->get();
        
        $user_info = $this->user;
        
        // get higher level ogrs
        // if user role = trainer or coarch  need to get company, department, program
        $codes = explode('.', $this->user['tree_code']);
        
        $company_code = array_slice($codes, 0, 1);
        $the_code = implode('.', $company_code);
        $company = User::where('tree_code', 'LIKE', $the_code)->first();
        $user_info['company_name'] = $company['name'];
        $user_info['org_type'] = $company['org_type'];

        if($my_role == 'coach' || $my_role == 'trainer' || $my_role == 'department' || $my_role == 'program'){
            
            $department_code = array_slice($codes, 0, 2);
            $the_code = implode('.', $department_code); 
            $department = User::where('tree_code', 'LIKE', $the_code)->first();
            $user_info['department_name'] = $department['name'];
           
            if($my_role == 'coach' || $my_role == 'trainer' || $my_role == 'program') {
                $program_code = array_slice($codes, 0, 3);
                $the_code = implode('.', $program_code);
                $program = User::where('tree_code', 'LIKE', $the_code)->first();
                $user_info['program_name'] = $program['name'];
            }       
        }
       
        return view('userManagement', [
            'title' => $title,
            'user' => $user_info, 
            'users' => $users,
            'roles' => $include_roles
        ]);
    }

    public function getUserTreeByRole(Request $request){
        $roles = Config::get('constants.roles.user');

        // register target_role : trainee => coach, trainer
        // register target_role : coach or trainer => program
        if($request->role == 'trainee'){
            $users = User::wherein('role', ['coach', 'trainer'])
            ->where('tree_code', 'LIKE', $this->user['tree_code'] . '%')->get();
            
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

        }else if($request->role == 'coach' || $request->role == 'trainer'){
            $users = User::where('role', 'program')
            ->where('tree_code', 'LIKE', $this->user['tree_code'] . '%')->get();

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
            ->where('tree_code', 'LIKE', $this->user['tree_code'] . '%')->get();
        }

        if(count($users) > 0)
            return response()->json(['code'=>200, 'message'=>__('Successfully removed'), 'data'=>$users], 200);
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
            'active' => 'active'
        ]);


        // tree code update
        $new_tree_code = $parent_user['tree_code'] . '.' . $user['id'];
        $user->tree_code = $new_tree_code;
        
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
        
        $my_role = $this->user['role'];
        if($my_role == 'company'){
            $users = User::wherein('role', ['trainee', 'coach', 'trainer'])
            ->where('tree_code', 'LIKE', $this->user['tree_code'] . '%')
            ->orderBy('tree_code', 'asc')
            ->get();
        }
        else {
            $users = User::where('role', 'trainee')
            ->where('tree_code', 'LIKE', $this->user['tree_code'] . '%')
            ->orderBy('tree_code', 'asc')
            ->get();
        }        
        
        $user_info = $this->user;

        $roles = Config::get('constants.roles.user');

        $index = array_search($my_role, $roles);
        if($my_role == 'coach')
            $index++;
        $include_roles = array_slice($roles, $index + 1);
        
        // get higher level ogrs
        // if user role = trainer or coarch  need to get company, department, program
        $codes = explode('.', $this->user['tree_code']);
        
        $company_code = array_slice($codes, 0, 1);
        $the_code = implode('.', $company_code);
        $company = User::where('tree_code', 'LIKE', $the_code)->first();
        $user_info['company_name'] = $company['name'];
        $user_info['org_type'] = $company['org_type'];

        if($my_role == 'coach' || $my_role == 'trainer' || $my_role == 'department' || $my_role == 'program'){
            
            $department_code = array_slice($codes, 0, 2);
            $the_code = implode('.', $department_code); 
            $department = User::where('tree_code', 'LIKE', $the_code)->first();
            $user_info['department_name'] = $department['name'];
           
            if($my_role == 'coach' || $my_role == 'trainer' || $my_role == 'program') {
                $program_code = array_slice($codes, 0, 3);
                $the_code = implode('.', $program_code);
                $program = User::where('tree_code', 'LIKE', $the_code)->first();
                $user_info['program_name'] = $program['name'];
            }       
        }


       
        return view('treineeManagement', [
            'title' => $title,
            'user' => $user_info, 
            'users' => $users,
            'roles' => $include_roles
        ]);
    }

}
