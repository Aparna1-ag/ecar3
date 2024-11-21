<?php
ob_start();
session_start();
require '../CLASS/admin.php';
$obj_admin = new appControl();

if (isset($_GET['logout'])) {
  $obj_admin->admin_logout();
}


// // auth check
if (isset($_SESSION['admin_id'])) {
  $user_id = $_SESSION['admin_id'];
  $user_name = $_SESSION['admin_name'];
  $security_key = $_SESSION['security_key'];
  if ($user_id != NULL && $security_key != NULL) {
    header('Location: ../Dashboard.php');
    exit();
  }
}

//  Check if the login button is clicked
if (isset($_POST['login_btn'])) {
  // Check if the user is blocked
  if (isset($_SESSION['block_user']) && $_SESSION['block_user'] >= time()) {
    // Calculate the remaining time until the user can attempt to log in again
    $remaining_time = $_SESSION['block_user'] - time();

    // Display an error message with the remaining time
    $info = "Account is temporarily blocked. Please try again in " . gmdate("i:s", $remaining_time) . " minutes and seconds.";
  } else {
    // Check if the username and password are provided (add your validation logic here)
    if (isset($_POST['username']) && isset($_POST['admin_password'])) {
      // Verify the login credentials (add your verification logic here)
      $info = $obj_admin->admin_login_check($_POST);

      if ($info === "Login successful!") {
        // Clear the login attempt count and block status if login is successful
        unset($_SESSION['login_attempts']);
        unset($_SESSION['block_user']);
        header('Location: Dashboard.php');
        exit();
      }
      // else {
      //   // Increment and store login attempt count
      //   $_SESSION['login_attempts'] = isset($_SESSION['login_attempts']) ? ($_SESSION['login_attempts'] + 1) : 1;

      //   // Check if the maximum attempts are reached
      //   if ($_SESSION['login_attempts'] >= 3) {
      //     // Check if the user is not already blocked
      //     if (!isset($_SESSION['block_user'])) {
      //       // Set the block status and the block duration (e.g., 30 seconds)
      //       $_SESSION['block_user'] = time() + 30;
      //       $info .= " Too many unsuccessful attempts. Account is temporarily blocked. Please try again in " . gmdate("i:s", 30) . " minutes and seconds.";
      //     } else {
      //       // Display a different error message for blocked users
      //       $info = "Account is temporarily blocked. Please try again later.";
      //       // Remove all cookies
      //       foreach ($_COOKIE as $cookie_name => $cookie_value) {
      //         setcookie($cookie_name, '', time() - 3600, '/');
      //       }
      //     }
      //   } else {
      //     // Display a regular error message
      //     $info .= " Attempt: " . $_SESSION['login_attempts'];
      //   }
      // }
    }
  }
}

$page_name = "Login";
?>

<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Euro Float</title>
  <link href="https://fonts.googleapis.com/css?family=Karla:400,700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.materialdesignicons.com/4.8.95/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/css/login.css">
</head>

<body>
  <main class="d-flex align-items-center min-vh-100 py-3 py-md-0">
    <div class="container">
      <div class="card login-card">
        <div class="row no-gutters">
          <div class="col-md-5">
            <img src="assets/images/7112274.jpg" alt="login" class="login-card-img">
          </div>
          <div class="col-md-7">
            <div class="card-body">
              <div class="brand-wrapper">
                <img src="assets/images/euroFloats-Logo.avif" alt="logo" class="logo">
              </div>
              <p class="login-card-description">Sign into your account</p>
              <form method="POST">
                <?php if (isset($info)) { ?>
                  <h5 class="alert alert-danger"><?php echo $info; ?></h5>
                <?php } ?>
                <div class="form-group">
                  <label for="email" class="sr-only">Email</label>
                  <input type="text" name="username" id="username" class="form-control" placeholder="Email address">
                </div>
                <div class="form-group mb-4">
                  <label for="password" class="sr-only">Password</label>
                  <input type="password" name="admin_password" id="password" class="form-control" placeholder="***********">
                </div>
                <select name="login_type" id="" class="form-control">
                  <option value="euro_float">Euro Float Employee</option>
                  <option value="vendor_login">Vendor Login</option>
                  <option value="supplier_login">Supplier Login</option>
                </select>
                <!-- <input name="login_btn" id="login" class="btn btn-block login-btn mb-4" type="button" value="Login"> -->
                <button name="login_btn" id="login" class="btn btn-block login-btn mb-4">
                  Login
                </button>

              </form>
              <!--
              <a href="#!" class="forgot-password-link">Forgot password?</a>
              <p class="login-card-footer-text">Don't have an account? <a href="#!" class="text-reset">Register here</a></p>
              <nav class="login-card-footer-nav">
                <a href="#!">Terms of use.</a>
                <a href="#!">Privacy policy</a>
              </nav>
                -->
            </div>
          </div>
        </div>
      </div>

    </div>
  </main>
  <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
</body>

</html>