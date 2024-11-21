<?php
require __DIR__ . '/../vendor/autoload.php';
include '../conn.php';

use Google\Client;
use Google\Service\Drive;
use GuzzleHttp\Client as GuzzleClient;

// Function to initialize Google Client
function getClient()
{
    $client = new Google\Client();
    $client->setApplicationName('Your Application Name');
    $client->setScopes(Google\Service\Drive::DRIVE_FILE);
    $client->setAuthConfig('./csa-engineering-402307-8b9d357f3235.json');
    $client->setAccessType('offline');

    // Get access token using service account credentials
    $client->fetchAccessTokenWithAssertion();

    return $client;
}

if (isset($_POST['universal_file_upload'])) {

    echo $orderId = $_POST['orderId'];
    $sql = " SELECT folder_id FROM live_purchase WHERE purchase_id = $orderId ";
    $results = $conn->query($sql);
    // Check if the query was successful
    if ($results) {
        // Fetch the result as an associative array
        $row = $results->fetch_assoc();
        $folderId = $row['folder_id'];
        // Free the result set
        $results->free();
    } else {
        // Handle the error if the query fails
        echo "Error: " . $conn->error;
    }


    // Initialize Google Client and Drive Service
    $client = getClient();
    $service = new Google\Service\Drive($client);

    foreach ($_FILES['file']['tmp_name'] as $key => $uploadedFilePath) {
        if (!empty($uploadedFilePath)) {
            $originalFileName = $_FILES['file']['name'][$key];

            // Define metadata for the file
            $fileMetadata = new Drive\DriveFile([
                'name' => $originalFileName,
                'parents' => [$folderId],
            ]);

            // Upload the file
            try {
                $file = new Google\Service\Drive\DriveFile();
                $result = $service->files->create($fileMetadata, [
                    'data' => file_get_contents($uploadedFilePath),
                    'mimeType' => mime_content_type($uploadedFilePath),
                    'uploadType' => 'multipart',
                    'fields' => 'id',
                ]);

                if ($result->id) {
                    header('location:' . $_SERVER['HTTP_REFERER'] . '&?file_upload_success=true');
                } else {
                    echo "Failed to upload file: " . $originalFileName . "\n";
                }
            } catch (Exception $e) {
                echo "Error uploading file: " . $e->getMessage() . "\n";
            }
        }
    }
}
