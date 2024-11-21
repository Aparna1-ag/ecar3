<?php
// check role
$user_role = $_SESSION['user_role'];
$user_name = $_SESSION['name'];

include 'addOrders.php';
include 'addCustomer.php';
include 'placePurchase.php';

?>

<style>
    .bg-primary-custom {
        background-color: #007FFF;
    }
</style>

<div class="min-height-300 bg-primary-custom position-absolute w-100 "></div>
<aside class="sidenav  bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4 " id="sidenav-main">
    <div class="sidenav-header d-flex justify-content-center align-items-center">
        <img src="https://eurofloat.com.au/cdn/shop/files/logo_new_160x.png?v=1633607473" alt="logo">
    </div>
    <hr class="horizontal dark mt-0">
        <div class="collapse navbar-collapse h-100" id="navbarSupportedContent">


            <ul class="navbar-nav">
                <?php if ($user_role == 1 || $user_role == 2 || $user_role == 3 || $user_role == 4) { ?>
                    <li class="nav-item">
                        <a class="nav-link active" href="Dashboard.php">
                            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="ni ni-tv-2 text-primary text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">Dashboard</span>
                        </a>
                    </li>
                <?php } ?>

                <?php if ($user_role == 'suppliers') { ?>
                    <li class="nav-item">
                        <a class="nav-link active" href="supplierDashboard.php">
                            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="ni ni-tv-2 text-primary text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="supplierrecord.php">
                            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="ni ni-single-copy-04 text-primary text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">Past Records</span>
                        </a>
                    </li>
                <?php } ?>

                <?php if ($user_role == 'vendor') { ?>
                    <li class="nav-item">
                        <a class="nav-link active" href="vendorsDashboard.php">
                            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="ni ni-tv-2 text-primary text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="vendorrecord.php">
                            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="ni ni-single-copy-04 text-primary text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">Past Records</span>
                        </a>
                    </li>

                <?php }



                if ($user_role == 4 || $user_role == 1) { ?>
                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#orders" aria-expanded="false" aria-controls="collapseTwo">
                        <i class="fas fa-fw fa-cog text-danger"></i>

                        <span>Procurement </span>

                    </a>
                    <div id="orders" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">

                        <div class="bg-white py-2 collapse-inner rounded mt-3">

                            <li class="nav-item">
                                <a href="javascript:;" class="nav-link " data-bs-toggle="modal" data-bs-target="#placePurchase">
                                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                        <i class="ni ni-fat-add text-danger text-sm opacity-10"></i>
                                    </div>
                                    <span class="nav-link-text ms-1">Create Order</span>
                                </a>
                            </li>
                            <!-- <li class="nav-item">
                                <a class="nav-link " href="warehouse.php">
                                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                        <i class="fas fa-fw fa-solid fa-warehouse text-primary text-sm opacity-10"></i>
                                    </div>
                                    <span class="nav-link-text ms-1">Warehouse/Supplier</span>
                                </a>
                            </li> -->
                        </div>
                    </div>
                <?php } ?>


                <?php if ($user_role == 2 || $user_role == 1) { ?>
                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#Production" aria-expanded="false" aria-controls="collapseTwo">
                        <i class="fas fa-fw fa-trailer text-primary"></i>
                        <span>Production</span>
                    </a>
                    <div id="Production" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded mt-3">
                            <li class="nav-item">
                                <a href="production.php" class="nav-link ">
                                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                        <i class="ni ni-fat-add text-danger text-sm opacity-10"></i>
                                    </div>
                                    <span class="nav-link-text ms-1">On-Going Orders</span>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link " href="completedOrders.php">
                                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                        <i class="fas fa-fw fa-solid fa-store text-primary text-sm opacity-10"></i>
                                    </div>
                                    <span class="nav-link-text ms-1">Completed Orders</span>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link " href="soldOrders.php">
                                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                        <i class="fas fa-fw fa-solid fa-store text-primary text-sm opacity-10"></i>
                                    </div>
                                    <span class="nav-link-text ms-1">Sold Orders</span>
                                </a>
                            </li>
                        </div>
                    </div>
                <?php }
                if ($user_role == 3 || $user_role == 1) { ?>
                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#customers" aria-expanded="false" aria-controls="collapseTwo">
                        <i class="ni ni-delivery-fast text-success text-sm opacity-10"></i>
                        <span>Sales</span>
                    </a>
                    <div id="customers" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded mt-3">
                            <li class="nav-item">
                                <a href="sale.php" class="nav-link">
                                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                        <i class="ni ni-fat-add text-info text-sm opacity-10"></i>
                                    </div>
                                    <span class="nav-link-text ms-1">Sale</span>
                                </a>
                            </li>


                        </div>
                    </div>
                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#Complaint" aria-expanded="false" aria-controls="collapseTwo">
                        <i class="fas fa-robot" style="color: #b83273;"></i> <span>Customer Complaint</span>
                    </a>

                    <div id="Complaint" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded mt-3">

                            <li class="nav-item">
                                <a href="customer_complaint.php" class="nav-link">
                                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                        <i class="ni ni-fat-add text-info text-sm opacity-10"></i>
                                    </div>
                                    <span class="nav-link-text ms-1">Customer Complaint</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="ResolvedComplaints.php" class="nav-link">
                                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                        <i class="ni ni-fat-add text-info text-sm opacity-10"></i>
                                    </div>
                                    <span class="nav-link-text ms-1">Resolved Complaint</span>
                                </a>
                            </li>

                        </div>
                    </div>

                <?php  } ?>

                <?php if ($user_role == 1) { ?>

                    <!-- <li class="nav-item">
                        <a class="nav-link " href="./supplierrecord.php">
                            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="ni ni-single-copy-04 text-primary text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">Supplier Record</span>
                        </a>
                    </li> -->

                    <li class="nav-item">
                        <a class="nav-link " href="./controlaccess.php">
                            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="ni ni-ui-04 text-info text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">Access Control</span>
                        </a>
                    </li>
                    <a class="nav-link" aria-expanded="false"></a>
            </ul>

        <?php } ?>
        </div>


</aside>
<?php include './INCLUDES/topnav.php'; ?>