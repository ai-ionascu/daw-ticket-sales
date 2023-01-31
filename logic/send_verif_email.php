<?php

// Verification message body
$message = "Welcome to Train2Go !"."<br /><br/>Your verification code is:<br/>\n <b>".$token."</b>";
// Break a string every N characters with wordwrap()
$N = 50;
$message = wordwrap($message, $N, "<br/>\n");

$from_name = 'Train2Go Reservation System';
$to_name = 'New User Registration';
$subject = 'Verify your Train2Go registration';

require('send_email.php');
sendEmail($app_sender, $from_name, $email, $to_name, $subject, $message);

?>
