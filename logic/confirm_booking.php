<?php 

session_start();

include('../conf/db_con.php');

if (isset($_POST['car'])){
    $train_car = $_POST['car'];
    $train = $_SESSION['route_data']['train'];
    $seats_query = "SELECT seats.number as seat
                    FROM `cars` JOIN `seats` ON `cars`.id = `seats`.car_id
                    JOIN `trains` ON `trains`.id = `cars`.trains_id
                    WHERE `cars`.number='$train_car' AND `trains`.name='$train'
                    AND `seats`.locked=0 AND `seats`.occupied=0 ";
    $seats_query_run = mysqli_query($con, $seats_query);
    $seats = mysqli_fetch_assoc($seats_query_run);

    if (mysqli_num_rows($seats_query_run) > 0){ 

        while($seats = mysqli_fetch_assoc($seats_query_run)){ 
            
            $seat = $seats['seat']; ?>

        <option value="<?php echo $seat; ?>"><?php echo $seat; ?></option>

        <?php }
    }

    else{
        echo '<span class="error-message" style="color: red;">
                No cars available.
            </span>';
    }
}

if (isset($_POST['book'])){


    if(empty($_POST['first_name'])){
        $form_errors['first_name'] = "The first name is missing.";
    }
    else{
        $first_name = mysqli_real_escape_string($con, $_POST['first_name']);
    }

    if(empty($_POST['last_name'])){
        $form_errors['last_name'] = "The last name is missing.";
    }
    else{
        $last_name = mysqli_real_escape_string($con, $_POST['last_name']);
    }

    if(empty($_POST['phone'])){
        $form_errors['phone'] = "The phone number is missing.";
    }
    else{
        $phone = mysqli_real_escape_string($con, $_POST['phone']);
    }

    if(empty($_POST['address'])){
        $form_errors['address'] = "The address is missing.";
    }
    else{
        $address = mysqli_real_escape_string($con, $_POST['address']);
    }

    if(empty($_POST['car'])){
        $form_errors['car'] = "The car selection is missing.";
    }
    else{
        $car = mysqli_real_escape_string($con, $_POST['car']);
    }

    if(empty($_POST['seat'])){
        $form_errors['seat'] = "The seat selection is missing.";
    }
    else{
        $seat = mysqli_real_escape_string($con, $_POST['seat']);
    }
    $price = mysqli_real_escape_string($con, $_POST['price']);

    if (!empty($form_errors)){
        $_SESSION['form_errors'] = $form_errors;
        $return_page = $_SERVER['PHP_SELF'];
        $route = $_SESSION['route_data']['route'];
        header("Location: ../booking_page.php?results&route=$route");
    }else{
        $add_passenger = "INSERT INTO passengers (first_name, last_name, phone, address) 
                            VALUES('$first_name', '$last_name', '$address', '$phone')";
        $add_passenger_run = mysqli_query($con, $add_passenger);

        if ($add_passenger_run){
            $passenger_id = mysqli_insert_id($con);
        }else{
            $_SESSION['error'] = 'No passenger added.';
        }

        $current_user = $_SESSION['username'];
        $user_id_query = "SELECT users.id as id FROM users
                            WHERE users.username='$current_user'";
        $user_id_query_run = mysqli_query($con, $user_id_query);
        $user_id = mysqli_fetch_assoc($user_id_query_run);
        $user_id = $user_id['id'];


        $train = $_SESSION['route_data']['train'];
        $train_id_query = "SELECT trains.id as id FROM trains
                            WHERE trains.name='$train'";
        $train_id_query_run = mysqli_query($con, $train_id_query);
        $train_id = mysqli_fetch_assoc($train_id_query_run);
        $train_id = $train_id['id'];

        $add_booking = "INSERT INTO bookings (confirmed, booked_by) 
                            VALUES(1, '$user_id')";
        $add_booking_run = mysqli_query($con, $add_booking);

        if ($add_booking_run){
            $booking_id = mysqli_insert_id($con);
        }else{
            $_SESSION['error'] = 'No booking recorded.';
        }

        $seat_id_query = "SELECT s.id as id FROM seats s JOIN cars c ON s.car_id=c.id
                    JOIN trains t ON c.trains_id=t.id
                    WHERE t.name='$train' AND c.number='$car' AND s.number='$seat'";
        $seat_id_query_run = mysqli_query($con, $seat_id_query);
        $seat_id = mysqli_fetch_assoc($seat_id_query_run);
        $seat_id = $seat_id['id'];

        $occupy_seat = "UPDATE seats s JOIN cars c ON s.car_id=c.id
                        JOIN trains t ON c.trains_id=t.id
                        SET s.occupied=1
                        WHERE t.name='$train' AND c.number='$car' AND s.number='$seat'";
        $occupy_seat_run = mysqli_query($con, $occupy_seat);

        if (!$occupy_seat_run){
            $_SESSION['error'] = 'No seat was occupied.';
        }

        $create_ticket = "INSERT INTO ticket (price, booking_id, passenger_id, train_id, seat_id) 
                            VALUES('$price', '$booking_id', '$passenger_id', '$train_id', '$seat_id')";
        $create_ticket_run = mysqli_query($con, $create_ticket);

        if (!$create_ticket_run){
            $_SESSION['error'] = 'No seat was occupied.';
        }

        if (!empty($_SESSION['error'])){
            $_SESSION['error'] = 'Booking unsuccessful.';
        } else{
            $_SESSION['success'] = 'Booking confirmed.';
            mysqli_close($con);
            header('Location: ../index.php');
        }
    }
}

?>