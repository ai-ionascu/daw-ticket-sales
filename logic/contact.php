<?php

session_start();

include('../conf/db_con.php');

if(isset($_POST['contact'])){
   
    if(empty($_POST['subject'])){
        $form_errors['subject'] = "Please enter a subject.";
    }
    else{
        $subject = $_POST['subject'];
    }

    if(empty($_POST['firstname'])){
        $form_errors['firstname'] = "Please enter your first name.";
    }
    else{
        $firstname = $_POST['firstname'];
    }

    if(empty($_POST['lastname'])){
        $form_errors['lastname'] = "Please enter your last name.";
    }
    else{
        $lastname = $_POST['lastname'];
    }

    if(empty($_POST['email'])){
        $form_errors['email'] = "Email field is mandatory.";
    }
    else if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $form_errors['email'] = "Invalid email format."; 
      }
    else{
        $email = $_POST['email'];
    }
    
    $phone = $_POST['phone'];

    if(empty($_POST['message']) || strlen($_POST['message']) < 64){
        $form_errors['message'] = "The message minimum length is 64 characters.";
    }
    else{
        $message = $_POST['message'];
    }
    
    if (empty($form_errors)){
        require('send_email.php');
        $message = '<b>Subject:</b> '.$subject.'<br/><br/><b>Message:</b><br/>'.$message.'<br/><br/><b>Name:</b> '.$firstname.' '.$lastname;
        if (!empty($phone)){
            $message = $message.'<br/><br/><b>Phone Number:</b> '.$phone;
        }
        $confirmation = sendEmail($email, $firstname.' '.$lastname, 'test.unibuc@gmail.com', 'Train2Go', $subject, $message);
        if (is_bool($confirmation)){
            $_SESSION['success'] = 'Your message has been sent.';
            header('Location: ../index.php');
        }
        else{
            $_SESSION['error'] = 'Message not sent.'.$confirmation;
            header('Location: ../contact_page.php');
        }
    }
    else{
        $_SESSION['form_errors'] = $form_errors;
        header('Location: ../contact_page.php');
    }
}
?>
