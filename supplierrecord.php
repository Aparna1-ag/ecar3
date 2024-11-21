<?php
include 'conn.php';
require './authentication.php';
include './INCLUDES/header.php';
$pagename = 'Past Supplier Records';

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
                        <h6>Past Supplier Records</h6>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <?php
                            $sql = "SELECT s.production_id, s.unit, COUNT(s.unit) AS total_units , lp.tracking_id,tracking_service 
                                     FROM supplierrecords s 
                                     LEFT JOIN live_purchase lp on purchase_id = s.production_id
                                     WHERE supplier_id = $user_id 
                                     GROUP BY s.production_id, s.unit";
                            $info = $obj_admin->manage_all_info($sql);
                            $serial  = 1;
                            $num_row = $info->rowCount();
                            if ($num_row == 0) {

                                echo '<tr><td colspan="7" class="d-flex justify-content-center align-items-center">No Projects were found</td></tr>';
                            }
                            ?>

                            <table id="table" class="table align-items-center justify-content-center mb-0">
                                <thead>
                                    <tr>
                                        <th>Odoo Id</th>
                                        <th>Tracking Id</th>
                                        <th>Tracking Service Provider</th>
                                        <th>Quantity</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    while ($row = $info->fetch(PDO::FETCH_ASSOC)) {
                                    ?>
                                        <tr>
                                            <td>
                                                <p class="text-sm font-weight-bold mb-0 ms-4"><?php echo $row['unit'] ?></p>
                                            </td>
                                            <td>
                                                <span class="text-xs font-weight-bold ms-2"><?php echo $row['tracking_id'] ?></span>
                                            </td>
                                            <td>
                                                <span class="text-xs font-weight-bold ms-2"><?php echo $row['tracking_service'] ?></span>
                                            </td>
                                            <td>
                                                <span class="text-xs font-weight-bold ms-2"><?php echo $row['total_units'] ?></span>
                                            </td>

                                            <td class="align-middle d-flex align-content-center justify-content-center">
                                                <a href="viewSupplierrecords.php?purchase_id=<?php echo $row['production_id']; ?>" class="btn btn-primary">View Order</a>
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