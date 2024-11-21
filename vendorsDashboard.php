<?php

include 'conn.php';
require './authentication.php';
include './INCLUDES/header.php';
$pagename = 'Vendor Dashboard';

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

error_reporting(E_ALL);
ini_set('display_errors', 1);
ob_end_flush();

if (isset($_GET['upload_success']) && $_GET['upload_success'] == 'true') {
    echo '<script>
        setTimeout(function() {
            Swal.fire({
                title: "Success",
                text: "Uploads succcessful!",
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


<div class=" container d-flex justify-content-center py-2 bg-gradient shadow-lg text-uppercase" style="background: #28928b" ><h2 class="m-auto text-light"  style="font-weight: 400 " >  My Dashboard</h2> </div>


    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-11 m-auto">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h5>Vendor Requests</h5>
                    </div>
                    <div class=" px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                        <table class="table shadow-lg table-bordered table-sm" id="dataTable" name="dataTable" style="border: 1px solid white">
                  <thead style="height:4.5rem;">
                  <tr class="bg-gradient text-light my-2 text-center" style="background: #28928b">
                                        <th scope="col" class="align-middle " width="6%" style="font-weight:500;">Order No.</th>
                                        <th scope="col" class="align-middle " width="6%" style="font-weight:500;">VIN</th>
                                        <th scope="col" class="align-middle " width="6%" style="font-weight:500;">Work</th>
                                        <th scope="col" class="align-middle " width="6%" style="font-weight:500;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody style="background: #dddddd70;">
                                <?php
$sql = "SELECT * FROM production GROUP BY unit, vin_number, production_id";
$info = $conn->query($sql);

if ($info->num_rows > 0) {
    while ($row = $info->fetch_assoc()) {
        // Assuming $user_id is defined earlier in the code
        if (
            ($row['process1'] == $user_id && $row['process1_status'] == 'Active') ||
            ($row['process2'] == $user_id && $row['process2_status'] == 'Active') ||
            ($row['process3'] == $user_id && $row['process3_status'] == 'Active') ||
            ($row['process4'] == $user_id && $row['process4_status'] == 'Active')
        ) {
            ?>
            <tr style="height: 5rem;">
                <td class="align-middle">
                    <p class="text-sm font-weight-bold mb-0 ms-4"><?php echo htmlspecialchars($row['unit']); ?></p>
                </td>
                <td class="align-middle">
                    <p class="text-sm font-weight-bold mb-0 ms-4"><?php echo htmlspecialchars($row['vin_number']); ?></p>
                </td>
              

                <td> </td>
                <td class="align-middle d-flex align-content-center justify-content-center pt-4">
                   
                </td>
               
            </tr>
            <?php
        }
    }

                                    } ?>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
  





    <?php include './INCLUDES/footer.php'; ?>