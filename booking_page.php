<?php
session_start();
$page_title = "Booking Page";
include('include/head.php');
include('include/header.php') ;

?>

<h1>Booking Page</h1>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Train Ticket Reservation</h5>
                    <small class="text-muted float-end">Train 2 Go</small>
                </div>
                <div class="card-body">
                    <form>
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label" for="departure">Departure</label>
                                <input type="text" class="form-control" id="departure" placeholder="Departure">
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label" for="arrival">Arrival</label>
                                <input type="text" class="form-control" id="arrival" placeholder="Arrival">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label" for="train">Train Name/Number</label>
                                <input type="text" class="form-control" id="train" placeholder="Train Name/Number">
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label" for="departure-time">Departure Time</label>
                                <input type="date" class="form-control" id="departure-time">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label" for="arrival-time">Arrival Time</label>
                                <input type="date" class="form-control" id="arrival-time">
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label" for="class">Train Class</label>
                                <select class="form-control" id="class">
                                    <option>First Class</option>
                                    <option>Second Class</option>
                                    <option>Third Class</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-6 col-lg-4 mb-3">
                                <label class="form-label" for="passengers">Number of Passengers</label>
                                <input type="number" class="form-control" id="passengers" min="1">
                            </div>
                            <button type="submit" class="btn btn-primary">Book Ticket</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

               


<?php
include('include/alerts.php');
include('include/tail.php') 
?>