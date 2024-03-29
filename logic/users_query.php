<?php

include('./conf/db_con.php');
include('./include/alerts.php');

$users_query = mysqli_query($con, "SELECT * FROM users");
$users_count = mysqli_num_rows($users_query);
$items_per_page = 4;
$num_pages = ceil($users_count/$items_per_page);

if (isset($_GET['page'])){
    $page = (int) $_GET['page'];
}
else{
    $page = 1;
}

if (isset($_GET['usr'])){
    $user_update = $_GET['usr'];
}

if (!isset($page) || $page == '1'){
    $start = 0;
}
else{
    $start = $page * $items_per_page - $items_per_page;
}

$users_page = mysqli_query($con, "SELECT * FROM users LIMIT ".$start.",".$items_per_page);
?>

<div class="card users-query">
    <h5 class="card-header">Admin Panel</h5>
    <div class="card-body">
    <div class="table-responsive text-nowrap">
        <table class="table">
            <thead class="table-dark">
            <tr>
                <th class="text-center">Username</th>
                <th class="text-center">Email</th>
                <th class="text-center">Password</th>
                <th class="text-center">Role</th>
                <th class="text-center">Verified</th>
                <th class="text-center">Actions</th>
            </tr>
            </thead>
            <tbody class="table-border-bottom-0">

<?php
while ($row = mysqli_fetch_array($users_page)){
    
    echo '<tr><td><i class="fab fa-angular fa-lg text-danger me-3"></i> <strong>'.$row['username'].'</strong></td>';
    echo '<td>'.$row['email'].'</td>';
    echo '<td>'.$row['password'].'</td>';

    if (isset($_GET['update']) && $row['username']  == $_GET['usr']){ 
        ?>
            <form action="logic/update_user.php" method="post">
                <div class="form-group mb-3">
                    <input type="hidden" id="user_to_update" name="user_to_update" value="<?php echo $row['id'] ?>">
                    <input type="hidden" id="username_to_update" name="username_to_update" value="<?php echo $row['username'] ?>">
                    <input type="hidden" id="page" name="page" value="<?php echo $page ?>">
                </div>
                <div class="form-group mb-3">
                    <td>
                        <select name="role" id="role">
                            <?php
                                $roles = array('admin', 'employee', 'user');
                                foreach ($roles as $role){
                                    if ($role == $row['role']){
                                        echo '<option value="'.$role.'" selected>'.ucfirst($role).'</option>';
                                    }
                                    else{
                                        echo '<option value="'.$role.'">'.ucfirst($role).'</option>';
                                    } 
                                }
                            ?>
                        </select>
                        <span class="error-message" style="color: red;">
                            <?php
                                if(isset($_SESSION['role_upd_err'])){
                                    echo $_SESSION['role_upd_err'];
                                    unset($_SESSION['role_upd_err']);}
                            ?>
                        </span>
                    </td>
                </div>
                <div class="form-group mb-3">
                    <td>
                        <select name="verified" id="verified">
                            <?php
                                if (!empty($row['verified'])){
                                    echo '<option value="yes" selected>Yes</option>
                                        <option value="">No</option>';
                                }
                                else{
                                    echo '<option value="yes">Yes</option>
                                        <option value="" selected>No</option>';
                                }
                            ?>
                        </select>
                        <span class="error-message" style="color: red;">
                            <?php
                                if(isset($_SESSION['verified_err'])){
                                    echo $_SESSION['verified_err'];
                                    unset($_SESSION['verified_err']);}
                            ?>
                        </span>
                    </td>
                </div>
                <div class="form-group">
                    <td class="text-center">
                        <button type="submit" name="update" class="btn btn-primary">Update</button>
                        <a class="btn btn-primary" href="<?php echo $_SERVER['PHP_SELF'] ?>" role="button">Cancel</a>
                    </td>
                </div>
            </form>
        </tr>

        <?php
    }
    else if (isset($_GET['delete']) && $row['username']  == $_GET['usr']){
        ?>
            <td><?php echo $row['role'] ?></td>
            <td><?php echo $row['verified'] ?></td>

                <form action="logic/delete_user.php" method="post">
                    <div class="form-group mb-3">
                        <input type="hidden" id="user_to_delete" name="user_to_delete" value="<?php echo $row['id'] ?>">
                        <input type="hidden" id="username_to_delete" name="username_to_delete" value="<?php echo $row['username'] ?>">
                        <input type="hidden" id="page" name="page" value="<?php echo $page ?>">
                    </div>
                    <div class="form-group">
                        <td class="text-center">
                            <button type="submit" name="delete" class="btn btn-primary">Delete</button>
                            <a class="btn btn-primary" href="<?php echo $_SERVER['PHP_SELF'] ?>" role="button">Cancel</a>
                        </td>
                    </div>
                </form>

        </tr>

        <?php
    }
    else{
        echo '<td>'.$row['role'].'</td>';
        echo '<td>'.$row['verified'].'</td>';

        ?>
            <td>
                <div class="dropdown">
                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                    <i class="bi bi-three-dots-vertical"></i>
                    </button>
                    <div class="dropdown-menu">
                    
                    <a class="dropdown-item" href="<?php echo 'dashboard_page.php?page='.$page.'&update=true&usr='.$row['username'] ?>"
                        ><i class="bx bx-edit-alt me-1"></i> Edit</a>
                    <a class="dropdown-item" href="<?php echo 'dashboard_page.php?page='.$page.'&delete=true&usr='.$row['username'] ?>">
                        <i class="bx bx-trash me-1"></i> Delete</a>
                    </div>
                </div>
            </td>
        </tr>

    <?php
    }
}
?>
            </tbody>
        </table>
    </div>
    </div>
