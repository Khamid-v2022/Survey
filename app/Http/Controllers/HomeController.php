<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    //
    public function index(){
        $title = "Dashboard";
        return view('home')->with('title', $title);
    }

    public function survey($id){
        return view('survey', ['survey_id_str' => $id]);
    }
}
