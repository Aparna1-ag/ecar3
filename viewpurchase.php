<?php

include 'conn.php';

require './authentication.php';

include './INCLUDES/header.php';

$pagename = 'Edit Purchase';

include './INCLUDES/sidebar.php';

// auth check

$user_id = $_SESSION['admin_id'];

$user_name = $_SESSION['name'];

$security_key = $_SESSION['security_key'];

if ($user_id == NULL || $security_key == NULL) {

    header('Location: index.php');
}

// check admin

$user_role = $_SESSION['user_role'];

?>

<div class="container bg-white mt-6" style="border-radius: 20px;">

    <div class="row">

        <div class="table-responsive ">

            <table class="table">

                <tbody>

                    <?php

                    $purchase_id = $_GET['purchase_id'];

                    $sql = "SELECT * FROM live_purchase

                                    WHERE purchase_id = $purchase_id";

                    $info = $obj_admin->manage_all_info($sql);

                    $serial  = 1;

                    $num_row = $info->rowCount();

                    if ($num_row == 0) {

                        echo '<tr><td colspan="7" class="d-flex justify-content-center align-items-center">No Contacts were found</td></tr>';
                    }

                    while ($row = $info->fetch(PDO::FETCH_ASSOC)) {

                    ?>

<a class="btn btn-secondary my-5 " href="liveOrders.php"> &#8592; Back</a>

                        <div class="">

                            <tr class=" mt-2">

                                <td class="fw-bold">UNIT</td>

                                <td><?php echo $row['unit']; ?></td>

                            </tr>
                            <tr class=" mt-2">

                                <td class="fw-bold">Order Created Time Date </td>

                                <td><?php echo $row['purchase_timestamp']; ?></td>

                            </tr>
                            
                            <tr class=" mt-2">

                                <td class="fw-bold">Purchase Status</td>

                                <td> <?php 
                        $status_bg_color = "";
                        if ($row['purchase_status'] === "Received From Supplier" ) {
                          $status_bg_color = "darkgoldenrod"; 
                        } else  if ($row['purchase_status'] === "InProcess" ) {
                          $status_bg_color = "purple";
                        } else  if ($row['purchase_status'] === "Sent To Supplier" ) {
                          $status_bg_color = "darkcyan";
                         } else  if ($row['purchase_status'] === "Completed" ) {
                            $status_bg_color = "darkolivegreen";

                         }
                        
                         ?>
                        <span style="font-weight: 500; background: <?php  echo  $status_bg_color; ?>" class="badge text-capitalize rounded-pill "> <?php echo $row['purchase_status'] ?></span></td>

                            </tr>
                            <tr class=" mt-2">

                                <td class="fw-bold">Supplier Status</td>

                                <td>
                                    
                                <?php 
                        $supply_status_bg_color = "";
                        if ($row['supplier_status'] === "Incomplete" ) {
                           $supply_status_bg_color = "darkgoldenrod"; 
                        } else  if ($row['supplier_status'] === "Complete" ) {
                           $supply_status_bg_color = "green";
                        }
                        
                         ?>
                        <span style="font-weight: 500; background: <?php  echo   $supply_status_bg_color; ?>" class="badge text-capitalize rounded-pill "> <?php echo $row['supplier_status'] ?></span>
                            
                            
                            </td>

                            </tr>
                            <tr class=" mt-2">

                                <td class="fw-bold">Tracking Id </td>

                                <td><?php echo $row['tracking_id']; ?></td>

                            </tr>
                            <tr class=" mt-2">

                                <td class="fw-bold">Tracking Service Provider</td>

                                <td><?php echo $row['tracking_service']; ?></td>

                            </tr>
                            <tr class=" mt-2">

                                <td class="fw-bold">Attached Files</td>

                                <td><button class="btn btn-info bg-gradient" onclick="location.href='https://drive.google.com/drive/folders/<?php echo $row['folder_id']; ?>?usp=sharing'">View Files</button>
                                </td>

                            </tr>



                        </div>

                    <?php }  ?>

                </tbody>

            </table>

          

        </div>

    </div>

</div>

</div>

<?php include './INCLUDES/footer.php'  ?>

