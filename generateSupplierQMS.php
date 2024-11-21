<?php

require_once 'vendor/autoload.php';
require './authentication.php';
include './conn.php';

// auth check
$user_id = $_SESSION['admin_id'];
$user_name = $_SESSION['name'];
$security_key = $_SESSION['security_key'];
if ($user_id == NULL || $security_key == NULL) {
    header('Location: ./index.php');
}

// check admin
$user_role = $_SESSION['user_role'];

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Debugging: Check if the script is receiving the file upload
if (isset($_FILES['signature_data'])) {
    $signatureFile = $_FILES['signature_data'];
    $uploadDirectory = __DIR__ . '/uploads/'; // Define the upload directory
  
    // Create uploads directory if it does not exist
    if (!is_dir($uploadDirectory)) {
        mkdir($uploadDirectory, 0755, true);
    }
  
    // Validate and move the uploaded file
    $allowedMimeTypes = ['image/png', 'image/jpeg', 'image/gif', 'image/jpg'];
    if (in_array($signatureFile['type'], $allowedMimeTypes)) {
        $uploadedFilePath = $uploadDirectory . basename($signatureFile['name']);
        if (move_uploaded_file($signatureFile['tmp_name'], $uploadedFilePath)) {
            echo "File uploaded successfully: " . $uploadedFilePath . "<br>";
            $signatureFilePath = $uploadedFilePath; // Save the path for later use
        } else {
            echo "File upload failed.<br>";
        }
    } else {
        echo "Invalid file type. Only PNG, JPEG, and GIF are allowed.<br>";
    }
  } else {
    echo "No file uploaded.<br>";
  }

// use TCPDF\TCPDF;

// Retrieve checkbox values from POST
$yescheckBox1 = isset($_POST['yescheckBox1']) ? 'Yes' : 'No';
$yescheckBox2 = isset($_POST['yescheckBox2']) ? 'Yes' : 'No';
$yescheckBox3 = isset($_POST['yescheckBox3']) ? 'Yes' : 'No';
$yescheckBox4 = isset($_POST['yescheckBox4']) ? 'Yes' : 'No';
$yescheckBox5 = isset($_POST['yescheckBox5']) ? 'Yes' : 'No';

$remark1 = isset($_POST['remarks1']) ? $_POST['remarks1'] : '';
$remark2 = isset($_POST['remarks2']) ? $_POST['remarks2'] : '';
$remark3 = isset($_POST['remarks3']) ? $_POST['remarks3'] : '';
$remark4 = isset($_POST['remarks4']) ? $_POST['remarks4'] : '';
$remark5 = isset($_POST['remarks5']) ? $_POST['remarks5'] : '';

$unit = isset($_GET['unit']) ? htmlspecialchars($_GET['unit']) : '';

// Create a new PDF instance
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

// Set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Your Name');
$pdf->SetTitle('Supplier QMS - ' . $unit); 
$pdf->SetSubject('Form Data');
$pdf->SetKeywords('Supplier, QMS, Form');

// Set default header and footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// set header and footer fonts
$pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
    require_once(dirname(__FILE__) . '/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set font
$pdf->SetFont('helvetica', 'B', 20);

// add a page
$pdf->AddPage();

$pdf->Write(0, 'SUPPLIER QMS - ORDER NO: ' . $unit, '', 0, 'L', true, 0, false, false, 0);

$pdf->SetFont('helvetica', '', 8);

$pdf->ln();

// NON-BREAKING TABLE (nobr="true")

$tbl = <<<EOD
<table border="1" cellpadding="5" cellspacing="2" nobr="true">
 <tr>
  <th width="50px" >Sr. No.</th>
  <th width="200px">Checkpoint</th>
  <th>Checkpoint Instruction</th>
  <th>Observation</th>
  <th>Remark if any</th>
  
 </tr>
 <tr>
 <td width="50px">1</td>
  <td width="200px">BUILD</td>
  <td>Does the build conform to the supplied engineering drawing?</td>
  <td>$yescheckBox1</td>
  <td>$remark1</td>
 </tr>
 <tr>
  <td>2</td>
  <td>FABRICATION</td>
  <td>Is the fabrication done to agreed Quality Assurance Plan?</td>
  <td>$yescheckBox2</td>
  <td>$remark2</td>
 </tr>
 <tr>
 <td>3</td>
 <td>MATERIAL</td>
 <td>Shall conform to Engineering drawing.</td>
 <td>$yescheckBox3</td>
 <td>$remark3</td>
</tr>
<tr>
<td>4</td>
<td>METAL COATING</td>
<td>Shall conform to Engineering drawing.</td>
<td>$yescheckBox4</td>
<td>$remark4</td>
</tr>
 <tr>
 <td>5</td>
 <td>CERTIFICATION</td>
 <td>Does all supplied components have CTA Number?</td>
 <td>$yescheckBox5</td>
 <td>$remark5</td>
</tr>
</table>
</div>
EOD;

$pdf->writeHTML($tbl, true, false, false, false, '');

// Add signature if present
if (isset($signatureFilePath) && file_exists($signatureFilePath)) {
    // Add some space before the signature
    $pdf->Ln(10);
  
    // Add signature title
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Write(0, 'Signature', '', 0, 'L', true, 0, false, false, 0);
  
    // Embed the image in the PDF
    $pdf->Image($signatureFilePath, $pdf->GetX(), $pdf->GetY(), 50, 20);
  }
  

// Create output folder if it doesn't exist
$outputFolder = 'QMSSupplierfolder';
if (!is_dir($outputFolder)) {
    mkdir($outputFolder);
}

// Save the PDF file to the output folder
$oodo_order_id = $_GET['unit'];
$pdfFilePath = $outputFolder . '/OrderNo' . $oodo_order_id . '-QMS_SupplierForm_Data.pdf';
$pdf->Output(__DIR__ . DIRECTORY_SEPARATOR . $pdfFilePath, 'F');

// Redirect the user to another page

include './google-drive/supplierUpload.php';

exit; // Ensure that subsequent code is not executed after the redirection

?>