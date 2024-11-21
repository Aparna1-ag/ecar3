<?php

require './authentication.php';
include './INCLUDES/header.php';
$pagename = 'Production/sold-orders';

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
include './conn.php';

?>





<div class=" container d-flex justify-content-center my-5 py-2 container  bg-gradient shadow-lg text-uppercase" style="background: #aa758e"><h2 class="m-auto text-light"  style="font-weight: 400">Sold Orders Tab</h2> </div>


    <div class="row">
        <div class="col-11 m-auto">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h5>Sold Orders Tab</h5>
                </div>
                <div class=" px-0 pt-0 pb-2">
                    <div class="table-responsive p-0 p-3">
                    <table class="table shadow-lg table-bordered table-sm" id="sold-orders-table" name="dataTable" style="border: 1px solid white">
                  <thead style="height:4.5rem;">
                  <tr class="bg-gradient text-light my-2 text-center" style="background: #aa758e">
                                    <th scope="col" class="align-middle " width="6%" style="font-weight:500;">Order No</th>
                                    <th scope="col" class="align-middle " width="6%" style="font-weight:500;">VIN</th>
                                    <th scope="col" class="align-middle " width="6%" style="font-weight:500;">Customer Name</th>
                                    <th scope="col" class="align-middle " width="6%" style="font-weight:500;">Customer Contact</th>
                                    <th scope="col" class="align-middle " width="6%" style="font-weight:500;">Order Date & Time</th>
                                    <th scope="col" class="align-middle " width="6%" style="font-weight:500;">Action</th>
                                </tr>
                            </thead>

                            <tbody class="text-center"  style="background: #dddddd70;">

                                <?php
$sql = "SELECT p.*, lp.customer 
FROM production p 
LEFT JOIN live_purchase lp ON p.production_id = lp.purchase_id AND p.unit = lp.unit 
WHERE p.sold = 'yes'";
                                $info = $obj_admin->manage_all_info($sql);

                                $serial  = 1;

                                $num_row = $info->rowCount();


                                while ($row = $info->fetch(PDO::FETCH_ASSOC)) {


                                ?>
                                    <tr style="height: 5rem;">
                                        <td class="align-middle">
                                            <div class="d-flex px-5 ">
                                                <h6 class="mb-0 text-sm"><?php echo $row['unit']; ?></h6>
                                            </div>
                                        </td>
                                        <td class="align-middle">
                                            <div class="d-flex px-2 ">
                                                <h6 class="mb-0 text-sm"><?php echo $row['vin_number']; ?></h6>
                                            </div>
                                        </td>
                                        <td class="align-middle">
                                            <div class="d-flex px-2 ">
                                                <h6 class="mb-0 text-sm"><?php echo $row['customer']; ?></h6>
                                            </div>
                                        </td>
                                        <td class="align-middle" >
                                                <div class="d-flex px-2 ">
                                                    <h6 class="mb-0 text-sm"><?php echo $row['customer_contact'] ?></h6>
                                                </div>
                                            </td>
                                         
                                            <td class="align-middle" >
                                                <div class="d-flex px-2 ">
                                                    <h6 class="mb-0 text-sm"><?php echo $row['purchase_timestamp'] ?></h6>
                                                </div>
                                            </td>
                                           
                                            <td class="align-middle pt-4 ">
                                            <a href="#" class="btn btn-link" data-bs-toggle="modal" data-bs-target="#editModal" onclick="populateEditModal(this)" data-sales-id="<?php echo $row['sales_id']; ?>" data-customer-name="<?php echo $row['customer_name']; ?>" data-customer-contact="<?php echo $row['customer_contact']; ?>" data-date="<?php echo $row['date']; ?>">
    <i class="fas fa-user-edit" style="color: #7a4edf;"></i>
</a>

<?php if ($row['customer_complaint'] === NULL) : ?>
    <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#infoModal" onclick="populateinfoModal(this)" data-customer-complaint="<?php echo $row['customer_complaint']; ?>" data-vin-number="<?php echo $row['vin_number']; ?>" data-sales-id="<?php echo $row['sales_id']; ?>" data-customer-name="<?php echo $row['customer_name']; ?>" data-customer-contact="<?php echo $row['customer_contact']; ?>" data-date="<?php echo $row['date']; ?>">
        Customer Complaint
    </button>
<?php endif; ?>

                                    <?php } ?>
</td>
                                    </tr>
                               
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <script>

$(document).ready(function () {
    var table = $('#sold-orders-table').DataTable({
        paging: true,
        searching: true,
        ordering: true
    });

    $('#customSearchInput').on('keyup', function () {
        table.search(this.value).draw();
    });
});


</script>
    </div>


<?php include './INCLUDES/footer.php'; ?>