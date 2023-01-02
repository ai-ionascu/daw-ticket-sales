<?php

session_start();

include('db_con.php');

if(isset($_POST['login'])){
    $login_data = mysqli_real_escape_string($con, $_POST['user_email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);

    if(empty($login_data)){
        $login_errors['user_email'] = "Username or email required.";
    }
    if(empty($password)){
        $login_errors['password'] = "Password required.";
    }

    if(empty($login_errors)){
        $password = crypt($password, 'something');
        $query_user = "SELECT * FROM users WHERE username = '$login_data' AND password = '$password'";
        $query_email = "SELECT username FROM users WHERE email = '$login_data' AND password = '$password'";
        $user_res = mysqli_query($con, $query_user);
        $email_res = mysqli_query($con, $query_email);

        if(mysqli_num_rows($user_res) == 1 || mysqli_num_rows($email_res) == 1){
            if(mysqli_num_rows($user_res) == 1){
                $_SESSION['username'] = $login_data;
            }
            if(mysqli_num_rows($email_res) == 1){
                $_SESSION['username'] = $email_res;
            }
            $_SESSION['success'] = 'You have successfully signed in.';
            header('Location: index.php');
        }
        else{
            $_SESSION['error'] = 'Incorrect login information.';
            header('Location: login.php');
        }
    }
    else{
        $_SESSION['login_errors'] = $login_errors;
        header('Location: login.php');
    }
}
?>