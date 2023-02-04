<?php

session_start();

include('../conf/db_con.php');
if (!isset($_SESSION['logged_in'])){
    $_SESSION['error'] = 'You have to log in before booking.';
    header("Location: ../login_page.php");
} else {

    if (isset($_POST['sel_route'])){

        $_SESSION['booking'] = true;

        $route_data = json_decode($_POST['route_data'],true);
        $_SESSION['route_data'] = $route_data;
        // $date = mysqli_real_escape_string($con, date("d-M-Y", $route_data['dep_time']));
        // $departure = mysqli_real_escape_string($con, $route_data['departure']);
        // $arrival = mysqli_real_escape_string($con, $route_data['arrival']);
        // $dep_time = mysqli_real_escape_string($con, date("H:i", strtotime($route_data['dep_time'])));
        // $arr_time = mysqli_real_escape_string($con, date("H:i", strtotime($route_data['arr_time'])));
        // $next_day = mysqli_real_escape_string($con, $route_data['next_day']);
        // $distance = mysqli_real_escape_string($con, $route_data['distance']);
        // $train = mysqli_real_escape_string($con, $route_data['train']);
        // $operator = mysqli_real_escape_string($con, $route_data['operator']);
        $route = mysqli_real_escape_string($con, $route_data['route']);

        if(empty($_POST['fare']) || !in_array($_POST['fare'], array('1','2','0'))){
            $form_errors['fare'] = "Invalid fare selection.";
        }
        else{
            $class = mysqli_real_escape_string($con, $_POST['fare']);
        }
            
        $_SESSION['fare'] = $class;
    }
        
    header("Location: ../booking_page.php?results&route=$route");
}

?>