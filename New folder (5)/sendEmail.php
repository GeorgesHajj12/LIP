<?php
use PHPMailer\PHPMailer\PHPMailer;
function sendEmailKK($myname,$target,$mybody) {
    
    
        $i=0;
        if($myname==''){
          $name= 'there';
          }else{
          $name= $myname;
          }
          
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
       'Hi&nbsp;'.$myname.'!<br><br><b>'.$mybody.'</b>&nbsp;';

        
        $mail->AtlBody ='this is the body in plain text for non-HTML ';
        
        if ($mail->send()) {
          $status = "success";
          $response = "Email is sent!";
        } else {
          $status = "failed";
          $response = "Something is wrong: <br><br>" . $mail->ErrorInfo;
      
        }
        
}
function sendEmailAdmin($myname,$target,$mybody,$mysubject) {
    
    
  $i=0;
  if($myname==''){
    $name= 'there';
    }else{
    $name= $myname;
    }
    
  $email = $target;
  $subject = $mysubject;
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
 'Hi&nbsp;'.$myname.'!<br><br><b>'.$mybody.'</b>&nbsp;';

  
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
