<?php
include 'conn.php';
require './authentication.php';
include './INCLUDES/header.php';
$pagename = 'Past Vendor Records';

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

?>


<div class=" container d-flex justify-content-center py-2 bg-gradient shadow-lg text-uppercase" style="background: #956020" ><h2 class="m-auto text-light"  style="font-weight: 400 " >  Past Records</h2> </div>

<div class="container-fluid py-4">
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-11 m-auto">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h6>Past Vendor Records</h6>
                    </div>
                    <div class=" px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <?php
                            $sql = "SELECT timestamp, production_id, job, vin_number, COUNT(unit) AS total_units, unit 
                            FROM records 
                            GROUP BY production_id, vin_number, unit";
                    
                            $info = $obj_admin->manage_all_info($sql);
                            $serial  = 1;
                            $num_row = $info->rowCount();
                            if ($num_row == 0) {

                                echo '<tr><td colspan="7" class="d-flex justify-content-center align-items-center">No Projects were found</td></tr>';
                            }
                            ?>

<table class="table shadow-lg table-bordered table-sm" id="dataTable" name="dataTable" style="border: 1px solid white">
                  <thead style="height:4.5rem;">
                  <tr class="bg-gradient text-light my-2 text-center" style="background: #956020">
                                        <th scope="col" class="align-middle " width="6%" style="font-weight:500;">Order No.</th>
                                        <th scope="col" class="align-middle " width="6%" style="font-weight:500;">VIN</th>
                                        <th scope="col" class="align-middle " width="6%" style="font-weight:500;">No. of jobs</th>
                                        <th scope="col" class="align-middle " width="6%" style="font-weight:500;">Time Stamp</th>
                                        <th scope="col" class="align-middle " width="6%" style="font-weight:500;">Job</th>
                                        <th scope="col" class="align-middle " width="6%" style="font-weight:500;">Status</th>
                                    </tr>
                                </thead>
                                <tbody style="background: #dddddd70;">
                                    <?php
                                    while ($row = $info->fetch(PDO::FETCH_ASSOC)) {
                                    ?>
                                        <tr style="height: 5rem;">
                                            <td class="align-middle">
                                                <p class=" font-weight-bold mb-0 ms-4"><?php echo $row['unit'] ?></p>
                                            </td>
                                            <td class="align-middle">
                                                <p class=" font-weight-bold mb-0 ms-4"><?php echo $row['vin_number'] ?></p>
                                            </td>
                                            <td class="align-middle">
                                                <span class=" font-weight-bold ms-2"><?php echo $row['total_units'] ?></span>
                                            </td>
                                            
                                            <td class="align-middle">
                                                <span class=" font-weight-bold ms-2"><?php echo $row['timestamp'] ?></span>
                                            </td>

                                            <td class="align-middle">
                                               <span class=" font-weight-bold ms-2"><?php echo $row['job'] ?></span>
                                            </td>

                                            <td class=" d-flex justify-content-center pt-4">
                                              <h5>  <span class=" badge bg-success text-lowercase" style="font-weight: 400">Completed</span> </h5>
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


    <?php include './INCLUDES/footer.php'; ?>