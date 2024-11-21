<?php
include 'conn.php';
require './authentication.php';
include './INCLUDES/header.php';
$pagename = 'Supplier Upload Form';

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

if (isset($_POST['update'])){
    $production_id = $_GET['purchase_id'];
    $unit = $_GET['unitNo'];
    $tracking_id = $_POST['tracking_id'];
    $tracking_info = $_POST['tracking_info'];

    $sql = "UPDATE  live_purchase set tracking_id = ? ,tracking_service = ? WHERE purchase_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssi',$tracking_id,$tracking_info,$production_id);
    $stmt->execute();
    if($stmt->affected_rows > 0){
        header('location:supplierDashboard.php');
        exit();
    }else{
        echo "ERROR";
    }  
}
?>

<head>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <!-- Bootstrap JS and Popper.js -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        input[type="checkbox"] {
            margin-right: 5px;
        }

        input[type="text"] {
            width: 100%;
            box-sizing: border-box;
            padding: 5px;
        }
    </style>
</head>




<div class="container">
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var myModal = new bootstrap.Modal(document.getElementById('exampleModal'));
            myModal.show();
        });
    </script>



    <!-- Modal -->
    <div class="modal fade" id="exampleModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog " role="document">
        <div class="modal-content ">
        <div class="modal-header bg-primary bg-gradient">
                    <h5 class="modal-title text-light" id="exampleModalLabel">Update Tracking Information</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                 
                    <!-- Your HTML Form -->
                    <form  method="POST" enctype="multipart/form-data">

                        <label class="form-label" for="formFile">Update Tracking Id</label>
                        <input type="text" name="tracking_id" class="form-control" placeholder="125Tdfxxxxxxxxx" required>
                        <label class="form-label mt-3" for="formFile">Update Tracking Service Provider</label>
                        <input type="text" name="tracking_info" class="form-control" placeholder="Blue Dart Express Limited" required>
                       

                        <!-- Display Upload Message -->
                        <?php

                        if (!empty($uploadMessage)) : ?>
                            <div class="mt-3 alert <?php echo strpos($uploadMessage, 'successfully') !== false ? 'alert-success' : 'alert-danger'; ?>">
                                <?php echo $uploadMessage; ?>
                            </div>
                        <?php endif; ?>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-success mt-3" name="update" type="submit">Submit</button>
                    </form>
                    <a class="btn btn-secondary mt-3" href="liveOrders.php">Back</a>
                </div>


                <?php
                // ... (previous PHP code)

                echo '<script>
    // Show loading spinner when the form is submitted
    $(document).on("submit", "form", function () {
        Swal.fire({
            title: "Updating...",
            html: "Please wait while the files are being uploaded.",
            allowOutsideClick: false,
            onBeforeOpen: () => {
                Swal.showLoading();
            },
        });
    });
</script>';

                // ... (rest of your PHP code)
                ?>

            </div>
        </div>
    </div>
</div>


<?php include './INCLUDES/footer.php'  ?>