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
            margin-top: 30px;
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

        .modal-custom-width {
            max-width: 50%;
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
        <div class="modal-dialog modal-custom-width" role="document">
            <div class="modal-content px-5 pt-5 bg-gradient">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-light" id="exampleModalLabel"><?php echo "File Uploads"  ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php
                    $purchaseId = isset($_GET['purchase_id']) ? $_GET['purchase_id'] : null;
                    $unit = isset($_GET['unitNo']) ? $_GET['unitNo'] : null;
                    $modalName = isset($_GET['modal_name']) ? $_GET['modal_name'] : null;
                    ?>

                    <!-- Your HTML Form -->
                    <form action="./generateADRQMS.php?purchase_id=<?php echo $purchaseId ?>&unit=<?php echo $unit ?>&modal_name=<?php echo $modalName ?>&type=adr_qms" method="POST" enctype="multipart/form-data" id="qmsForm" name="qmsForm">

                        <label class="form-label" for="formFile">Work Photos/Videos</label>
                        <input type="file" name="attachment_file[]" id="formFile" class="form-control" accept="image/*;capture=camera" multiple>
                        <p class="mt-3 text-warning">* Take a photo or video of the completion work </p>
                        <p class="mt-3 text-warning">* Maximum File Size For Upload = 250MB </p>

                        <input type="hidden" name="modal_name" value="<?php echo htmlspecialchars($modalName); ?>">
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
                    <h6> &nbsp;&nbsp;APPROVED COMPONENTS DATA - ORDER NO: <?php echo htmlspecialchars($unit); ?> </h6>
                    <table>
                        <thead>
                            <tr>
                                <th>Sr. No.</th>
                                <th>Component</th>
                                <th>Specification</th>
                                <th>CTA Number</th>
                                <th>Observation</th>
                                <th>Remark if any</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                            <tr>
                                <td>1</td>
                                <td>BRAKING SYSTEM</td>
                                <td>Braking System shall comply with ADR 38/05-5.</td>
                                <td>CTA-061335 for 10" brake,CTA-061339 for 12" brake</td>
                                <td>
                                    <input type="checkbox" name="yescheckBox1" id="yescheckBox1">
                                    <label for="yescheckBox">Yes</label>
                                </td>
                                <td><textarea class="form-control" name="remarks1" id="remarks" cols="10" rows="1"></textarea></td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>LIGHTING SYSTEM</td>
                                <td>Lighting System shall comply with ADR 13/00</td>
                                <td>CTA-060303</td>
                                <td>
                                    <input type="checkbox" name="yescheckBox2" id="yescheckBox2">
                                    <label for="yescheckBox">Yes</label>
                                </td>
                                <td><textarea class="form-control" name="remarks2" id="remarks" cols="10" rows="1"></textarea></td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>MUD GUARDS</td>
                                <td>Mud guards shall comply with ADR 42/05- 17.2.4 or 17.2.5</td>
                                <td></td>
                                <td>
                                    <input type="checkbox" name="yescheckBox3" id="yescheckBox3">
                                    <label for="yescheckBox">Yes</label>
                                </td>
                                <td><textarea class="form-control" name="remarks3" id="remarks" cols="10" rows="1"></textarea></td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>TYRE PLACARD</td>
                                <td>Tyre Placard shall comply with ADR 42/05-20</td>
                                <td></td>
                                <td>
                                    <input type="checkbox" name="yescheckBox4" id="yescheckBox4">
                                    <label for="yescheckBox">Yes</label>
                                </td>
                                <td><textarea class="form-control" name="remarks4" id="remarks" cols="10" rows="1"></textarea></td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td>ELECTRICAL WIRING</td>
                                <td>Electrical Wiring shall comply with ADR 42/05-21</td>
                                <td></td>
                                <td>
                                    <input type="checkbox" name="yescheckBox5" id="yescheckBox5">
                                    <label for="yescheckBox">Yes</label>
                                </td>
                                <td><textarea class="form-control" name="remarks3" id="remarks" cols="10" rows="1"></textarea></td>
                            </tr>
                            <tr>
                                <td>6</td>
                                <td>AXLE CONFIGURATION</td>
                                <td>Axle Configuration shall comply with ADR 43/04-7</td>
                                <td>CTA-061335</td>
                                <td>
                                    <input type="checkbox" name="yescheckBox6" id="yescheckBox6">
                                    <label for="yescheckBox">Yes</label>
                                </td>
                                <td><textarea class="form-control" name="remarks3" id="remarks" cols="10" rows="1"></textarea></td>
                            </tr>
                            <tr>
                                <td>7</td>
                                <td>SUSPENSION</td>
                                <td>Suspension system shall comply with ADR 43/04-8</td>
                                <td></td>
                                <td>
                                    <input type="checkbox" name="yescheckBox7" id="yescheckBox7">
                                    <label for="yescheckBox">Yes</label>
                                </td>
                                <td><textarea class="form-control" name="remarks3" id="remarks" cols="10" rows="1"></textarea></td>
                            </tr>
                            <tr>
                                <td>8</td>
                                <td>VEHICLE MARKING</td>
                                <td>Vehicle Marking shall comply with ADR 61/03</td>
                                <td></td>
                                <td>
                                    <input type="checkbox" name="yescheckBox8" id="yescheckBox8">
                                    <label for="yescheckBox">Yes</label>
                                </td>
                                <td><textarea class="form-control" name="remarks3" id="remarks" cols="10" rows="1"></textarea></td>
                            </tr>
                            <tr>
                                <td>9</td>
                                <td>COUPLING</td>
                                <td>Coupling shall comply with ADR 62/02 - 8 to 12</td>
                                <td>CTA-060079 for 50mm coupling,CTA-060767 for 70mm coupling</td>
                                <td>
                                    <input type="checkbox" name="yescheckBox9" id="yescheckBox9">
                                    <label for="yescheckBox">Yes</label>
                                </td>
                                <td><textarea class="form-control" name="remarks3" id="remarks" cols="10" rows="1"></textarea></td>
                            </tr>
                            <tr>
                                <td>10</td>
                                <td>DRAWBAR</td>
                                <td>Drawbar shall comply with ADR 62/02 -14.2 and 14.4</td>
                                <td></td>
                                <td>
                                    <input type="checkbox" name="yescheckBox10" id="yescheckBox10">
                                    <label for="yescheckBox">Yes</label>
                                </td>
                                <td><textarea class="form-control" name="remarks3" id="remarks" cols="10" rows="1"></textarea></td>
                            </tr>
                            <tr>
                                <td>11</td>
                                <td>SAFETY CHAIN</td>
                                <td>Safety chain shall comply with ADR 62/02 -14.3</td>
                                <td>CTA-061234</td>
                                <td>
                                    <input type="checkbox" name="yescheckBox11" id="yescheckBox11">
                                    <label for="yescheckBox">Yes</label>
                                </td>
                                <td><textarea class="form-control" name="remarks3" id="remarks" cols="10" rows="1"></textarea></td>
                            </tr>
                            <tr>
                                <td>12</td>
                                <td>TYRES</td>
                                <td>Tyre shall comply with ADR 96/00-5 and ADR 95/00-5.3</td>
                                <td></td>
                                <td>
                                    <input type="checkbox" name="yescheckBox12" id="yescheckBox12">
                                    <label for="yescheckBox">Yes</label>
                                </td>
                                <td><textarea class="form-control" name="remarks3" id="remarks" cols="10" rows="1"></textarea></td>
                            </tr>
                        </tbody>
                    </table>
                    <br>
                    <label for="digital_signature">Upload Digital Signature:</label>
                    <input type="file" name="signature_data" id="signature_data" accept="image/*"><br>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success mt-3" type="submit">Submit</button>
                    </form>
                    <a class="btn btn-secondary mt-3" href="./liveOrders.php">Back</a>
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
    addCheckboxListener("yescheckBox6");
    addCheckboxListener("yescheckBox7");
    addCheckboxListener("yescheckBox8");
    addCheckboxListener("yescheckBox9");
    addCheckboxListener("yescheckBox10");
    addCheckboxListener("yescheckBox11");
    addCheckboxListener("yescheckBox12");
</script>



<?php include './INCLUDES/footer.php'  ?>