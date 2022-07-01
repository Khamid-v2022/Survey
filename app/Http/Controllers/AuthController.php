<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;

use App\Models\User;
use Hash;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       
    }

    public function signup_page(){
        return view('auth.register');
    }

    public function signin_page(){
        return view('auth.login');
    }

    
    /**
     *
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function sign_in(Request $request){
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
   
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            if(Auth::user()->active == 'inactive')
                return response()->json(['code'=>201, 'message'=>'Please wait for approval from the Administrator'], 201);
            else
                return response()->json(['code'=>200, 'message'=>'You have Successfully loggedin'], 200);
        }
  
        return response()->json(['code'=>401, 'message'=>'Oppes! You have entered invalid credentials'], 401);
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function sign_out() {
        Session::flush();
        Auth::logout();
  
        return Redirect('login');
    }


    public function profile_page(){
        $title = "Profile";
        $user = Auth::user();
        
        if(!Auth::check()){
            redirect('login');
            return;
        }
        return view('auth.profile', [
            'title' => $title,
            'user' => $user
        ]);
    }

    /**
     * Update.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update_profile(Request $request){
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
       
        if(!(Hash::check($request->password, Auth::user()->password))){
            return response()->json(['code'=>401, 'message'=>'Failed authentication'], 200);
        }
        
        $user = Auth::user();
        $user->email = $request->email;
        $user->save();
 
        return response()->json(['code'=>200, 'message'=>'Successfully udpated email'], 200);
    }

     /**
     * Change password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function change_password(Request $request){
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required',
        ]);
       
        if(!(Hash::check($request->current_password, Auth::user()->password))){
            return response()->json(['code'=>401, 'message'=>'Failed authentication'], 200);
        }

        if(strcmp($request->current_password, $request->new_password) == 0){
            //Current password and new password are same
            return response()->json(['code'=>402, 'message'=>'New Password cannot be same as your current password. Please choose a different password.'], 200);
        }
        
        // change password
        $user = Auth::user();
        $user->password = Hash::make($request->new_password);
        // $user->password = bcrypt($request->new_password);
        $user->save();
 
        return response()->json(['code'=>200, 'message'=>'Successfully udpated password'], 200);
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
        $request->validate([
            'first_name'        => 'required|max:50',
            'last_name'         => 'required|max:50',
            'chamber_commerce'  => 'required',
            'city'              => 'required',
            'email'             => 'required',
            'tel'               => 'required'
        ]);

        // check email has used or not
        $user = new User;
        $users = $user->where('email', $request->email)->get();
        if(count($users) > 0){
            return response()->json(['code'=>422, 'message'=>'Het e-mailadres dat je hebt ingevoerd, is al in gebruik door een andere gebruiker.'], 200);
        }
 
        $user = User::updateOrCreate(['id' => $request->id], [
            'name' => $request->company_name,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'chamber_commerce' => $request->chamber_commerce,
            'city' => $request->city,
            'email' => $request->email,
            'tel' => $request->tel,
            'role' => 'company',
            'active' => 'inactive',
            'password' => Hash::make($request->password),
            'parent_id' => 0                // company: parent 0
        ]);

        // tree_code update
        // company tree_code is own id
        User::where('id', $user['id'])->update(['tree_code' => $user['id']]);

        return response()->json(['code'=>200, 'message'=>'Created successfully'], 200);
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
        $user = User::find($id);
        return response()->json($user);
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
        //
        $user = User::find($id)->delete();

        return response()->json(['success'=>'Deleted successfully']);
    }
}
