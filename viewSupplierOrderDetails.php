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

?>


<div class="container-fluid py-4">
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-11 m-auto">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h5>Supplier Request</h5>
                    </div>
                    <div class=" px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                         
            <table class="table shadow-lg table-bordered table-sm" id="live-orders-table" name="dataTable" style="border: 1px solid white">
  
  <thead style="height:4.5rem;">
  <tr class="bg-gradient  text-light my-2 text-center" style= "background: #28928b">
                                        <th cscope="col" class="align-middle " width="6%" style="font-weight:500;">Order No.</th>
                                        <th scope="col" class="align-middle " width="6%" style="font-weight:500;">Actions</th>
                                        <th scope="col" class="align-middle " width="6%" style="font-weight:500;">Work</th>
                                    </tr>
                                </thead>
                                <tbody style="background: #dddddd70;">
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
                                        <tr style="height: 5rem;">
                                            <td class="align-middle" >
                                                <span class="text-xs font-weight-bold ms-2"><?php echo $row['unit'] ?></span>
                                            </td>
                                            <td class="pt-4 text-center">


                                                <a href="./universalfileupload.php?orderId=<?php echo $row['purchase_id'] ?>" class="btn btn-primary mx-2">
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

                                                    // Check if final_qms is 'Complete'
                                                    if ($row['final_qms'] == 'Complete') {
                                                        // If final_qms is 'Complete', display the button as grey and disabled
                                                        echo '<a href="#" class="btn btn-secondary mx-2" disabled>Quality Assurance</a>';
                                                    } else {
                                                        // If final_qms is not 'Complete', display the normal button
                                                        echo '<a href="supplierCompletionForm.php?purchase_id=' . $row['purchase_id'] . '&unitNo=' . $row['unit'] . '" class="btn btn-info">Quality Assurance</a>';
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

                                                    // Check if adr_qms is 'Complete'
                                                    if ($row['adr_qms'] == 'Complete') {
                                                        // If adr_qms is 'Complete', display the button as grey and disabled
                                                        echo '<a href="#" class="btn btn-secondary mx-2" disabled>ADR Checklist</a>';
                                                    } else {
                                                        // If adr_qms is not 'Complete', display the normal button
                                                        echo '<a href="supplierADRchecklist.php?purchase_id=' . $row['purchase_id'] . '&unitNo=' . $row['unit'] . '" class="btn btn-success">ADR Checklist</a>';
                                                    }
                                                }
                                                ?>


                                                &nbsp;
                                                </td>
                                                <td class="pt-4 d-flex justify-content-center ">
                                                <a href="updateTrackingInfo.php?purchase_id=<?php echo $row['purchase_id']; ?>&unitNo=<?php echo $row['unit']; ?>" class="btn btn-warning">Update Tracking Info</a>
                                                &nbsp;
                                                <?php
    // Fetching the row again to ensure it reflects the correct value
    $sql = "SELECT * FROM live_purchase WHERE purchase_id = $purchase_id";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        // Fetch the row from the result set
        $row = mysqli_fetch_assoc($result);

        // Check if supplier_status is 'Complete'
        if ($row['supplier_status'] == 'Complete') {
            // If supplier_status is 'Complete', disable the button
            echo '<a href="#" class="btn btn-secondary mx-2" disabled>Vehicle Complete</a>';
        } else {
            // If supplier_status is not 'Complete', allow button to be clicked
            echo '<a href="#" class="btn btn-green mx-2" data-bs-toggle="modal" data-bs-target="#showConfirmationModal" onclick="setConfirmationModal(' . $row['purchase_id'] . ')">Mark as Vehicle Complete</a>';
        }
    }
    ?>
                                                </td>


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
                                                function setConfirmationModal(purchaseId) {
                                                    const confirmButton = document.getElementById('confirmSoldButton');
                                                    confirmButton.href = '<?= $_SERVER['PHP_SELF'] ?>?purchase_id=' + purchaseId + '&action=complete';
                                                }
                                            </script>
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
// Ensure that the script only runs if the HTTP request method is GET
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['purchase_id'], $_GET['action']) && $_GET['action'] === 'complete') {
    // Validate the purchase_id to ensure it's an integer
    $productionId = filter_var($_GET['purchase_id'], FILTER_VALIDATE_INT);

    if ($productionId === false) {
        // If the purchase_id is invalid, stop further processing
        die("Invalid purchase ID.");
    }

    // Include database connection

    // Prepare the SQL query to update the record
    $updateSql = "UPDATE live_purchase 
                  SET supplier_status = 'Complete', purchase_status = 'Received From Supplier' 
                  WHERE purchase_id = ?";

    // Prepare the statement
    if ($stmt = $conn->prepare($updateSql)) {
        // Bind the parameter
        $stmt->bind_param("i", $productionId);

        // Execute the statement
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                // Update was successful; redirect to the liveOrders.php page
                header('Location: liveOrders.php');
                exit; // Ensure no further code is executed
            } else {
                // No rows were updated; handle the case
                // echo "No records were updated. This may mean the purchase ID does not exist or no changes were needed.";
            }
        } else {
            // Handle execution errors
            echo "Error executing the query: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        // Handle preparation errors
        echo "Failed to prepare the query: " . $conn->error;
    }

    // Close the database connection
} else {
    // Invalid request handling
}
?>




    <?php include './INCLUDES/footer.php'; ?>