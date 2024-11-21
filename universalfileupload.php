<?php

include 'conn.php';
require './authentication.php';
include './INCLUDES/header.php';
$pagename = 'fileUpload';

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

if (isset($_GET['file_upload_success']) && $_GET['file_upload_success'] == 'true') {
    echo '<script>
      
  </script>';
}

?>

<script>
    $(document).ready(function() {
        $('#fileupload').modal('show');
    });
</script>
<div class="modal fade  " id="fileupload" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-l" role="document">
        <div class="modal-content">
            <div class="modal-header ">
                <h5 class="modal-title" id="exampleModalLabel">File Upload</h5>
                <button class="close" type="button" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            <div class="modal-body ">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-container">


                            <form class="row g-3" role="form" action="./google-drive/universal_file_upload.php" method="post" autocomplete="on" enctype="multipart/form-data">
                                <div class="form-horizontal">




                                    <!-- File Attachment For Units -->
                                    <div class="form-group mt-2">
                                        <label class="control-label">File Attachment For Units</label>
                                        <input type="file" name="file[]" id="file" class="form-control" multiple required>
                                        <input type="text" name="orderId" value="<?php echo $_GET['orderId']; ?>" hidden>

                                    </div>
                                </div>




                                <div class="d-flex justify-content-end ">
                                    <!-- Submit button -->
                                    <button class="btn btn-primary" onclick="window.history.go(-2)">Go Back</button>
                                    <button type="submit" name="universal_file_upload" class=" btn btn-primary ms-3">Upload</button>
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





<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>


<?php

include './INCLUDES/footer.php';
?>