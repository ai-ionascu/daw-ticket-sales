<?php
session_start();
require_once('../conf/db_con.php');

if(isset($_POST['delete'])){

    $user_id = $_POST['user_to_delete'];
    $username = $_POST['username_to_delete'];

    if ($username == $_SESSION['username']){
        $_SESSION['error'] = "You cannot delete your own user.";
    }
    else{
        $delete_query = mysqli_query($con, "DELETE FROM users WHERE id='$user_id'");
        if(delete_query){
            $_SESSION['success'] = 'User '.$username.' has been sucessfully deleted.';
        } else {
            $_SESSION['error'] = "Error: " . mysqli_error($con);
        }
    }
    
    $p = $_POST['page'];
    header("Location: ../dashboard_page.php?page=$p");
}
?>
