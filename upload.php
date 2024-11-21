<?php
ob_start(); // Start output buffering

require __DIR__ . '/../vendor/autoload.php';

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

use Google\Client;
use Google\Service\Drive;
use GuzzleHttp\Client as GuzzleClient;

// Google Drive API Client initialization
$client = new Client();
$client->setAuthConfig('./csa-engineering-402307-8b9d357f3235.json');
$client->setSubject('ecar-623@csa-engineering-402307.iam.gserviceaccount.com');
$client->addScope(Drive::DRIVE);
$driveService = new Drive($client);

// Define the parent folder ID
$parentFolderId = '1-Kxq3i-NkSHhF7-SmXWldXWX50WZF_4Q';

include '../conn.php';





// Check if the form with the name 'newPurchase' is submitted
if (isset($_POST['newPurchase'])) {
    // Check if the file has been uploaded
    if (!empty($_FILES['file']['name'][0]) && is_array($_FILES['file']['tmp_name'])) {
        $purchase_name = $_POST['mailSubject'];
        $purchase_details = $_POST['mailContent'];
        $suppliers = $_POST['supplier_id'];
        $purchase_status = "Sent To Supplier";
        $selected_models = array();
        $components = array();
        $selected_vinNumbers = array();
        $selected_customerNames = array();
        $selected_noteUnit = array();
        $max_units = 10;
        $purchasetimestamp = date('Y-m-d H:i:s');

        // Collect attachments
        $attachments = array();
        foreach ($_FILES['file']['tmp_name'] as $key => $tmp_name) {
            if (!empty($_FILES['file']['tmp_name'][$key]) && file_exists($_FILES['file']['tmp_name'][$key])) {
                $attachments[] = array(
                    'name' => $_FILES['file']['name'][$key],
                    'tmp_name' => $_FILES['file']['tmp_name'][$key]
                );
            }
        }

        // Send email with all attachments
        if (!empty($attachments)) {
            supplierMail($conn, $suppliers, $purchase_name, $purchase_details, $attachments);
        }

        for ($i = 1; $i <= $max_units; $i++) {
            $field_name = 'unit_' . $i;
            $VinNumber_name = 'vinNumberUnit' . $i;
            $customerNameUnit_name = 'customerNameUnit' . $i;
            $components = 'components' . $i;
            $note = 'noteUnit' . $i;

            if (isset($_POST[$field_name]) && isset($_POST[$VinNumber_name])) {
                $selected_model = htmlspecialchars($_POST[$field_name]);
                $selected_models[$i] = $selected_model;

                $selected_vinNumber = htmlspecialchars($_POST[$VinNumber_name]);
                $selected_vinNumbers[$i] = $selected_vinNumber;

                $selected_customerName = htmlspecialchars($_POST[$customerNameUnit_name]);
                $selected_customerNames[$i] = $selected_customerName;

                $selected_component = htmlspecialchars($_POST[$components]);
                $selected_components[$i] = $selected_component;

                $selected_note = htmlspecialchars($_POST[$note]);
                $selected_noteUnit[$i] = $selected_note;
            }
        }

        $sql1 = "INSERT INTO live_purchase (subject, content, warehouse_suppliers, modal_name, purchase_status, vin_number, unit, customer, notes,components, purchase_timestamp) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?)";
        $stmt1 = $conn->prepare($sql1);

        // Prepare and execute the SQL statement for subsequent insertions
        $sql2 = "INSERT IGNORE INTO live_purchase (subject, content, warehouse_suppliers, modal_name, purchase_status, vin_number, unit, purchase_id, customer, notes, components, purchase_timestamp) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt2 = $conn->prepare($sql2);

        $first = true; // Flag to identify the first iteration

        if ($stmt1 && $stmt2) {
            foreach ($selected_models as $unit_number => $selected_model) {
                $vin_number = isset($selected_vinNumbers[$unit_number]) ? $selected_vinNumbers[$unit_number] : null;
                $customer = isset($selected_customerNames[$unit_number]) ? $selected_customerNames[$unit_number] : null;
                $note = isset($selected_noteUnit[$unit_number]) ? $selected_noteUnit[$unit_number] : null;
                $components = isset($selected_components[$unit_number]) ? $selected_components[$unit_number] : null;

                if ($first) {
                    $stmt1->bind_param("ssssssissss", $purchase_name, $purchase_details, $suppliers, $selected_model, $purchase_status, $vin_number, $unit_number, $customer, $note,$components, $purchasetimestamp);
                    $stmt1->execute();

                    $purchase_id = $conn->insert_id;
                    $first = false;
                } else {
                    $stmt2->bind_param("ssssssisssss", $purchase_name, $purchase_details, $suppliers, $selected_model, $purchase_status, $vin_number, $unit_number, $purchase_id, $customer, $note, $components, $purchasetimestamp);
                    if (!$stmt2->execute()) {
                        echo "Error: " . $stmt2->error;
                        break;
                    }
                }
            }

            // Check if there are selected customer names
            if (!empty($selected_customerNames)) {
                $salessql = "INSERT INTO sales (vin_number, model_name, customer_name, unit, purchase_timestamp) VALUES (?, ?, ?, ?, ?)";
                $stmtSales = $conn->prepare($salessql);

                if ($stmtSales) {
                    // Loop through selected customer names and insert into sales table
                    foreach ($selected_customerNames as $index => $customerName) {
                        $vin_number = $selected_vinNumbers[$index];
                        $selected_model = $selected_models[$index];
                        $unit_number = $index + 1; // Adjusting to 1-based index

                        $stmtSales->bind_param("sssis", $vin_number, $selected_model, $customerName, $unit_number, $purchasetimestamp);
                        $stmtSales->execute();
                    }

                    $stmtSales->close();
                } else {
                    echo "Error: " . $conn->error;
                }
            }

            $stmt1->close();
            $stmt2->close();


            // Create or get the necessary folders
            $parentFolderID = createOrGetFolder($driveService, $parentFolderId,  'Order' . $purchase_id);
            $PurchaseFolderName = createOrGetFolder($driveService, $parentFolderID, 'Purchases');
            $folderId = $PurchaseFolderName;

            // Handle file upload
            foreach ($_FILES['file']['tmp_name'] as $key => $uploadedFilePath) {
                // Ensure $uploadedFilePath is not empty before proceeding
                if (!empty($uploadedFilePath)) {
                    $originalFileName = $_FILES['file']['name'][$key];

                    // Initialize Guzzle HTTP client
                    $httpClient = new GuzzleClient();

                    // Define metadata for the file
                    $fileMetadata = new Drive\DriveFile([
                        'name' => $originalFileName,
                        'parents' => [$folderId],
                    ]);

                    // Open the uploaded file for reading
                    $fileHandle = fopen($uploadedFilePath, 'rb');

                    // Check if the file is uploaded successfully
                    if (is_resource($fileHandle)) {
                        try {
                            // Upload the file to Google Drive
                            $response = $httpClient->request('POST', 'https://www.googleapis.com/upload/drive/v3/files?uploadType=multipart&supportsAllDrives=true', [
                                'headers' => [
                                    'Authorization' => 'Bearer ' . $client->getAccessToken()['access_token'],
                                    'Content-Type' => 'multipart/related; boundary=boundary',
                                ],
                                'body' => "--boundary\r\nContent-Type: application/json; charset=UTF-8\r\n\r\n" .
                                    json_encode($fileMetadata->toSimpleObject()) . "\r\n" .
                                    "--boundary\r\nContent-Type: " . mime_content_type($uploadedFilePath) . "\r\n\r\n" .
                                    fread($fileHandle, filesize($uploadedFilePath)) . "\r\n" .
                                    "--boundary--",
                            ]);

                            // Check if the file was uploaded successfully
                            if ($response->getStatusCode() === 200) {
                                // echo "File uploaded successfully: " . $originalFileName . "\n";

                                echo '<script>
                                setTimeout(function() {
                                Swal.fire({
                                title: "Success",
                                text: "File uploaded successfully!",
                                icon: "success",
                                position: "top-end",
                                showConfirmButton: false,
                                timer: 2000,
                                toast: true,
                            });
                        }, 1000); // Adjust the delay (in milliseconds) as needed

                        // Redirect to the dashboard after processing
                        window.location.href = "../Dashboard.php?upload_success=true";
                    </script>';
                            } else {
                                echo "Failed to upload file: " . $originalFileName . "\n";
                            }
                        } catch (Exception $e) {
                            echo "Error uploading file: " . $e->getMessage() . "\n";
                        } finally {
                            // Close the file handle
                            fclose($fileHandle);
                        }
                    } else {
                        echo "Failed to open file: " . $originalFileName . "\n";
                    }
                }
            }
        } else {
            echo "Error preparing statements: " . $conn->error;
        }
    } else {
        echo 'No file uploaded.';
    }
}


