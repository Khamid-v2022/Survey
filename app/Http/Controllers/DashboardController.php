<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends MyController
{

    public function index(){
        $title = "Mijn Dashboard";

        $description = "Successvol ingelogd als <b> {$this->user['first_name']} {$this->user['last_name']} </b>. Welkom terug!";

        return view('dashboard', [
            'title' => $title,
            'description' => $description,
            'user' => $this->user, 
        ]);
    }

    public function survey($id){
        return view('survey', ['survey_id_str' => $id]);
    }
}
