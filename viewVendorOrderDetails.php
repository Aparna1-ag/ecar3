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

?>

<div class="container-fluid py-4">
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h6>Live Production</h6>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center justify-content-center mb-0">
                                <thead>
                                    <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 d-flex-column justify-content-center align-content-center">Order No</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 d-flex-column justify-content-center align-content-center">VIN Number</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2 d-flex-column justify-content-center align-content-center">Work</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2 d-flex-column justify-content-center align-content-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $production_id = intval($_GET['production_id']);
                                    $vin_number = $_GET['vin_number'];
                                    
                                    $sql = "SELECT * 
                                    FROM production 
                                    WHERE ((process1 = $user_id AND process1_status = 'Active') 
                                           OR (process2 = $user_id AND process2_status = 'Active') 
                                           OR (process3 = $user_id AND process3_status = 'Active')
                                           OR (process4 = $user_id AND process4_status = 'Active'))
                                          AND production_id = $production_id
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
                                                  echo '<tr>';

                                            // Iterate over process columns and check if each is active
                                            for ($i = 1; $i <= 4; $i++) {
                                                $process = 'process' . $i;
                                                $status = $process . '_status';

                                                // Check if the key exists and process is active
                                                if (isset($row[$process]) && isset($row[$status]) && $row[$status] === 'Active') {
                                                    if ($row[$process] == $user_id && $row[$status] === "Active") {
                                                    echo '<td>' . $row['unit'] . '</td>';
                                                    echo '<td>' . $row['vin_number'] . '</td>';
                                                    echo '<td>';
                                                    
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
                                                    echo '<td><a href="' . $qualityAssuranceURL . '" class="btn btn-primary">Quality Assurance</a></td>';
                                                    echo '</tr><tr>'; // Start a new row for the next process
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

    </div>





    <?php

    include './INCLUDES/footer.php';
    ?>