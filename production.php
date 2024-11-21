<?php

use function PHPSTORM_META\type;

require './authentication.php';
include './INCLUDES/header.php';
$pagename = 'Production/Live-Orders';

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

if (isset($_GET['start_process']) && $_GET['start_process'] == 'true') {
    echo '<script>
        setTimeout(function() {
            Swal.fire({
                title: "Success",
                text: "Process Updated!",
                icon: "success",
                position: "top-end",
                showConfirmButton: false,
                timer: 2000,
                toast: true,
            });
        }, 1000); // Adjust the delay (in milliseconds) as needed
    </script>';
}

if (isset($_GET['qc_upload']) && $_GET['qc_upload'] == 'true') {
    echo '<script>
        setTimeout(function() {
            Swal.fire({
                title: "Success",
                text: "Quality Control Form has been uploaded!",
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








<div class=" py-4">
<div class=" mb-5 mt-2 container d-flex justify-content-center py-2 container  bg-gradient shadow-lg text-uppercase" style="background: #5d855b"><h2 class="m-auto text-light" style="font-weight: 400" >Production Tab</h2> </div>

<div class="row ">
        <div class="col-11 m-auto">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h5>Projects Ready to Start</h5>
                   

                </div>

<div class="table-responsive p-3">
                <table class="table shadow-lg table-bordered table-sm" id="production-table-first" name="dataTable" style="border: 1px solid white">
                  <thead style="height:4.5rem;">
                  <tr class="bg-gradient text-light my-2 text-center" style="background: #5d855b">
                                    <th scope="col" class="align-middle " width="6%" style="font-weight:500;">Odoo Order Id</th>
                                    <th scope="col" class="align-middle " width="6%" style="font-weight:500;">VIN Number</th>

                                    <th scope="col" class="align-middle " width="6%" style="font-weight:500;">Actions</th>
                                </tr>
                            </thead>
                            <tbody style="background: #dddddd70;">
                                <?php
                                $sql = "SELECT lp.*, p.production_id, p.process1_status, p.process2_status, p.process3_status, p.process4_status, p.QC_Status
                                FROM live_purchase lp
                                LEFT JOIN production p ON lp.unit = p.unit AND lp.purchase_id = p.production_id
                                WHERE lp.purchase_status = 'Completed' AND (p.production_id IS NULL OR p.process1_status IS NULL OR p.process1_status = 'Waiting' OR p.process2_status IS NULL OR p.process2_status = 'Waiting' OR p.process3_status IS NULL OR p.process3_status = 'Waiting' OR p.process4_status IS NULL OR p.process4_status = 'Waiting')";



                                $info = $obj_admin->manage_all_info($sql);

                                $serial  = 1;

                                $num_row = $info->rowCount();

                                if ($num_row == 0) {

                                    echo '<tr style="height: 5rem; background: white"><td colspan="12" class="d-flex pt-3 justify-content-center align-items-center">No Ready to Start projects found.</td></tr>';
                                }
                                ?>

                                <?php
                                while ($row = $info->fetch(PDO::FETCH_ASSOC)) {

                                ?>
                                    <tr style="height: 5rem;" >
                                        <td class="align-middle">
                                            <p class="text-sm font-weight-bold mb-0  ms-4" ><?php echo $row['unit'] ?></p>
                                        </td>
                                        <td class="align-middle">
                                            <p class="text-sm font-weight-bold mb-0  ms-4" ><?php echo $row['vin_number'] ?></p>
                                        </td>





                                        <td class="px-3 pt-4">

                                            <?php
                                            if ($row['process1_status'] === 'Active') {
                                                $buttonColor = 'badge bg-warning';
                                            } elseif (is_null($row['process1_status'])) {
                                                $buttonColor = 'badge bg-danger';
                                            } elseif ($row['process1_status'] === 'Completed') {
                                                $buttonColor = 'badge bg-success';
                                            } elseif ($row['process1_status'] === 'Waiting') {
                                                $buttonColor = ' badge bg-danger';
                                            }
                                            ?>
                                            <a href="startProduction.php?production_id=<?php echo $row['purchase_id']; ?>&unit_number=<?php echo $row['unit']; ?>&process=process1" class="<?php echo $buttonColor; ?> ">Mechanical<br>Installation</a>



                                            <?php
                                            if ($row['process2_status'] === 'Active') {
                                                $buttonColor = ' badge bg-warning';
                                            } elseif (is_null($row['process2_status'])) {
                                                $buttonColor = ' badge bg-danger';
                                            } elseif ($row['process2_status'] === 'Completed') {
                                                $buttonColor = ' badge bg-success';
                                            } elseif ($row['process2_status'] === 'Waiting') {
                                                $buttonColor = ' badge bg-danger';
                                            }
                                            ?>
                                            <a href="startProduction.php?production_id=<?php echo $row['purchase_id']; ?>&unit_number=<?php echo $row['unit']; ?>&process=process2" class=" <?php echo $buttonColor; ?>">Electrical<br>Installation</a>


                                            <?php
                                            if ($row['process3_status'] === 'Active') {
                                                $buttonColor = ' badge bg-warning';
                                            } elseif (is_null($row['process3_status'])) {
                                                $buttonColor = ' badge bg-danger';
                                            } elseif ($row['process3_status'] === 'Completed') {
                                                $buttonColor = ' badge bg-success';
                                            } elseif ($row['process3_status'] === 'Waiting') {
                                                $buttonColor = ' badge bg-danger';
                                            }
                                            ?>
                                            <a href="startProduction.php?production_id=<?php echo $row['purchase_id']; ?>&unit_number=<?php echo $row['unit']; ?>&process=process3" class="<?php echo $buttonColor; ?>">Internal Fit Out<br>Installation</a>



                                            <?php
                                            if ($row['process4_status'] === 'Active') {
                                                $buttonColor = ' badge bg-warning';
                                            } elseif (is_null($row['process4_status'])) {
                                                $buttonColor = ' badge bg-danger';
                                            } elseif ($row['process4_status'] === 'Completed') {
                                                $buttonColor = ' badge bg-success';
                                            } elseif ($row['process4_status'] === 'Waiting') {
                                                $buttonColor = ' badge bg-danger';
                                            }
                                            ?>
                                            <a href="startProduction.php?production_id=<?php echo $row['purchase_id']; ?>&unit_number=<?php echo $row['unit']; ?>&process=process4" class="<?php echo $buttonColor; ?>">Optional<br>Installation</a>


                                        </td>
                                    </tr>




                                <?php } ?>

                            </tbody>
                        </table>

                    </div>
                    </div>
                    </div>
                    </div>


    <div class="row">
        <div class="col-11 m-auto">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    
                    <h5>Live Production</h5>
                </div>


                <div class="">
              <div class="table-responsive p-3">
                <table class="table shadow-lg table-bordered  table-sm" id="production-table" name="dataTable" >
                  <thead style="height:4.5rem;">
                    <tr class="bg-gradient text-light my-2 text-center" style="background: #5d855b">
                                    <th scope="col" class="align-middle " width="6%" style="font-weight:500;">Odoo Order Id</th>
                                    <th scope="col" class="align-middle " width="6%" style="font-weight:500;">VIN Number</th>


                                    <th scope="col" class="align-middle " width="6%" style="font-weight:500;">Mechanical Installation</th>
                                    <th scope="col" class="align-middle " width="6%" style="font-weight:500;">Electrical Installation</th>
                                    <th scope="col" class="align-middle " width="6%" style="font-weight:500;">Internal Fit Out Installation</th>
                                    <th scope="col" class="align-middle " width="6%" style="font-weight:500;">Optional Installation</th>
                                    <th scope="col" class="align-middle " width="15%" style="font-weight:500;">Actions</th>
                                </tr>
                            </thead>

                            <tbody style="background: #dddddd70;" >
                                <?php
                                $sql = "SELECT p.*, 
                                v1.fullname AS process1_vendor, 
                                v2.fullname AS process2_vendor, 
                                v3.fullname AS process3_vendor, 
                                v4.fullname AS process4_vendor
                         FROM production p
                         LEFT JOIN vendors v1 ON p.process1 = v1.vendor_id
                         LEFT JOIN vendors v2 ON p.process2 = v2.vendor_id
                         LEFT JOIN vendors v3 ON p.process3 = v3.vendor_id
                         LEFT JOIN vendors v4 ON p.process4 = v4.vendor_id
                         WHERE QC_Status <> 'QC_DONE' OR QC_Status IS NULL;
                         
        
                                         ";

                                $info = $obj_admin->manage_all_info($sql);

                                $serial  = 1;

                                $num_row = $info->rowCount();

                                if ($num_row == 0) {

                                    echo '<tr><td colspan="7" class="d-flex justify-content-center align-items-center">No Projects were found</td></tr>';
                                }
                                ?>
                                <?php
                                while ($row = $info->fetch(PDO::FETCH_ASSOC)) {
                                ?>
                                    <tr style="height: 5rem;">
                                        <td class="align-middle">
                                            <p class=" font-weight-bold mb-0  ms-4"><?php echo $row['unit'] ?></p>
                                        </td>
                                        <td class="align-middle">
                                            <p class=" font-weight-bold mb-0  ms-4"><?php echo $row['vin_number'] ?></p>
                                        </td>

                                        <td class="px-3 pt-4">
                                            <div class="<?php
                                                        if ($row['process1_status'] === 'Active') {
                                                            echo 'badge bg-warning';
                                                        } elseif (is_null($row['process1_status'])) {
                                                            echo 'badge bg-danger';
                                                        } elseif ($row['process1_status'] === 'Completed') {
                                                            echo 'badge bg-success';
                                                        } elseif ($row['process1_status'] === 'Waiting') {
                                                            echo 'badge bg-danger';
                                                        }
                                                        ?>">
                                                <span class=" font-weight-bold  ">
                                                    <?php echo !empty($row['process1']) ? $row['process1_vendor'] : "Not Assigned"; ?>
                                                </span>
                                            </div>
                                        </td>

                                        <td class="px-3 pt-4">
                                            <div class="<?php
                                                        if ($row['process2_status'] === 'Active') {
                                                            echo 'badge bg-warning';
                                                        } elseif (is_null($row['process2_status'])) {
                                                            echo 'badge bg-danger';
                                                        } elseif ($row['process2_status'] === 'Completed') {
                                                            echo 'badge bg-success';
                                                        } elseif ($row['process2_status'] === 'Waiting') {
                                                            echo 'badge bg-danger';
                                                        }
                                                        ?>">
                                                <span class="text-xs font-weight-bold ">
                                                    <?php echo !empty($row['process2']) ? $row['process2_vendor'] : "Not Assigned"; ?>
                                                </span>
                                            </div>
                                        </td>

                                        <td class="px-3 pt-4">
                                            <div class="<?php
                                                        if ($row['process3_status'] === 'Active') {
                                                            echo 'badge bg-warning';
                                                        } elseif (is_null($row['process3_status'])) {
                                                            echo 'badge bg-danger';
                                                        } elseif ($row['process3_status'] === 'Completed') {
                                                            echo 'badge bg-success';
                                                        } elseif ($row['process3_status'] === 'Waiting') {
                                                            echo ' badge bg-danger';
                                                        }
                                                        ?>">
                                                <span class="text-xs font-weight-bold  ">
                                                    <?php echo !empty($row['process3']) ? $row['process3_vendor'] : "Not Assigned"; ?>
                                                </span>
                                            </div>
                                        </td>

                                        <td class="px-3 pt-4">
                                            <div class="<?php
                                                        if ($row['process4_status'] === 'Active') {
                                                            echo 'badge bg-warning';
                                                        } elseif (is_null($row['process4_status'])) {
                                                            echo 'badge bg-danger';
                                                        } elseif ($row['process4_status'] === 'Completed') {
                                                            echo 'badge bg-success';
                                                        } elseif ($row['process4_status'] === 'Waiting') {
                                                            echo ' badge bg-danger';
                                                        }
                                                        ?>">
                                                <span class=" font-weight-bold ">
                                                    <?php echo !empty($row['process4']) ? $row['process4_vendor'] : "Not Assigned"; ?>
                                                </span>
                                            </div>
                                        </td>




                                        <td class=" font-weight-bold  pt-4" style="width:25%">


                                         <div class="d-flex justify-content-center">
                                         <button onclick="window.location.href='QC.php?productionId=<?php echo $row['production_id']; ?>&unit=<?php echo $row['unit']; ?>'" class="btn btn-sm btn-info" <?= $row['process1_status'] === "Completed" &&
                                                                                                                                                                                                            $row['process2_status'] === "Completed" &&
                                                                                                                                                                                                            $row['process3_status'] === "Completed" &&
                                                                                                                                                                                                            $row['process4_status'] === "Completed" ? '' : 'disabled' ?>>
                                                Pre Delivery
                                            </button>
                                            <a href="./universalfileupload.php?orderId=<?php echo $row['production_id']; ?>" class="btn btn-warning btn-sm ms-3">
                                                Upload File
                                            </a>
                                         </div>

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
    <script>

$(document).ready(function () {
    var table = $('#production-table').DataTable({
        paging: true,
        searching: true,
        ordering: true
    });

    $('#customSearchInput').on('keyup', function () {
        table.search(this.value).draw();
    });
});


</script>


<script>

$(document).ready(function () {
    var table = $('#production-table-first').DataTable({
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










<?php include './INCLUDES/footer.php'; ?>