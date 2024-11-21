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
$unit = $_POST['unit'];

// Verify values using var_dump
echo "Production ID: ";
var_dump($productionId);
echo "<br>";

echo "Unit: ";
var_dump($unit);
echo "<br>";

// Alternatively, you can use echo
echo "Production ID: " . $productionId . "<br>";
echo "Unit: " . $unit . "<br>";

if ($productionId !== null && $unit !== null) {
    // Prepare and execute the SQL query to update QC status
    $qc_status = "QC_DONE";
    $sql = "UPDATE production SET QC_Status = ? WHERE production_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $qc_status, $productionId);
    $stmt->execute();
    $stmt->close();



}


    // Perform folder operations here using retrieved data
    // Example: Create or get necessary folders in Google Drive
    $parentFolderID = createOrGetFolder($driveService, $parentFolderId,  'Order-' . $unit);
    $PurchaseFolderName = createOrGetFolder($driveService, $parentFolderID,   'Unit-' . $unit . '_QMS' );


$QMSformpath = './QMSfolder/OrderNo'.$unit.'-QMS_Form_Data.pdf'; // Assuming this is the correct path to your PDF file



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

header('Location:./production.php?qc_upload=true');

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
