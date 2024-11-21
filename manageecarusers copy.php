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
}
// check admin
$user_role = $_SESSION['user_role'];


if (isset($_POST['add_user'])) {
    $error = $obj_admin->add_new_user($_POST);
}

if (isset($_GET['delete_user'])) {
    $action_id = $_GET['admin_id'];

    $sql = "DELETE FROM accounts WHERE user_id = :id";
    $sent_po = "admin.php";
    $obj_admin->delete_data_by_this_method($sql, $action_id, $sent_po);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_changes'])) {
    $userId = $_POST['user_id'];
    $fullName = $_POST['full_name'];
    $email = $_POST['email_id'];
    $phone = $_POST['phone_no'];

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Update database
    $sql = "UPDATE accounts SET fullname = ?, email_id = ?, phone_no = ? WHERE user_id = ?";

    $stmt = $conn->prepare($sql);

    // Bind parameters
    $stmt->bind_param("sssi", $fullName, $email, $phone, $userId);

    // Execute the statement
    if ($stmt->execute()) {
        header('Location: admin.php');
        exit();
    } else {
        // Handle error
        echo "Error: " . $conn->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}



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

if (isset($_GET['save_changes']) && $_GET['save_changes'] == 'true') {
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
        
                <div class="card mb-4" id="add_supplier">
                    <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Warehouse / Suppliers</h6>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addWarehouse">
                            Add WareHouse/Suppliers
                        </button>
                    </div>


                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-3">
                            <table class="allTables table align-items-center justify-content-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 d-flex-column justify-content-center align-content-center">Name</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 d-flex-column justify-content-center align-content-center">Email</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 d-flex-column justify-content-center align-content-center">Phone No</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 d-flex-column justify-content-center align-content-center">Address</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 d-flex-column justify-content-center align-content-center">Actions</th>
                                    </tr>
                                </thead>

                                <?php

                                $sql = "SELECT * FROM suppliers";

                                $info = $obj_admin->manage_all_info($sql);

                                $serial  = 1;

                                $num_row = $info->rowCount();


                                while ($row = $info->fetch(PDO::FETCH_ASSOC)) {

                                ?>
                                    <tbody>
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
                                            <td>
                                                <div class="d-flex px-2 ">
                                                    <h6 class="mb-0 text-sm"><?php echo $row['address'] ?></h6>
                                                </div>
                                            </td>
                                            </td>
                                            <td class="align-middle ">
                                                <a href="#" class="btn btn-link" data-bs-toggle="modal" data-bs-target="#editModal" onclick="populateEditModal(this)" data-warehouse-id="<?php echo $row['supplier_id']; ?>" data-warehouse-name="<?php echo $row['fullname']; ?>" data-email="<?php echo $row['email_id']; ?>" data-address="<?php echo $row['address']; ?>" data-phone-number="<?php echo $row['contact_number']; ?>">
                                                    <i class="fas fa-user-edit" style="color: #7a4edf;"></i>
                                                </a>
                                                <a class="btn btn-link" title="Delete" href="#" data-bs-toggle="modal" data-bs-target="#deleteModal" data-warehouse-id="<?php echo $row['supplier_id']; ?>">
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

<script script>
    var manage_users = document.getElementById("manage_users");
    var password_change = document.getElementById("password_change");
    var add_user = document.getElementById("add_user");
    var add_vendor_user = document.getElementById("add_vendor_user");
    var add_supplier = document.getElementById("add_supplier");


    function toggleView(value) {
        if (value == 0) {
            manage_users.style.display = "block";
            password_change.style.display = "none";
            add_user.style.display = "none";
            add_vendor_user.style.display = "none";
            add_supplier.style.display = "none";
        } else if (value == 1) {
            manage_users.style.display = "none";
            password_change.style.display = "block";
            add_user.style.display = "none";
            add_vendor_user.style.display = "none";
            add_supplier.style.display = "none";
        } else if (value == 2) {
            manage_users.style.display = "none";
            password_change.style.display = "none";
            add_user.style.display = "block";
            add_vendor_user.style.display = "none";
            add_supplier.style.display = "none";
        } else if (value == 3) {
            manage_users.style.display = "none";
            password_change.style.display = "none";
            add_user.style.display = "none";
            add_vendor_user.style.display = "block";
            add_supplier.style.display = "none";
        } else if (value == 4) {
            manage_users.style.display = "none";
            password_change.style.display = "none";
            add_user.style.display = "none";
            add_vendor_user.style.display = "none";
            add_supplier.style.display = "block";
        }

    }
</script>
</div>




<?php
include 'INCLUDES/footer.php';

?>