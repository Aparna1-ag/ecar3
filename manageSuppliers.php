<?php

include 'conn.php';
require './authentication.php';
include './INCLUDES/header.php';
$pagename = 'Manage suppliers';

include './INCLUDES/sidebar.php';
// auth check
$user_id = $_SESSION['admin_id'];
$user_name = $_SESSION['name'];
$security_key = $_SESSION['security_key'];
if ($user_id == NULL || $security_key == NULL) {
    header('Location: index.php');
    exit();
}
// check admin
$user_role = $_SESSION['user_role'];

if (isset($_POST['add_user'])) {
    $error = $obj_admin->add_new_user($_POST);
}

?>






<div class="container-fluid py-4">
    <div class="row">
        <div class="col-11 m-auto">
        <a class="btn btn-secondary my-5 " href="controlaccess.php"> &#8592; Back</a>

            <div class="card mb-4" id="add_supplier">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Manage suppliers </h5>
                    <button type="button" class="btn btn-lg btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                        Add suppliers
                    </button>
                </div>

                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-3">
                    <table class="table shadow-lg table-bordered table-sm" id="manage-suppliers" name="dataTable" style="border: 1px solid white">
                  <thead style="height:4.5rem;">
                  <tr class="bg-gradient bg-secondary text-light text-center" style="background: ">
                                    <th scope="col" class="align-middle " width="6%" style="font-weight:500;">Name</th>
                                    <th scope="col" class="align-middle " width="6%" style="font-weight:500;">Email</th>
                                    <th scope="col" class="align-middle " width="6%" style="font-weight:500;">Phone No</th>
                                    <th scope="col" class="align-middle " width="6%" style="font-weight:500;">Account Type</th>
                                    <th scope="col" class="align-middle " width="6%" style="font-weight:500;">Address</th>
                                    <th scope="col" class="align-middle " width="6%" style="font-weight:500;">Actions</th>
                                </tr>
                            </thead>
                            <tbody  style="background: #dddddd70;">
                                <?php
                                $sql = "SELECT * FROM suppliers";
                                $stmt = $conn->prepare($sql);
                                $stmt->execute();
                                $result = $stmt->get_result();

                                while ($row = $result->fetch_assoc()) {
                                ?>
                                    <tr style="height: 5rem;">
                                        <td class="align-middle px-3"><?php echo htmlspecialchars($row['fullname']); ?></td>
                                        <td class="align-middle px-3"><?php echo htmlspecialchars($row['email_id']); ?></td>
                                        <td class="align-middle px-3"><?php echo htmlspecialchars($row['contact_number']); ?></td>
                                        <td class="align-middle px-3"><?php
                                            $userRole = $row['type_role'];
                                            if ($userRole == 1) {
                                                echo "Admin";
                                            } elseif ($userRole == 2) {
                                                echo "Production Team";
                                            } elseif ($userRole == 3) {
                                                echo "Sales Team";
                                            } elseif ($userRole == 4) {
                                                echo "Purchase Team";
                                            } elseif ($userRole == 5) {
                                                echo "suppliers";
                                            } else {
                                                echo $row['type_role']; // Add a default case if needed
                                            }
                                            ?></td>
                                        <td><?php echo htmlspecialchars(!empty($row['address']) ? $row['address'] : 'N/A'); ?></td>
                                        <td>
                                            <a href="#"
                                                class="btn btn-link"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editModal"
                                                onclick="populateEditModal(this)"
                                                data-vendor-id="<?php echo $row['supplier_id']; ?>"
                                                data-fullname="<?php echo $row['fullname']; ?>"
                                                data-email="<?php echo $row['email_id']; ?>"
                                                data-account-type="<?php echo $row['type_role']; ?>"
                                                data-address="<?php echo $row['address']; ?>"
                                                data-contact-number="<?php echo $row['contact_number']; ?>">
                                                <i class="fas fa-user-edit" style="color: #7a4edf;"></i>
                                                
                                            </a>

                                            <a href="#" class="btn btn-link" title="Delete" data-bs-toggle="modal" data-bs-target="#deleteModal" data-vendor-id="<?php echo $row['supplier_id']; ?>">
                                                <i class="fas fa-trash"></i>
                                            </a>

                                        </td>
                                    </tr>
                                <?php  } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- ADD User Modal -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">ADD Vendor Information</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Full Name</label>
                            <input type="text" class="form-control is-invalid" name="vendor_fullname" id="fullName_addvendor" placeholder="Full Name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Email Id</label>
                            <input type="email" class="form-control is-invalid" name="vendor_email" id="email_addvendor" placeholder="Email Id" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>New Password</label>
                            <input type="password" class="form-control is-invalid" name="vendor_password" id="password_addvendor" placeholder="Password" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Confirm Password</label>
                            <input type="password" class="form-control is-invalid" name="confirm_password" id="confirm_password_addvendor" placeholder="Confirm Password" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="validationServer02">Phone Number</label>
                            <input type="tel" class="form-control is-invalid" id="phone_addvendor" name="vendor_phone" placeholder="+ 00000000" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="validationServer02">Account Type</label>
                            <select name="vendor_account_type" id="account_type_addvendor" class="form-control is-invalid" required>
                                <option selected value="suppliers">suppliers</option>
                            </select>
                        </div>
                        <!-- Add Address input -->
                        <div class="col-md-12 mb-3">
                            <label for="address">Address</label>
                            <input type="text" class="form-control is-invalid" name="vendor_address" id="address_addvendor" placeholder="Enter Address" required>
                        </div>
                    </div>
                    <button class="btn btn-primary" type="submit" name="add_suppliers">Add Vendor</button>
                </form>
            </div>
        </div>
    </div>
