<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require './PHPMailer/src/Exception.php';
require './PHPMailer/src/PHPMailer.php';
require './PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

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
$production_id = $_GET['production_id'];
$unit_number = $_GET['unit_number'];


?>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        var myModal = new bootstrap.Modal(document.getElementById('editpurchase'));
        myModal.show();
    });
</script>



<div class="modal fade " id="editpurchase" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">

    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header ">
                <h5 class="modal-title" id="exampleModalLabel">Production </h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-container">
                            <form role="form" action="" method="post" autocomplete="off">
                                <div class="form-horizontal">
                                    <div class="form-group">
                                        <div>
                                            <?php

                                            // Query to get the total number of customer
                                            $sql = "SELECT vin_number,note1,note2,note3,note4 FROM production WHERE production_id = $production_id";
                                            // Execute the query
                                            $results = $conn->query($sql);
                                            // Check if the query was successful
                                            if ($results) {
                                                // Fetch the result as an associative array
                                                $row = $results->fetch_assoc();
                                                @$vin_number =  $row['vin_number'];

                                                switch ($_GET['process']) {
                                                    case 'process1':
                                                        @$notedata = $row['note1'];
                                                        break;
                                                    case 'process2':
                                                        $notedata = $row['note2'];
                                                        break;
                                                    case 'process3':
                                                        $notedata = $row['note3'];
                                                        break;
                                                    case 'process4':
                                                        $notedata = $row['note4'];
                                                        break;
                                                    default:
                                                        exit();
                                                }
                                                // Free the result set
                                                $results->free();
                                            } else {
                                                // Handle the error if the query fails
                                                echo "Error: " . $conn->error;
                                            }



                                            $waitingStatus = '';

                                            // Check if the 'process' parameter is set in the URL
                                            if (isset($_GET['process'])) {
                                                // Assign the value of the 'process' parameter to the $waitingStatus variable
                                                $waitingStatus = $_GET['process'];
                                            }
                                            ?>



                                            <div class="mt-2">
                                                <label for="vin_number">VIN Number</label>
                                                <input type="text" value="<?php echo $vin_number ?>" class="form-control" name="vin_number" required>


                                            </div>

                                            <label for="select_vendor">Select Vendors For <?php

                                                                                            if ($waitingStatus === 'process1') {
                                                                                                echo "Mechanical Body Works";
                                                                                            } elseif ($waitingStatus === 'process2') {
                                                                                                echo "Electrical Works";
                                                                                            } elseif ($waitingStatus === 'process3') {
                                                                                                echo "Internal Fit Out Works";
                                                                                            } elseif ($waitingStatus === 'process4') {
                                                                                                echo "Optional Works";
                                                                                            }

                                                                                            ?>

                                            </label>
                                            <select name="selected_vendor_id" id="selected_vendor_id" class="form-control">
                                                <?php
                                                $sql = "SELECT * from vendors ";
                                                $info = $obj_admin->manage_all_info($sql);

                                                $serial  = 1;

                                                $num_row = $info->rowCount();

                                                if ($num_row == 0) {

                                                    echo 'No vendors were found';
                                                }
                                                while ($row = $info->fetch(PDO::FETCH_ASSOC)) {
                                                ?>

                                                    <option value="<?php echo $row['vendor_id'] ?>"><?php echo $row['fullname'] ?></option>

                                                <?php } ?>


                                            </select>
                                            <?php if ($waitingStatus === 'process4') { ?>
                                                <div class="form-check mt-4">
                                                    <input class="form-check-input" type="checkbox" name="no_optional_installations" id="no_optional_installations" value="0">
                                                    <label class="form-check-label" for="no_optional_installations">No optional installations to be done</label>
                                                </div>
                                            <?php } ?>
                                        </div>

                                        <div class="mt-2">
                                            <label for="vin_number">Note (optional)</label>
                                            <textarea class="form-control" name="note"><?php echo $notedata ?></textarea>


                                        </div>

                                    </div>
                                </div>
                                <div class="d-flex gap-2">
                                    <a class=" form-control btn btn-primary" href="production.php">Back</a>
                                    <button type="submit" name="start_process" class="form-control btn btn-primary">Assign</button>
                                </div>

                            </form>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php

