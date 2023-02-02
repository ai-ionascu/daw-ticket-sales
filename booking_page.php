<?php
session_start();
$page_title = "Booking Page";
include('include/head.php');
include('include/header.php') ;

?>

<h1>Booking Page</h1>
<?php if (isset($_GET['results'])){?>


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
                                                    <th class="text-center">Departure</th>
                                                    <th class="text-center">Arrival</th>
                                                    <th class="text-center">Departure Time</th>
                                                    <th class="text-center">Total Trip</th>
                                                    <th class="text-center">Train</th>
                                                    <th class="text-center">Operator</th>
                                                    <th class="text-center">Fare</th>
                                                </tr>
                                                </thead>
                                                <tbody class="table-border-bottom-0">
                                                <?php
foreach ($_SESSION['routes_query'] as $key => $row){?>
    
    <tr><td class="text-center"><i class="fab fa-angular fa-lg text-danger me-3"></i> <strong><?php echo $row['departure']?></strong></td>
    <td class="text-center"><strong><?php echo $row['arrival']?></strong></td>
    <td class="text-center"><?php echo $row['dep_time']?></td>
    <td class="text-center"><?php echo $row['trip_time']?></td>
    <td class="text-center"><?php echo $row['train']?></td>
    <td class="text-center"><?php echo $row['operator']?></td>
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

<?php } else {?>

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
                                <!-- <label class="form-label" for="arrivals-time">Arrival Time</label> -->
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
                                <label class="form-label" for="class">Class</label>
                                <select class="form-control" name="class" id="class">
                                    <option>First Class</option>
                                    <option>Second Class</option>
                                </select>
                                <span class="error-message" style="color: red;">
                                    <?php
                                        if(isset($_SESSION['form_errors']['class'])){
                                            echo $_SESSION['form_errors']['class'];
                                            unset($_SESSION['form_errors']['class']);}
                                    ?>
                                </span>
                            </div>
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
  });
</script>

<?php
include('include/tail.php') 
?>