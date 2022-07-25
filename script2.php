<?php 

 use PHPMailer\PHPMailer\PHPMailer;
 use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/vendor/phpmailer/phpmailer/src/Exception.php';
require __DIR__ . '/vendor/phpmailer/phpmailer/src/PHPMailer.php';
// require __DIR__ . '/vendor/phpmailer/phpmailer/src/SMTP.php';

//  require_once __DIR__ . "/vendor/autoload.php";


$mail = new PHPMailer(true); 

  try {
      $mail->SMTPDebug = 2;                                       
      $mail->isSMTP();                                            
      $mail->Host       = 'smtp.office365.com';                    
      $mail->SMTPAuth   = true;                             
      $mail->Username   = 'taras.petros@outlook.com';                 
      $mail->Password   = 'NewStar216!@';                        
      $mail->SMTPSecure = 'tls';                              
      $mail->Port       = 587;  
    
      $mail->setFrom('taras.petros@outlook.com', 'survey');           
      $mail->addAddress('silverstar90216@gmail.com');
        
      $mail->isHTML(true);                                  
      $mail->Subject = 'Coachingsupport Herinnering';
      $mail->Body    = 'Beste ' . '<br/><br/>' . 'Je hebt de enquete nog niet ingevuld, gelieve dat via onderstaande link te doen:<br/>' . '<a href="" target="_black">hjkhkjh</a>';
      $mail->send();
      return true;
  } catch (Exception $e) {
      return false;
  }



//  $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
// $dotenv = new Dotenv\Dotenv(__DIR__);
//  $dotenv->load();

//  $servername = "localhost";
//  $username = "u965508571_coach";
//  $password = "1D$+0e~3fH";
//  $dbname = "u965508571_coach";


//  // Create connection
//  $conn = new mysqli($servername, $username, $password, $dbname);
//  // Check connection
//  if ($conn->connect_error) {
//      die("Connection failed: " . $conn->connect_error);
//  }

//  $sql = "INSERT INTO webforms (unique_str, form_name, created_id, company_id, active)
//  VALUES ('" . __DIR__ . "', 'cron test form', 1, 1, 'active')";

//  if ($conn->query($sql) === TRUE) {
//      echo "New record created successfully";
//  } else {
//      echo "Error: " . $sql . "<br>" . $conn->error;
//  }

//  $conn->close();
?>