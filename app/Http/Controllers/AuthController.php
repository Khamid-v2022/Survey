<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;

use App\Models\Company;
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
        // return response()->json(Hash::make($credentials['password']));
        if (Auth::attempt($credentials)) {
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
    
        $company = Company::updateOrCreate(['id' => $request->id], [
                    'company_name' => $request->company_name,
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'chamber_commerce' => $request->chamber_commerce,
                    'city' => $request->city,
                    'email' => $request->email,
                    'tel' => $request->tel
                ]);
    
        return response()->json(['code'=>200, 'message'=>'Created successfully','data' => $company], 200);
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
        $company = Company::find($id);
        return response()->json($company);
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
        $company = Company::find($id)->delete();

        return response()->json(['success'=>'Deleted successfully']);
    }
}
