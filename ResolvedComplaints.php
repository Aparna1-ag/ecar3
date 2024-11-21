<?php

include 'conn.php';
require './authentication.php';
include './INCLUDES/header.php';
$pagename = 'Resolved Complaints';

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
                    <h6>Resolved Complaints</h6>
                </div>
                <div class="card-body ">
                <div class="container-fluid">
                    <ul class="nav nav-tabs">
                    </ul>
                    <div class="card mt-2" id="manage_users">
                        <div class="card-body">
                            <table class="table align-items-center justify-content-center ">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 d-flex-column justify-content-center align-content-center">VIN Number</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 d-flex-column justify-content-center align-content-center">Model Name</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 d-flex-column justify-content-center align-content-center">Customer Name</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 d-flex-column justify-content-center align-content-center">Customer Complaint</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 d-flex-column justify-content-center align-content-center">Complaint Status</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    $sql = "SELECT * FROM customer_complaint WHERE complaint_status = 'Resolved'";


                                    $info = $obj_admin->manage_all_info($sql);

                                    $serial  = 1;

                                    $num_row = $info->rowCount();


                                    while ($row = $info->fetch(PDO::FETCH_ASSOC)) {

                                    ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex px-2 ">
                                                    <h6 class="mb-0 text-sm"><?php echo $row['vin_number'] ?></h6>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex px-2 ">
                                                    <h6 class="mb-0 text-sm"><?php echo $row['model_name'] ?></h6>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex px-2 ">
                                                    <h6 class="mb-0 text-sm"><?php echo $row['customer_name'] ?></h6>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex px-2 ">
                                                    <h6 class="mb-0 text-sm"><?php echo $row['customer_complaint'] ?></h6>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex px-2 ">
                                                    <h6 class="mb-0 text-sm"><?php echo $row['complaint_status'] ?></h6>
                                                </div>
                                            </td>
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
</div>

<div class="modal fade" id="infoModal" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="infoModalLabel">Customer Complaint Information</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" >
                    <input type="hidden" name="sales_id" id="SalesId" value="">
                    <div class="form-group">
                        <input type="hidden" class="form-control" id="CustomerName" name="customer_name" required>
                        <input type="hidden" class="form-control" id="CustomerContact" name="customer_contact" required>
                        <input type="hidden" class="form-control" id="date" name="date" required>
                        <input type="hidden" class="form-control" id="vinnumber" name="vin_number" required>
                        <input type="hidden" class="form-control" id="modelname" name="model_name" required>

                        <label for="updatecomplaintstatus">Update Complaint Status</label>
                            <select name="update_complaint_status" id="update_complaint_status" class="form-control" required>
                                <option value="">Select Status</option>
                                <option value="Not Resolved">Not Resolved</option>
                                <option value="Resolved">Resolved</option>
                                <option value="Discarded">Discarded</option>
                            </select>
                    </div>
                    <!-- Add more input fields as needed -->
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary" type="submit" name="save_changes">Update Complaint Status</button>
                </form>
            </div>
        </div>
    </div>
<script>
    function populateinfoModal(button) {
        // Extract data attributes from the button
        var salesId = button.getAttribute('data-sales-id');
        var CustomerName = button.getAttribute('data-customer-name');
        var CustomerContact = button.getAttribute('data-customer-contact');
        var CustomerComplaint = button.getAttribute('data-customer-complaint');
        var vin_number = button.getAttribute('data-vin-number');
        var model_name = button.getAttribute('data-model-name');
        var date = button.getAttribute('data-date');

        // Set values in the modal input fields
        document.getElementById('SalesId').value = salesId;
        document.getElementById('CustomerName').value = CustomerName;
        document.getElementById('CustomerContact').value = CustomerContact;
        document.getElementById('CustomerComplaint').value = CustomerComplaint;
        document.getElementById('vinnumber').value = vin_number;
        document.getElementById('modelname').value = model_name;
        document.getElementById('date').value = date;
    }
</script>
</div>


<?php include 'INCLUDES/footer.php'; ?>