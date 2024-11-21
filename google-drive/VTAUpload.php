<?php
ob_start(); // Start output buffering
require '../authentication.php';


// auth check
$user_id = $_SESSION['admin_id'];
$user_name = $_SESSION['name'];
$security_key = $_SESSION['security_key'];
if ($user_id == NULL || $security_key == NULL) {
    header('Location: ../index.php');
}
// check admin
$user_role = $_SESSION['user_role'];


require __DIR__ . '/../vendor/autoload.php';

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
if (isset($_POST['QC'])) {

    // Sanitize and retrieve form data
    $productionId = $_GET['productionId'];
    $purchase_status = $_POST['status'];
    $sql = "SELECT unit FROM live_purchase WHERE purchase_id = $productionId";
    $stmt = $conn->query($sql);
    if ($stmt->num_rows > 0) {
        while ($row = $stmt->fetch_assoc()) {
            $unit = $row['unit'];
        }
    }
    $stmt->close();
    


    if (!empty($_FILES['file']['name'][0]) && is_array($_FILES['file']['tmp_name'])) {

        // Create or get the necessary folders
        $parentFolderID = createOrGetFolder($driveService, $parentFolderId,  'Order-' . $unit);
        $PurchaseFolderName = createOrGetFolder($driveService, $parentFolderID, 'Purchases');
        $folderId = $PurchaseFolderName;


        $sql = "SELECT warehouse_suppliers FROM live_purchase WHERE purchase_id = $productionId LIMIT 1";
        $info = $obj_admin->manage_all_info($sql);

        $serial  = 1;

        $num_row = $info->rowCount();


        while ($row = $info->fetch(PDO::FETCH_ASSOC)) {
            $suppliers = $row['warehouse_suppliers'];
        }

        $sql = "UPDATE live_purchase SET  purchase_status=? WHERE purchase_id=?";

        $stmt = $conn->prepare($sql);
        // Assuming $purchase_status is a string, adjust the data type if it's an integer
        $stmt->bind_param("ss",  $purchase_status, $productionId);

        $stmt->execute();

        $stmt->close();



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
                            header('Location:../production.php?update_success=true');
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
        $sql = "SELECT warehouse_suppliers FROM live_purchase WHERE purchase_id = $productionId LIMIT 1";
        $info = $obj_admin->manage_all_info($sql);

        $serial  = 1;

        $num_row = $info->rowCount();


        while ($row = $info->fetch(PDO::FETCH_ASSOC)) {
            $suppliers = $row['warehouse_suppliers'];
        }

        $sql = "UPDATE live_purchase SET  purchase_status=? WHERE purchase_id=?";

        $stmt = $conn->prepare($sql);
        // Assuming $purchase_status is a string, adjust the data type if it's an integer
        $stmt->bind_param("ss",  $purchase_status, $productionId);

        $stmt->execute();

        $stmt->close();

        header('Location:../Dashboard.php?update_success=true');
    }
} else {
    $uploadMessage = "Error uploading file.";
}










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