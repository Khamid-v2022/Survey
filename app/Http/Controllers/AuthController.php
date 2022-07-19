<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Session;

use App\Models\User;
use Hash;

use App\Mail\SurveyMail;

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

    public function password_reset_page(){
        return view('auth.password_reset');
    }
    

    public function password_reset(Request $request){
        $request->validate([
            'email' => 'required',
        ]);

        $user = User::where('email', strtolower($request->email))->first();
        if(!$user)
            return response()->json(['code'=>201, 'message'=>'Dit is een niet-geregistreerde e-mail.'], 200);

        $new_pass = $this->randomPassword();

        $details = [
            'title' => 'Coachingsupport Update wachtwoord',
            'body' => 'Uw wachtwoord is successvol veranderd<br/>
            U kunt inloggen met de volgende gegevens: <b>' . $new_pass . '</b>'
        ];

        try {
            Mail::to($user['email'])->send(new SurveyMail($details));
        } catch (Exception $e) {
            if (count(Mail::failures()) > 0) {
                return response()->json(['code'=>202, 'message'=>'Kan e-mail niet verzenden'], 200);
            }
        }

        $user->password = Hash::make($new_pass);
        $user->save();
        
        return response()->json(['code'=>200, 'message'=>'Success'], 200);
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
                return response()->json(['code'=>201, 'message'=>'Wacht op goedkeuring van de beheerder'], 201);
            else
                return response()->json(['code'=>200, 'message'=>'Je bent succesvol ingelogd'], 200);
        }
  
        return response()->json(['code'=>401, 'message'=>'U heeft ongeldige inloggegevens ingevoerd'], 401);
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
            return response()->json(['code'=>401, 'message'=>'Verificatie mislukt'], 200);
        }
        
        $exist = User::where('email', strtolower($request->email))->get();
        if(count($exist) > 0){
            return response()->json(['code'=>422, 'message'=>'Het e-mailadres dat je hebt ingevoerd, is al in gebruik door een andere gebruiker'], 200);
        }

        $user = Auth::user();
        $user->email = strtolower($request->email);
        $user->save();
 
        return response()->json(['code'=>200, 'message'=>'E-mail geüpdatet'], 200);
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
            return response()->json(['code'=>401, 'message'=>'Verificatie mislukt'], 200);
        }

        if(strcmp($request->current_password, $request->new_password) == 0){
            //Current password and new password are same
            return response()->json(['code'=>402, 'message'=>'Nieuw wachtwoord mag niet hetzelfde zijn als uw huidige wachtwoord. Kies een ander wachtwoord.'], 200);
        }
        
        // change password
        $user = Auth::user();
        $user->password = Hash::make($request->new_password);
        $user->save();
 
        return response()->json(['code'=>200, 'message'=>'Wachtwoord geüpdatet'], 200);
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
        $users = $user->where('email', strtolower($request->email))->get();
        if(count($users) > 0){
            return response()->json(['code'=>422, 'message'=>'Het e-mailadres dat je hebt ingevoerd, is al in gebruik door een andere gebruiker.'], 200);
        }
 
        $user = User::updateOrCreate(['id' => $request->id], [
            'name' => $request->company_name,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'chamber_commerce' => $request->chamber_commerce,
            'city' => $request->city,
            'email' => strtolower($request->email),
            'tel' => $request->tel,
            'role' => 'company',
            'active' => 'inactive',
            'password' => Hash::make($request->password),
            'parent_id' => 0                // company: parent 0
        ]);

        // tree_code update
        // company tree_code is own id
        User::where('id', $user['id'])->update(['tree_code' => $user['id']]);

        // send Email
        $code = $this->send_signup_email($user);

        return response()->json(['code'=>200, 'message'=>'Met succes gemaakt', 'data'=>$code], 200);
    }

    private function send_signup_email($info){
        // get SuperAdmin Email
        $admin = User::where('role', 'admin')->where('active', 'active')->first();

        $info_body_html = '';
        $info_body_html .= '<br/>Bedrijfsnaam: <b>' . $info['name'] . '</b>';
        $info_body_html .= '<br/>Voornaam: <b>' . $info['first_name'] . '</b>';
        $info_body_html .= '<br/>Achternaam: <b>' . $info['last_name'] . '</b>';
        $info_body_html .= '<br/>KvK#: <b>' . $info['chamber_commerce'] . '</b>';
        $info_body_html .= '<br/>Stad: <b>' . $info['city'] . '</b>';
        $info_body_html .= '<br/>Email: <b>' . $info['email'] . '</b>';
        $info_body_html .= '<br/>Tel: <b>' . $info['tel'] . '</b><br/>';

        $details = [
            'title' => 'Registratie coachingsupport',
            'body' => 'Er heeft een nieuw bedrijf geregistreerd:' . $info_body_html
        ];
        
        $resonse = Mail::to($admin['email'])->send(new SurveyMail($details));
        
        return $resonse;
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

        return response()->json(['success'=>'Met succes verwijderd']);
    }
}
