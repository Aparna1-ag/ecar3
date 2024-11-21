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





<script>
    document.addEventListener('DOMContentLoaded', function() {
        var myModal = new bootstrap.Modal(document.getElementById('updateModel'));
        myModal.show();
    });
</script>




<div class="modal fade " id="updateModel" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">

    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-gradient bg-primary ">
                <h5 class="modal-title text-light" id="exampleModalLabel">Update Purchase</h5>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-container">

                            <?php

                            $purchase_id = $_GET['purchase_id'];


                            ?>

                            <form role="form" action="./google-drive/VTAUpload.php?productionId=<?php echo $purchase_id ?>" method="post" autocomplete="off" enctype="multipart/form-data">

                                <div class="form-horizontal">

                                    <div class="form-group">
                                        <label for="" class="control-label">Status</label>
                                        <div>
                                            <select class="form-control" name="status" id="status">
                                                <option value="Processing">Order In Processing</option>
                                                <option value="Completed">Order Confirmation Received</option>
                                                <option value="Delayed">Order Delayed</option>
                                                <option value="Rejected">Order Rejected</option>
                                            </select>
                                        </div>
                                    </div>

                                    <script>
                                        document.addEventListener('DOMContentLoaded', function() {
                                            var selectDiv = document.getElementById("status");
                                            var completedDiv = document.getElementById("completedDiv");

                                            // Function to toggle visibility of completedDiv based on selectDiv value
                                            function toggleCompletedDiv() {
                                                if (selectDiv.value === "Completed") {
                                                    // Create elements only if the value is "Completed"
                                                    var divElement = document.createElement('div');
                                                    divElement.className = 'form-group';

                                                    var labelElement = document.createElement('label');
                                                    labelElement.textContent = 'Approvals Files';
                                                    labelElement.className = 'form-label';

                                                    var inputElement = document.createElement('input');
                                                    inputElement.className = 'form-control';
                                                    inputElement.name = 'file[]';
                                                    inputElement.type = 'file';
                                                    inputElement.required = false;
                                                    inputElement.multiple = true;

                                                    // Append elements to the divElement
                                                    divElement.appendChild(labelElement);
                                                    divElement.appendChild(inputElement);

                                                    // Append the divElement to the completedDiv
                                                    completedDiv.appendChild(divElement);

                                                    // Create and append the paragraph
                                                    var paragraph = document.createElement('p');
                                                    paragraph.className = 'mt-3 text-warning';
                                                    paragraph.textContent = '* Copy of the Commercial Invoice plus a copy of the Rover VTA';
                                                    completedDiv.appendChild(paragraph);
                                                } else {
                                                    // If value is not "Completed", remove any existing elements in completedDiv
                                                    completedDiv.innerHTML = '';
                                                }
                                            }

                                            // Call toggleCompletedDiv on page load
                                            toggleCompletedDiv();

                                            // Add event listener for change event on selectDiv
                                            selectDiv.addEventListener('change', toggleCompletedDiv);
                                        });
                                    </script>



                                    <div id="completedDiv" class="form-group"></div>



                                    <div class="d-flex gap-2">
                                        <a class=" form-control btn btn-secondary" href="liveOrders.php">Back</a>
                                        <button type="submit" name="QC" class="form-control btn btn-success">Update purchase</button>
                                    </div>

                            </form>
                            <!-- <?php   ?> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<?php include './INCLUDES/footer.php'; ?>