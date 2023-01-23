<?php

session_start();

include('../conf/db_con.php');

if(isset($_POST['send_code']))
{   
    if(empty($_POST['email'])){
        $_SESSION['email_err'] = "Email is required.";
        header("Location: ../register_page.php");
    }
    else{
        $email = $_POST['email'];

        # verify if email exists
        $email_existing_query = "SELECT * FROM users WHERE email='$email' LIMIT 1";
        $email_existing_query_run = mysqli_query($con, $email_existing_query);

        if(mysqli_num_rows($email_existing_query_run) > 0)
        {
            # add message into session variable
            $_SESSION['error'] = "This email already exists.";
            header("Location: ../register_page.php");
        }
        else
        {   
            # generate verification token
            $token = rand(100000, 999999);

            # send token email
            include('send_verif_email.php');

            # save user data into user session data
            $_SESSION['email'] = $email;
            $_SESSION['token'] = $token;
            $_SESSION['success'] = "Verification code sent. Please check your inbox.";

            # hide email verification form and make token verification form visible
            $_SESSION['visibility'] = array(0,1,0);
            header("Location: ../register_page.php");
        }
    }

}

if(isset($_POST['verify_code']))
{
    if(empty($_POST['verification_code'])){
        $_SESSION['code_err'] = "Verification code is required.";
        $_SESSION['visibility'] = array(0,1,0);
        header("Location: ../register_page.php");
    }
    else{
        $verification_code = $_POST['verification_code'];
        if($verification_code != $_SESSION['token']){
            # add message into session variable
            $_SESSION['error'] = "Wrong token.";
            $_SESSION['visibility'] = array(0,1,0);
            header("Location: ../register_page.php");
        }
        else
        {
            # save user data into user session data
            $_SESSION['success'] = "Code successfully verified.";
            $_SESSION['visibility'] = array(0,0,1);
            header("Location: ../register_page.php");
        }
    }
}

if(isset($_POST['reg_btn'])){
    if(empty($_POST['username'])){
        $errors['username_err'] = "A username is required.";
    }
    else{
        $username = $_POST['username'];
        # verify if username exists
        $username_existing_query = "SELECT * FROM users WHERE username='$username' LIMIT 1";
        $username_existing_query_run = mysqli_query($con, $username_existing_query); 
        if(mysqli_num_rows($username_existing_query_run) > 0)
        {
            $errors['username_err'] = "This username already exists.";
        } 
    }
 
    if(empty($_POST['email_reg']) || $_POST['email_reg'] != $_SESSION['email']){
        $_SESSION['error'] = "Email not verified.";
    }
    else{
        $email = $_POST['email_reg'];
    }

    if(empty($_POST['password'])){
        $errors['pass_err'] = "Password is required.";
    }
    else if(empty($_POST['repeat_password']) || $_POST['repeat_password'] != $_POST['password']){
        $errors['repass_err'] = "Passwords do not match.";
    }
    else{
        $password = crypt($_POST['password'], 'something');
    }

    if(empty($_POST['usertype'])){
        $errors['usertype_err'] = "Invalid user type selection.";
    }
    else{
        $role = $_POST['usertype'];
    }

    if(!empty($_SESSION['error'])){
        unset($_SESSION['visibility']);
        header("Location: ../register_page.php");
    }
    else if(isset($errors)){
        $_SESSION['reg_errors'] = $errors;
        $_SESSION['visibility'] = array(0,0,1);
        header("Location: ../register_page.php");
    }
    else{
        // insert new user into database
        if ($role == 'user'){
        $register_query = "INSERT INTO users (username, email, password, role, validated) 
                            VALUES('$username', '$email', '$password', '$role', 'yes')";
        }
        else{
            $register_query = "INSERT INTO users (username, email, password, role) 
                                VALUES('$username', '$email', '$password', '$role')";
        }
        $register_query_con = mysqli_query($con, $register_query);

        // grant the 'user_role' role to the new user
        $assign_role = "GRANT user_role TO '$username'@'%'";
        $assign_role_con = mysqli_query($con, $assign_role);

        $_SESSION['username'] = $username;
        $_SESSION['success'] = 'You have been successfully registered.';
        header('Location: ../index.php');
    }
}

?>