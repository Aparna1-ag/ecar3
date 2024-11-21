<div class="modal fade  " id="placePurchase" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-l" role="document">
        <div class="modal-content">
            <div class="modal-header ">
                <h5 class="modal-title" id="exampleModalLabel">New Purchase</h5>
                <button class="close" type="button" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            <div class="modal-body ">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-container">


                            <form class="row g-3" role="form" action="./google-drive/upload.php" method="post" autocomplete="on" enctype="multipart/form-data">
                                <div class="form-horizontal">
                                    <!-- Subject -->
                                    <div class="">

                                        <div class="form-group ">
                                            <label class="control-label">Purchase Title</label>
                                            <div>
                                                <input type="text" placeholder="Purchase Title" id="mailSubject" name="mailSubject" list="expense" class="form-control" required>
                                            </div>
                                        </div>
                                        <!-- Content -->
                                        <div class="form-group" hidden>
                                            <label class="control-label">Content</label>
                                            <div>
                                                <textarea placeholder="Details for Purchase" name="mailContent" class="form-control" id="mailContent" cols="5" rows="5" required>Please login E-car Software for order details</textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">

                                        <!-- Select Warehouse/Suppliers -->
                                        <div class="form-group col-md-6 col-md-6 ">
                                            <label class="control-label">Select Warehouse/Suppliers</label>
                                            <div>
                                                <select class="form-control" name="supplier_id" id="supplier_id" required>
                                                    <option value="">Choose Warehouse/Suppliers</option>
                                                    <?php
                                                    $sql = "SELECT * from suppliers";
                                                    $result = $conn->query($sql);

                                                    if ($result->num_rows > 0) {
                                                        while ($row = $result->fetch_assoc()) {

                                                    ?>
                                                            <option value="<?php echo $row['supplier_id'] ?>"><?php echo $row['fullname'] ?></option>

                                                    <?php
                                                        }
                                                    }
                                                    $result->close();
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <!-- Quantity -->
                                        <div class="form-group col-md-6">
                                            <label class="control-label">Quantity</label>
                                            <div>
                                                <input type="number" class="form-control" name="unit" id="unit" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group col-md-12">
                                        <label class="control-label">ODOO Order Id</label>
                                        <div>
                                            <input type="number" class="form-control" name="odoo_order_id" id="odoo_order_id" required>
                                        </div>
                                    </div>


                                    <!-- Dynamic content container -->
                                    <div id="dynamicContentContainer" class="row"></div>
                                    <!-- File Attachment For Units -->
                                    <div class="form-group mt-2">
                                        <label class="control-label">File Attachment For Units</label>
                                        <input type="file" name="file[]" id="file" class="form-control" multiple required>
                                    </div>
                                </div>



                                <div class="d-flex justify-content-end ">
                                    <!-- Submit button -->
                                    <button data-bs-dismiss="modal" class=" btn btn-primary ">Close</button>
                                    <button type="submit" name="newPurchase" class=" btn btn-primary ms-3">Create Order</button>
                                </div>
                            </form>
                            <script>
                                // Show loading spinner when the form is submitted
                                $(document).on("submit", "form", function() {
                                    Swal.fire({
                                        title: "Uploading...",
                                        html: "Please wait while the files are being uploaded.",
                                        allowOutsideClick: false,
                                        onBeforeOpen: () => {
                                            Swal.showLoading();
                                        },
                                        showConfirmButton: false, // Remove the OK button
                                    });
                                });
                            </script>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>












<!-- end of projects section -->