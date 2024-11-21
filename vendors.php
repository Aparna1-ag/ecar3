<?php

include 'conn.php';
require './authentication.php';
include './INCLUDES/header.php';
$pagename = 'Vendors Control';

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


if (isset($_POST['add_user'])) {
    $error = $obj_admin->add_new_vendor_user($_POST);
}

if (isset($_GET['delete_user'])) {
    $action_id = $_GET['vendor_id'];

    $sql = "DELETE FROM vendors WHERE vendor_id = :id";
    $sent_po = "vendor.php";
    $obj_admin->delete_data_by_this_method($sql, $action_id, $sent_po);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_changes'])) {
    $vendorId = $_POST['vendor_id'];
    $fullName = $_POST['full_name'];
    $email = $_POST['email_id'];
    $phone = $_POST['contact_number'];

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Update database
    $sql = "UPDATE vendors SET fullname = ?, email_id = ?, contact_number = ? WHERE vendor_id = ?";

    $stmt = $conn->prepare($sql);

    // Bind parameters
    $stmt->bind_param("sssi", $fullName, $email, $phone, $vendorId);

    // Execute the statement
    if ($stmt->execute()) {
        header('Location: vendors.php?save_changes=true');
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
                text: "Vendor details have been updated!",
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
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Vendor Panel</h6>
                </div>

                <div class="card-body ">
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <button class="nav-link" onclick="toggleView(0);">Manage Users</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" onclick="toggleView(1);">Password Change</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" onclick="toggleView(2);">Add User</button>
                        </li>
                    </ul>





                    <!-- manage users table  -->

                    <div class="card mt-2" id="manage_users">
                        <div class="card-body">
                            <table class="allTables table align-items-center justify-content-center ">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 d-flex-column justify-content-center align-content-center">Full Name</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 d-flex-column justify-content-center align-content-center">Email</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 d-flex-column justify-content-center align-content-center">Phone No</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 d-flex-column justify-content-center align-content-center">Actions</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    $sql = "SELECT * FROM vendors";


                                    $info = $obj_admin->manage_all_info($sql);

                                    $serial  = 1;

                                    $num_row = $info->rowCount();


                                    while ($row = $info->fetch(PDO::FETCH_ASSOC)) {

                                    ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex px-2 ">
                                                    <h6 class="mb-0 text-sm"><?php echo $row['fullname'] ?></h6>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex px-2 ">
                                                    <h6 class="mb-0 text-sm"><?php echo $row['email_id'] ?></h6>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex px-2 ">
                                                    <h6 class="mb-0 text-sm"><?php echo $row['contact_number'] ?></h6>
                                                </div>
                                            </td>
                                            </td>
                                            <td class="align-middle ">

                                            <a href="#" class="btn btn-link" data-toggle="modal" data-target="#editModal" onclick="populateEditModal(this)" data-vendor-id="<?php echo $row['vendor_id']; ?>" data-full-name="<?php echo $row['fullname']; ?>" data-email-id="<?php echo $row['email_id']; ?>" data-phone-no="<?php echo $row['contact_number']; ?>">
    <i class="fas fa-user-edit" style="color: #7a4edf;"></i>
</a>
                                                    <a class="btn btn-link" title="Delete" href="#" data-toggle="modal" data-target="#deleteModal" data-vendor-id="<?php echo $row['vendor_id']; ?>">
    <i class="fas fa-trash"></i>
</a>
                                            </td>
                                        </tr>

                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                                        <!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Vendor Information</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Add your form for editing vendor information here -->
                <form method="post" >
                    <!-- Include input fields for vendor information -->
                    <input type="hidden" name="vendor_id" id="editVendorId" value="">
                    <div class="form-group">
                        <label for="editFullName">Full Name</label>
                        <input type="text" class="form-control" id="editFullName" name="full_name" required>

                        <label for="editEmailId">Email Id</label>
                        <input type="text" class="form-control" id="editEmailId" name="email_id" required>

                        <label for="editPhoneNo">Phone Number</label>
                        <input type="text" class="form-control" id="editPhoneNo" name="contact_number" required>
                    </div>
                    <!-- Add more input fields as needed -->
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary" type="submit" name="save_changes">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
    <script>
    function populateEditModal(button) {
        // Extract data attributes from the button
        var vendorId = button.getAttribute('data-vendor-id');
        var fullName = button.getAttribute('data-full-name');
        var emailId = button.getAttribute('data-email-id');
        var phoneNo = button.getAttribute('data-phone-no');

        // Set values in the modal input fields
        document.getElementById('editVendorId').value = vendorId;
        document.getElementById('editFullName').value = fullName;
        document.getElementById('editEmailId').value = emailId;
        document.getElementById('editPhoneNo').value = phoneNo;
    }
</script>
</div>


 <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this user?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <a class="btn btn-danger" id="confirmDeleteButton" href="#">Delete</a>
            </div>
        </div>
    </div>
</div>

<script>
    $('#editModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var vendorId = button.data('vendor-id');
    var fullName = button.data('full-name');
    var email = button.data('email-id');
    var phone = button.data('phone-no');

    // Set the values in the modal form
    $('#editVendorId').val(vendorId);
    $('#editFullName').val(fullName);
    $('#editEmailId').val(email);
    $('#editPhoneNo').val(phone);
});
</script>


<script>
    $('#deleteModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var vendorId = button.data('vendor-id');
        $('#confirmDeleteButton').attr('href', '?delete_user=delete_user&vendor_id=' + vendorId);
    });
</script>





                    <div class="card" id="password_change" style="display: none;">
                        <div class="card-body">

                            <form id="passwordForm" method="post" onsubmit="return validateForm()">
                                <input type="text" class="form-control" name="current_password" id="current_password" placeholder="Current Password"><br>
                                <input type="text" class="form-control" name="new_password" id="new_password" placeholder="New Password"><br>
                                <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Confirm Password">
                                <span id="passwordMatchError" class="text-danger"></span><br><br>

                                <button type="submit" class="btn btn-primary" name="update_password" id="update_password">Change Password</button>
                            </form>

                        </div>
                    </div>

                      <!-- Add a modal for confirmation -->
<div class="modal fade" id="confirmPasswordModal" tabindex="-1" role="dialog" aria-labelledby="confirmPasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmPasswordModalLabel">Confirm Password Change</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to change your password?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="submitPasswordForm()">Yes, Change Password</button>
            </div>
        </div>
    </div>
</div>

<script>
    function showConfirmationModal() {
        $('#confirmPasswordModal').modal('show');
    }

    function submitPasswordForm() {
        // You can add additional validation here if needed
        $('#passwordForm').submit();
    }
</script>




                    <script>
                        // Get references to the input fields and error span
                        var newPasswordInput = document.getElementById('new_password');
                        var confirmPasswordInput = document.getElementById('confirm_password');
                        var passwordMatchError = document.getElementById('passwordMatchError');
                        var update_password = document.getElementById('update_password');

                        // Add event listeners to the input fields
                        newPasswordInput.addEventListener('input', checkPasswordMatch);
                        confirmPasswordInput.addEventListener('input', checkPasswordMatch);

                        // Function to check if the passwords match
                        function checkPasswordMatch() {
                            var newPassword = newPasswordInput.value;
                            var confirmPassword = confirmPasswordInput.value;

                            // Reset previous error messages
                            passwordMatchError.textContent = '';

                            // Check if the passwords match
                            if (newPassword !== confirmPassword) {
                                passwordMatchError.textContent = 'Passwords do not match';
                                update_password.style.display = "none";
                            } else {
                                passwordMatchError.textContent = ''; // Clear the error message
                                // If passwords match, enable the button
                                update_password.style.display = "block";
                            }

                        }

                        // Function to validate the form before submission
                        function validateForm() {


                            // Check the passwords match one more time before submission
                            checkPasswordMatch();

                            // If there is an error, prevent form submission
                            return passwordMatchError.textContent === '';
                        }
                    </script>

                    <div class="card" id="add_user" style="display: none;">
                        <div class="card-body">

                            <form method="POST">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label>Full Name</label>
                                        <input type="text" class="form-control is-invalid" name="em_fullname" id="fullName_adduser" placeholder="Full Name" required>

                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Email Id</label>
                                        <input type="email" class="form-control is-invalid" name="em_email" id="email_adduser" placeholder="Email Id" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>New Password</label>
                                        <input type="password" class="form-control is-invalid" name="em_password" id="password_adduser" placeholder="Password" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Confirm Password</label>
                                        <input type="password" class="form-control is-invalid" name="confirm_password" id="confirm_password_adduser" placeholder="Confirm Password" required>
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label for="validationServer02">Phone Number</label>

                                        <input type="tel" class="form-control is-invalid" id="phone_adduser" name="phone" placeholder="+ 00000000" required>

                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="validationServer02">User Role</label>

                                        <select name="vendor_type" id="user_role_adduser" class="form-control is-invalid" required>
                                            <option value="vendor" selected>Vendor</option>
                                            
                                        </select>

                                    </div>

                                </div>

                                <button class="btn btn-primary" type="submit" name="add_user">Add Vendor</button>
                            </form>

                        </div>
                    </div>


                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            let fullnameinput = document.getElementById("fullName_adduser");
                            let emailinput = document.getElementById("email_adduser");
                            let phoneinput = document.getElementById("phone_adduser");
                            let passwordinput = document.getElementById("password_adduser");
                            let confirmpasswordinput = document.getElementById("confirm_password_adduser");
                            let userroleinput = document.getElementById("user_role_adduser");

                            fullnameinput.addEventListener('input', validclass);
                            emailinput.addEventListener('input', validclass);
                            phoneinput.addEventListener('input', validclass);
                            passwordinput.addEventListener('input', passwordValidity);
                            confirmpasswordinput.addEventListener('input', passwordValidity);
                            userroleinput.addEventListener('input', validclass);

                            function validclass() {
                                updateValidity(fullnameinput, fullnameinput.value.trim() !== '');
                                updateValidity(emailinput, emailinput.value.trim() !== '');
                                updateValidity(phoneinput, phoneinput.value.trim() !== '');
                                updateValidity(passwordinput, passwordinput.value.trim() !== '');
                                updateValidity(confirmpasswordinput, confirmpasswordinput.value.trim() !== '');
                                updateValidity(userroleinput, userroleinput.value.trim() !== '');
                            }

                            function passwordValidity() {
                                const isValid = passwordinput.value.trim() !== '' && passwordinput.value === confirmpasswordinput.value;
                                updateValidity(passwordinput, isValid);
                                updateValidity(confirmpasswordinput, isValid);
                            }

                            function updateValidity(inputElement, isValid) {
                                const classToAdd = isValid ? "is-valid" : "is-invalid";
                                const classToRemove = isValid ? "is-invalid" : "is-valid";

                                inputElement.classList.remove(classToRemove);
                                inputElement.classList.add(classToAdd);
                            }
                        });
                    </script>

                    <?php
                    $sql = "SELECT password FROM vendors where vendor_id = $user_id";


                    $info = $obj_admin->manage_all_info($sql);

                    $serial  = 1;

                    $num_row = $info->rowCount();


                    while ($row = $info->fetch(PDO::FETCH_ASSOC)) {

                        $user_password = $row['password'];
                    }

                    if (isset($_POST['update_password'])) {
                        $current_password = $_POST['current_password'];
                        $new_password = $_POST['new_password'];
                        $confirm_password = $_POST['confirm_password'];

                        // Validate and sanitize user input (you might want to do more checks here)

                        // Check if the passwords match
                        if (
                            $new_password !== $confirm_password
                        ) {
                            echo "Passwords do not match";
                            exit; // Stop further execution
                        }

                        // Assuming $conn is your database connection

                        // Hash the new password securely
                        $hashed_password = md5($new_password);

                        $sql = "UPDATE accounts SET password = ? WHERE user_id = ?";

                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("si", $hashed_password, $user_id);

                        if ($stmt->execute()) {
                            // Password Updated
                            $stmt->close();
                            $conn->close();
                            $obj_admin->admin_logout(); // Assuming this function handles the logout logic

                            // Redirect to index.php
                            header('Location: ./index.php');
                            exit; // Stop further execution after the header redirect
                        } else {
                            // Error during the update
                            $stmt->close();
                            $conn->close();

                            // Output an error message, or handle it in some other way
                            echo "Error: " . $stmt->error;
                            exit; // Stop further execution
                        }
                    }




                    ?>







                    <script script>
                        var manage_users = document.getElementById("manage_users");
                        var password_change = document.getElementById("password_change");
                        var add_user = document.getElementById("add_user");


                        function toggleView(value) {
                            if (value == 0) {
                                manage_users.style.display = "block";
                                password_change.style.display = "none";
                                add_user.style.display = "none";
                            } else if (value == 1) {
                                manage_users.style.display = "none";
                                password_change.style.display = "block";
                                add_user.style.display = "none";
                            } else if (value == 2) {
                                manage_users.style.display = "none";
                                password_change.style.display = "none";
                                add_user.style.display = "block";
                            }
                        }
                    </script>
                </div>
            </div>
        </div>
    </div>

</div>



<?php
include 'INCLUDES/footer.php';

?>