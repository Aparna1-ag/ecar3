<?php

include 'conn.php';
require './authentication.php';
include './INCLUDES/header.php';
$pagename = 'Supplier Dashboard';

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

?>


<div class="container-fluid py-4">
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h6>Supplier Request</h6>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table id="table" class="table align-items-center justify-content-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2 d-flex-column justify-content-center align-content-center">Order No</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2 d-flex-column justify-content-center align-content-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $purchase_id = $_GET['purchase_id'];
                                    $sql = "SELECT * FROM live_purchase  WHERE purchase_id = $purchase_id";
                                    $info = $obj_admin->manage_all_info($sql);
                                    $serial  = 1;
                                    $num_row = $info->rowCount();
                                    if ($num_row == 0) {
                                        echo '<tr><td colspan="7" class="d-flex justify-content-center align-items-center">No Projects were found</td></tr>';
                                    }
                                    while ($row = $info->fetch(PDO::FETCH_ASSOC)) {
                                    ?>
                                        <tr>
                                            <td>
                                                <span class="text-xs font-weight-bold ms-2"><?php echo $row['unit'] ?></span>
                                            </td>
                                            <td class="align-middle d-flex align-content-center justify-content-center">
                                              

                                                <a href="./universalfileupload.php?orderId=<?php echo $row['purchase_id'] ?>" class="btn btn-primary">
                                                    File Upload
                                                </a>
                                                &nbsp;
                                                <?php
                                                $sql = "SELECT * FROM live_purchase WHERE purchase_id = $purchase_id";
                                                $result = mysqli_query($conn, $sql);

                                                // Check if the query executed successfully
                                                if ($result) {
                                                    // Fetch the row from the result set
                                                    $row = mysqli_fetch_assoc($result);

                                                    // Check if final_qms is not 'Complete'
                                                    if ($row['final_qms'] != 'Complete') {
                                                        // If condition is met, display the Quality Assurance button
                                                ?>
                                                        <a href="supplierCompletionForm.php?purchase_id=<?php echo $row['purchase_id']; ?>&unitNo=<?php echo $row['unit']; ?>" class="btn btn-primary">Quality Assurance</a>
                                                <?php
                                                    }
                                                }
                                                ?>

                                                &nbsp;

                                                <?php
                                                $sql = "SELECT * FROM live_purchase WHERE purchase_id = $purchase_id";
                                                $result = mysqli_query($conn, $sql);

                                                // Check if the query executed successfully
                                                if ($result) {
                                                    // Fetch the row from the result set
                                                    $row = mysqli_fetch_assoc($result);

                                                    // Check if final_qms is not 'Complete'
                                                    if ($row['adr_qms'] != 'Complete') {
                                                        // If condition is met, display the Quality Assurance button
                                                ?>
                                                        <a href="supplierADRchecklist.php?purchase_id=<?php echo $row['purchase_id']; ?>&unitNo=<?php echo $row['unit']; ?>" class="btn btn-primary">ADR Checklist</a>
                                                <?php
                                                    }
                                                }
                                                ?>
                                                &nbsp;
                                                <a href="updateTrackingInfo.php?purchase_id=<?php echo $row['purchase_id']; ?>&unitNo=<?php echo $row['unit']; ?>" class="btn btn-primary">Update Tracking Info</a>



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





    <?php include './INCLUDES/footer.php'; ?>