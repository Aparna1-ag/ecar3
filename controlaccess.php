<?php

include 'conn.php';
require './authentication.php';
include './INCLUDES/header.php';
$pagename = 'Access Control';

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
                    <h5>Access Control</h5>
                </div>

                <div class="card-body ">


                    <!-- manage users table  -->

                    <div class="container mt-4">
                        <div class="row">

                            <div class="col-md-4">
                                <a href="manageecarusers.php" style="text-decoration: none; color: inherit;">
                                    <div class="card d-flex align-items-center justify-content-center" style="height: 200px;">
                                       <img src="https://cdn3d.iconscout.com/3d/premium/thumb/business-team-4620689-3833030.png"  style="height: 4rem; width:auto;" />
                                        <p class="card-text">Manage Ecar Users</p>
                                    </div>
                                </a>

                            </div>

                            <div class="col-md-4">
                                <a href="manageVendors.php" style="text-decoration: none; color: inherit;">
                                <div class="card d-flex align-items-center justify-content-center" style="height: 200px;">
                                <img src="https://cdn1.iconfinder.com/data/icons/logistics-delivery-1-14/64/31-1024.png"  style="height: 4rem; width:auto;" />

                                    <p class="card-text">Manage Vendors</p>
                                </div>
                                </a>

                            </div>

                            <div class="col-md-4">
                            <a href="manageSuppliers.php" style="text-decoration: none; color: inherit;">

                                <div class="card d-flex align-items-center justify-content-center" style="height: 200px;">
                                <img src="https://cdni.iconscout.com/illustration/premium/thumb/shipping-truck-2811193-2339997.png"  style="height: 4rem; width:auto;" />

                                    <p class="card-text">Manage Suppliers</p>
                                </div>
                            </a>
                            </div>
                        </div>
                    </div>









                    <div class="card" id="add_vendor_user" style="display: none;">
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
                </div>


            </div>
        </div>

    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Warehouse/Supplier Information</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Add your form for editing vendor information here -->
                    <form method="post">
                        <!-- Include input fields for vendor information -->
                        <input type="hidden" name="warehouse_id" id="editWarehouseId" value="">
                        <div class="form-group">
                            <label for="editWarehouseName">Warehouse/Supplier Name</label>
                            <input type="text" class="form-control" id="editWarehouseName" name="warehouse_name" required>

                            <label for="editEmailId">Email Id</label>
                            <input type="text" class="form-control" id="editEmailId" name="email" required>

                            <label for="editAddress">Address</label>
                            <input type="text" class="form-control" id="editAddress" name="address" required>

                            <label for="editPhoneNo">Phone Number</label>
                            <input type="text" class="form-control" id="editPhoneNo" name="phone_number" required>
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
                var warehouseId = button.getAttribute('data-warehouse-id');
                var warehouseName = button.getAttribute('data-warehouse-name');
                var email = button.getAttribute('data-email');
                var address = button.getAttribute('data-address');
                var phoneNo = button.getAttribute('data-phone-number');

                // Set values in the modal input fields
                document.getElementById('editWarehouseId').value = warehouseId;
                document.getElementById('editWarehouseName').value = warehouseName;
                document.getElementById('editEmailId').value = email;
                document.getElementById('editAddress').value = address;
                document.getElementById('editPhoneNo').value = phoneNo;
            }
        </script>
    </div>


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
                    Are you sure you want to delete this Warehouse/Supplier?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <a class="btn btn-danger" id="confirmDeleteButton" href="#">Delete</a>
                </div>
            </div>
        </div>
    </div>



    <!-- Modal -->
    <div class="modal fade" id="addWarehouse" tabindex="-1" role="dialog" aria-labelledby="addWarehouse" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Create New WareHouse/Supplier</h5>
                    <button class="close" type="button" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST">
                        <label class="form-label" for="name">Warehouse/Supplier Name</label>
                        <input type="text" id="name" name="warehouseName" class="form-control" placeholder="Name of the Warehouse/Supplier ">

                        <label class="form-label mt-3" for="email">Email Id</label>
                        <input type="email" id="email" name="email" class="form-control" placeholder="Email Id">
                        <label class="form-label mt-3" for="Address">Phone Number</label>
                        <input type="number" id="phone" name="phone" class="form-control" placeholder="Contact Number ">
                        <label class="form-label mt-3" for="Address">Address</label>
                        <textarea id="Address" name="address" class="form-control" rows="5" placeholder="Address of the Warehouse/Supplier  "></textarea>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="addWarehouse" class="btn btn-primary">Add</button>
                </div>
                </form>

            </div>
        </div>
    </div>

    <script>
        $('#editModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var warehouseId = button.data('warehouse-id');
            var warehouseName = button.data('warehouse-name');
            var email = button.data('email');
            var address = button.data('address');
            var phone = button.data('phone-number');

            // Set the values in the modal form
            $('#editWarehouseId').val(warehouseId);
            $('#editWarehouseName').val(warehouseName);
            $('#editEmailId').val(email);
            $('#editAddress').val(address);
            $('#editPhoneNo').val(phone);
        });
    </script>

    <script>
        $('#deleteModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var warehouseId = button.data('warehouse-id');
            $('#confirmDeleteButton').attr('href', '?delete_warehouse=delete_warehouse&warehouse_id=' + warehouseId);
        });
    </script>

    <?php
    if (isset($_POST['addWarehouse'])) {
        // Assuming you have established a database connection already and stored it in $conn

        // Retrieve form data
        $warehouseName = $_POST['warehouseName'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];
        $email = $_POST['email'];

        // Prepare SQL statement with placeholders
        $sql = "INSERT INTO warehouse (warehouse_name, email, phone_number, address) VALUES (?, ?, ?, ?)";

        // Prepare and bind parameters
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $warehouseName, $email, $phone, $address);

        // Execute the statement
        if ($stmt->execute()) {
            // Data inserted successfully

            // Close statement and connection
            $stmt->close();
            $conn->close();

            // Redirect to warehouse.php
            header('Location: ./warehouse.php?addWarehouse=true');
            exit; // Stop further execution after the header redirect
        } else {
            // Error during execution
            // Handle the error (e.g., display an error message)
            echo "Error: " . $stmt->error;
            // Close statement and connection
            $stmt->close();
            $conn->close();
            exit; // Stop further execution
        }
    }

    if (isset($_GET['delete_warehouse'])) {
        $action_id = $_GET['warehouse_id'];

        $sql = "DELETE FROM warehouse WHERE warehouse_id = :id";
        $sent_po = "warehouse.php";
        $obj_admin->delete_data_by_this_method($sql, $action_id, $sent_po);
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_changes'])) {
        $warehouseId = $_POST['warehouse_id'];
        $warehouseName = $_POST['warehouse_name'];
        $email = $_POST['email'];
        $phone = $_POST['phone_number'];
        $address = $_POST['address'];

        // Check the connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Update database
        $sql = "UPDATE warehouse SET warehouse_name = ?, email = ?, phone_number = ?, address = ? WHERE warehouse_id = ?";

        $stmt = $conn->prepare($sql);

        // Bind parameters
        $stmt->bind_param("ssssi", $warehouseName, $email, $phone, $address, $warehouseId);

        // Execute the statement
        if ($stmt->execute()) {
            // Redirect or respond based on success
            header('Location: warehouse.php?save_changes=true');
            exit();
        } else {
            // Handle error
            echo "Error: " . $conn->error;
        }

        // Close the statement and connection
        $stmt->close();
        $conn->close();
    }

    if (isset($_GET['addWarehouse']) && $_GET['addWarehouse'] == 'true') {
        echo '<script>
        setTimeout(function() {
            Swal.fire({
                title: "Success",
                text: "Warehouse/Supplier has been added!",
                icon: "success",
                position: "top-end",
                showConfirmButton: false,
                timer: 2000,
                toast: true,
            });
        }, 1000); // Adjust the delay (in milliseconds) as needed
    </script>';
    }

    if (isset($_GET['save_changes']) && $_GET['save_changes'] == 'true') {
        echo '<script>
        setTimeout(function() {
            Swal.fire({
                title: "Success",
                text: "Warehouse/Supplier details have been updated!",
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

</div>
</div>
</div>

</div>




<?php
include 'INCLUDES/footer.php';

?>