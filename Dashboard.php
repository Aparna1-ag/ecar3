<?php

include 'conn.php';
require './authentication.php';
include './INCLUDES/header.php';
$pagename = 'Dashboard';

include './INCLUDES/sidebar.php';
// auth check
$user_id = $_SESSION['admin_id'];
$user_name = $_SESSION['name'];
$security_key = $_SESSION['security_key'];
if ($user_id == NULL || $security_key == NULL) {
  header('Location: index.php');
}

if ($user_role === "vendor") {
  header('Location: VendorsDashboard.php');
}


// check admin
$user_role = $_SESSION['user_role'];


if (isset($_GET['delete_purchase'])) {
  $action_id = $_GET['purchase_id'];
  $sql = "DELETE FROM live_purchase WHERE purchase_id = :id";
  $sent_po = "Dashboard.php";
  $obj_admin->delete_data_by_this_method($sql, $action_id, $sent_po);
}


if (isset($_GET['update_success']) && $_GET['update_success'] == 'true') {
  echo '<script>
      setTimeout(function() {
          Swal.fire({
              title: "Success",
              text: "Purchase Updated!",
              icon: "success",
              position: "top-end",
              showConfirmButton: false,
              timer: 2000,
              toast: true,
          });
      }, 1000); // Adjust the delay (in milliseconds) as needed
  </script>';
}

if (isset($_GET['file_upload_success']) && $_GET['file_upload_success'] == 'true') {
  echo '<script>
      setTimeout(function() {
          Swal.fire({
              title: "Success",
              text: "File Uploaded Successfully",
              icon: "success",
              position: "top-end",
              showConfirmButton: false,
              timer: 2000,
              toast: true,
          });
      }, 1000); // Adjust the delay (in milliseconds) as needed
  </script>';
}
if (isset($_GET['upload_success']) && $_GET['upload_success'] == 'true') {
  echo '<script>
      setTimeout(function() {
          Swal.fire({
              title: "Success",
              text: "Order Placed! Upload succcessful",
              icon: "success",
              position: "top-end",
              showConfirmButton: false,
              timer: 2000,
              toast: true,
          });
      }, 1000); // Adjust the delay (in milliseconds) as needed
  </script>';
}

// Query to get the total number of vendors
$sql = "SELECT COUNT(*) AS totalVendors FROM vendors";
// Execute the query
$result = $conn->query($sql);
// Check if the query was successful
if ($result) {
  // Fetch the result as an associative array
  $row = $result->fetch_assoc();
  // Get the total number of vendors
  $totalVendors = $row['totalVendors'];
  // Free the result set
  $result->free();
} else {
  // Handle the error if the query fails
  echo "Error: " . $conn->error;
}


// Query to get the total number of customer
$sql = "SELECT COUNT(*) AS totalCustomers FROM customers";
// Execute the query
$results = $conn->query($sql);
// Check if the query was successful
if ($results) {
  // Fetch the result as an associative array
  $row = $results->fetch_assoc();
  $totalCustomers = $row['totalCustomers'];
  // Free the result set
  $results->free();
} else {
  // Handle the error if the query fails
  echo "Error: " . $conn->error;
}

// Query to get the total number of customer
$sql = "SELECT COUNT(*) AS totalProduction FROM production";
// Execute the query
$results = $conn->query($sql);
// Check if the query was successful
if ($results) {
  // Fetch the result as an associative array
  $row = $results->fetch_assoc();
  $totalProduction = $row['totalProduction'];
  // Free the result set
  $results->free();
} else {
  // Handle the error if the query fails
  echo "Error: " . $conn->error;
}

// Query to count the total number of items sold
$sql = "SELECT COUNT(*) AS totalSoldItems FROM production WHERE sold = 'yes'";
// Execute the query
$results = $conn->query($sql);
// Check if the query was successful
if ($results) {
  // Fetch the result as an associative array
  $row = $results->fetch_assoc();
  $totalSoldItems = $row['totalSoldItems'];
  // Free the result set
  $results->free();
} else {
  // Handle the error if the query fails
  echo "Error: " . $conn->error;
}

// Close the database connection
$conn->close();

?>

<style>



.numbers {
  font-size: 3.5rem;
}



</style>

<div style="width:100vw; margin-bottom: 8rem;">

<div class=" container d-flex justify-content-center py-2 bg-secondary bg-gradient shadow-lg text-uppercase" ><h2 class="m-auto text-light"  style="font-weight: 400" >  My Dashboard</h2> </div>

<div class=" mt-5 mx-auto " style="width:55%;">
  <div class="row d-flex justify-content-between">
    <div class="card col-4 py-3">
      <div class=" d-flex justify-content-center"><p class="fs-4">Total Vendors:</p></div>
      <div class="d-flex justify-content-center "> <div class="numbers text-primary fw-bold">  <?php echo $totalVendors; ?> </div>   </div>
    
    </div>

    <div class="card col-4 py-3">
      <div class=" d-flex justify-content-center"><p class="fs-4">Total Customers:</p></div>
      <div class="d-flex justify-content-center "> <div class="numbers text-primary fw-bold">  <?php echo $totalCustomers; ; ?> </div>   </div>
    
    </div>

    
  </div>


  <div class="row d-flex justify-content-between mt-3">
    <div class="card col-4 py-3">
      <div class=" d-flex justify-content-center"><p class="fs-4">Total Production:</p></div>
      <div class="d-flex justify-content-center "> <div class="numbers text-primary fw-bold">  <?php echo  $totalProduction; ?> </div>   </div>
    
    </div>

    <div class="card col-4 py-3">
      <div class=" d-flex justify-content-center"><p class="fs-4">Total No. of Items Sold:</p></div>
      <div class="d-flex justify-content-center "> <div class="numbers text-primary fw-bold">  <?php echo $totalSoldItems; ?> </div>   </div>
    
    </div>

    
  </div>






 
</div >

 <div class="d-flex justify-content-center container mt-4">
 <a href="javascript:;" class="btn btn-lg bg-gradient btn-primary m-auto align-middle fs-5  py-3" style="width:400px;" data-bs-toggle="modal" data-bs-target="#placePurchase">
                Create New Order
              </a>
   <a href="sale.php" class="btn btn-lg btn-secondary bg-gradient m-auto align-middle fs-5  py-3" style="width:400px;">  Go to Sales Tab </a>
   <a href="archived_orders.php" class="btn btn-lg bg-gradient m-auto align-middle fs-5  py-3 text-light" style="width:400px; background: #722424;">  See Archived Orders </a>

   </div>


</div>



  


  <!-- jQuery -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

  <?php

  include './INCLUDES/footer.php';
  ?>