<?php

session_start();

include('../conf/db_con.php');

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
        $query_email = "SELECT * FROM users WHERE email = '$login_data' AND password = '$password'";
        $user_res = mysqli_query($con, $query_user);
        $email_res = mysqli_query($con, $query_email);

        if(mysqli_num_rows($user_res) == 1 || mysqli_num_rows($email_res) == 1){
            if(mysqli_num_rows($user_res) == 1){
                $user_info = mysqli_fetch_assoc($user_res);
            }
            if(mysqli_num_rows($email_res) == 1){
                $user_info = mysqli_fetch_assoc($email_res);
            }
            $_SESSION['username'] = $user_info['username'];
            $_SESSION['email'] = $user_info['email'];
            $_SESSION['role'] = $user_info['role'];
            $_SESSION['logged_in'] = true;
            $_SESSION['success'] = 'You have successfully signed in.';
            header('Location: ../index.php');
        }
        else{
            $_SESSION['error'] = 'Incorrect login information.';
            header('Location: ./login_page.php');
        }
    }
    else{
        $_SESSION['login_errors'] = $login_errors;
        header('Location: ./login_page.php');
    }
}
?>