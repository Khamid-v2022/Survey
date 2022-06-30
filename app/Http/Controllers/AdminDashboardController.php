<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\User;

class AdminDashboardController extends MyController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = "Registratie coachingsupport";

        $companies = Company::all();
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
        
        $company = Company::updateOrCreate(['id' => $request->id], [
                    'active' => $request->active
                ]);

        // user - company role active
        // $user = User::update(['company_id' => $company['id'], 'role' => 'company'], [
        //     'active' => $request->active
        // ]);

        $user = new User;
        $user->where('company_id', $company['id'])->where('role', 'company')->update(['active' => $request->active]);
 
        return response()->json(['code'=>200, 'message'=>'Successfully changed satus','data' => $company], 200);
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
        $company = Company::where('id',$id)->delete();
   
        return response()->json(['code'=>200, 'message'=>'Successfully deleted'], 200);
    }
}
