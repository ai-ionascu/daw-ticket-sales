<?php

require_once('conf/config.php');
$servername = $config['SERVER_NAME'];
$username = $config['DB_USER'];
$password = $config['DB_PASS'];
$db_name = $config['DB_NAME'];
$con = mysqli_connect($servername, $username, $password, $db_name);
if (mysqli_connect_errno()) {
  echo "DB connection failed: " . mysqli_connect_error();
  exit();
}

mysqli_select_db($con, $db_name);

include('scrape.php');

# add into trains table
function insert_trains($trains){

    global $con;
    foreach ($trains as $name => $details){

        # check if train exists in the db
        $train_exists_query = "SELECT name FROM trains WHERE name='$name' LIMIT 1";
        $train_exists_query_run = mysqli_query($con, $train_exists_query);

        if (mysqli_num_rows($train_exists_query_run) == 0){
            $train_name = $name;
            $train_type = explode(" ", $name)[0];
            if ($train_type == 'IR' || $train_type == 'IR-N'){
                $standing_seats = 50;
            }
            else {
                $standing_seats =100;
            }
            $operator = $details['operator'];

            $train_query = "INSERT INTO trains (name, type, standing_seats, operator) 
                            VALUES ('$train_name', '$train_type', '$standing_seats', '$operator')";
            $train_query_con = mysqli_query($con, $train_query);
            echo $train_query."\n";
        }
        else{
            continue;
        }
    }
}

function insert_stations($departures, $arrivals, $stops){

    global $con;
    $stations = array_unique(array_merge($departures, $arrivals, $stops));

    foreach ($stations as $station){

        if (in_array($station, $departures) || in_array($station, $arrivals)){
            $is_main_station = 1;
        }
        else{
            $is_main_station = 0;
        }

        # check if station exists in the db
        $station_exists_query = "SELECT * FROM stations WHERE name='$station' LIMIT 1";
        $station_exists_query_run = mysqli_query($con, $station_exists_query);

        if (mysqli_num_rows($station_exists_query_run) == 0){

            $station_query = "INSERT INTO stations (name, is_main_station) 
                            VALUES ('$station', '$is_main_station')";
            $station_query_con = mysqli_query($con, $station_query);
            echo $station_query."\n";
        }
        else{
            $station_status = mysqli_fetch($station_exists_query_run);
            $station_status = $station_status['is_main_station'];
            if ($is_main_station == 1 && $station_status == 0){
                $update_query = "UPDATE stations SET is_main_station='$is_main_station' WHERE name='$station'";
                echo "Entry updated.";
            }
        }
    } 
}

function insert_departures($departures){

    global $con;
    foreach ($departures as $dep){

        $line = random_int(1,10);

        # check if departure exists in the db
        $departure_exists_query = "SELECT departures.id FROM departures JOIN stations ON departures.station_id=stations.id WHERE stations.name='$dep' LIMIT 1";
        $departure_exists_query_run = mysqli_query($con, $departure_exists_query);

        # query stations table for the entry corresponding to $dep value and get the id value
        $station_query = "SELECT id FROM stations WHERE name='$dep'";
        $station_query_run = mysqli_query($con, $station_query);
        $departure_fk = mysqli_fetch($station_query_run);
        $departure_fk = $departure_fk['id'];

        if (mysqli_num_rows($departure_exists_query_run) == 0 && mysqli_num_rows($station_query_run) == 1){

            $departure_query = "INSERT INTO departures (line, station_id) 
                            VALUES ('$line', '$departure_fk')";
            $departure_query_con = mysqli_query($con, $departure_query);
            echo $departure_query."\n";
        }
    } 
}

