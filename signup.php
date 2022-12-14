<?php
session_start();
if(isset($_SESSION['visibility'])){
    $visibility = $_SESSION['visibility'];
}
else{
    unset($visibility);
}
$page_title = "Registration Page";
include('include/head.php');
include('include/header.php');
?>  

    <div class="row justify-content-center">
        <h1>Registration</h1>
    </div>

<?php

include('include/alerts.php');

if (isset($visibility) && $visibility[0]== 0){
    echo '<div name="email_verification_form" id="email_verification_form" class="py-5'.' d-none'.'">';
    unset($_SESSION['visibility']);
}
else{
echo '<div name="email_verification_form" id="email_verification_form" class="py-5">';
}
?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="card shadow">
                    <div class="card-header">
                        <h5>Email Verification</h5>
                    </div>
                    <div class="card-body">
                        <form id="email_form" action="register.php" method="POST">
                            <div class="form-group mb-3">  
                                <label for="email">Enter a valid email address</label> 
                                <input type="text" class="form-control" name="email" id="email" placeholder="Type your email address here">
                                <span class="error-message" style="color:red;"><?php if(isset($_SESSION['email_err'])){echo $_SESSION['email_err'];unset($_SESSION['email_err']);}?></span>
                            </div>
                            <div class="form-group mb-3 float-end">
                                <button type="submit" name= "send_code" id="send_code" class="btn btn-primary">Send Verification Code</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
if (isset($visibility) && $visibility[1]== 1){
    echo '<div name="code_verification_form" id="code_verification_form" class="py-5">';
    unset($_SESSION['visibility']);
}
else{
    echo '<div name="code_verification_form" id="code_verification_form" class="py-5'.' d-none'.'">';
}
?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="card shadow">
                    <div class="card header">
                        <h5>Verify Code</h5>
                    </div>
                    <div class="card-body">
                    <form id="code_form" action="register.php" method="POST">
                        <div class="form-group mb-3">  
                            <label for="mobile">Enter the code received on your email</label>
                            <input type="text" class="form-control" name="verification_code" id="verification_code" placeholder="Type your code here">
                            <span class="error-message" style="color: red;">
                                <?php 
                                    if(isset($_SESSION['code_err']))
                                        {echo $_SESSION['code_err'];
                                        unset($_SESSION['code_err']);} 
                                ?>
                            </span>
                        </div>
                        <div class="form-group mb-3 float-end">
                            <button type="submit" class="btn btn-primary" name="verify_code" id="verify_code">Verify Code</button>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
if (isset($visibility) && $visibility[2]== 1){
    echo '<div name="registration_form" id="registration_form" class="py-5">';
    unset($_SESSION['visibility']);
}
else{
    echo '<div name="registration_form" id="registration_form" class="py-5'.' d-none'.'">';
}
?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="card shadow">
                    <div class="card-header">
                        <h5>New User Registration</h5>
                    </div>
                    <div class="card-body">
                        <form id="register" action="register.php" method="POST">
                            <div class="form-group mb-3">
                                <label for="">Username</label>
                                <input type="text" name="username" class="form-control">
                                <span class="error-message" style="color: red;">
                                    <?php
                                        if(isset($_SESSION['reg_errors']['username_err'])){
                                            echo $_SESSION['reg_errors']['username_err'];
                                            unset($_SESSION['reg_errors']['username_err']);}
                                    ?>
                                </span>
                            </div>
                            <div class="form-group mb-3">
                                <label for="">Your Email Address</label>
                                <input type="email" name="email_reg" id="email_reg" class="form-control" value="<?php echo $_SESSION['email']; ?>">
                            </div>
                            <div class="form-group mb-3">
                                <label for="">Password</label>
                                <input type="password" name="password" class="form-control">
                                <span class="error-message" style="color: red;">
                                    <?php
                                        if(isset($_SESSION['reg_errors']['pass_err'])){
                                            echo $_SESSION['reg_errors']['pass_err'];
                                            unset($_SESSION['reg_errors']['pass_err']);}
                                    ?>
                                </span>
                            </div>
                            <div class="form-group mb-3">
                                <label for="">Repeat Password</label>
                                <input type="password" name="repeat_password" class="form-control">
                                <span class="error-message" style="color: red;">
                                    <?php
                                        if(isset($_SESSION['reg_errors']['repass_err'])){
                                            echo $_SESSION['reg_errors']['repass_err'];
                                            unset($_SESSION['reg_errors']['repass_err']);}
                                    ?>
                                </span>
                            </div>
                            <div class="form-group">
                                <button type="submit" name="reg_btn" class="btn btn-primary float-end">Register</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- <script type="text/javascript">
$(document).ready(function(){
    $(#send_code).on("submit",function(e){
        e,preventDefault();
        var email = $(#email).val()
        $.ajax({
            url: "send_email.php",
            type: "POST"
            cache: false,
            data: {email:email},
            success: function(response){
                var jsonData = JSON.parse(response);
                if (jsonData.response == "1"){
                    $(.alert-success, #code_verification_form).show();
                    $(#registration_form).hide();
                }
                else{
                    $(.alert-error).show();
                }
                
            }
        });
    });
});
</script> -->
<?php include('include/tail.php') ?>