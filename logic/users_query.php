<?php

include('./conf/db_con.php');

$users_query = mysqli_query($con, "SELECT * FROM users");
$users_count = mysqli_num_rows($users_query);
$items_per_page = 4;
$num_pages = ceil($users_count/$items_per_page);

if (isset($_GET['page'])){
    $page = $_GET['page'];
}

if (!isset($page) || $page == '1'){
    $start = 0;
}
else{
    $start = $page * $items_per_page - $items_per_page;
}

$users_page = mysqli_query($con, "SELECT * FROM users LIMIT $start,$items_per_page");
?>

<div class="card users-query">
    <h5 class="card-header">Admin Panel</h5>
    <div class="card-body">
    <div class="table-responsive text-nowrap">
        <table class="table">
            <thead class="table-dark">
            <tr>
                <th>Username</th>
                <th>Email</th>
                <th>Password</th>
                <th>Role</th>
                <th>Validated</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody class="table-border-bottom-0">

<?php
while ($row = mysqli_fetch_array($users_page)){
    
    echo '<tr><td><i class="fab fa-angular fa-lg text-danger me-3"></i> <strong>'.$row['username'].'</strong></td>';
    echo '<td>'.$row['email'].'</td>';
    echo '<td>'.$row['password'].'</td>';
    echo '<td>'.$row['role'].'</td>';
    echo '<td>'.$row['validated'].'</td>';
    ?>
                    <td>
                        <div class="dropdown">
                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                            <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <div class="dropdown-menu">
                            <?php
                            if (empty($row['validated'])){
                                echo 
                                    '<a class="dropdown-item" href=""
                                    ><i class="bx bx-edit-alt me-1"></i> Validate</a>';
                                if ($row['role'] != 'user'){
                                    echo
                                        '<a class="dropdown-item" href=""
                                        ><i class="bx bx-edit-alt me-1"></i> Reset Role</a>';
                                }
                            }
                            ?>
                            <a class="dropdown-item" href=""
                                ><i class="bx bx-edit-alt me-1"></i> Edit</a
                            >
                            <a class="dropdown-item" href=""
                                ><i class="bx bx-trash me-1"></i> Delete</a
                            >
                            </div>
                        </div>
                    </td>
                </tr>

    <?php
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