</div>




<!-- Edit Modal -->
<!-- Edit Modal -->
<!-- Modal to Edit Supplier Information -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit User Information</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post">
                    <input type="hidden" name="supplier_id" id="edituser_id"> <!-- Added hidden field for supplier_id -->
                    <div class="form-group">
                        <label for="editfullname">Full Name</label>
                        <input type="text" class="form-control" id="editfullname" name="fullname" required>
                        <label for="editEmailId">Email Id</label>
                        <input type="text" class="form-control" id="editEmailId" name="email" required>
                        <label for="edituser_role">User Role</label>
                        <select class="form-control" id="edituser_role" name="type_role" required>
                            <option selected value="suppliers">suppliers</option>
                        </select>
                        <label for="editPhoneNo">Phone Number</label>
                        <input type="text" class="form-control" id="editPhoneNo" name="phone_number" required>
                        
                        <!-- Added Address Field -->
                        <label for="editAddress">Address</label>
                        <input type="text" class="form-control" id="editAddress" name="address" required>

                        <label for="changePassword">Change Password (optional)</label>
                        <input type="password" class="form-control" id="changePassword" name="changePassword">
                    </div>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary" type="submit" name="save_changes">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function populateEditModal(button) {
        var supplier_id = button.getAttribute('data-vendor-id'); // Corrected the attribute
        var fullname = button.getAttribute('data-fullname');
        var email = button.getAttribute('data-email');
        var type_role = button.getAttribute('data-account-type'); // Corrected attribute
        var phoneNo = button.getAttribute('data-contact-number');
        var address = button.getAttribute('data-address'); // Add this line to get the address

        document.getElementById('edituser_id').value = supplier_id; // Set hidden supplier_id
        document.getElementById('editfullname').value = fullname;
        document.getElementById('editEmailId').value = email;
        document.getElementById('edituser_role').value = type_role; // Set type_role
        document.getElementById('editPhoneNo').value = phoneNo;
        document.getElementById('editAddress').value = address; // Set the address field
    }
</script>


<!-- Delete Modal -->
<!-- Delete Vendor Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Delete Vendor</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this vendor?</p>
            </div>
            <div class="modal-footer">
                <!-- Add the vendor ID to the delete link -->
                <a href="#" id="deleteVendorBtn" class="btn btn-danger">Delete</a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<script>
    // JavaScript to handle the deletion action
    var deleteVendorBtn = document.getElementById('deleteVendorBtn');
    var deleteModal = document.getElementById('deleteModal');
    var vendorId = null;

    // When the delete button is clicked, pass the vendor ID to the delete link
    $('#deleteModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        vendorId = button.data('vendor-id'); // Extract vendor ID from data-* attribute
        deleteVendorBtn.href = 'manageSuppliers.php?delete_vendor=true&supplier_id=' + vendorId; // Set the delete link
    });
</script>

