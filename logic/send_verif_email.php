<?php

//sursa: https://github.com/PHPMailer/PHPMailer
//tutorial: https://alexwebdevelop.com/phpmailer-tutorial/
//Gmail restriction: https://support.google.com/mail/answer/22370?hl=en

require_once('../phpmailer/class.phpmailer.php');

if ($_SERVER['HTTP_HOST'] == 'localhost'){
  require_once('../phpmailer/mail_config.php');
}
else{
  $app_sender = getenv('app_sender');
  $app_password = getenv('app_password');
}

// Message body
$message = "Welcome to Train2Go !"."<br /><br/>Your verification code is:<br/>\n <b>".$token."</b>";
// Break a string every N characters with
// wordwrap()
$N = 50;
$message = wordwrap($message, $N, "<br/>\n");

$mail = new PHPMailer(TRUE);
$mail->IsSMTP();

try {
 
  $mail->SMTPDebug  = 3;                     
  $mail->SMTPAuth   = true; 
  $mail->SMTPSecure = "ssl";                 
  $mail->Host       = "smtp.gmail.com";      
  $mail->Port       = 465;                   
  $mail->Username   = $app_sender;  			         // GMAIL username
  $mail->Password   = $app_password;            // GMAIL app password
  $mail->AddReplyTo($app_sender, 'Re: Train2Go Reservation System');
  $mail->AddAddress($email, 'New Registration');
 
  $mail->SetFrom($app_sender, 'Train2Go Reservation System');
  $mail->Subject = 'Verify your Train2Go registration';
  $mail->AltBody = 'To view this post you need a compatible HTML viewer!'; 
  $mail->MsgHTML($message);
  $mail->Send();
  echo "Message Sent OK</p>\n";
} catch (phpmailerException $e) {
  echo $e->errorMessage();                    //error from PHPMailer
} catch (Exception $e) {
  echo $e->getMessage();                      //error from anything else!
}
?>
