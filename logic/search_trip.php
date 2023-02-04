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

function search_nested($item, $arr){
    foreach ($arr as $k => $v){
        if (in_array($item, $v)){
            return $k;
        }
    }
    return null;
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
            $route_time = date("H:i", strtotime($route_time));
        }  
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
                                        dep.departure_time as dep_time, arr.arrival_time as arr_time,
                                        arr.next_day_arrival as next_day,t.name as train,
                                        t.operator as operator
                                        
                                        FROM routes r
                                        JOIN stops dep ON dep.route_id = r.id
                                        JOIN stops arr ON arr.route_id = r.id
                                        JOIN stations dep_st ON dep_st.id = dep.station_id 
                                        JOIN stations arr_st ON arr_st.id = arr.station_id
                                        JOIN trains t ON r.train_id = t.id
                                        
                                        WHERE dep_st.name = '$departure'
                                        AND arr_st.name = '$arrival' 
                                        AND dep.order < arr.order
                                        AND dep.departure_time > '$route_time'
                                        
                            UNION

                            SELECT DISTINCT r.id as route, dep_st.name as departure, arr_st.name as arrival,
                                        r.start_time as dep_time, arr.arrival_time as arr_time,
                                        arr.next_day_arrival as next_day,t.name as train,
                                        t.operator as operator
                                        
                                        FROM routes r
                                        JOIN departures d ON r.departures_id = d.id
                                        JOIN stops arr ON arr.route_id = r.id
                                        JOIN stations dep_st ON dep_st.id = d.station_id
                                        JOIN stations arr_st ON arr_st.id = arr.station_id
                                        JOIN trains t ON r.train_id = t.id
                                        
                                        WHERE dep_st.name = '$departure'
                                        AND arr_st.name = '$arrival' 
                                        AND r.start_time > '$route_time'

                            ORDER BY dep_time
                            LIMIT 10";
        }
        else{
            $routes_query = "SELECT DISTINCT r.id as route, dep_st.name as departure, arr_st.name as arrival,
                                        dep.departure_time as dep_time, arr.arrival_time as arr_time,
                                        arr.next_day_arrival as next_day,t.name as train,
                                        t.operator as operator
                                        
                                        FROM routes r
                                        JOIN stops dep ON dep.route_id = r.id
                                        JOIN stops arr ON arr.route_id = r.id
                                        JOIN stations dep_st ON dep_st.id = dep.station_id 
                                        JOIN stations arr_st ON arr_st.id = arr.station_id
                                        JOIN trains t ON r.train_id = t.id
                                        
                                        WHERE dep_st.name = '$departure'
                                        AND arr_st.name = '$arrival' 
                                        AND dep.order < arr.order
                                        AND arr.arrival_time < '$route_time'
                                        
                            UNION

                            SELECT DISTINCT r.id as route, dep_st.name as departure, arr_st.name as arrival,
                                        r.start_time as dep_time, arr.arrival_time as arr_time,
                                        arr.next_day_arrival as next_day,t.name as train,
                                        t.operator as operator
                                        
                                        FROM routes r
                                        JOIN departures d ON r.departures_id = d.id
                                        JOIN stops arr ON arr.route_id = r.id
                                        JOIN stations dep_st ON dep_st.id = d.station_id
                                        JOIN stations arr_st ON arr_st.id = arr.station_id
                                        JOIN trains t ON r.train_id = t.id
                                        
                                        WHERE dep_st.name = '$departure'
                                        AND arr_st.name = '$arrival' 
                                        AND arr.arrival_time < '$route_time'

                            ORDER BY arr_time DESC
                            LIMIT 10";
        }
        $routes_query_run = mysqli_query($con, $routes_query);
    
        if ($routes_query_run){
    
            $routes = mysqli_fetch_all($routes_query_run, MYSQLI_ASSOC);
            $route_day = date("d-m-Y", strtotime($route_time));

            $trip_stops = array();

            foreach ($routes as &$r){

                $r['dep_time'] = $route_day . date(" H:i", strtotime($r['dep_time']));
                $arr_time = $route_day . date("H:i", strtotime($r['arr_time']));
                $arr_time = date_create($arr_time);
                if ($r['next_day'] == 1){
                    $arr_time = date_add($arr_time, date_interval_create_from_date_string("1 day"));
                }
                $trip_time = date_diff($arr_time, date_create($r['dep_time']));
                $trip_hours = $trip_time -> format("%a") * 24 + $trip_time -> format("%H");
                $trip_min = $trip_time -> format("%I");
                $r['trip_time'] = $trip_hours.':'.$trip_min;
                $r_id = $r['route'];

                $stops_query = "SELECT stops.order as ord, stations.name as name, stops.arrival_time as st_arr,
                                        stops.next_day_arrival as nd_arr, stops.departure_time as st_dep,
                                        stops.next_day_departure as nd_dep, stops.line as line, 
                                        stops.prev_stop_dist as dist 
                                        FROM stops JOIN stations ON stops.station_id = stations.id
                                        WHERE stops.route_id = '$r_id'";

                $stops_query_run = mysqli_query($con, $stops_query);
                $stops = mysqli_fetch_all($stops_query_run, MYSQLI_ASSOC);

                $dep_index = search_nested($departure, $stops);
                $arr_index = search_nested($arrival, $stops);
                $tot_dist = 0;

                for ($i=$arr_index; $i >=max(0,$dep_index); $i--){
                    if ($stops[$i]['name'] == $r['departure']){
                        break;
                    }
                    $trip_stops[$r_id][] = $stops[$i];
                    $tot_dist = $tot_dist + (float) $stops[$i]['dist'];
                }
                $r['dist'] = $tot_dist;

                $seats_1_query = "SELECT cars.number as car, seats.number as seat
                                FROM `cars` JOIN `seats` ON cars.id = `seats`.car_id 
                                JOIN `trains` ON `cars`.trains_id=`trains`.id JOIN `routes` ON `routes`.train_id=`trains`.id
                                WHERE `routes`.id='$r_id' AND `cars`.`class`=1 AND `seats`.`occupied`=0";
                
                $seats_1_query_run = mysqli_query($con, $seats_1_query);
                $seats_1 = mysqli_fetch_all($seats_1_query_run, MYSQLI_ASSOC);

                $seats_2_query = "SELECT cars.number as car, seats.number as seat 
                                FROM `cars` JOIN `seats` ON cars.id = `seats`.car_id 
                                JOIN `trains` ON `cars`.trains_id=`trains`.id JOIN `routes` ON `routes`.train_id=`trains`.id
                                WHERE `routes`.id='$r_id' AND `cars`.`class`=2 AND `seats`.`occupied`=0";
                
                $seats_2_query_run = mysqli_query($con, $seats_2_query);
                $seats_2 = mysqli_fetch_all($seats_2_query_run, MYSQLI_ASSOC);

                $seats_st_query = "SELECT trains.standing_seats as standing
                                FROM `routes` JOIN `trains` ON `routes`.train_id=`trains`.id
                                WHERE `routes`.id='$r_id'";
                
                $seats_st_query_run = mysqli_query($con, $seats_st_query);
                $seats_st = mysqli_fetch_all($seats_st_query_run, MYSQLI_ASSOC);

                $r['seats_1'] = $seats_1;
                $r['seats_2'] = $seats_2;
                $r['seats_st'] = $seats_st;
                $r['passengers'] = $passengers;
            }
            
            $_SESSION['route_info'] = $routes;
            $_SESSION['stops'] = $trip_stops;
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