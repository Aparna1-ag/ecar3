<?php
include 'conn.php';
require './authentication.php';
include './INCLUDES/header.php';
$pagename = 'Supplier Completion Form';

include './INCLUDES/sidebar.php';
// auth check
$user_id = $_SESSION['admin_id'];
$user_name = $_SESSION['name'];
$security_key = $_SESSION['security_key'];
if ($user_id == NULL || $security_key == NULL) {
    header('Location: index.php');
}
// check admin
$user_role = $_SESSION['user_role']
?>

<head>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <!-- Bootstrap JS and Popper.js -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        input[type="checkbox"] {
            margin-right: 5px;
        }

        input[type="text"] {
            width: 100%;
            box-sizing: border-box;
            padding: 5px;
        }
    </style>
</head>




<div class="container">
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var myModal = new bootstrap.Modal(document.getElementById('exampleModal'));
            myModal.show();
        });
    </script>


    <script>
        function validateForm() {
            var checkboxes = document.querySelectorAll('input[type="checkbox"]');
            var checkedCheckboxes = Array.from(checkboxes).filter(checkbox => checkbox.checked);

            if (checkedCheckboxes.length === checkboxes.length) {
                return true;
            } else {
                alert("Please check all checkboxes before submitting.");
                return false;
            }
        }
    </script>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><?php echo "File Uploads"  ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php
                    $purchaseId = isset($_GET['purchase_id']) ? $_GET['purchase_id'] : null;
                    $unit = isset($_GET['unitNo']) ? $_GET['unitNo'] : null;
                    ?>

                    <!-- Your HTML Form -->
                    <form action="./generateSupplierQMS.php?purchase_id=<?php echo $purchaseId ?>&unit=<?php echo $unit ?>&type=final_qms" method="POST" enctype="multipart/form-data" id="qmsForm" name="qmsForm">

                        <label class="form-label" for="formFile">Work Photos/Videos</label>
                        <input type="file" name="attachment_file[]" id="formFile" class="form-control" accept="image/*;capture=camera" multiple>
                        <p class="mt-3 text-warning">* Take a photo or video of the completion work </p>
                        <p class="mt-3 text-warning">* Maximum File Size For Upload = 250MB </p>

                        <input type="hidden" name="purchase_id" value="<?php echo htmlspecialchars($purchaseId); ?>">
                        <input type="hidden" name="unit" value="<?php echo htmlspecialchars($unit); ?>">

                        <!-- Display Upload Message -->
                        <?php

                        if (!empty($uploadMessage)) : ?>
                            <div class="mt-3 alert <?php echo strpos($uploadMessage, 'successfully') !== false ? 'alert-success' : 'alert-danger'; ?>">
                                <?php echo $uploadMessage; ?>
                            </div>
                        <?php endif; ?>
                </div>


                <!-- QMS Form for Process 1 -->
                <div class="mt-3">
                    <h6> &nbsp;&nbsp;Supplier QMS Form - ORDER NO: <?php echo htmlspecialchars($unit); ?></h6>
                    <table>
                        <thead>
                            <tr>
                                <th>Sr. No.</th>
                                <th>Checkpoint</th>
                                <th>Checkpoint Instruction</th>
                                <th>Observation</th>
                                <th>Remark if any</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                            <tr>
                                <td>1</td>
                                <td>BUILD</td>
                                <td>Does the build conform to the supplied engineering drawing?</td>
                                <td>
                                    <input type="checkbox" name="yescheckBox1" id="yescheckBox1">
                                    <label for="yescheckBox">Yes</label>
                                </td>
                                <td><textarea class="form-control" name="remarks1" id="remarks" cols="10" rows="1"></textarea></td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>FABRICATION</td>
                                <td>Is the fabrication done to agreed Quality Assurance Plan?</td>
                                <td>
                                    <input type="checkbox" name="yescheckBox2" id="yescheckBox2">
                                    <label for="yescheckBox">Yes</label>
                                </td>
                                <td><textarea class="form-control" name="remarks2" id="remarks" cols="10" rows="1"></textarea></td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>MATERIAL</td>
                                <td>Shall conform to Engineering drawing.</td>
                                <td>
                                    <input type="checkbox" name="yescheckBox3" id="yescheckBox3">
                                    <label for="yescheckBox">Yes</label>
                                </td>
                                <td><textarea class="form-control" name="remarks3" id="remarks" cols="10" rows="1"></textarea></td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>METAL COATING</td>
                                <td>Shall conform to Engineering drawing.</td>
                                <td>
                                    <input type="checkbox" name="yescheckBox4" id="yescheckBox4">
                                    <label for="yescheckBox">Yes</label>
                                </td>
                                <td><textarea class="form-control" name="remarks4" id="remarks" cols="10" rows="1"></textarea></td>
                            </tr>

                            <tr>
                                <td>5</td>
                                <td>CERTIFICATION</td>
                                <td>Does all supplied components have CTA Number?</td>
                                <td>
                                    <input type="checkbox" name="yescheckBox5" id="yescheckBox5">
                                    <label for="yescheckBox">Yes</label>
                                </td>
                                <td><textarea class="form-control" name="remarks5" id="remarks" cols="10" rows="1"></textarea></td>
                            </tr>
                        </tbody>
                    </table>
                    <br>
                    <label for="digital_signature">Upload Digital Signature:</label>
                    <input type="file" name="signature_data" id="signature_data" accept="image/*"><br>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary mt-3" type="submit">Submit</button>
                    </form>
                    <a class="btn btn-primary mt-3" href="supplierDashboard.php">Back</a>
                </div>


                <?php
                // ... (previous PHP code)

                echo '<script>
    // Show loading spinner when the form is submitted
    $(document).on("submit", "form", function () {
        Swal.fire({
            title: "Uploading...",
            html: "Please wait while the files are being uploaded.",
            allowOutsideClick: false,
            onBeforeOpen: () => {
                Swal.showLoading();
            },
        });
    });
</script>';

                // ... (rest of your PHP code)
                ?>

            </div>
        </div>
    </div>
</div>


<script>
    function addCheckboxListener(yesId, noId) {
        var yesCheckbox = document.getElementById(yesId);
        var noCheckbox = document.getElementById(noId);

        yesCheckbox.addEventListener("change", function() {
            if (yesCheckbox.checked) {
                noCheckbox.checked = false; // Uncheck "no" if "yes" is checked
            }
        });

        noCheckbox.addEventListener("change", function() {
            if (noCheckbox.checked) {
                yesCheckbox.checked = false; // Uncheck "yes" if "no" is checked
            }
        });
    }

    // Add event listeners for each pair of checkboxes
    addCheckboxListener("yescheckBox1");
    addCheckboxListener("yescheckBox2");
    addCheckboxListener("yescheckBox3");
    addCheckboxListener("yescheckBox4");
    addCheckboxListener("yescheckBox5");
</script>



<?php include './INCLUDES/footer.php'  ?>