</div>
<br><br><br>
<nav aria-label="Page navigation example">
  <ul class="pagination">
    <!-- <li class="page-item">
      <a class="page-link" href="#" aria-label="Previous">
        <span aria-hidden="true">&laquo;</span>
      </a>
    </li> -->

<?php

foreach (range(1,$num_pages) as $i){
    ?>
    
    <li class="page-item"><a class="page-link" href="dashboard_page.php?page=<?php echo $i; ?>"><?php echo $i.' '; ?></a></li><?php
}

?>

    <!-- <li class="page-item">
      <a class="page-link" href="#" aria-label="Next">
        <span aria-hidden="true">&raquo;</span>
      </a>
    </li> -->
  </ul>
</nav>

<!-- <div class="card users-query">
                    <h5 class="card-header">Admin Panel</h5>
                    <div class="table-responsive text-nowrap">
                        <table class="table">
                            <thead class="table-dark">
                            <tr>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Password</th>
                                <th>Role</th>
                                <th>Verified</th>
                            </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                            <tr>

                                <td><i class="fab fa-angular fa-lg text-danger me-3"></i> <strong>username</strong></td>
                                <td>email</td>
                                <td>password</td>
                                <td>role</td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                        <a class="dropdown-item" href="javascript:void(0);"
                                            ><i class="bx bx-edit-alt me-1"></i> Edit</a
                                        >
                                        <a class="dropdown-item" href="javascript:void(0);"
                                            ><i class="bx bx-trash me-1"></i> Delete</a
                                        >
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><i class="fab fa-angular fa-lg text-danger me-3"></i> <strong>User Id</strong></td>
                                <td>user id</td>
                                <td>username</td>
                                <td><span class="badge bg-label-primary me-1">password</span></td>
                                <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                    <a class="dropdown-item" href="javascript:void(0);"
                                        ><i class="bx bx-edit-alt me-1"></i> Edit</a
                                    >
                                    <a class="dropdown-item" href="javascript:void(0);"
                                        ><i class="bx bx-trash me-1"></i> Delete</a
                                    >
                                    </div>
                                </div>
                                </td>
                            </tr>
                            <tr>
                                <td><i class="fab fa-angular fa-lg text-danger me-3"></i> <strong>User Id</strong></td>
                                <td>user id</td>
                                <td>username</td>
                                <td><span class="badge bg-label-primary me-1">password</span></td>
                                <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                    <a class="dropdown-item" href="javascript:void(0);"
                                        ><i class="bx bx-edit-alt me-1"></i> Edit</a
                                    >
                                    <a class="dropdown-item" href="javascript:void(0);"
                                        ><i class="bx bx-trash me-1"></i> Delete</a
                                    >
                                    </div>
                                </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div> -->