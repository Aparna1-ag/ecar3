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
<div class=" mb-5 mt-2 container d-flex justify-content-center py-2 container  bg-gradient shadow-lg text-uppercase" style="background: #616eaa"><h2 class="m-auto text-light"  style="font-weight: 400" >Live Purchases Tab</h2> </div>

<div class="row">
      <div class="col-11 m-auto">
        <div class="card mb-4">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="pb-3">Live Purchases</h5>
            <div>
            <a href="javascript:;" class="btn btn-lg btn-primary m-auto align-middle fs-5  py-3" style="width:400px;" data-bs-toggle="modal" data-bs-target="#placePurchase">
                Create New Order
              </a>
</div>
</div>

<div class=" px-0 pt-0 pb-2">
            <div class=" table-responsive p-3">



            <table class="table shadow-lg table-bordered table-sm" id="dataTable" name="dataTable" style="border: 1px solid white">
                  <thead style="height:4.5rem;">
                  <tr class="bg-gradient  text-light my-2 text-center" style= "background: #616eaa">

                    <th scope="col" class="align-middle " width="6%" style="font-weight:500;">Purchase Title</th>
                    <th  class="align-middle " width="6%" style="font-weight:500;">ODOO Order Number</th>
                    <th scope="col" class="align-middle " width="6%" style="font-weight:500;">Order Status</th>
                    <th scope="col" class="align-middle " width="6%" style="font-weight:500;">Date of purchase</th>
                    <th scope="col" class="align-middle " width="6%" style="font-weight:500;">Tracking Code </th>
                    <th scope="col" class="align-middle " width="45%" style="font-weight:500;">Actions</th>
                  </tr>
                </thead>
                <?php
                $sql = "SELECT * FROM (
                SELECT*,
                ROW_NUMBER() OVER (PARTITION BY purchase_id ORDER BY unit DESC) AS row_num
                FROM
                 live_purchase
                   WHERE
                    purchase_status <> 'Completed'
                         ) AS subquery
                          WHERE
                             row_num = 1";



                $info = $obj_admin->manage_all_info($sql);

                $serial  = 1;

                $num_row = $info->rowCount();

                if ($num_row == 0) {
                  echo '<tr><td colspan="7" class="d-flex justify-content-center align-items-center">No Purchases were found</td></tr>';
                }

                while ($row = $info->fetch(PDO::FETCH_ASSOC)) {

                ?>
                  <tbody class="text-center"  style="background: #dddddd70;">

                    <tr style="height: 5rem;">

                      <td class="align-middle" >
                        <div class="d-flex px-2 ">
                        
                          <div class="">
                            <h6 class="mb-0 "><?php echo $row['subject'] ?></h6>
                          </div>
                        </div>
                      </td>

                      <td class="align-middle" style="width:5%"   width="5%">
                        <p class=" font-weight-bold mb-0"><?php echo $row['unit'] ?></p>
                      </td>

                      <td class="align-middle">
                        <span class=" font-weight-bold ms-2"><?php echo $row['purchase_status'] ?></span>
                      </td>
                      <td class="align-middle">
                        <span class=" font-weight-bold ms-2"><?php echo $row['purchase_timestamp'] ?></span>
                      </td>
                      <td class="align-middle">
                        <span class=" font-weight-bold ms-2"><?php echo $row['tracking_id'] ?></span>
                      </td>

                      <td class="align-middle d-flex align-content-center justify-content-center pt-4 "  >
                        <a href="viewpurchase.php?purchase_id=<?php echo $row['purchase_id']; ?>" class="ms-3  btn btn-primary btn-sm">
                          View Order
                        </a>
                        <a href="./universalfileupload.php?orderId=<?php echo $row['purchase_id'] ?>" class="btn btn-info btn-sm ms-3">
                          Upload File
                        </a>

                        <?php
                       // if ($row['supplier_status'] === 'Complete') :
                        ?>
                          <a href="Updatepurchase.php?purchase_id=<?php echo $row['purchase_id']; ?>" class="ms-3  btn btn-warning btn-sm">
                            Update Order Status </a>
                        <?php
                       // endif;
                        ?>
                        <a href="supplierDashboard.php?id=<?php echo $row['purchase_id']; ?>" class="ms-3 text-white btn btn-success btn-sm">
                          View Supplier Dashboard </a>

                          <a href="archived_orders.php" class="ms-3 text-white btn btn-danger btn-sm ">
                          Archive </a>


                      </td>
                    </tr>
                  <?php } ?>
                  </tbody>
              </table>
           
    </div>
    </div> 
</div>
 </div>
 </div>





  <!-- jQuery -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

  <?php

  include './INCLUDES/footer.php';
  ?>