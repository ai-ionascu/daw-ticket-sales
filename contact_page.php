<?php
session_start();
$page_title = "Contact Page";
include('include/head.php');
include('include/header.php') ;
include(dirname(__FILE__).'/conf/db_con.php');
?>

<h1>Home Page</h1>
<div class="container">
  <div class="row">
    <div class="col-xl">
      <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0">Contact</h5>
          <small class="text-muted float-end">Train 2 Go</small>
        </div>
        <div class="card-body">
          <form>
            <div class="mb-3">
              <label class="form-label" for="basic-default-fullname">First Name</label>
              <input type="text" class="form-control" id="basic-default-fullname" placeholder="Your first name here..." />
            </div>
            <div class="mb-3">
              <label class="form-label" for="basic-default-company">Last Name</label>
              <input type="text" class="form-control" id="basic-default-company" placeholder="Your last name here..." />
            </div>
            <div class="mb-3">
              <label class="form-label" for="basic-default-email">Email</label>
              <div class="input-group input-group-merge">
                <input
                  type="text"
                  id="basic-default-email"
                  class="form-control"
                  placeholder="email@example.com"
                />
              </div>
              <div class="form-text">You can use letters, numbers & periods</div>
            </div>
            <div class="mb-3">
              <label class="form-label" for="basic-default-phone">Phone No</label>
              <input
                type="text"
                id="basic-default-phone"
                class="form-control phone-mask"
                placeholder="Your number here..."
              />
            </div>
            <div class="mb-3">
              <label class="form-label" for="basic-default-message">Message</label>
              <textarea
                id="basic-default-message"
                class="form-control"
                placeholder="Tell us how can we help you..."
              ></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Send</button>
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

