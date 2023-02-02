<?php

require_once('../phpmailer/class.phpmailer.php');

if ($_SERVER['HTTP_HOST'] == 'localhost'){
  require_once('../phpmailer/mail_config.php');
}
else{
  $app_sender = getenv('app_sender');
  $app_password = getenv('app_password');
}

function sendEmail($from, $from_name, $to, $to_name, $subject, $body){

  global $app_sender, $app_password;
  $mail = new PHPMailer(TRUE);
  $mail->IsSMTP();

  try {
 
    $mail->SMTPDebug  = 3;                     
    $mail->SMTPAuth   = true; 
    $mail->SMTPSecure = "ssl";                 
    $mail->Host       = "smtp.gmail.com";      
    $mail->Port       = 465;                   
    $mail->Username   = $app_sender;            // GMAIL username
    $mail->Password   = $app_password;          // GMAIL app password
    $mail->AddReplyTo($from, 'Re: '.$subject);
    $mail->AddAddress($to, $to_name);
   
    $mail->SetFrom($from, $from_name);
    $mail->Subject = $subject;
    $mail->MsgHTML($body);
    $mail->Send();
    return true;
  } 
  catch (phpmailerException $e) { 
    return $e->errorMessage();                  //error from PHPMailer
  } 
  catch (Exception $e) {
    return $e->getMessage();                    //error from anything else
  }
}
?>
