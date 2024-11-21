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
                                 
                                    
                                    $sql = "SELECT * 
                                    FROM production 
                                    WHERE ((process1 = $user_id AND process1_status = 'Active') 
                                           OR (process2 = $user_id AND process2_status = 'Active') 
                                           OR (process3 = $user_id AND process3_status = 'Active')
                                           OR (process4 = $user_id AND process4_status = 'Active'))
                                         
                                          ";
                            
                                    $info = $obj_admin->manage_all_info($sql);

                                    $serial  = 1;

                                    $num_row = $info->rowCount();

                                    // Check if there are any rows returned
                                    if ($info->rowCount() == 0) {
                                        echo '<tr><td colspan="5" class="text-center">No Active Processes Found</td></tr>';
                                    } else {
                                        // Loop through the results
                                        while ($row = $info->fetch(PDO::FETCH_ASSOC)) {
                                             
                                                // Output fetched row for debugging
                                                  echo '<tr style="height: 5rem;">';

                                            // Iterate over process columns and check if each is active
                                            for ($i = 1; $i <= 4; $i++) {
                                                $process = 'process' . $i;
                                                $status = $process . '_status';

                                                // Check if the key exists and process is active
                                                if (isset($row[$process]) && isset($row[$status]) && $row[$status] === 'Active') {
                                                    if ($row[$process] == $user_id && $row[$status] === "Active") {
                                                    echo '<td class="align-middle px-3">' . $row['unit'] . '</td>';
                                                    echo '<td class="align-middle px-3">' . $row['vin_number'] . '</td>';
                                                    echo '<td class="align-middle px-3">';
                                                    
                                                        $currentProcess =  "Process" . $i;
                                                        if ($currentProcess === 'Process1') {
                                                            echo "Mechanical Body Installation";
                                                        } elseif ($currentProcess === 'Process2') {
                                                            echo "Electrical Installation";
                                                        } elseif ($currentProcess === 'Process3') {
                                                            echo "Internal Fit Out Installation";
                                                        } elseif ($currentProcess === 'Process4') {
                                                            echo "Optional Installation";
                                                        }
                                                       
                                                        $processSQL = "process" . $i;
                                                    
                                                    echo '</td>';

                                                    // Construct the URL for Quality Assurance link
                                                    $qualityAssuranceURL = 'vendorWorkCompletionForm.php?production_id=' . $row['production_id'] . '&unitNo=' . $row['unit'] . '&vin_number=' . $row['vin_number'] . '&CurrentProcess=' . $processSQL;
                                                    echo '<td class="d-flex justify-content-center pt-4 px-3"><a href="' . $qualityAssuranceURL . '" class="btn bg-gradient btn-warning">Quality Assurance</a></td>';
                                                    echo '</tr>'; // Start a new row for the next process
                                                    }
                                                }
                                            }
                                        } 
                                      }
                                    
                                    ?>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
  





    <?php include './INCLUDES/footer.php'; ?>