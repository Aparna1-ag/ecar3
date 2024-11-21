<?php
ob_start(); // Start output buffering

require '../conn.php';


// Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';
session_start();

$user_id = $_SESSION['admin_id'];

$user_name = $_SESSION['name'];

$security_key = $_SESSION['security_key'];

if ($user_id == NULL || $security_key == NULL) {

  header('Location: index.php');
}


function generate_otp()
{
  $otp = '';
  for ($i = 1; $i <= 6; $i++) {
    $otp .= random_int(0, 9);
  }

  return $otp;
}

$otp = generate_otp();






if ($_GET['MFA'] === "accounts") {
  $sql_select = "SELECT email_id FROM accounts WHERE user_id = ?";
  $sql_update = "UPDATE accounts SET OTP = ?, OTP_Timestamp = CURRENT_TIMESTAMP WHERE user_id = ?";
} elseif ($_GET['MFA'] === "vendors") {
  $sql_select = "SELECT email_id FROM vendors WHERE vendor_id = ?";
  $sql_update = "UPDATE vendors SET OTP = ?, OTP_Timestamp = CURRENT_TIMESTAMP WHERE vendor_id = ?";
}
 elseif ($_GET['MFA'] === "suppliers") {
  $sql_select = "SELECT email_id FROM suppliers WHERE supplier_id = ?";
  $sql_update = "UPDATE suppliers SET OTP = ?, OTP_Timestamp = CURRENT_TIMESTAMP WHERE supplier_id = ?";
}

$stmt_update = $conn->prepare($sql_update);
$stmt_update->bind_param("si", $otp, $user_id); // Assuming user_id is an integer
$stmt_update->execute();
$stmt_update->close();

$stmt_select = $conn->prepare($sql_select);
$stmt_select->bind_param("i", $user_id); // Assuming user_id is an integer
$stmt_select->execute();
$stmt_select->bind_result($userMailId); // Bind the result to a variable
$stmt_select->fetch(); // Fetch the result
$stmt_select->close();

$conn->close();







$mail = new PHPMailer(true); // Passing `true` enables exceptions 



//Server settings 
$mail->SMTPDebug = 2; // Enable verbose debug output 
$mail->isSMTP(); // Set mailer to use SMTP 
$mail->Host = 'smtp.gmail.com'; // Specify main and backup SMTP servers 
$mail->SMTPAuth = true; // Enable SMTP authentication
$mail->Username   = "media.csaengineering@gmail.com";
$mail->Password   = "muxylpqlrbimecgn";
$mail->SMTPSecure = 'tls'; // Enable SSL encryption, TLS also accepted with port 465 
$mail->Port = 587; // TCP port to connect to 

//Recipients 
$mail->setFrom('noreply@gmail.com', 'CSA-OTP'); //This is the email your form sends From 
$mail->addAddress($userMailId); // Add a recipient address 

//Content 
$mail->isHTML(true); // Set email format to HTML 
$mail->Subject = "Your One-Time Password (OTP) for Account Verification";

$mail->Body = "
<html>
<head>
  <style>
    /* Add CSS styles for better alignment */
    body {
      font-family: Arial, sans-serif;
    }
    .container {
      max-width: 600px;
      margin: 0 auto;
    }
    .message {
      padding: 20px;
      background-color: #f7f7f7;
      border-radius: 5px;
    }
  </style>
</head>
<body>
  <div class='container'>
    <div class='message'>
      <p>Dear $user_name,</p>
      <p>Thank you for choosing our services. To ensure the security of your account, we require verification through a One-Time Password (OTP).</p>
      <p>Your 6 digit OTP is: <strong>$otp</strong></p>
      <p>Please enter this OTP on the verification page to complete the process. This OTP is valid for the next 10 minutes.</p>
      <p>If you did not request this OTP, please contact our support team immediately at <a href='mailto:media.csaengineering@gmail.com'>media.csaengineering@gmail.com</a></p>
      <p>Thank you for your cooperation.</p>
    </div>
  </div>
</body>
</html>
";




$mail->send();
if ($_GET['MFA'] === "accounts") {

  header('location:mfa.php?MFA=accounts');
} elseif ($_GET['MFA'] === "vendors") {

  header('location:mfa.php?MFA=vendors');
}
elseif ($_GET['MFA'] === "suppliers") {

  header('location:mfa.php?MFA=suppliers');
}
ob_end_flush(); // Flush the output buffer and send the output to the browser
