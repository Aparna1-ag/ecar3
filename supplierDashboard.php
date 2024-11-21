<?php
ob_start();
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
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 d-flex-column justify-content-center align-content-center">Odoo Id</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2 d-flex-column justify-content-center align-content-center">Total Unit</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2 d-flex-column justify-content-center align-content-center">Date/Time</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2 d-flex-column justify-content-center align-content-center">Tracking Id</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2 d-flex-column justify-content-center align-content-center">Tracking Service Provider</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2 d-flex-column justify-content-center align-content-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $check_id = isset($_GET['id']);

                                    if (!empty($check_id)) {
                                        $id = $_GET['id'];
                                        $sql = "SELECT 
                                    purchase_timestamp, 
                                    purchase_id, 
                                    unit,
                                    tracking_id,
                                    tracking_service,
                                    COUNT(unit) AS total_units 
                                FROM 
                                    live_purchase 
                                WHERE 
                                      purchase_id = $id
                                GROUP BY 
                                    purchase_timestamp, 
                                    purchase_id";
                                    } else {
                                        $sql = "SELECT 
                                    purchase_timestamp, 
                                    purchase_id, 
                                    unit,
                                    tracking_id,
                                    tracking_service,
                                    COUNT(unit) AS total_units 
                                FROM 
                                    live_purchase 
                                WHERE 
                                     purchase_status IN ('Sent To Supplier', 'InProcess') 
                                     AND (supplier_status != 'Complete' OR supplier_status IS NULL)
                                GROUP BY 
                                    purchase_timestamp, 
                                    purchase_id";
                                    }



                                    $info = $conn->query($sql);

                                    if ($info->num_rows > 0) {


                                        while ($row = $info->fetch_assoc()) {
                                    ?>
                                            <tr>
                                                <td>
                                                    <p class="text-sm font-weight-bold mb-0 ms-4"><?php echo $row['unit'] ?></p>
                                                </td>
                                                <td>
                                                    <span class="text-xs font-weight-bold ms-2"><?php echo $row['total_units'] ?></span>
                                                </td>
                                                <td>
                                                    <span class="text-xs font-weight-bold ms-2"><?php echo $row['purchase_timestamp'] ?></span>
                                                </td>
                                                <td>
                                                    <span class="text-xs font-weight-bold ms-2"><?php echo $row['tracking_id'] ?></span>
                                                </td>
                                                <td>
                                                    <span class="text-xs font-weight-bold ms-2"><?php echo $row['tracking_service'] ?></span>
                                                </td>
                                                <td class="align-middle d-flex align-content-center justify-content-center">
                                                    <a href="viewSupplierOrderDetails.php?purchase_id=<?php echo $row['purchase_id']; ?>" class="btn btn-primary">View Order</a>
                                                    &nbsp;
                                                    <a href="#" class="btn btn-primary" onclick="showConfirmationModal('<?php echo $row['purchase_id']; ?>')">Save Changes</a>
                                                </td>
                                            </tr>
                                    <?php }
                                    } ?>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="showConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="showConfirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="showConfirmationModalLabel">Confirm Complete Order</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to mark this item as completed? <br>

                    <span class="text-warning">Caution: This order will not appear on the dashboard until it is marked as completed and all QC checks are uploaded.</span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <a class="btn btn-success" id="confirmSoldButton" href="#">Completed</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showConfirmationModal(purchaseId) {
            // Set the href attribute of the confirmation button dynamically
            document.getElementById('confirmSoldButton').href = '<?= $_SERVER['PHP_SELF'] ?>?purchase_id=' + purchaseId;

            // Show the confirmation modal
            var myModal = new bootstrap.Modal(document.getElementById('showConfirmationModal'));
            myModal.show();
        }
    </script>


    <?php

    if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['purchase_id'])) {
        $productionId = $_GET['purchase_id'];

        // Perform the update in the database
        $updateSql = "UPDATE live_purchase SET supplier_status = 'Complete', purchase_status = 'Received From Supplier' WHERE purchase_id = ?";


        $stmt = $conn->prepare($updateSql);
        $stmt->bind_param("i", $productionId);
        $stmt->execute();

        // Check if the update was successful
        if ($stmt->affected_rows > 0) {
            // Update successful, you can redirect or perform any additional actions here
            // For example, redirecting back to the page where the confirmation was initiated
            header('Location: supplierDashboard.php?process=true');
            exit();
        } else {
            // Update did not affect any rows, handle the case where no rows were updated
            echo "No rows were updated. Possibly the record with provided IDs does not exist.";
        }
    }

    ob_end_flush();
    ?>



    <?php include './INCLUDES/footer.php'; ?>