<div class="modal fade" id="placeOrder" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">

    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header ">
                <h5 class="modal-title" id="exampleModalLabel">Create Sale Order</h5>
                <button class="close" type="button" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-container">



                            <form role="form" action="" method="post" autocomplete="off">
                                <div class="form-horizontal">
                                    <div class="form-group">
                                        <label class="control-label">Order Name</label>
                                        <div class="">
                                            <input type="text" placeholder="Order Name" id="order_name" name="order_name" list="expense" class="form-control" id="default" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label ">Order Details</label>
                                        <div class="">
                                            <textarea placeholder="Order Details" name="order_details" class="form-control" id="order_details" cols="5" rows="5" required></textarea>
                                        </div>
                                    </div>
                                    <?php
                                    $sql = "SELECT * from customers  ORDER BY customer_id DESC";
                                    $info = $obj_admin->manage_all_info($sql);
                                    $serial  = 1;

                                    $num_row = $info->rowCount();

                                    if ($num_row == 0) {

                                        echo '<tr><td colspan="7" class="d-flex justify-content-center align-items-center">No Contacts were found</td></tr>';
                                    }

                                    while ($row = $info->fetch(PDO::FETCH_ASSOC)) {


                                    ?>
                                        <div class="form-group">
                                            <label class="control-label ">Customer</label>
                                            <div class="">
                                                <select class="form-control" name="customer_id" id="customer_id">
                                                    <option value="">Select Customer</option>
                                                    <option value="<?php echo $row['customer_id'] ?>"><?php echo $row['customer_name'] ?></option>
                                                </select>
                                            </div>
                                        </div>
                                    <?php } ?>


                                    <div class="form-group">
                                        <label for="" class="control-label">Trailer Model</label>
                                        <div>
                                            <select class="form-control" name="model_name" id="model_name">
                                                <option value="">Select Model with ATM</option>


                                                <option value="RN 2HSL-S,3200">RN 2HSL-S,3200</option>
                                                <option value="RN 2HSL-L400,3200">RN 2HSL-L400,3200</option>
                                                <option value="RN 2HSL-L500,3200">RN 2HSL-L500,3200</option>
                                                <option value="RN 2HSL-L600,3200">RN 2HSL-L600,3200</option>
                                                <option value="SN 2HAL-L600,3200">SN 2HAL-L600,3200</option>
                                                <option value="SN 2HAL-S,3500">SN 2HAL-S,3500</option>
                                                <option value="SN 2HAL-L860,3400">SN 2HAL-L860,3400</option>
                                                <option value="RN 3HAL-S,3800">RN 3HAL-S,3800</option>
                                                <option value="SN 2HAL-O 4900,3950">SN 2HAL-O 4900,3950</option>
                                                <option value="SN 3HAL-L500,3800">SN 3HAL-L500,3800</option>
                                                <option value="SN 2HAL-O 5106,3950">SN 2HAL-O 5106,3950</option>
                                                <option value="SN 2HAL-O 5475,3950">SN 2HAL-O 5475,3950</option>
                                                <option value="SN 2HGSN 7620,4500">SN 2HGSN 7620,4500</option>
                                                <option value="SN 2HSL-L400,3400">SN 2HSL-L400,3400</option>
                                                <option value="SN 2HSL-L500,3400">SN 2HSL-L500,3400</option>
                                                <option value="SN 2HSL-L600,3400">SN 2HSL-L600,3400</option>

                                            </select>
                                        </div>
                                    </div>

                                    <div>
                                        <label for="" class="control-label">Quantity</label>
                                        <input class="form-control" type="number" name="quantity" id="quantity">
                                    </div>

                                </div>
                                <button type="submit" name="newOrder" class="form-control btn btn-primary">Place Order</button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php

include 'conn.php';

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['newOrder'])) {

    $order_name = $_POST['order_name'];
    $order_details = $_POST['order_details'];
    $model_name = $_POST['model_name'];
    $customer_id = $_POST['customer_id'];
    $quantity = $_POST['quantity'];


    $sql = "INSERT INTO live_order (order_name,order_details,model_name,quantity,customer_id)VALUES (?,?,?,?,?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssis", $order_name, $order_details, $model_name, $quantity, $customer_id);

    if ($stmt->execute()) {
        echo "Order Placed";
        $stmt->close();
        $conn->close();
        header('Location:' . $_SERVER['HTTP_REFERER']);
    } else {
        echo "Error : " . $conn->error;
        $stmt->close();
        $conn->close();
        header('Location:' . $_SERVER['HTTP_REFERER']);
    }
}


?>



<!-- end of projects section -->