<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


// use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\Exception;

// require_once __DIR__. "/../../../vendor/autoload.php";

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

    // public function send_email_test(){
    //     $survey_url = "https://google.com";
    //     $mail = new PHPMailer(true); 

    //     try {
    //         $mail->SMTPDebug = 2;                                       
    //         $mail->isSMTP();                                            
    //         $mail->Host       = $_ENV['MAIL_HOST'];                    
    //         $mail->SMTPAuth   = true;                             
    //         $mail->Username   = $_ENV['MAIL_USERNAME'];                 
    //         $mail->Password   = $_ENV['MAIL_PASSWORD'];                        
    //         $mail->SMTPSecure = $_ENV['MAIL_ENCRYPTION'];                              
    //         $mail->Port       = $_ENV['MAIL_PORT'];  
            
    //         $mail->setFrom($_ENV['MAIL_FROM_ADDRESS'], $_ENV['MAIL_FROM_NAME']);           
    //         $mail->addAddress("silverstar90216@gmail.com");
                
    //         $mail->isHTML(true);                                  
    //         $mail->Subject = 'Coachingsupport Herinnering';

    //         $div = "<div style='background-color:#edf2f7;color:#718096;padding: 50px 0'>";
    //             $div .= "<div style='background-color:white; padding: 32px; width: 570px; margin: 0 auto; border-radius: 3px'>";
    //             $div .= "<h2>Coachingsupport Herinnering</h2>"; 
    //             $div .= "Beste " . "Hitman" . "<br/><br/>" . "Je hebt de enquete nog niet ingevuld, gelieve dat via onderstaande link te doen:<br/>" . "<a href='" . $survey_url . "' target='_black'>" . $survey_url . "</a>";
    //             $div .= "</div>";

    //             $div .= "<div style='text-align: center;font-size: 12px; margin-top: 30px'>";
    //             $div .= "© 2022 Survey. All rights reserved.";
    //             $div .= "</div>";
    //         $div .= "</div>";

    //         $mail->Body = $div;

    //         $mail->send();
    //         return true;
    //     } catch (Exception $e) {
    //         return false;
    //     }
    // }
}
