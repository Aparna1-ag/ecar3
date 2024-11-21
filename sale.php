<?php
include 'conn.php';
require './authentication.php';
include './INCLUDES/header.php';
$pagename = 'Sales';

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
    $CustomerName = $_POST['customer_name'];
    $CustomerContact = $_POST['customer_contact'];
    $date = $_POST['date'];

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Update database
    $sql = "UPDATE sales SET customer_name = ?, customer_contact = ?, date = ? WHERE sales_id = ?";

    $stmt = $conn->prepare($sql);

    // Bind parameters
    $stmt->bind_param("sssi", $CustomerName, $CustomerContact, $date, $salesId);

    // Execute the statement
    if ($stmt->execute()) {
        header('Location: sale.php?save_changes=true');
    } else {
        // Handle error
        echo "Error: " . $conn->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['make_changes'])) {
    $salesId = $_POST['sales_id'];
    $CustomerName = $_POST['customer_name'];
    $CustomerContact = $_POST['customer_contact'];
    $CustomerComplaint = $_POST['customer_complaint'];
    $ComplaintSeverity = $_POST['complaint_severity'];
    $date = $_POST['date'];
    $vin_number = $_POST['vin_number'];
    $model_name = $_POST['model_name'];

    echo "Inputs:<br>";
    echo "salesId: $salesId<br>";
    echo "CustomerName: $CustomerName<br>";
    echo "CustomerContact: $CustomerContact<br>";
    echo "CustomerComplaint: $CustomerComplaint<br>";
    echo "ComplaintSeverity: $ComplaintSeverity<br>";
    echo "date: $date<br>";
    echo "vin_number: $vin_number<br>";

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Update customer_complaint table
    $salessql = "INSERT INTO customer_complaint (sales_id, vin_number, customer_name, customer_contact, customer_complaint, complaint_severity, date) VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($salessql);
    $stmt->bind_param("issssss", $salesId, $vin_number, $CustomerName, $CustomerContact, $CustomerComplaint, $ComplaintSeverity, $date);

    if (!$stmt->execute()) {
        // Handle error
        echo "Error: " . $stmt->error;
    }

    // Close the first statement 
    $stmt->close();

    // Update sales table
    $sql = "UPDATE sales SET customer_complaint = ?, complaint_severity = ? WHERE sales_id = ?";
    $stmt = $conn->prepare($sql);

    // Bind parameters
    $stmt->bind_param("ssi", $CustomerComplaint, $ComplaintSeverity, $salesId);

    // Execute the statement
    if ($stmt->execute()) {
        header('Location: customer_complaint.php');
    } else {
        // Handle error
        echo "Error: " . $stmt->error;
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
                text: "Sales details have been updated!",
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



<div class=" container d-flex justify-content-center my-5 py-2 container  ]bg-gradient shadow-lg text-uppercase" style="background: #566033"><h2 class="m-auto text-light" style="font-weight: 400">Sales Tab</h2> </div>

    <div class="row">
        <div class="col-11 m-auto">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h5>Sales</h5>
                </div>
                <div class=" ">
                    <ul class="nav nav-tabs">
                    </ul>
                    <!-- manage users table  -->
                    <div class=" mt-2" id="manage_users" style="overflow: auto;">
                        <div class="">
                        <table class="table shadow-lg table-bordered table-sm" id="sales-table" name="myTable" style="border: 1px solid white">
                  <thead style="height:4.5rem;">
                  <tr class="bg-gradient text-light my-2 text-center" style="background: #566033">
                                    <th scope="col" class="align-middle " width="6%" style="font-weight:500;">Order No</th>
                                        <th scope="col" class="align-middle " width="6%" style="font-weight:500;">VIN Number</th>
                                        <th scope="col" class="align-middle " width="6%" style="font-weight:500;">Customer Name</th>
                                        <th scope="col" class="align-middle " width="6%" style="font-weight:500;">Customer Contact</th>
                                        <th scope="col" class="align-middle " width="6%" style="font-weight:500;">Date</th>
                                        <th scope="col" class="align-middle " width="6%" style="font-weight:500;">Order Date/Time</th>
                                        <th scope="col" class="align-middle " width="6%" style="font-weight:500;">Action</th>
                                    </tr>
                                </thead>

                                <tbody class="text-center"  style="background: #dddddd70;">
                                    <?php                        

                                    $sql = "SELECT * FROM sales";

                                    $info = $obj_admin->manage_all_info($sql);

                                    $serial  = 1;

                                    $num_row = $info->rowCount();


                                    while ($row = $info->fetch(PDO::FETCH_ASSOC)) {

                                    ?>
                                   

<tr style="height: 5rem;">
                                        <td class="align-middle" >
                                                <div class="d-flex px-2 ">
                                                    <h6 class="mb-0 text-sm"><?php echo $row['unit'] ?></h6>
                                                </div>
                                            </td>
                                            <td class="align-middle" >
                                                <div class="d-flex px-2 ">
                                                    <h6 class="mb-0 text-sm"><?php echo $row['vin_number'] ?></h6>
                                                </div>
                                            </td>
                                            <td class="align-middle" >
                                                <div class="d-flex px-2 ">
                                                    <h6 class="mb-0 text-sm"><?php echo $row['customer_name'] ?></h6>
                                                </div>
                                            </td>
                                            <td class="align-middle" >
                                                <div class="d-flex px-2 ">
                                                    <h6 class="mb-0 text-sm"><?php echo $row['customer_contact'] ?></h6>
                                                </div>
                                            </td>
                                            <td class="align-middle" >
                                                <div class="d-flex px-2 ">
                                                    <h6 class="mb-0 text-sm"><?php echo $row['date'] ?></h6>
                                                </div>
                                            </td>
                                            <td class="align-middle" >
                                                <div class="d-flex px-2 ">
                                                    <h6 class="mb-0 text-sm"><?php echo $row['purchase_timestamp'] ?></h6>
                                                </div>
                                            </td>
                                           
                                            <td class="align-middle pt-4 ">
                                            <a href="#" class="btn btn-link" data-bs-toggle="modal" data-bs-target="#editModal" onclick="populateEditModal(this)" data-sales-id="<?php echo $row['sales_id']; ?>" data-customer-name="<?php echo $row['customer_name']; ?>" data-customer-contact="<?php echo $row['customer_contact']; ?>" data-date="<?php echo $row['date']; ?>">
    <i class="fas fa-user-edit" style="color: #7a4edf;"></i>
</a>

<?php if ($row['customer_complaint'] === NULL) : ?>
    <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#infoModal" onclick="populateinfoModal(this)" data-customer-complaint="<?php echo $row['customer_complaint']; ?>" data-vin-number="<?php echo $row['vin_number']; ?>" data-sales-id="<?php echo $row['sales_id']; ?>" data-customer-name="<?php echo $row['customer_name']; ?>" data-customer-contact="<?php echo $row['customer_contact']; ?>" data-date="<?php echo $row['date']; ?>">
        Customer Complaint
    </button>
<?php endif; ?>

                                    <?php } ?>
</td>
</tr>
                                </tbody>
                            </table>
                        </div>
                    </div>            
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Sales Information</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" >
                    <input type="hidden" name="sales_id" id="editSalesId" value="">
                    <div class="form-group">
                        <label for="editCustomerName">Customer Name</label>
                        <input type="text" class="form-control" id="editCustomerName" name="customer_name" required>

                        <label for="editCustomerContact">Customer Contact</label>
                        <input type="text" class="form-control" id="editCustomerContact" name="customer_contact" required>

                        <label for="editdate">Date</label>
                        <input type="date" class="form-control" id="editdate" name="date" required>
                    </div>
                    <!-- Add more input fields as needed -->
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary" type="submit" name="save_changes">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
    <script>
    function populateEditModal(button) {
        // Extract data attributes from the button
        var salesId = button.getAttribute('data-sales-id');
        var CustomerName = button.getAttribute('data-customer-name');
        var CustomerContact = button.getAttribute('data-customer-contact');
        var date = button.getAttribute('data-date');

        // Set values in the modal input fields
        document.getElementById('editSalesId').value = salesId;
        document.getElementById('editCustomerName').value = CustomerName;
        document.getElementById('editCustomerContact').value = CustomerContact;
        document.getElementById('editdate').value = date;
    }
</script>
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

                        <label for="editCustomerComplaint">Customer Complaint</label>
                        <textarea class="form-control" id="CustomerComplaint" name="customer_complaint" required>
                        </textarea>

                        <label for="date">Date</label>
                        <input type="date" class="form-control" id="date" name="date" required>

                        <div class="form-group">
    <label for="complaintType">Complaint Type</label>
    <div class="d-flex flex-column align-items-start">
        <div class="form-check">
            <input type="radio" name="complaint_severity" value="High Severity" class="form-check-input" required>
            <label class="form-check-label">High Severity</label>
        </div>
        <div class="form-check">
            <input type="radio" name="complaint_severity" value="Medium Severity" class="form-check-input" required>
            <label class="form-check-label">Medium Severity</label>
        </div>
        <div class="form-check">
            <input type="radio" name="complaint_severity" value="Low Severity" class="form-check-input" required>
            <label class="form-check-label">Low Severity</label>
        </div>
    </div>
</div>
                    </div>
                    <!-- Add more input fields as needed -->
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary" type="submit" name="make_changes">Register A Complaint</button>
                </form>
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
<script>

$(document).ready(function () {
    var table = $('#sales-table').DataTable({
        paging: true,
        searching: true,
        ordering: true
    });

    $('#customSearchInput').on('keyup', function () {
        table.search(this.value).draw();
    });
});


</script>
</div>


<?php
include 'INCLUDES/footer.php';

?>