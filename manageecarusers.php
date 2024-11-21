<?php

include 'conn.php';
require './authentication.php';
include './INCLUDES/header.php';
$pagename = 'Manage Users';

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
            <div class="card mb-4" id="add_supplier">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Manage Users </h5>
                    <button type="button" class="btn btn-lg btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                        Add New User
                    </button>
                </div>

                <div class="card-body px-0 pt-0 ">
                    <div class="table-responsive p-3">
                    <table class="table shadow-lg table-bordered table-sm" id="dataTable" name="dataTable" style="border: 1px solid white">
                  <thead style="height:4.5rem;">
                  <tr class="bg-gradient bg-secondary text-light text-center" style="background: ">
                                    <th scope="col" class="align-middle " width="6%" style="font-weight:500;">Name</th>
                                    <th scope="col" class="align-middle " width="6%" style="font-weight:500;">Email</th>
                                    <th scope="col" class="align-middle " width="6%" style="font-weight:500;">Phone No</th>
                                    <th scope="col" class="align-middle " width="6%" style="font-weight:500;">User role</th>
                                    <th scope="col" class="align-middle " width="6%" style="font-weight:500;">Actions</th>
                                </tr>
                            </thead>
                            <tbody  style="background: #dddddd70;">
                                <?php
                                $sql = "SELECT * FROM accounts";
                                $stmt = $conn->prepare($sql);
                                $stmt->execute();
                                $result = $stmt->get_result();

                                while ($row = $result->fetch_assoc()) {
                                ?>
                                    <tr style="height: 5rem;">
                                        <td class="align-middle px-3"><?php echo htmlspecialchars($row['fullname']); ?></td>
                                        <td class="align-middle px-3"><?php echo htmlspecialchars($row['email_id']); ?></td>
                                        <td class="align-middle px-3"><?php echo htmlspecialchars($row['phone_no']); ?></td>
                                        <td class="align-middle px-3"><?php
                                            $userRole = $row['user_role'];
                                            if ($userRole == 1) {
                                                echo "Admin";
                                            } elseif ($userRole == 2) {
                                                echo "Production Team";
                                            } elseif ($userRole == 3) {
                                                echo "Sales Team";
                                            } elseif ($userRole == 4) {
                                                echo "Purchase Team";
                                            } else {
                                                echo "Unknown Role"; // Add a default case if needed
                                            }
                                            ?></td>
                                        <td class="d-flex justify-content-center pt-4">
                                            <a href="#" class="btn btn-link" data-bs-toggle="modal" data-bs-target="#editModal" onclick="populateEditModal(this)" data-warehouse-id="<?php echo $row['user_id']; ?>" data-warehouse-name="<?php echo $row['fullname']; ?>" data-email="<?php echo $row['email_id']; ?>" data-user_role="<?php echo $row['user_role']; ?>" data-phone-number="<?php echo $row['phone_no']; ?>">
                                                <i class="fas fa-user-edit" style="color: #7a4edf;"></i>
                                            </a>
                                            <a href="#" class="btn btn-link" title="Delete" data-bs-toggle="modal" data-bs-target="#deleteModal" data-warehouse-id="<?php echo $row['user_id']; ?>">
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
                <h5 class="modal-title" id="addModalLabel">ADD User Information</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
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

                            <select name="em_user_role" id="user_role_adduser" class="form-control is-invalid" required>
                                <option value="">Select Role</option>
                                <option value="1">Admin</option>
                                <option value="2">Production Team</option>
                                <option value="3">Sales Team</option>
                                <option value="4">Purchase Team</option>
                            </select>

                        </div>

                    </div>

                    <button class="btn btn-primary" type="submit" name="add_user">Add User</button>
                </form>

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
            </div>
        </div>
    </div>
</div>


<!-- Edit Modal -->
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
                    <input type="hidden" name="user_id" id="edituser_id">
                    <div class="form-group">
                        <label for="editfullname">Full Name</label>
                        <input type="text" class="form-control" id="editfullname" name="fullname" required>
                        <label for="editEmailId">Email Id</label>
                        <input type="text" class="form-control" id="editEmailId" name="email" required>
                        <label for="edituser_role">user_role</label>
                        <input type="text" class="form-control" id="edituser_role" name="user_role" required>
                        <label for="editPhoneNo">Phone Number</label>
                        <input type="text" class="form-control" id="editPhoneNo" name="phone_number" required>
                        <label for="changePassword">Change Password (optional)</label>
                        <input type="text" class="form-control" id="changePassword" name="changePassword">
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
        var user_id = button.getAttribute('data-warehouse-id');
        var fullname = button.getAttribute('data-warehouse-name');
        var email = button.getAttribute('data-email');
        var user_role = button.getAttribute('data-user_role');
        var phoneNo = button.getAttribute('data-phone-number');

        document.getElementById('edituser_id').value = user_id;
        document.getElementById('editfullname').value = fullname;
        document.getElementById('editEmailId').value = email;
        document.getElementById('edituser_role').value = user_role;
        document.getElementById('editPhoneNo').value = phoneNo;
    }
</script>
<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this user?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <a class="btn btn-danger" id="confirmDeleteButton" href="#">Delete</a>
            </div>
        </div>
    </div>
</div>

<script>
    $('#deleteModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var user_id = button.data('warehouse-id');
        $('#confirmDeleteButton').attr('href', '?delete_user=delete_user&user_id=' + user_id);
    });
</script>





<?php

// Handle deletion
if (isset($_GET['delete_user']) && isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    // Ensure the user is an admin
    if ($user_role == 1) {
        $sql = "DELETE FROM accounts WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id); // Bind user_id as integer

        if ($stmt->execute()) {
            header('Location: manageecarusers.php?delete_success=true');
            exit();
        } else {
            echo "Error: Could not delete the user.";
        }
    }
}

// Handle successful deletion message
if (isset($_GET['delete_success'])) {
    echo '<script>
        setTimeout(function() {
            Swal.fire({
                title: "Success",
                text: "User deleted successfully!",
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
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_changes'])) {
    $user_id = $_POST['user_id'];
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $phone = $_POST['phone_number'];
    $user_role = $_POST['user_role'];
    $changePassword = $_POST['changePassword'];

    // Check if password needs to be changed
    if (!empty($changePassword)) {
        $updateSetValue = ', password=?';
        $updatebindparms = 's';  // 's' for string (password)
        $updatebindparmsValue = md5($changePassword);  // Encrypt the password
    } else {
        $updateSetValue = '';
        $updatebindparms = '';  // No password change, no additional binding
        $updatebindparmsValue = null;  // No value for password
    }

    // Validate data
    if (empty($fullname) || empty($email) || empty($phone) || empty($user_role)) {
        echo "Please fill all fields!";
    } else {
        // Prepare the SQL query
        $sql = "UPDATE accounts SET fullname = ?, email_id = ?, phone_no = ?, user_role = ? $updateSetValue WHERE user_id = ?";
        $stmt = $conn->prepare($sql);

        // Bind parameters based on whether password is updated or not
        if (!empty($updatebindparms)) {
            $stmt->bind_param("ssss" . $updatebindparms . "i", $fullname, $email, $phone, $user_role, $updatebindparmsValue, $user_id);
        } else {
            $stmt->bind_param("ssssi", $fullname, $email, $phone, $user_role, $user_id);
        }

        // Execute and redirect if successful
        if ($stmt->execute()) {
            header('Location: manageecarusers.php?save_changes=true');
            exit();
        } else {
            echo "Error: Could not save changes.";
        }
    }
}

?>