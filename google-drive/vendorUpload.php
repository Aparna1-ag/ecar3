<?php

use Google\Client;
use Google\Service\Drive;
use GuzzleHttp\Client as GuzzleClient;

// Google Drive API Client initialization
$client = new Client();
$client->setAuthConfig('./google-drive/csa-engineering-402307-8b9d357f3235.json');
$client->setSubject('ecar-623@csa-engineering-402307.iam.gserviceaccount.com');
$client->addScope(Drive::DRIVE);
$driveService = new Drive($client);

// Define the parent folder ID
$parentFolderId = '1-Kxq3i-NkSHhF7-SmXWldXWX50WZF_4Q';


// Sanitize and retrieve form data
$productionId = $_POST['production_id'];
$vinNumber = $_POST['vin_number'];
$unit = $_POST['unit'];
echo $currentProcess = $_POST['currentProcess'].'_status';

$sql = "SELECT warehouse_suppliers from live_purchase WHERE purchase_id = $productionId ";
$info = $obj_admin->manage_all_info($sql);

$serial  = 1;

$num_row = $info->rowCount();


while ($row = $info->fetch(PDO::FETCH_ASSOC)) {
    $suppliers = $row['warehouse_suppliers'];
}

if ($currentProcess === 'process1_status') {
    $job = "Mechanical Body Works";
} elseif ($currentProcess === 'process2_status') {
    $job = "Electrical Works";
} elseif ($currentProcess === 'process3_status') {
    $job = "Accessories Fitting Works";
}
 elseif ($currentProcess === 'process4_status') {
    $job = "Optional Installation";
}
    
// Insert data into the database with timestamp
$timestamp = date("Y-m-d H:i:s"); // Current date and time
$sql = "INSERT INTO records ( timestamp, vin_number, production_id, unit,job) VALUES ( '$timestamp', '$vinNumber', ' $productionId', '$unit','$job' )";    

if ($conn->query($sql) === TRUE) {
    // Database record inserted successfully
    $databaseMessage = "Data saved to the database.";

    // Retrieve the production_id and unit from the form or your session
    $productionId = $_POST['production_id']; // Adjust based on how you retrieve this value
    $unit = $_POST['unit'];

    // Update process1 to 'Complete' and process2 to 'Waiting' in the other table

    switch ($currentProcess) {
        case 'process1_status':
            $nextProcess = "process2_status";
            break;
        case 'process2_status':
            $nextProcess = "process3_status";
            break;
        case 'process3_status':
            $nextProcess = "process4_status";
            break;
        default:
            $nextProcess = '';
    }


    
        $updateStatusSql = "UPDATE production 
            SET `" . $currentProcess . "` = 'Completed'
            WHERE production_id = ? 
            AND unit = ?";
    

    $stmt = $conn->prepare($updateStatusSql);
    $stmt->bind_param("ss", $productionId, $unit);
    $stmt->execute();
} else {
    // Error inserting into the database
    $databaseMessage = "Error: " . $conn->error;
}


// Close the database connection
$conn->close();


// Create or get the necessary folders
$parentFolderID = createOrGetFolder($driveService, $parentFolderId, 'Order-' . $unit);
$PurchaseFolderName = createOrGetFolder($driveService, $parentFolderID,   'ODoo_Order_Id-' . $unit);

$folderId = $PurchaseFolderName;


if ($currentProcess === 'process1_status') {
    $QMSformpath = './QMSMechanicalfolder/OrderNo'.$unit.'-MechanicalQMS.pdf';
} elseif ($currentProcess === 'process2_status') {
    $QMSformpath = './QMSElectricalfolder/OrderNo' . $unit . '-ElectricalQMS .pdf';
} elseif ($currentProcess === 'process3_status') {
    $QMSformpath = './QMSInternalfolder/OrderNo' . $unit . '-InternalQMS .pdf';
}

// Check if attachment file is provided
if (isset($_FILES['file'])) {
    $numFiles = count($_FILES['file']['name']);

    // Loop through each uploaded file
    for ($i = 0; $i < $numFiles; $i++) {
        if ($_FILES['file']['error'][$i] === UPLOAD_ERR_OK) {
            // Handle each attachment file
            $attachmentFilePath = $_FILES['file']['tmp_name'][$i];
            $attachmentFileName = $_FILES['file']['name'][$i];


            $folderId = $PurchaseFolderName;

            // Define metadata for the attachment file
            $attachmentMetadata = new Google\Service\Drive\DriveFile([
                'name' => $attachmentFileName,
                'parents' => [$folderId],
            ]);

            // Upload the attachment file to Google Drive
            $uploadedFile = $driveService->files->create($attachmentMetadata, [
                'data' => file_get_contents($attachmentFilePath),
                'mimeType' => mime_content_type($attachmentFilePath),
                'uploadType' => 'multipart',
                'fields' => 'id, webViewLink' // Request webViewLink in addition to ID
            ]);
        }
    }
}

if (file_exists($QMSformpath)) {
    // Handle voice file
    $QMSformpathtemp = $QMSformpath;
    $QMSformname = basename($QMSformpathtemp);

    $folderId = $PurchaseFolderName;

    // Define metadata for the attachment file
    $attachmentMetadata = new Google\Service\Drive\DriveFile([
        'name' => $QMSformname,
        'parents' => [$folderId],
    ]);

    // Upload the attachment file to Google Drive
    $uploadedFile = $driveService->files->create($attachmentMetadata, [
        'data' => file_get_contents($QMSformpathtemp),
        'mimeType' => mime_content_type($QMSformpathtemp),
        'uploadType' => 'multipart',
        'fields' => 'id, webViewLink' // Request webViewLink in addition to ID
    ]);
    unlink($QMSformpathtemp);  
} 

    header('Location:./vendorsDashboard.php');









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



ob_end_flush(); // Flush the output buffer
