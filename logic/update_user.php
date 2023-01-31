<?php
session_start();
require_once('../conf/db_con.php');

if(isset($_POST['update'])){

    $p = $_POST['page'];
    $role = mysqli_real_escape_string($con, $_POST['role']);

    if (empty($role)){
        $_SESSION['error'] = 'Role is mandatory.';
    }
    if (!in_array($role, array('user', 'employee', 'admin'))){
        $_SESSION['error'] = 'Role is invalid.';
    }

    $verified = mysqli_real_escape_string($con, $_POST['verified']);

    if (!in_array($verified, array('yes', ''))){
        $_SESSION['error'] = 'Verified options should not be modified.';
    }

    $user_id = $_POST['user_to_update'];
    $username = $_POST['username_to_update'];

    if ($username == $_SESSION['username']){
        $_SESSION['error'] = "You cannot unverify your own user.";
    }
    else if (isset($_SESSION['error'])){
        header("Location: ../dashboard_page.php?page=$p");
    }
    else{
        if (!empty($verified)){
            $update_query = mysqli_query($con, "UPDATE users SET role='$role', verified='$verified' WHERE id='$user_id'");
        } 
        else{
            $update_query = mysqli_query($con, "UPDATE users SET verified='$verified' WHERE id='$user_id'");
        }

        if($update_query){
            $_SESSION['success'] = 'User '.$username.' has been sucessfully updated.';
        } else {
            $_SESSION['error'] = "Error: " . mysqli_error($con);
        }     
    }
    
    header("Location: ../dashboard_page.php?page=$p");
}
?>
