<div class="modal fade" id="addCustomer" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="false" data>

    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header ">
                <h5 class="modal-title" id="exampleModalLabel">New Customer</h5>
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
                                        <label class="control-label">Full Name</label>
                                        <div class="">
                                            <input type="text" id="name" name="name" list="expense" class="form-control" id="default" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label ">Email Id</label>
                                        <div class="">
                                            <input type="email" name="email" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="" class="control-label">Contact Number</label>
                                        <div>
                                            <input type="text" name="contact_number" id="contact_number" class="form-control" >
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="" class="control-label">Address</label>
                                        <div>
                                            <textarea name="address" id="address" cols="30" rows="10" class="form-control"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" name="addCustomer" class="form-control btn btn-primary">Add Customer</button>
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

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['addCustomer'])) {

    $customer_name = $_POST['name'];
    $customer_email = $_POST['email'];
    $customer_ph = $_POST['contact_number'];
    $customer_address = $_POST['address'];

    $sql = "INSERT INTO customers (customer_name,customer_email,customer_ph,customer_address)VALUES (?,?,?,?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $customer_name, $customer_email, $customer_ph, $customer_address);

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