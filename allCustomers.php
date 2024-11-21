<?php

require './authentication.php';
include './INCLUDES/header.php';
$pagename = 'Customers';

include './INCLUDES/sidebar.php';
// auth check
$user_id = $_SESSION['admin_id'];
$user_name = $_SESSION['name'];
$security_key = $_SESSION['security_key'];
if ($user_id == NULL || $security_key == NULL) {
    header('Location: index.php');
}
// check admin
$user_role = $_SESSION['user_role'];

?>


<div class="container-fluid py-4">

    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>All Customers Information</h6>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">

                        <table class="table align-items-center justify-content-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 d-flex-column justify-content-center align-content-center">Customer Id</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 d-flex-column justify-content-center align-content-center">Name</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 d-flex-column justify-content-center align-content-center">Email</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 d-flex-column justify-content-center align-content-center">Phone No</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 d-flex-column justify-content-center align-content-center">Address</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 d-flex-column justify-content-center align-content-center">Actions</th>

                                </tr>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                $sql = "SELECT c.* from customers c ";
                                if ($user_role == 1) {

                                    // Admin can see all projects

                                    $sql .= " ORDER BY c.customer_id DESC";
                                }

                                $info = $obj_admin->manage_all_info($sql);

                                $serial  = 1;

                                $num_row = $info->rowCount();

                                if ($num_row == 0) {

                                    echo '<tr><td colspan="7" class="d-flex justify-content-center align-items-center">No Contacts were found</td></tr>';
                                }

                                while ($row = $info->fetch(PDO::FETCH_ASSOC)) {



                                ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex px-2 ">
                                                <h6 class="mb-0 text-sm"><?php echo $row['customer_id'] ?></h6>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex px-2 ">
                                                <h6 class="mb-0 text-sm"><?php echo $row['customer_name'] ?></h6>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex px-2 ">
                                                <h6 class="mb-0 text-sm"><?php echo $row['customer_email'] ?></h6>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex px-2 ">
                                                <h6 class="mb-0 text-sm"><?php echo $row['customer_ph'] ?></h6>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex px-2 ">
                                                <h6 class="mb-0 text-sm"><?php echo $row['customer_address'] ?></h6>
                                            </div>
                                        </td>
                                        <td>
                                            <a href="#" class="btn btn-link d-flex align-content-center justify-content-center mt-3">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php  } ?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>









<?php include './INCLUDES/footer.php'; ?>