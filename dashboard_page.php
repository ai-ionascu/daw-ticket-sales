<?php
session_start();
$page_title = "User Dashboard";
include('./include/head.php');
include('./include/header.php') ;
?>    

<div class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow">
                    <div class="card-header">
                           <h5>Hi 
                            <?php if (isset($_SESSION['username'])){
                                echo $_SESSION['username'].'. Your role is "'.$_SESSION['role'].'"';
                                if (!empty($_SESSION['verified'])){
                                    echo ' (verified)';
                                }
                                else{
                                    echo ' (not verified)';
                                }
                            }
                            else{
                                echo 'Guest';
                            }
                            ?>
                        </h5>
                    </div>
                </div>
                <br><br><br>
                <?php
                if (isset($_SESSION['username']) && $_SESSION['role'] == 'admin' && isset($_SESSION['verified'])){
                    include('./logic/users_query.php');
                }
                ?>
            </div>
        </div>
    </div>
</div>

<?php include('./include/tail.php') ?>