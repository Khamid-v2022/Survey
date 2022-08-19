<?php 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once "vendor/autoload.php";

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$servername = $_ENV['DB_HOST'];
$username = $_ENV['DB_USERNAME'];
$password = $_ENV['DB_PASSWORD'];
$dbname = $_ENV['DB_DATABASE'];

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

cron_job();

$conn->close();

function cron_job(){

  global $conn;

  $now = date("Y-m-d H:i:s");
  $limit_day = date('Y-m-d H:i:s', strtotime('-' . $_ENV['MAIL_REMINDE_DAY'] . ' day'));
  
  $sql = "SELECT * FROM user_forms WHERE emailled_at < '" . $limit_day . "' AND emailled_times < " . $_ENV['MAIL_REMINDE_TIMES'];
  $result = $conn->query($sql);
  
  if($result->num_rows > 0){
    while($row = $result->fetch_assoc()){
      // get user info 
      $user_sql = "SELECT * FROM users WHERE id = " . $row['user_id'];
      $user_result = $conn->query($user_sql);
      
      if($user_result->num_rows > 0){
        $user_info = $user_result->fetch_assoc();
  
        // send email   $user_info['email']
        // $root = (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';
        $root = 'https://coachingsupport.solvware.online/';
        $root = $_ENV['HOME_PAGE'];
        $url = $root . "survey/" . $row['unique_str'];
        
        $email_result = send_email($user_info, $url);
        
        if($email_result){
          // update
          $update_query = "UPDATE user_forms SET emailled_at = '" . $now . "', emailled_times = " . ($row['emailled_times'] + 1) . " WHERE id = " . $row['id'];
          
          if ($conn->query($update_query) === TRUE) {
            // echo "Record updated successfully";
          } else {
            echo "Error updating record: " . $conn->error;
          }
        }
        
      }
  
    }
  }else { 
    echo "No records has been found";
  }
}

function send_email($user_info, $survey_url){
  $mail = new PHPMailer(true); 

  try {
      $mail->SMTPDebug = 2;                                       
      $mail->isSMTP();                                            
      $mail->Host       = $_ENV['MAIL_HOST'];                    
      $mail->SMTPAuth   = true;                             
      $mail->Username   = $_ENV['MAIL_USERNAME'];                 
      $mail->Password   = $_ENV['MAIL_PASSWORD'];                        
      $mail->SMTPSecure = $_ENV['MAIL_ENCRYPTION'];                              
      $mail->Port       = $_ENV['MAIL_PORT'];  
    
      $mail->setFrom($_ENV['MAIL_FROM_ADDRESS'], $_ENV['MAIL_FROM_NAME']);           
      $mail->addAddress($user_info['email']);
        
      $mail->isHTML(true);                                  
      $mail->Subject = 'Coachingsupport Herinnering';

      $div = "<div style='background-color:#edf2f7;color:#718096;padding: 50px 0'>";
        $div .= "<div style='background-color:white; padding: 32px; width: 570px; margin: 0 auto; border-radius: 3px'>";
        $div .= "<h2>Coachingsupport Herinnering</h2>"; 
        $div .= "Beste " . $user_info['first_name'] . "<br/><br/>" . "Je hebt de enquete nog niet ingevuld, gelieve dat via onderstaande link te doen:<br/>" . "<a href='" . $survey_url . "' target='_black'>" . $survey_url . "</a>";
        $div .= "</div>";

        $div .= "<div style='text-align: center;font-size: 12px; margin-top: 30px'>";
        $div .= "© 2022 Survey. All rights reserved.";
        $div .= "</div>";
      $div .= "</div>";

      $mail->Body = $div;

      $mail->send();
      return true;
  } catch (Exception $e) {
      return false;
  }
}

?>