function insert_arrivals($arrivals){

    global $con;
    foreach ($arrivals as $arr){

        $line = random_int(1,10);

        # check if departure exists in the db
        $arrival_exists_query = "SELECT arrivals.id FROM arrivals JOIN stations ON arrivals.station_id=stations.id WHERE stations.name='$arr' LIMIT 1";
        $arrival_exists_query_run = mysqli_query($con, $arrival_exists_query);

        # query stations table for the entry corresponding to $arr value and get the id value
        $station_query = "SELECT id FROM stations WHERE name='$arr'";
        $station_query_run = mysqli_query($con, $station_query);
        $arrival_fk = mysqli_fetch($station_query_run);
        $arrival_fk = $arrival_fk['id'];

        if (mysqli_num_rows($arrival_exists_query_run) == 0 && mysqli_num_rows($station_query_run) == 1){

            $arrival_query = "INSERT INTO arrivals (line, station_id) 
                            VALUES ('$line', '$arrival_fk')";
            $arrival_query_con = mysqli_query($con, $arrival_query);
            echo $arrival_query."\n";
        }
    } 
}

function insert_routes($routes){

    global $con;
    foreach ($routes as $route){

        $train = $route['train'];
        $departure = $route['departure'];
        $arrival = $route['arrival'];

        $start_time = $route['departure_time'];
        $end_time = $route['arrival_time'];
        $next_day_arrival = $route['next_day_arrival'];

        # check if route exists in the db
        $route_exists_query = "SELECT routes.id FROM routes JOIN trains ON routes.train_id=trains.id WHERE trains.name='$train' LIMIT 1";
        $route_exists_query_run = mysqli_query($con, $route_exists_query);

        $trains_id_query = "SELECT id FROM trains WHERE name='$train'";
        $trains_id_run = mysqli_query($con, $trains_id_query);
        $train_id = mysqli_fetch($trains_id_run);
        $train_id = $train_id['id'];

        $departure_id_query = "SELECT departures.id FROM departures JOIN stations ON departures.station_id=stations.id WHERE stations.name='$departure'";
        $departure_id_run = mysqli_query($con, $departure_id_query);
        $departure_id = mysqli_fetch($departure_id_run);
        $departure_id = $departure_id['id'];

        $arrival_id_query = "SELECT arrivals.id FROM arrivals JOIN stations ON arrivals.station_id=stations.id WHERE stations.name='$arrival'";
        $arrival_id_run = mysqli_query($con, $arrival_id_query);
        $arrival_id = mysqli_fetch($arrival_id_run);
        $arrival_id = $arrival_id['id'];

        if (mysqli_num_rows($route_exists_query_run) == 0 && mysqli_num_rows($trains_id_run) == 1
                            && mysqli_num_rows($departure_id_run) == 1 && mysqli_num_rows($arrival_id_run) == 1){

            $route_query = "INSERT INTO routes (start_time, end_time, next_day_arrival, train_id, departures_id, arrivals_id) 
                            VALUES ('$start_time', '$end_time', '$next_day_arrival', '$train_id', '$departure_id', '$arrival_id')";
            $route_query_con = mysqli_query($con, $route_query);
            echo $route_query."\n";
        }
    }
}

