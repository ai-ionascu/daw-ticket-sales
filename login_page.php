<?php
session_start();
$page_title = "Sign In Page";
include('include/head.php');
include('include/header.php') ;
?>    
<h1>Sign In</h1>
<?php include('include/alerts.php'); ?>
<div class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="card shadow">
                    <div class="card-header">
                        <h5>Sign In</h5>
                    </div>
                    <div class="card-body">
                        <form action="logic/signin.php" method="post">
                            <div class="form-group mb-3">
                                <label for="">Username or Email Address</label>
                                <input type="text" name="user_email" class="form-control">
                                <span class="error-message" style="color: red;">
                                    <?php
                                        if(isset($_SESSION['login_errors']['user_email'])){
                                            echo $_SESSION['login_errors']['user_email'];
                                            unset($_SESSION['login_errors']['user_email']);}
                                    ?>
                                </span>
                            </div>
                            <div class="form-group mb-3">
                                <label for="">Password</label>
                                <input type="password" name="password" class="form-control">
                                <span class="error-message" style="color: red;">
                                    <?php
                                        if(isset($_SESSION['login_errors']['password'])){
                                            echo $_SESSION['login_errors']['password'];
                                            unset($_SESSION['login_errors']['password']);}
                                    ?>
                                </span>
                            </div>
                            <div class="form-group">
                                <button type="submit" name="login" class="btn btn-primary float-end">Sign In</button>
                            </div>
                        </form>
                        <p>
                            Not yet a member? <a href="signup.php">Sign up</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('include/tail.php') ?>