include 'conn.php';

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['start_process'])) {
    $production_id = $_GET['production_id'];
    $selected_vendor_id = $_POST['selected_vendor_id'];
    $vin_number = $_POST['vin_number'];
    $unit = $_GET['unit_number'];
    $process = isset($_GET['process']) ? $_GET['process'] : '';
    $status = "Active";
    $note = $_POST['note'];

    switch ($process) {
        case  'process1':
            $notesql = 'note1';
            break;
        case  'process2':
            $notesql = 'note2';
            break;
        case  'process3':
            $notesql = 'note3';
            break;
        case  'process4':
            $notesql = 'note4';
            break;
        default:
            echo "error";
            exit();
    }

    $updateVinNumber = "UPDATE production SET vin_number = ? WHERE production_id = ? AND unit = ?";
    $updateVin = $conn->prepare($updateVinNumber);

    // Bind parameters
    $updateVin->bind_param("sis", $vin_number, $production_id, $unit_number);
    $updateVin->execute();
    $updateVin->close();

    $updateVinNumberliveProduction = "UPDATE live_purchase SET vin_number = ? WHERE purchase_id = ? ";
    $updateVinliveProduction = $conn->prepare($updateVinNumberliveProduction);

    // Bind parameters
    $updateVinliveProduction->bind_param("si", $vin_number, $production_id);
    $updateVinliveProduction->execute();
    $updateVinliveProduction->close();

    $updateVinNumberSales = "UPDATE sales 
                            SET vin_number = ? 
                          WHERE production_id = ?";
    $updateVinSales = $conn->prepare($updateVinNumberSales);

    // Bind parameters
    $updateVinSales->bind_param("si", $vin_number, $production_id);
    $updateVinSales->execute();
    $updateVinSales->close();



    // Check if the "no_optional_installations" checkbox is clicked and its value is "0"
    if (isset($_POST['no_optional_installations']) && $_POST['no_optional_installations'] === "0") {
        // No optional installations are required, so update the process status to 'Completed'
        $sql = "UPDATE production SET process4_status = 'Completed', process4 = '0' WHERE production_id = ?";

        // Prepare and execute the SQL statement to update the process status
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $production_id);

        if ($stmt->execute()) {
            // Process status updated successfully

            // Close the statement
            $stmt->close();

            // Redirect to the production page with a success message
            header('Location: production.php?no_optional_installations=true');
            exit(); // Terminate the script to prevent further execution
        } else {
            // Error updating the process status
            echo "Error: " . $conn->error;
            $stmt->close();
            $conn->close();
            exit(); // Terminate the script
        }
    } else {
        // The checkbox is not clicked or its value is not "0", proceed with regular process updates

        // Get the process parameter from the URL

        // Update the process status based on the process parameter
        switch ($process) {
            case 'process1':
                $processStatusColumn = 'process1_status';
                break;
            case 'process2':
                $processStatusColumn = 'process2_status';
                break;
            case 'process3':
                $processStatusColumn = 'process3_status';
                break;
            case 'process4':
                $processStatusColumn = 'process4_status';
                break;
            default:
                // Handle the case where the process parameter is invalid or not provided
                echo "Error: Invalid process";
                exit(); // Terminate the script
        }

        // Prepare the SQL query with the appropriate process status column
        $sql = "INSERT INTO production (production_id, vin_number, $process, unit, $processStatusColumn,$notesql)
                VALUES (?, ?, ?, ?, ?,?)
                ON DUPLICATE KEY UPDATE $process = VALUES($process), $processStatusColumn = VALUES($processStatusColumn) ,$notesql = VALUES($notesql)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isssss", $production_id, $vin_number, $selected_vendor_id, $unit, $status, $note);



        // Execute the statement
        if ($stmt->execute()) {
            // Production record inserted or updated successfully

            // Now, insert data into the sales table
            $salessql = "INSERT INTO sales (sales_id, vin_number, unit)
                         VALUES (?, ?, ?)
                         ON DUPLICATE KEY UPDATE vin_number = VALUES(vin_number)";

            $salesStmt = $conn->prepare($salessql);
            $salesStmt->bind_param("iss", $production_id, $vin_number, $unit);

            if ($salesStmt->execute()) {
                // Sales record inserted or updated successfully
                echo "Order Placed";
            } else {
                // Error inserting into the sales table
                echo "Error: " . $conn->error;
            }

            $salesStmt->close();
            $stmt->close();

            header('Location: production.php?start_process=true');
        } else {
            // Error inserting or updating the production table
            echo "Error: " . $conn->error;
            $stmt->close();
            exit(); // Terminate the script
        }

    }
    vendorMail($conn, $note, $selected_vendor_id);
}

function vendorMail($conn, $note, $selected_vendor_id)
{
    // SMTP configuration
    $smtpHost = 'smtp.gmail.com';
    $smtpUsername = 'engineering@csaengineering.com.au';
    $smtpPassword = 'kezfduovpirmalcs';
    $smtpPort = 587;
    $sql = "SELECT email_id,fullname from vendors Where vendor_id = $selected_vendor_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $vendor_mail_id = $row['email_id'];
            $vendor_name = $row['fullname'];
        }
    }
    $result->close();


    

    // Recipient email address
    $to = $vendor_mail_id;
    // Email subject
    $subject = 'New Project Work From E-CAR';

    // Initialize PHPMailer
    $mail = new PHPMailer(true);

    try {
        // SMTP configuration
        $mail->isSMTP();
        $mail->Host = $smtpHost;
        $mail->SMTPAuth = true;
        $mail->Username = $smtpUsername;
        $mail->Password = $smtpPassword;
        $mail->SMTPSecure = 'tls';
        $mail->Port = $smtpPort;

        // Recipients
        $mail->setFrom($smtpUsername, 'E-car Work Order');
        $mail->addAddress($to);

        // Email content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body =  'Hi '.$vendor_name.',<br><br>'.$note . '<br><br>For more details for this order login into Vendor Dashboard: <a href="https://csaappstore.com/demo.ecar.com/login/">Login here</a>';

        // Send email
        $mail->send();
        $conn->close();

    } catch (Exception $e) {
        echo "Error sending email: {$mail->ErrorInfo}";
    }
}



?>





<?php include './INCLUDES/footer.php'; ?>