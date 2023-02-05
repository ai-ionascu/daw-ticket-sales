<?php
session_start();
$page_title = "Booking Page";
include('include/head.php');
include('include/header.php') ;

?>

<h1>Booking Page</h1>
<?php if (isset($_GET['results']) && !isset($_GET['route'])){?>
                <div class="py-5">
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-md-10">
                                <div class="card users-query">
                                    <h5 class="card-header">Your Trip</h5>
                                    <div class="card-body">
                                        <div class="table-responsive text-nowrap">
                                            <table class="table">
                                                <thead class="table-dark">
                                                <tr>
                                                    <th class="text-center">Date</th>
                                                    <th class="text-center">Departure</th>
                                                    <th class="text-center">Arrival</th>
                                                    <th class="text-center">Departure Time</th>
                                                    <th class="text-center">Trip Time</th>
                                                    <th class="text-center">Distance</th>
                                                    <th class="text-center">Train</th>
                                                    <th class="text-center">Operator</th>
                                                    <!-- <th class="text-center">Fare</th> -->
                                                </tr>
                                                </thead>
                                                <tbody class="table-border-bottom-0">
<?php
foreach ($_SESSION['route_info'] as $key => $row){?>
    <tr><td class="text-center"><strong><?php echo date("d-M-Y", strtotime($row['dep_time']));?></strong></td>
    <td class="text-center"><strong><?php echo $row['departure'];?></strong></td>
    <td class="text-center"><strong><?php echo $row['arrival'];?></strong></td>
    <td class="text-center"><?php echo date("H:i", strtotime($row['dep_time']));?></td>
    <td class="text-center"><?php echo $row['trip_time'];?></td>
    <td class="text-center"><?php echo $row['dist'];?></td>
    <td class="text-center"><?php echo $row['train'];?></td>
    <td class="text-center"><?php echo $row['operator'];?></td>
</tr>
<tr>
    <td colspan="1" class="text-center">
        <p>
            <a class="btn btn-primary" data-bs-toggle="collapse" href="<?php echo '#collapse'.$key;?>" role="button">
                Stops
            </a>
        </p>
        
    </td>
    <form action="logic/booking_details.php" method="post">
        <td colspan="6">
            <div class="form-group mb-3 d-flex justify-content-center">
                <?php if (!empty($row['seats_1'])){ ?>
                    <div class="form-check mx-3">
                        <input class="form-check-input" type="radio" name="fare" id="class_1" value="1" checked>
                        <label class="form-check-label" for="dep_time">1st Class</label>
                    </div>
                <?php } ?>
                <?php if (!empty($row['seats_2'])){ ?>
                    <div class="form-check mx-3">
                        <input class="form-check-input" type="radio" name="fare" id="class_2" value="2">
                        <label class="form-check-label" for="arr_time">2nd Class</label>
                    </div>
                <?php } ?>
                <?php if (empty($row['seats_1']) && empty($row['seats_2']) && !empty($row['seats_st'])){ ?>
                    <div class="form-check mx-3">
                        <input class="form-check-input" type="radio" name="fare" id="stand" value="0">
                        <label class="form-check-label" for="arr_time">Standing</label>
                    </div>
                <?php } ?>
                <?php if (empty($row['seats_1']) && empty($row['seats_2']) && empty($row['seats_st'])){ ?>
                    <span class="error-message" style="color: red;">Sold Out.</span>
                <?php } ?>
                <span class="error-message" style="color: red;">
                    <?php
                        if(isset($_SESSION['form_errors']['fare'])){
                            echo $_SESSION['form_errors']['fare'];
                            unset($_SESSION['form_errors']['fare']);}
                    ?>
                </span>
                <input type="hidden" id="route_data" name="route_data" value="<?php echo htmlentities(json_encode($row)); ?>">
            </div>
        </td>
        <td class="text-center">
            <div class="form-group">
                <button type="submit" name="sel_route" class="btn btn-primary">Select Route</button>
            </div>
        </td>
        </form>        
    </td>
    <tr>
        <td colspan="9">
            <div class="collapse" id="<?php echo 'collapse'.$key;?>">
                <div class="card card-body">
                    
                        <?php $stops = array_reverse($_SESSION['stops'][$row['route']]);
                            foreach ($stops as $i=>$st) {?>
                                <p>   
                                    <span><i><?php echo $i+1;?>. </i><?php echo $st['name'];?> <i>(<?php echo $st['dist'];?> km)</i></span>&emsp;
                                    <span><i>Arrival: <?php 
                                    if ($st['nd_arr'] == '1'){
                                        echo $st['st_arr'].' (+1)</i></span>&emsp;&emsp;';
                                    }else{
                                        echo $st['st_arr'].'</i></span>&emsp;&emsp;';
                                    }?>
                                    <span><?php if ($st != end($stops)) {
                                        if ($st['nd_dep'] == '1'){
                                            echo '<i>Departure: '.$st['st_dep'].' (+1)</i>';
                                        }else{
                                            echo '<i>Departure: '.$st['st_dep'].'</i>';
                                        }
                                    }?></span>
                                </p>
                    <?php } ?>
                </div>
            </div>
        </td>
    </tr>
</tr>
<?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

<?php } 
else if (isset($_GET['route'])){ ?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Reservation Details</h5>
                    <small class="text-muted float-end">Train2Go</small>
                </div>

                <div class="card-body">
                    <form action="logic/confirm_booking.php" method="post">
                        <div class="row">
                            <div class="col-12 col-md-6 mb-3">
                                <label class="form-label" for="first_name">First Name</label>
                                <input type="text" class="form-control" name="first_name" id="first_name" placeholder="First Name">
                                <span class="error-message" style="color: red;">
                                    <?php
                                        if(isset($_SESSION['form_errors']['first_name'])){
                                            echo $_SESSION['form_errors']['first_name'];
                                            unset($_SESSION['form_errors']['first_name']);}
                                    ?>
                                </span>
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                                <label class="form-label" for="last_name">Last Name</label>
                                <input type="text" class="form-control" name="last_name" id="last_name" placeholder="Last Name">
                                <span class="error-message" style="color: red;">
                                    <?php
                                        if(isset($_SESSION['form_errors']['last_name'])){
                                            echo $_SESSION['form_errors']['last_name'];
                                            unset($_SESSION['form_errors']['last_name']);}
                                    ?>
                                </span>
                            </div>
                        </div>
                        <div class="row align-items-center">
                            <div class="col-12 col-md-6 mb-3">
                                <label class="form-label" for="phone">Phone Number</label>
                                <input type="text" class="form-control" name="phone" id="phone" placeholder="Phone Number">
                                <span class="error-message" style="color: red;">
                                    <?php
                                        if(isset($_SESSION['form_errors']['phone'])){
                                            echo $_SESSION['form_errors']['phone'];
                                            unset($_SESSION['form_errors']['phone']);}
                                    ?>
                                </span>
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                                <label class="form-label" for="address">Address</label>
                                <input type="text" class="form-control" name="address" id="address" placeholder="Address">
                                <span class="error-message" style="color: red;">
                                    <?php
                                        if(isset($_SESSION['form_errors']['address'])){
                                            echo $_SESSION['form_errors']['address'];
                                            unset($_SESSION['form_errors']['address']);}
                                    ?>
                                </span>
                            </div>
                        </div>
                        <?php if ($_SESSION['fare'] != 0){?>
                            <div class="row">
                                <div class="col-12 col-md-6 mb-3">
                                    <label class="form-label" for="car">Car</label>
                                    <select class="form-control" name="car" id="car">
                                        <option value="">Select Car</option>
                                        <?php 
                                        $route = $_SESSION['route_data'];
                                        $cars = array();
                                        switch ($_SESSION['fare']){
                                            case "1":
                                                foreach ($route['seats_1'] as $value){ 
                                                    $cars[] = $value['car'];
                                                }
                                                break;

                                            case "2":
                                                $cars = array();
                                                foreach ($route['seats_2'] as $value){ 
                                                    $cars[] = $value['car'];
                                                }
                                                break;
                                        } foreach (array_unique($cars) as $value){ ?>
                                                    <option value="<?php echo $value; ?>">Car <?php echo $value; ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="error-message" style="color: red;">
                                        <?php
                                            if(isset($_SESSION['form_errors']['car'])){
                                                echo $_SESSION['form_errors']['car'];
                                                unset($_SESSION['form_errors']['car']);}
                                        ?>
                                    </span>
                                </div>
                                <div class="col-12 col-md-6 mb-3">
                                    <label class="form-label" for="seat">Seat</label>
                                    <select class="form-control" name="seat" id="seat">
                                        <option value="">Select Seat</option>
                                    </select>
                                    <span class="error-message" style="color: red;">
                                        <?php
                                            if(isset($_SESSION['form_errors']['seat'])){
                                                echo $_SESSION['form_errors']['seat'];
                                                unset($_SESSION['form_errors']['seat']);}
                                        ?>
                                    </span>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="row">
                            <p><span><?php if ($_SESSION['fare'] == '1'){
                                    echo '1st Class';
                            }else if ($_SESSION['fare'] == '2'){
                                    echo '2nd Class';
                                }else{
                                    echo 'Standing';
                                }
                            ?>
                                </span>&emsp;&emsp;&emsp;<span>Price: <?php if ($_SESSION['fare'] == '1'){
                                    echo $price=130;
                            }else if ($_SESSION['fare'] == '2'){
                                    echo $price=80;
                                }else{
                                    echo $price=55;
                                }
                            ?></span></p>
                            <input type="hidden" id="price" name="price" value="<?php echo $price; ?>">
                        </div>
                        <div class="row">
                            <button type="submit" name="book" id="book" class="btn btn-primary">Confirm Booking</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

<?php }
else {?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Ticket Reservation</h5>
                    <small class="text-muted float-end">Train2Go</small>
                </div>
                <div class="card-body">
                    <form action="logic/search_trip.php" method="post">
                        <div class="row">
                            <div class="col-12 col-md-6 mb-3">
                                <label class="form-label" for="departures">Departure</label>
                                <input type="text" class="form-control" list="departures_options" name="departures" id="departures" placeholder="Departure Station">
                                <span class="error-message" style="color: red;">
                                    <?php
                                        if(isset($_SESSION['form_errors']['departures'])){
                                            echo $_SESSION['form_errors']['departures'];
                                            unset($_SESSION['form_errors']['departures']);}
                                    ?>
                                </span>
                                <datalist name="departures_options" id="departures_options">
                                </datalist>
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                                <label class="form-label" for="arrivals">Arrival</label>
                                <input type="text" class="form-control" list="arrivals_options" name="arrivals" id="arrivals" placeholder="Arrival Station">
                                <span class="error-message" style="color: red;">
                                    <?php
                                        if(isset($_SESSION['form_errors']['arrivals'])){
                                            echo $_SESSION['form_errors']['arrivals'];
                                            unset($_SESSION['form_errors']['arrivals']);}
                                    ?>
                                </span>
                                <datalist name="arrivals_options" id="arrivals_options">
                                </datalist>
                            </div>
                        </div>
                        <div class="row align-items-center">
                            <div class="col-12 col-md-6 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="select_time" id="dep_time" value="departure" checked>
                                    <label class="form-check-label" for="dep_time">Departure Time</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="select_time" id="arr_time" value="arrival">
                                    <label class="form-check-label" for="arr_time">Arrival Time</label>
                                </div>
                                <span class="error-message" style="color: red;">
                                    <?php
                                        if(isset($_SESSION['form_errors']['select_time'])){
                                            echo $_SESSION['form_errors']['select_time'];
                                            unset($_SESSION['form_errors']['select_time']);}
                                    ?>
                                </span>
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                                <!-- <label class="form-label" for="last_name-time">Last Name Time</label> -->
                                <input type="datetime-local" class="form-control" name="route_time" id="route_time">
                                <span class="error-message" style="color: red;">
                                    <?php
                                        if(isset($_SESSION['form_errors']['route_time'])){
                                            echo $_SESSION['form_errors']['route_time'];
                                            unset($_SESSION['form_errors']['route_time']);}
                                    ?>
                                </span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-6 mb-3">
                                <label class="form-label" for="passengers">Number of Passengers</label>
                                <select class="form-control" name="passengers" id="passengers">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                </select>
                                <span class="error-message" style="color: red;">
                                    <?php
                                        if(isset($_SESSION['form_errors']['passengers'])){
                                            echo $_SESSION['form_errors']['passengers'];
                                            unset($_SESSION['form_errors']['passengers']);}
                                    ?>
                                </span>
                            </div>
                        </div>
                        <div class="row">
                            <button type="submit" name="search" id="search" class="btn btn-primary">Search Routes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php } ?>

<?php
include('include/alerts.php');
?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.2/jquery.min.js"></script>

<script type="text/javascript">
  $(document).ready(function(){

    $("#departures").keyup(function(){
      var dep_input = $(this).val();
      if (dep_input != ''){
        $.ajax({
            url: "logic/search_trip.php",
            method: "POST",
            data: {dep_input:dep_input},
            success: function(data){
                $("#departures_options").html(data);
                $("#departures_options").css("display", "flex");
            }
        });
      }else{
        $("#departures_options").css("display", "none");
      }
    });

    $("#arrivals").keyup(function(){
        var arr_input = $(this).val();
        if (arr_input != ''){
            $.ajax({
                url: "logic/search_trip.php",
                method: "POST",
                data: {arr_input:arr_input},
                success: function(data){
                    $("#arrivals_options").html(data);
                    $("#arrivals_options").css("display", "flex");
                }
            });
        }else{
            $("#arrivals_options").css("display", "none");
        }
    });

    $("#car").change(function(){
        var car = $(this).val();
        if (car != ''){
            $.ajax({
                url: "logic/confirm_booking.php",
                method: "POST",
                data: {car : car},
                success: function(data){
                    $("#seat").html(data);
                }
            });
        }
    });
  });
</script>

<?php
include('include/tail.php') 
?>