<?php
include 'conn.php';
require './authentication.php';
include './INCLUDES/header.php';
$pagename = 'Past Vendor Records';

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



error_reporting(E_ALL);
ini_set('display_errors', 1);
ob_end_flush();

if (isset($_GET['delete_purchase'])) {
    $action_id = $_GET['purchase_id'];



    $sql = "DELETE FROM live_purchase WHERE purchase_id = :id";
    $sent_po = "vendorsdashboard.php";
    $obj_admin->delete_data_by_this_method($sql, $action_id, $sent_po);
}
?>

<div class="container-fluid py-4">




    <div class="container-fluid py-4">



        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h6>Past Vendor Records</h6>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">

                            <?php
                            $production_id = intval($_GET['production_id']);

                            $sql = "SELECT * FROM records WHERE production_id = $production_id";

                            $info = $obj_admin->manage_all_info($sql);

                            $serial  = 1;

                            $num_row = $info->rowCount();

                            if ($num_row == 0) {

                                echo '<tr><td colspan="7" class="d-flex justify-content-center align-items-center">No Projects were found</td></tr>';
                            }
                            ?>

                            <table class="table align-items-center justify-content-center mb-0">
                                <thead>
                                    <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 d-flex-column justify-content-center align-content-center">Order No</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 d-flex-column justify-content-center align-content-center">VIN Number</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2 d-flex-column justify-content-center align-content-center">Time Stamp</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2 d-flex-column justify-content-center align-content-center">JOB</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2 d-flex-column justify-content-center align-content-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    while ($row = $info->fetch(PDO::FETCH_ASSOC)) {


                                    ?>


                                        <tr>
                                            <td>
                                                <p class="text-sm font-weight-bold mb-0  ms-4"><?php echo $row['unit'] ?></p>
                                            </td>

                                            <td>
                                                <p class="text-sm font-weight-bold mb-0  ms-4"><?php echo $row['vin_number'] ?></p>
                                            </td>

                                            <td>
                                                <span class="text-xs font-weight-bold ms-2"><?php echo $row['timestamp'] ?></span>
                                            </td>

                                            <td>
                                               <span class="text-xs font-weight-bold ms-2"><?php echo $row['job'] ?></span>
                                            </td>

                                            <td>
                                               <span class="text-xs font-weight-bold ms-2">Completed</span>
                                            </td>
                                        </tr>




                                    <?php } ?>

                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>


 


    <?php

    include './INCLUDES/footer.php';
    ?>