<header class="bg-dark">
    <div class="container">
        <nav class="navbar navbar-dark navbar-expand-lg bg-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.php"></a>

                <?php if(isset($_SESSION['username'])): ?>
                    <ul class="navbar-nav">
                        <li class="nav-item ms-2">
                            <p class="nav-link" style="color:yellow!important; margin:0px;">
                                <i>Welcome <?php echo $_SESSION['username'];?></i>
                            </p>
                        </li>
                    </ul>
                <?php endif ?>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="index.php">Home</a>
                        </li>
                        <li class="nav-item">
                        <a class="nav-link" href="#">About</a>
                        </li>
                        <li class="nav-item">
                        <a class="nav-link" href="#">New Trip</a>
                        </li>
                        <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Dashboard
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="dashboard.php">My Account</a></li>
                            <li><a class="dropdown-item" href="#">Another action</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#">Something else here</a></li>
                        </ul>
                        </li>
                        <li class="nav-item">
                        <a class="nav-link" href="#">Contact</a>
                        </li>
                    </ul>
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <?php
                            if(isset($_SESSION['username'])){
                                echo    '<li class="nav-item">
                                            <a class="nav-link" aria-current="page" href="sign_out.php">Sign Out</a>
                                        </li>';
                            }
                            else{
                                echo    '<li class="nav-item">
                                            <a class="nav-link" aria-current="page" href="login.php">Sign In</a>
                                        </li>';
                            }
                        ?>
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="signup.php">Register</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</header>