<script>
$(document).ready(function () {
    var table = $('#manage-suppliers').DataTable({
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

// Handle deletion
// Check if the delete request has been made and a vendor ID is passed
if (isset($_GET['delete_vendor']) && isset($_GET['supplier_id'])) {
    $supplier_id = $_GET['supplier_id'];

    // Ensure the user is an admin (assuming $user_role is already set and contains the current user's role)
    if ($user_role == 1) {
        // Prepare the SQL statement to delete the vendor
        $sql = "DELETE FROM suppliers WHERE supplier_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $supplier_id); // Bind supplier_id as integer

        if ($stmt->execute()) {
            header('Location: manageSuppliers.php?delete_success=true');
            exit();
        } else {
            echo "Error: Could not delete the vendor.";
        }
    }
}

// Handle successful deletion message
if (isset($_GET['delete_success'])) {
    echo '<script>
        setTimeout(function() {
            Swal.fire({
                title: "Success",
                text: "Vendor deleted successfully!",
                icon: "success",
                position: "top-end",
                showConfirmButton: false,
                timer: 2000,
                toast: true,
            });
        }, 1000);
    </script>';
}

// Handle saving changes
// Handle saving changes
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_changes'])) {
    $supplier_id = $_POST['supplier_id'];  // Ensure this corresponds with the form field 'supplier_id'
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $phone = $_POST['phone_number']; // Updated to match the form field
    $type_role = $_POST['type_role'];  // Ensure this corresponds with the 'type_role' field
    $address = $_POST['address'];  // Get the address field
    $changePassword = $_POST['changePassword'];

    // Check if password needs to be changed
    if (!empty($changePassword)) {
        $updateSetValue = ', `password`=?'; // Ensure column name matches `password`
        $updatebindparms = 's'; // 's' for string (password)
        $updatebindparmsValue = md5($changePassword); // Encrypt the password
    } else {
        $updateSetValue = '';
        $updatebindparms = ''; // No password change, no additional binding
        $updatebindparmsValue = null; // No value for password
    }

    // Validate data
    if (empty($fullname) || empty($email) || empty($phone) || empty($type_role) || empty($address)) {
        echo "Please fill all fields!";
    } else {
        // Prepare the SQL query
        $sql = "UPDATE `suppliers` 
                SET `fullname` = ?, `email_id` = ?, `contact_number` = ?, `type_role` = ?, `address` = ? $updateSetValue 
                WHERE `supplier_id` = ?";
        $stmt = $conn->prepare($sql);

        // Bind parameters based on whether password is updated or not
        if (!empty($updatebindparms)) {
            // Bind parameters including the password
            $stmt->bind_param(
                "ssssssi",  // 5 string params and 1 int param
                $fullname,
                $email,
                $phone,
                $type_role,
                $address,  // bind address
                $updatebindparmsValue,  // password (md5)
                $supplier_id
            );
        } else {
            // If no password update
            $stmt->bind_param(
                "sssssi",  // 5 string params and 1 int param
                $fullname,
                $email,
                $phone,
                $type_role,
                $address,  // bind address
                $supplier_id
            );
        }

        // Execute and redirect if successful
        if ($stmt->execute()) {
            header('Location: manageSuppliers.php?save_changes=true');
            exit();
        } else {
            echo "Error: Could not save changes.";
        }
    }
}




// Check if the form is submitted for adding a new vendor
if (isset($_POST['add_suppliers'])) {
    // Get the data from the POST request
    $vendor_fullname = $_POST['vendor_fullname'];
    $vendor_email = $_POST['vendor_email'];
    $vendor_password = $_POST['vendor_password'];
    $confirm_password = $_POST['confirm_password'];
    $vendor_phone = $_POST['vendor_phone'];
    $vendor_account_type = "suppliers";
    $vendor_address = $_POST['vendor_address'];  // Get the address input from the form

    // Password validation
    if ($vendor_password !== $confirm_password) {
        echo "Passwords do not match!";
    } else {
        // Check if the email already exists in the database
        $check_email_query = "SELECT email_id FROM suppliers WHERE email_id = ?";
        $check_stmt = $conn->prepare($check_email_query);
        $check_stmt->bind_param("s", $vendor_email);
        $check_stmt->execute();
        $check_stmt->store_result();

        if ($check_stmt->num_rows > 0) {
            echo "<script>alert('Error: This email address is already in use!');</script>";
        } else {
            // Hash the password
            $hashed_password = md5($vendor_password);

            // Prepare the SQL query to insert the new vendor into the suppliers table
            $sql = "INSERT INTO suppliers (fullname, email_id, password, contact_number, type_role, address, username) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";

            // Prepare the statement
            $stmt = $conn->prepare($sql);

            // Bind the parameters, note the inclusion of `username` as email
            $stmt->bind_param("sssssss", $vendor_fullname, $vendor_email, $hashed_password, $vendor_phone, $vendor_account_type, $vendor_address, $vendor_email);

            // Execute the query
            if ($stmt->execute()) {
                header('Location: manageSuppliers.php?add_success=true');
                exit();
            } else {
                echo "Error: Could not add the vendor.";
            }
        }
    }
}





?>