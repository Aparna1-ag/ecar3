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

                        <div class="mt-6 ">

                            <tr class="bg-primary text-white mt-2">

                                <td class="fw-bold">UNIT</td>

                                <td><?php echo $row['unit']; ?></td>

                            </tr>
                            <tr class="bg-primary text-white mt-2">

                                <td class="fw-bold">Order Created Time Date </td>

                                <td><?php echo $row['purchase_timestamp']; ?></td>

                            </tr>
                            
                            <tr class="bg-primary text-white mt-2">

                                <td class="fw-bold">Purchase Status</td>

                                <td><?php echo $row['purchase_status']; ?></td>

                            </tr>
                            <tr class="bg-primary text-white mt-2">

                                <td class="fw-bold">Supplier Status</td>

                                <td><?php echo $row['supplier_status']; ?></td>

                            </tr>
                            <tr class="bg-primary text-white mt-2">

                                <td class="fw-bold">Tracking Id </td>

                                <td><?php echo $row['tracking_id']; ?></td>

                            </tr>
                            <tr class="bg-primary text-white mt-2">

                                <td class="fw-bold">Tracking Service Provider</td>

                                <td><?php echo $row['tracking_service']; ?></td>

                            </tr>
                            <tr class="bg-primary text-white mt-2">

                                <td class="fw-bold">Attached Files</td>

                                <td><button class="btn btn-primary" onclick="location.href='https://drive.google.com/drive/folders/<?php echo $row['folder_id']; ?>?usp=sharing'">View Files</button>
                                </td>

                            </tr>



                        </div>

                    <?php }  ?>

                </tbody>

            </table>

            <a class="btn btn-primary" href="Dashboard.php">Back</a>

        </div>

    </div>

</div>

</div>

<?php include './INCLUDES/footer.php'  ?>

