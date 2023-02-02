<?php

session_start();

include('../conf/db_con.php');

if (isset($_POST['dep_input'])){
    $departure = $_POST['dep_input'];
    $departure_query = "SELECT * FROM departures 
                        JOIN stations 
                        ON departures.station_id = stations.id
                        WHERE stations.name LIKE '%$departure%'";
    $departure_query_run = mysqli_query($con, $departure_query);

    if (mysqli_num_rows($departure_query_run) > 0){ 

        while($departures = mysqli_fetch_assoc($departure_query_run)){ 
            
            $dep_name = $departures['name']; ?>

        <option value="<?php echo $dep_name; ?>">

        <?php }
    }

    else{
        echo '<span class="error-message" style="color: red;">
                No results.
            </span>';
    }
}

if (isset($_POST['arr_input'])){
    $arrival = $_POST['arr_input'];
    $arrival_query = "SELECT * FROM arrivals 
                        JOIN stations 
                        ON arrivals.station_id = stations.id
                        WHERE stations.name LIKE '%$arrival%'";
    $arrival_query_run = mysqli_query($con, $arrival_query);

    if (mysqli_num_rows($arrival_query_run) > 0){ 

        while($arrivals = mysqli_fetch_assoc($arrival_query_run)){ 
            
            $arr_name = $arrivals['name']; ?>

        <option value="<?php echo $arr_name; ?>">

        <?php }
    }
    else{
        echo '<span class="error-message" style="color: red;">
                No results.
            </span>';
    }
}

if (isset($_POST['search'])) {
    
    if(empty($_POST['departures'])){
        $form_errors['departures'] = "The departure station is missing.";
    }
    else{
        $departure = mysqli_real_escape_string($con, $_POST['departures']);
    }

    if(empty($_POST['arrivals'])){
        $form_errors['arrivals'] = "The arrival station is missing.";
    }
    else{
        $arrival = mysqli_real_escape_string($con, $_POST['arrivals']);
    }

    if(empty($_POST['select_time'])){
        $form_errors['select_time'] = "Missing time selection.";
    }
    else{
        $select_time = mysqli_real_escape_string($con, $_POST['select_time']);
        if (!in_array($select_time, array('departure', 'arrival'))){
            $form_errors['select_time'] = "Invalid time selection.";
        }
    }

    if(empty($_POST['route_time'])){
        $form_errors['route_time'] = "The time selection is missing.";
    }
    else{
        $route_time = mysqli_real_escape_string($con, $_POST['route_time']);
        if (strtotime($route_time) == false){
            $form_errors['route_time'] = "The datetime format is invalid.";
        }
        else {
            $route_time = date("d-m-Y H:i", strtotime($route_time));
        }  
    }

    if(empty($_POST['class'])){
        $form_errors['class'] = "Invalid class selection.";
    }
    else{
        $class = mysqli_real_escape_string($con, $_POST['class']);
    }

    if(empty($_POST['passengers'])){
        $form_errors['passengers'] = "Invalid number of passengers.";
    }
    else{
        $passengers = mysqli_real_escape_string($con, $_POST['passengers']);
    }

    if (isset($form_errors)){
        $_SESSION['form_errors'] = $form_errors;
        header("Location: ../booking_page.php");
    }
    else{
        if ($select_time == 'departure'){
            $routes_query = "SELECT DISTINCT r.id as route, dep_st.name as departure, arr_st.name as arrival,
                                r.start_time as dep_time, r.end_time as arr_time, r.next_day_arrival as next_day,
                                t.name as train, t.operator as operator
                                FROM routes r 
                                JOIN departures d ON r.departures_id = d.id 
                                JOIN arrivals a ON r.arrivals_id = a.id 
                                JOIN stops dep ON dep.route_id = r.id 
                                JOIN stops arr ON arr.route_id = r.id 
                                JOIN stations dep_st ON dep_st.id = d.station_id OR dep_st.id = dep.station_id 
                                JOIN stations arr_st ON arr_st.id = a.station_id OR arr_st.id = arr.station_id
                                JOIN trains t ON r.train_id = t.id 
                                WHERE dep_st.name = '$departure' 
                                AND arr_st.name = '$arrival' 
                                AND dep.order < arr.order
                                AND r.start_time > '$route_time'
                                ORDER BY dep_time
                                LIMIT 10";
        }
        else{
            $routes_query = "SELECT DISTINCT r.id as route, dep_st.name as departure, arr_st.name as arrival,
                                r.start_time as dep_time, r.end_time as arr_time, r.next_day_arrival as next_day,
                                t.name as train, t.operator as operator
                                FROM routes r 
                                JOIN departures d ON r.departures_id = d.id 
                                JOIN arrivals a ON r.arrivals_id = a.id 
                                JOIN stops dep ON dep.route_id = r.id 
                                JOIN stops arr ON arr.route_id = r.id 
                                JOIN stations dep_st ON dep_st.id = d.station_id OR dep_st.id = dep.station_id 
                                JOIN stations arr_st ON arr_st.id = a.station_id OR arr_st.id = arr.station_id
                                JOIN trains t ON r.train_id = t.id 
                                WHERE dep_st.name = '$departure' 
                                AND arr_st.name = '$arrival' 
                                AND dep.order < arr.order
                                AND r.end_time < '$route_time'
                                ORDER BY arr_time DESC
                                LIMIT 10";
        }
        $routes_query_run = mysqli_query($con, $routes_query);
    
        if ($routes_query_run){
    
            $routes = mysqli_fetch_all($routes_query_run, MYSQLI_ASSOC);
            $route_day = date("d-m-Y", strtotime($route_time));
            foreach ($routes as &$r){
                $r['dep_time'] = $route_day . date(" H:i", strtotime($r['dep_time']));
                $arr_time = $route_day . date("H:i", strtotime($r['arr_time']));
                $arr_time = date_create($arr_time);
                if ($r['next_day'] == 1){
                    $arr_time = date_add($arr_time, date_interval_create_from_date_string("1 day"));
                }
                $trip_time = date_diff($arr_time, date_create($r['dep_time']));
                $trip_hours = $trip_time -> format("%a") * 24 + $trip_time -> format("%h");
                $trip_min = $trip_time -> format("%i");
                $r['trip_time'] = $trip_hours.':'.$trip_min;
            }
            $_SESSION['routes_query'] = $routes;
            header("Location: ../booking_page.php?results");
        }
        else {
            $_SESSION['error'] = 'Routes not found in the database.';
            header("Location: ../booking_page.php");
        }
    }
}
mysqli_close($con);

?>