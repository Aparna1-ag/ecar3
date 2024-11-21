<?php

require './authentication.php';
include './INCLUDES/header.php';
$pagename = 'Production/completed-orders';

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
include './conn.php';


if(isset($_GET['process']) && $_GET['process'] == 'true') {
    echo '<script>
        setTimeout(function() {
            Swal.fire({
                title: "Success",
                text: "Order has been moved to sold!",
                icon: "success",
                position: "top-end",
                showConfirmButton: false,
                timer: 2000,
                toast: true,
            });
        }, 1000); // Adjust the delay (in milliseconds) as needed
    </script>';
  }
?>



<div class=" container d-flex justify-content-center my-5 py-2 container  bg-gradient shadow-lg text-uppercase" style="background: #81609d"><h2 class="m-auto text-light" style="font-weight: 400">Completed Orders Tab</h2> </div>


    <div class="row">
        <div class="col-11 m-auto">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h5>Completed Orders</h5>
                </div>
                <div class=" px-0 pt-0 pb-2">
                    <div class="table-responsive p-0 p-3">
                    <table class="table shadow-lg table-bordered table-sm" id="completed-orders-table" name="dataTable" style="border: 1px solid white">
                  <thead style="height:4.5rem;">
                  <tr class="bg-gradient text-light my-2 text-center" style="background: #81609d">
                                    <th scope="col" class="align-middle " width="6%" style="font-weight:500;">Order No</th>
                                    <th scope="col" class="align-middle " width="6%" style="font-weight:500;">VIN Number</th>
                                    <th scope="col" class="align-middle " width="6%" style="font-weight:500;">Customer Name</th>
                                    <th scope="col" class="align-middle " width="6%" style="font-weight:500;">Actions</th>
                                </tr>
                            </thead>

                            <tbody  style="background: #dddddd70;">



                                <?php
                                $sql = "SELECT p.*,lp.customer FROM production p LEFT JOIN live_purchase lp ON p.production_id = lp.purchase_id AND p.unit = lp.unit WHERE p.QC_Status = 'QC_DONE' AND p.sold IS NULL";
                                $info = $obj_admin->manage_all_info($sql);

                                $serial  = 1;

                                $num_row = $info->rowCount();


                                while ($row = $info->fetch(PDO::FETCH_ASSOC)) {


                                ?>
                                 <tr style="height: 5rem;">
                                        <td class="align-middle">
                                            <div class="d-flex px-5 ">
                                                <h6 class="mb-0 text-sm"><?php echo $row['unit']; ?></h6>
                                            </div>
                                        </td>
                                        <td class="align-middle">
                                            <div class="d-flex px-2 ">
                                                <h6 class="mb-0 text-sm"><?php echo $row['vin_number']; ?></h6>
                                            </div>
                                        </td>
                                        <td class="align-middle">
                                            <div class="d-flex px-2 ">
                                                <h6 class="mb-0 text-sm"><?php echo $row['customer']; ?></h6>
                                            </div>
                                        </td>
                                    
                                        <td class="align-middle ">
                                        <a href="#" class="btn btn-sm btn-success text-decoration-none" onclick="showConfirmationModal('<?php echo $row['production_id']; ?>', '<?php echo $row['unit']; ?>')">
            Mark as Sold
        </a>
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


<!-- Confirmation Modal -->
<div class="modal fade" id="soldConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="soldConfirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="soldConfirmationModalLabel">Confirm Sale</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to mark this item as sold?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <a class="btn btn-success" id="confirmSoldButton" href="#">Yes, Mark as Sold</a>
            </div>
        </div>
    </div>



<script>
function showConfirmationModal(productionId, unit) {
    // Set the href attribute of the confirmation button dynamically
    document.getElementById('confirmSoldButton').href = '<?= $_SERVER['PHP_SELF'] ?>?production_id=' + productionId + '&unit=' + unit;
    
    // Show the confirmation modal
    $('#soldConfirmationModal').modal('show');
}
</script>
<script>

$(document).ready(function () {
    var table = $('#completed-orders-table').DataTable({
        paging: true,
        searching: true,
        ordering: true
    });

    $('#customSearchInput').on('keyup', function () {
        table.search(this.value).draw();
    });
});


</script>
<?php
include './conn.php';

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['production_id']) && isset($_GET['unit'])) {
    $productionId = $_GET['production_id'];
    $unit = $_GET['unit'];

    // Perform the update in the database
    $updateSql = "UPDATE production SET sold = 'yes' WHERE production_id = ? AND unit = ?";

    $stmt = $conn->prepare($updateSql);
    $stmt->bind_param("is", $productionId, $unit);
    $stmt->execute();

    // Check if the update was successful
    if ($stmt->affected_rows > 0) {
        // Update successful, you can redirect or perform any additional actions here
        // For example, redirecting back to the page where the confirmation was initiated
        header('Location: completedOrders.php?process=true');
        exit();
    } else {
        // Update did not affect any rows, handle the case where no rows were updated
        echo "No rows were updated. Possibly the record with provided IDs does not exist.";
    }
}
?>


<?php include './INCLUDES/footer.php'; ?>