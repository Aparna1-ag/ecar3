<?php

require './authentication.php';
include './INCLUDES/header.php';
$pagename = 'Production/Continue Production';

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
$production_id = $_GET['production_id'];
$unit_number = $_GET['unit_number'];


?>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        var myModal = new bootstrap.Modal(document.getElementById('editpurchase'));
        myModal.show();
    });
</script>



<div class="modal fade " id="editpurchase" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">

    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header ">
                <h5 class="modal-title" id="exampleModalLabel">Production </h5>

            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-container">



                            <form role="form" action="" method="post" autocomplete="off">
                                <div class="form-horizontal">

                                    <div class="form-group">

                                        <?php
                                        $sql = "SELECT model_name,
               vin_number,
               (CASE WHEN process1_status = 'Waiting' THEN 'Process 1' 
                     WHEN process2_status = 'Waiting' THEN 'Process 2' 
                     WHEN process3_status = 'Waiting' THEN 'Process 3' 
                    
                     ELSE '' END) AS waitingStatus
        FROM production
        WHERE production_id = $production_id
          AND unit = $unit_number";

                                        $info = $obj_admin->manage_all_info($sql);

                                        $serial  = 1;

                                        $num_row = $info->rowCount();

                                        if ($num_row == 0) {

                                            echo 'No vendors were found';
                                        }
                                        while ($row = $info->fetch(PDO::FETCH_ASSOC)) {

                                            $waitingStatus = $row['waitingStatus'];


                                        ?>


                                            <div class="mt-2">
                                                <label for="model">Model Name</label>

                                                <input type="text" value="<?php echo $row['model_name']; ?> " class="form-control" name="modal_name" readonly>


                                            </div>
                                            <div class="mt-2">
                                                <label for="vin_number">VIN Number</label>
                                                <input type="text" value="<?php echo $row['vin_number']; ?> " class="form-control" name="vin_number" readonly>


                                            </div>

                                        <?php } ?>


                                    </div>

                                    <div class="mt-2">
                                        <label for="select_vendor">Select Vendors For <?php

                                                                                        if ($waitingStatus === 'Process 1') {
                                                                                            echo "Mechanical Body Works";
                                                                                        } elseif ($waitingStatus === 'Process 2') {
                                                                                            echo "Electrical Works";
                                                                                        } elseif ($waitingStatus === 'Process 3') {
                                                                                            echo "Accessories Fitting Works";
                                                                                        }

                                                                                        ?>

                                        </label>


                                        <select name="selected_vendor_id" id="selected_vendor_id" class="form-control">
                                            <?php
                                            $sql = "SELECT * from vendors ";
                                            $info = $obj_admin->manage_all_info($sql);

                                            $serial  = 1;

                                            $num_row = $info->rowCount();

                                            if ($num_row == 0) {

                                                echo 'No vendors were found';
                                            }
                                            while ($row = $info->fetch(PDO::FETCH_ASSOC)) {
                                            ?>

                                                <option value="<?php echo $row['vendor_id'] ?>"><?php echo $row['fullname'] ?></option>

                                            <?php } ?>

                                        </select>
                                    </div>


                                </div>
                                <div class="d-flex gap-2 mt-3">
                                    <a class=" form-control btn btn-primary" href="production.php">Back</a>
                                    <button type="submit" name="start_process" class="form-control btn btn-primary">Assign</button>
                                </div>

                            </form>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php

include 'conn.php';

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['start_process'])) {

    $production_id = $_GET['production_id'];
    $model_name = $_POST['modal_name'];
    $selected_vendor_id = $_POST['selected_vendor_id'];
    $vin_number = $_POST['vin_number'];
    $unit = $_GET['unit_number'];
    $status = "Active";

    if ($waitingStatus === "Process 2") {
        $process_status = "process2_status";
        $process = "process2";
    } elseif ($waitingStatus === "Process 3") {
        $process_status = "process3_status";
        $process = "process3";
    }


    // Complete the WHERE clause by specifying the condition for the 'unit' column
    $sql = "UPDATE production 
        SET 
            $process = ?, 
            $process_status = ?
        WHERE production_id = ? AND unit = ?";

    $stmt = $conn->prepare($sql);

    // Bind parameters correctly
    $stmt->bind_param("ssis", $selected_vendor_id, $status, $production_id, $unit);

    if ($stmt->execute()) {
        echo "Order Placed";
        $stmt->close();
        $conn->close();
        // Redirect to the dashboard with a query parameter
        header('Location: production.php?start_process=true');
    } else {
        echo "Error : " . $conn->error;
        $stmt->close();
        $conn->close();
        header('Location: production.php');
    }
}


?>


<?php include './INCLUDES/footer.php'; ?>