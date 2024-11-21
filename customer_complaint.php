<?php

include 'conn.php';
require './authentication.php';
include './INCLUDES/header.php';
$pagename = 'Customer Complaint';

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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_changes'])) {
    $salesId = $_POST['sales_id'];
    $ComplaintStatus = $_POST['update_complaint_status'];
    $update_timestamp = date("Y-m-d H:i:s");

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Update database
    $sql = "UPDATE customer_complaint SET complaint_status = ?, time = ? WHERE sales_id = ?";

    $stmt = $conn->prepare($sql);

    // Bind parameters
    $stmt->bind_param("ssi", $ComplaintStatus, $update_timestamp, $salesId);

    // Execute the statement
    if ($stmt->execute()) {
        header('Location: customer_complaint.php?save_changes=true');
    } else {
        // Handle error
        echo "Error: " . $conn->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}


if(isset($_GET['save_changes']) && $_GET['save_changes'] == 'true') {
    echo '<script>
        setTimeout(function() {
            Swal.fire({
                title: "Success",
                text: "Customer Complaint details have been updated!",
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



<div class="container-fluid py-4">
    <div class="row">
        <div class="col-11 m-auto">
            <div class="card">
                <div class="card-header pb-0" >
                    <h5>Customer Complaint</h5>
                </div>
                <div class="px-2" >
                <div class="container-fluid" >
    <style>
      .custom-red {
        background-color: red;
        color: white;
        border-radius: 5px;

        /* Optional, set text color to contrast with the background */
      }

      .custom-orange {
        background-color: orange;
        color: white;
        border-radius: 5px;

      }

      .custom-white {
        background-color: white;
        color: black;
        border-radius: 5px;

      }

      .custom-green {
        background-color: green;
        color: white;
        border-radius: 5px;

      }

      .custom-purple {
        background-color: purple;
        color: white;
        border-radius: 5px;

      }
    </style>
  
  </div>
  <br>
                
                   
                    <div class="card" id="manage_users" style="overflow: auto;">
                        <div class="table-responsive p-3">
                        <table class="table shadow-lg table-bordered table-sm" id="customer-complaint-table" name="dataTable" style="border: 1px solid white">
                  <thead style="height:4.5rem;">
                  <tr class="bg-gradient text-light my-2 text-center" style="background: #af952a">
                                        <th scope="col" class="align-middle " width="6%" style="font-weight:500;">VIN </th>
                                        <th scope="col" class="align-middle " width="6%" style="font-weight:500;">Customer Name</th>
                                        <th scope="col" class="align-middle " width="6%" style="font-weight:500;">Customer Complaint</th>
                                        <th scope="col" class="align-middle " width="6%" style="font-weight:500;">Complaint Status</th>
                                        <th scope="col" class="align-middle " width="6%" style="font-weight:500;">Action</th>
                                    </tr>
                                </thead>

                                <tbody class="text-center"  style="background: #dddddd70;">
                                    <?php
                                    $sql = "SELECT * FROM customer_complaint";


                                    $info = $obj_admin->manage_all_info($sql);

                                    $serial  = 1;

                                    $num_row = $info->rowCount();


                                    while ($row = $info->fetch(PDO::FETCH_ASSOC)) {

                                    ?>
                                        
                                        <tr>

                                        <td class="pt-4 px-3">
                                                <h2 class="badge" style="background-color: <?php echo ($row['complaint_severity'] === 'High Severity') ? '#ff0000' : (($row['complaint_severity'] === 'Medium Severity') ? '#ffa500' : (($row['complaint_severity'] === 'Low Severity') ? '#800080' : '')); ?>;">
                                                    <span class="mb-0 text-sm"><?php echo $row['vin_number'] ?></span>
                                                </h2>
                                            </td>




                                            <td class="align-middle">
                                                <div class="d-flex px-2 ">
                                                    <h6 class="mb-0 text-sm"><?php echo $row['customer_name'] ?></h6>
                                                </div>
                                            </td>
                                            <td class="align-middle">
                                                <div class="d-flex px-2 ">
                                                    <h6 class="mb-0 text-sm"><?php echo $row['customer_complaint'] ?></h6>
                                                </div>
                                            </td>
                                            <td class="align-middle">
                                                <div class="d-flex px-2 ">
                                                    <h6 class="mb-0 text-sm"><?php echo $row['complaint_status'] ?></h6>
                                                </div>
                                            </td>
                                            <td class="align-middle pt-4 d-flex justify-content-center ">
                                                <div class="d-flex px-2 ">
                                                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#infoModal" onclick="populateinfoModal(this)" data-customer-complaint="<?php echo $row['customer_complaint']; ?>" data-vin-number="<?php echo $row['vin_number']; ?>" data-sales-id="<?php echo $row['sales_id']; ?>" data-customer-name="<?php echo $row['customer_name']; ?>" data-customer-contact="<?php echo $row['customer_contact']; ?>" data-date="<?php echo $row['date']; ?>">
                                            Update Complaint Status</button>
                                            </td>
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
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
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

                        <label for="updatecomplaintstatus">Update Complaint Status</label>
                            <select name="update_complaint_status" id="update_complaint_status" class="form-control" required>
                                <option value="">Select Status</option>
                                <option value="Not Resolved">Not Resolved</option>
                                <option value="Resolved">Resolved</option>
                                <option value="Discarded">Discarded</option>
                            </select>
                    </div>
                    <!-- Add more input fields as needed -->
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
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
        var date = button.getAttribute('data-date');

        // Set values in the modal input fields
        document.getElementById('SalesId').value = salesId;
        document.getElementById('CustomerName').value = CustomerName;
        document.getElementById('CustomerContact').value = CustomerContact;
        document.getElementById('CustomerComplaint').value = CustomerComplaint;
        document.getElementById('vinnumber').value = vin_number;
        document.getElementById('date').value = date;
    }
</script>
</div>

<script>

$(document).ready(function () {
    var table = $('#customer-complaint-table').DataTable({
        paging: true,
        searching: true,
        ordering: true
    });

    $('#customSearchInput').on('keyup', function () {
        table.search(this.value).draw();
    });
});


</script>


<div class="row justify-content-center text-center pt-3">
      <div class="col-1 border p-1  custom-red">High Severity</div>&nbsp;&nbsp;
      <div class="col-1 border p-1 custom-orange">Medium Severity</div>&nbsp;&nbsp;
      <div class="col-1 border p-1 custom-purple">Low Severity</div>&nbsp;&nbsp;
    </div>


<?php include 'INCLUDES/footer.php'; ?>