// Function to create or get a folder in Google Drive
function createOrGetFolder($driveService, $parentFolderId, $folderName)
{
    $existingFolders = $driveService->files->listFiles([
        'q' => "'$parentFolderId' in parents and mimeType='application/vnd.google-apps.folder' and name='$folderName'",
    ]);

    if (count($existingFolders->getFiles()) > 0) {
        // Folder with the same name already exists, use the existing one
        return $existingFolders->getFiles()[0]->id;
    } else {
        // Folder with the desired name does not exist, create a new one
        $folderMetadata = new Drive\DriveFile([
            'name' => $folderName,
            'parents' => [$parentFolderId],
            'mimeType' => 'application/vnd.google-apps.folder'
        ]);

        $folder = $driveService->files->create($folderMetadata, [
            'fields' => 'id'
        ]);

        return $folder->id;
    }
}




function supplierMail($conn, $suppliers, $purchase_name, $purchase_details, $attachments)
{
    // SMTP configuration
    $smtpHost = 'smtp.gmail.com'; // SMTP server hostname
    $smtpUsername = 'engineering@csaengineering.com.au'; // Your email address
    $smtpPassword = 'kezfduovpirmalcs'; // Your email password
    $smtpPort = 587; // SMTP port (usually 465 for SSL)

    $sql = "SELECT email_id from suppliers Where supplier_id = $suppliers";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $supplier_mail_id = $row['email_id'];
        }
    }
    $result->close();

    // Recipient email address
    $to = $supplier_mail_id;
    // Email subject
    $subject = $purchase_name;

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
        $mail->setFrom($smtpUsername, 'Ecar New Order');
        $mail->addAddress($to);

        // Email content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $purchase_details . '<br><br>For more details for this order login into Supplier Dashboard: <a href="https://csaappstore.com/demo.ecar.com/login/">Login here</a>';


        // Attach files uploaded via form
        foreach ($attachments as $attachment) {
            $file_name = $attachment['name'];
            $file_tmp_name = $attachment['tmp_name'];
            $mail->addAttachment($file_tmp_name, $file_name); // Attach the file
        }
        // Send email
        $mail->send();
        // header('location:../dashboard.php?upload_success=true');
    } catch (Exception $e) {
        echo "Error sending email: {$mail->ErrorInfo}";
    }
}





ob_end_flush(); // Flush the output buffer
