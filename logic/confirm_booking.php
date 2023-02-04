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




?>