function insert_stops($stops){

    global $con;
    foreach ($stops as $train => $route){
        foreach($route as $key => $stop){

            $name = $stop['name'];

            # query stations table for the entry corresponding to $arr value and get the id value
            $station_query = "SELECT id FROM stations WHERE name='$name'";
            $station_query_run = mysqli_query($con, $station_query);
            $station_fk = mysqli_fetch_assoc($station_query_run);
            $station_fk = $station_fk['id'];

            # query routes table for the entry corresponding to $arr value and get the id value
            $route_query = "SELECT routes.id FROM routes JOIN trains ON routes.train_id=trains.id WHERE trains.name='$train'";
            $route_query_run = mysqli_query($con, $route_query);
            $route_fk = mysqli_fetch_assoc($route_query_run);
            $route_fk = $route_fk['id'];

            # check if stop exists in the db
            $stop_exists_query = "SELECT * FROM stops 
                                    JOIN routes ON stops.route_id=routes.id 
                                    JOIN trains ON routes.train_id=trains.id 
                                    JOIN stations ON stops.station_id=stations.id
                                    WHERE trains.name='$train' 
                                    AND stations.name='$name'
                                    LIMIT 1";
            $stop_exists_query_run = mysqli_query($con, $stop_exists_query);

            if (mysqli_num_rows($stop_exists_query_run) == 0 && 
                mysqli_num_rows($station_query_run) == 1 &&
                mysqli_num_rows($route_query_run) == 1){
                    
                    $line = random_int(1,10);
                    $order = $stop['order'];
                    $prev_stop_dist = $stop['distance'];
                    $arrival_time = $stop['arrival_time'];
                    $next_day_arrival = $stop['next_day_arrival'];
                    // ($stop == end($route)) ? $arrival_time = "" : $arrival_time = $stop['arrival_time'];
                    // ($stop == end($route)) ? $next_day_arrival = "" : $next_day_arrival = $stop['next_day_arrival'];
                    (array_key_exists('departure_time', $stop)) ? $departure_time = $stop['departure_time'] : $departure_time = "NULL";
                    (array_key_exists('next_day_departure', $stop)) ? $next_day_departure = $stop['next_day_departure'] : $next_day_departure = "NULL";

                    $stop_query = "INSERT INTO `stops` (`order`, `arrival_time`, `next_day_arrival`, `departure_time`, `next_day_departure`, `line`, `prev_stop_dist`, `station_id`, `route_id`) 
                                    VALUES ('$order', '$arrival_time', '$next_day_arrival', '$departure_time', '$next_day_departure', '$line', '$prev_stop_dist', '$station_fk', '$route_fk')";
                    $stop_query_con = mysqli_query($con, $stop_query);
                    echo $stop_query."\n";
            }
        }
    } 
}

function insert_cars($trains){

    global $con;
    foreach ($trains as $name => $details){
        $numbers = range(1,9,1);
        foreach ($numbers as $key => $number){

            # check if car exists in the db
            $car_exists_query = "SELECT * FROM cars JOIN trains ON cars.trains_id=trains.id WHERE cars.number=$number AND trains.name='$name' LIMIT 1";
            $car_exists_query_run = mysqli_query($con, $car_exists_query);

            # query trains table for the entry corresponding to $arr value and get the id value
            $train_query = "SELECT id FROM trains WHERE name='$name'";
            $train_query_run = mysqli_query($con, $train_query);
            $train_fk = mysqli_fetch($train_query_run);
            $train_fk = $train_fk['id'];

            if (mysqli_num_rows($car_exists_query_run) == 0 && mysqli_num_rows($train_query_run) == 1){      
                    ($key<4) ? $class=1 : $class=2;
                    $car_query = "INSERT INTO `cars` (`number`, `class`, `trains_id`) 
                                VALUES ('$number', '$class', '$train_fk')";
                    // $route_query_con = mysqli_query($con, $car_query);
                    echo $car_query."\n";
                }
        }
    }
}

function insert_seats(){

    global $con;
    $cars_query = "SELECT id FROM cars";
    $cars_query_run = mysqli_query($con, $cars_query);
    $cars= mysqli_fetch_all($cars_query_run, MYSQLI_ASSOC);
  
    foreach ($cars as $key => $car){
        foreach ($car as $id => $car_id){
            $numbers = range(1,10,1);
            foreach ($numbers as $key => $number){
                # check if seat exists in the db
                $seat_exists_query = "SELECT * FROM seats JOIN cars ON seats.car_id=cars.id WHERE seats.number=$number AND cars.id='$car_id' LIMIT 1";
                $seat_exists_query_run = mysqli_query($con, $seat_exists_query);

                if (mysqli_num_rows($seat_exists_query_run) == 0){      
                        $car_query = "INSERT INTO `seats` (`number`, `locked`, `occupied`, `car_id`) 
                                    VALUES ('$number', 0, 0, '$car_id')";
                        $route_query_con = mysqli_query($con, $car_query);
                        echo $car_query."\n";
                    }
            }
        }
    }
}
    
// insert_trains($trains);   
// insert_stations($departures, $arrivals, $stops);
// insert_departures($departures);
// insert_arrivals($arrivals);
// insert_routes($routes);
insert_stops($stops);
// insert_cars($trains);
// insert_seats();

?>