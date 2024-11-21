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

$remark1 = isset($_POST['remarks1']) ? $_POST['remarks1'] : '';

$signatureData = isset($_POST['signature_data']) ? $_POST['signature_data'] : '';

$unit = isset($_GET['unit']) ? htmlspecialchars($_GET['unit']) : '';

// Create a new PDF instance
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

// Set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Your Name');
$pdf->SetTitle('QMS Form Data'. $unit); 
$pdf->SetSubject('Form Data');
$pdf->SetKeywords('QMS, Form, Data');

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

$pdf->Write(0, 'INTERNAL OUT-FITTING QMS - ORDER NO: ' . $unit, '', 0, 'L', true, 0, false, false, 0);

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
  <td width="200px">VISUAL INSPECTION</td>
  <td>All the internal fittings have been done in accordance to customer need.</td>
  <td>$yescheckBox1</td>
  <td>$remark1</td>
 </tr>
</table>
<div style="display: flex;">
    <div style="flex: 1;">
        <?php echo $tbl; ?>
    </div>
   <div style="flex: 1; margin-left: 20px;">
    <h3>Signature</h3>
    <img src="<?php echo $signatureData; ?>" alt="Signature">
</div>
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
$outputFolder = 'QMSInternalfolder';
if (!is_dir($outputFolder)) {
    mkdir($outputFolder);
}

// Save the PDF file to the output folder
$oodo_order_id = $_GET['unitNo'];
$pdfFilePath = $outputFolder . '/OrderNo' . $oodo_order_id . '-InternalQMS .pdf';
$pdf->Output(__DIR__ . DIRECTORY_SEPARATOR . $pdfFilePath, 'F');

// Redirect the user to another page
include './google-drive/vendorUpload.php';

exit; // Ensure that subsequent code is not executed after the redirection

?>
