<?php
session_start();
$page_title = "Contact Page";
include('include/head.php');
include('include/header.php') ;

?>

<h1>Contact Page</h1>
<?php include('include/alerts.php'); ?>
<div class="container">
  <div class="row">
    <div class="col-xl">
      <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0">Contact</h5>
          <small class="text-muted float-end">Train 2 Go</small>
        </div>
        <div class="card-body">
          <form action="logic/contact.php" method="post">
          <div class="form-group mb-3">
              <label class="form-label" for="subject">Subject</label>
              <input type="text" class="form-control" name="subject" id="subject" placeholder="Subject..." />
              <span class="error-message" style="color: red;">
                  <?php
                      if(isset($_SESSION['form_errors']['subject'])){
                          echo $_SESSION['form_errors']['subject'];
                          unset($_SESSION['form_errors']['subject']);}
                  ?>
              </span>
            </div>
            <div class="row">
              <div class="form-group col-6 mb-3">
                <label class="form-label" for="firstname">First Name</label>
                <input type="text" class="form-control" name="firstname" id="firstname" placeholder="Your first name here..." />
                <span class="error-message" style="color: red;">
                    <?php
                        if(isset($_SESSION['form_errors']['firstname'])){
                            echo $_SESSION['form_errors']['firstname'];
                            unset($_SESSION['form_errors']['firstname']);}
                    ?>
                </span>
              </div>
              <div class="form-group col-6 mb-3">
                <label class="form-label" for="lastname">Last Name</label>
                <input type="text" class="form-control" name="lastname" id="lastname" placeholder="Your last name here..." />
                <span class="error-message" style="color: red;">
                  <?php
                      if(isset($_SESSION['form_errors']['lastname'])){
                          echo $_SESSION['form_errors']['lastname'];
                          unset($_SESSION['form_errors']['lastname']);}
                  ?>
                </span>
              </div>
            </div>
            <div class="row">
              <div class="form-group col-6 mb-3">
                <label class="form-label" for="email">Email</label>
                <div class="input-group input-group-merge">
                  <input type="text" name="email" id="email" class="form-control" placeholder="email@example.com"/>
                  <span class="error-message" style="color: red;">
                    <?php
                        if(isset($_SESSION['form_errors']['email'])){
                            echo $_SESSION['form_errors']['email'];
                            unset($_SESSION['form_errors']['email']);}
                    ?>
                  </span>
                </div>
                <div class="form-text">You can use letters, numbers & periods</div>
              </div>
              <div class="form-group col-6 mb-3">
                <label class="form-label" for="phone">Phone No</label>
                <input type="text" name="phone" id="phone" class="form-control phone-mask" placeholder="Your number here..."/>
                <span class="error-message" style="color: red;">
                  <?php
                      if(isset($_SESSION['form_errors']['phone'])){
                          echo $_SESSION['form_errors']['phone'];
                          unset($_SESSION['form_errors']['phone']);}
                  ?>
                </span>
              </div>
            </div>
            <div class="form-group mb-3">
              <label class="form-label" for="message">Message</label>
              <textarea name="message" id="message" class="form-control" placeholder="Tell us how can we help you..."></textarea>
              <span class="error-message" style="color: red;">
                <?php
                    if(isset($_SESSION['form_errors']['message'])){
                        echo $_SESSION['form_errors']['message'];
                        unset($_SESSION['form_errors']['message']);}
                ?>
              </span>
            </div>
            <button type="submit" name="contact" class="btn btn-primary">Send</button>
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

