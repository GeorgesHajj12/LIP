<?php
use PHPMailer\PHPMailer\PHPMailer;
function sendEmailRegis($target) {
    
    
        $i=0;
        $name = $_POST['cust_FirstName'];
        $email = $target;
        $subject = 'subject';
        $body = 'body';
        require_once "PHPMailer/PHPMailer.php";
        require_once "PHPMailer/SMTP.php";
        require_once "PHPMailer/Exception.php";
        $mail = new PHPMailer();

        $mail->isSMTP(); 

       //$mail->Host = "smtp.office365.com";
       $mail->Host = "smtp.gmail.com";

        $mail->SMTPAuth = true;
        $mail->Username = "projetfinallip@gmail.com";
        $mail->Password = "olngzlaayjxftcbb";
    
        
        $mail->Port = 587; //587  465
        $mail->SMTPSecure = "tls"; //tls  ssl

        $mail->isHTML(true);

      $mail->setFrom("projetfinallip@gmail.com");
        $mail->addAddress($email);
        $mail->IsHTML(true); 
        
        $mail->Subject = $subject; 
        
       $mail->Body = 
       'Hi&nbsp;'.$name.'!<br><br><b>Your registration is now complete </b>&nbsp;';

        
        $mail->AtlBody ='this is the body in plain text for non-HTML ';
        
        if ($mail->send()) {
          $status = "success";
          $response = "Email is sent!";
        } else {
          $status = "failed";
          $response = "Something is wrong: <br><br>" . $mail->ErrorInfo;
      
        }
        
}
?>