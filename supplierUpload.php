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
$purchaseId = $_POST['purchase_id'];
$unit = $_POST['unit'];



// Insert data into the database with timestamp
$timestamp = date("Y-m-d H:i:s"); // Current date and time
$sql = "INSERT INTO supplierrecords ( timestamp, purchase_id, unit) VALUES ( '$timestamp', ' $purchaseId', '$unit' )";

if ($conn->query($sql) === TRUE) {
    // Database record inserted successfully
    $databaseMessage = "Data saved to the database.";

    // Retrieve the production_id and unit from the form or your session
    $purchaseId = $_POST['purchase_id']; // Adjust based on how you retrieve this value
    $unit = $_POST['unit'];
    $supplier_status = "Complete";
    $purchase_status = "Processed";

    $sqlUpdate = "UPDATE live_purchase SET supplier_status = ?, purchase_status = ? WHERE purchase_id = ? AND unit = ?";

    $stmt = $conn->prepare($sqlUpdate);
    $stmt->bind_param("ssss", $supplier_status, $purchase_status, $purchaseId, $unit);
    $stmt->execute();
} else {
    // Error inserting into the database
    $databaseMessage = "Error: " . $conn->error;
}
// Close the database connection
$conn->close();

// Create or get the necessary folders
$parentFolderID = createOrGetFolder($driveService, $parentFolderId,  'Order' . $purchaseId);

$PurchaseFolderName = createOrGetFolder($driveService, $parentFolderID,  'Unit-' . $unit . '_PurchaseId' . $purchaseId);

$QMSformpath = './QMSSupplierfolder/QMS_SupplierForm_Data .pdf';

// Check if attachment file is provided
if (isset($_FILES['attachment_file'])) {
    $numFiles = count($_FILES['attachment_file']['name']);

    // Loop through each uploaded file
    for ($i = 0; $i < $numFiles; $i++) {
        if ($_FILES['attachment_file']['error'][$i] === UPLOAD_ERR_OK) {
            // Handle each attachment file
            $attachmentFilePath = $_FILES['attachment_file']['tmp_name'][$i];
            $attachmentFileName = $_FILES['attachment_file']['name'][$i];


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

    header('Location:./supplierDashboard.php');

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
