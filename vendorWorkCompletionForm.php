<?php
include 'conn.php';
require './authentication.php';
include './INCLUDES/header.php';
$pagename = 'File Attachment';

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

                    $productionId = isset($_GET['production_id']) ? $_GET['production_id'] : null;
                    $unit = isset($_GET['unitNo']) ? $_GET['unitNo'] : null;
                    $vinNumber = isset($_GET['vin_number']) ? $_GET['vin_number'] : null;


                   $currentProcess = $_GET['CurrentProcess'];


                    ?>

                    <!-- Your HTML Form -->
                    <form method="POST" enctype="multipart/form-data" <?php if ($currentProcess == 'process1') : ?> action="./generateMechanicalQMS.php?production_id=<?php echo $productionId ?>&unit=<?php echo $unit ?>" <?php elseif ($currentProcess == 'process2') : ?> action="./generateElectricalQMS.php?production_id=<?php echo $productionId ?>&unitNo=<?php echo $unit ?>" <?php else : ?> action="./generateInternalQMS.php?production_id=<?php echo $productionId ?>&unitNo=<?php echo $unit ?>" <?php endif; ?>  id="qmsForm" name="qmsForm">
                        <label class="form-label" for="formFile">Work Photos/Videos</label>
                        <input type="file" name="file[]" id="formFile" class="form-control" accept="image/*;capture=camera">
                        <p class="mt-3 text-warning">* Take a photo or video of the completion work </p>
                        <p class="mt-3 text-warning">* Maximum File Size For Upload = 250MB </p>

                        <!-- Hidden Input Fields for production_id and unit -->
                        <!-- Hidden Input Fields for vin_number, model_name, production_id, and unit -->
                        <input type="hidden" name="vin_number" value="<?php echo htmlspecialchars($vinNumber); ?>">
                        <input type="hidden" name="production_id" value="<?php echo htmlspecialchars($productionId); ?>">
                        <input type="hidden" name="unit" value="<?php echo htmlspecialchars($unit); ?>">
                        <input type="hidden" name="currentProcess" value="<?php echo htmlspecialchars($currentProcess); ?>">
                        
                        <!-- Display Upload Message -->
                        <?php

                        if (!empty($uploadMessage)) : ?>
                            <div class="mt-3 alert <?php echo strpos($uploadMessage, 'successfully') !== false ? 'alert-success' : 'alert-danger'; ?>">
                                <?php echo $uploadMessage; ?>
                            </div>
                        <?php endif; ?>
                </div>

                <?php if ($currentProcess == 'process1') : ?>
                    <!-- QMS Form for Process 1 -->
                    <div class="mt-3">
                        <h6> &nbsp;&nbsp;Mechanical QMS Form - ORDER NO: <?php echo htmlspecialchars($unit); ?> </h6>
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
                            <tbody id="tableBodyMechanical">


                                <tr>
                                    <td>1</td>
                                    <td>DRAWBAR AND CHASSIS ASSEMBLY</td>
                                    <td>Shall conform to Engineering drawing.</td>
                                    <td>
                                        <input type="checkbox" name="yescheckBox1" id="yescheckBox1">
                                        <label for="yescheckBox">Yes</label>
                                    </td>
                                    <td><textarea class="form-control" name="remarks1" id="remarks" cols="10" rows="1"></textarea></td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>BRAKING SYSTEM</td>
                                    <td>Shall conform to requirements.</td>
                                    <td>
                                        <input type="checkbox" name="yescheckBox2" id="yescheckBox2">
                                        <label for="yescheckBox">Yes</label>
                                    </td>
                                    <td><textarea class="form-control" name="remarks2" id="remarks" cols="10" rows="1"></textarea></td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>DOOR AND WINDOW SIDE PANEL ASSEMBLY</td>
                                    <td>Shall conform to Engineering drawing.</td>
                                    <td>
                                        <input type="checkbox" name="yescheckBox3" id="yescheckBox3">
                                        <label for="yescheckBox">Yes</label>
                                    </td>
                                    <td><textarea class="form-control" name="remarks3" id="remarks" cols="10" rows="1"></textarea></td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>AXEL ASSEMBLY</td>
                                    <td>Shall conform to requirements.</td>
                                    <td>
                                        <input type="checkbox" name="yescheckBox4" id="yescheckBox4">
                                        <label for="yescheckBox">Yes</label>
                                    </td>
                                    <td><textarea class="form-control" name="remarks4" id="remarks" cols="10" rows="1"></textarea></td>
                                </tr>
                                <tr>
                                    <td>5</td>
                                    <td>TAILGATE ASSEMBLY</td>
                                    <td>Shall conform to Engineering drawing.</td>
                                    <td>
                                        <input type="checkbox" name="yescheckBox5" id="yescheckBox5">
                                        <label for="yescheckBox">Yes</label>
                                    </td>
                                    <td><textarea class="form-control" name="remarks5" id="remarks" cols="10" rows="1"></textarea></td>
                                </tr>
                                <tr>
                                    <td>6</td>
                                    <td>UPPER AND FRONT COVER ASSEMBLY</td>
                                    <td>Shall conform to Engineering drawing.</td>
                                    <td>
                                        <input type="checkbox" name="yescheckBox6" id="yescheckBox6">
                                        <label for="yescheckBox">Yes</label>
                                    </td>
                                    <td><textarea class="form-control" name="remarks6" id="remarks" cols="10" rows="1"></textarea></td>
                                </tr>
                                <tr>
                                    <td>7</td>
                                    <td>MUDGUARD AND WHEEL ASSEMBLY</td>
                                    <td>Shall conform to Engineering drawing.</td>
                                    <td>
                                        <input type="checkbox" name="yescheckBox7" id="yescheckBox7">
                                        <label for="yescheckBox">Yes</label>
                                    </td>
                                    <td><textarea class="form-control" name="remarks7" id="remarks" cols="10" rows="1"></textarea></td>
                                </tr>
                                <tr>
                                    <td>8</td>
                                    <td>WHEELS</td>
                                    <td>Shall conform to requirements.</td>
                                    <td>
                                        <input type="checkbox" name="yescheckBox8" id="yescheckBox8">
                                        <label for="yescheckBox">Yes</label>
                                    </td>
                                    <td><textarea class="form-control" name="remarks8" id="remarks" cols="10" rows="1"></textarea></td>
                                </tr>
                                <tr>
                                    <td>9</td>
                                    <td>COUPLING, SAFETY CHAIN, AXLES, SPRING, SUSPENSION and OTHER COMPONENTS</td>
                                    <td>Shall conform to the Approved components data.</td>
                                    <td>
                                        <input type="checkbox" name="yescheckBox9" id="yescheckBox9">
                                        <label for="yescheckBox">Yes</label>
                                    </td>
                                    <td><textarea class="form-control" name="remarks9" id="remarks" cols="10" rows="1"></textarea></td>
                                </tr>
                                <tr>
                                    <td>10</td>
                                    <td>VIN PLATE AND CHASSIS VIN</td>
                                    <td>Shall conform to Engineering drawing and comply with ADR 61/03.</td>
                                    <td>
                                        <input type="checkbox" name="yescheckBox10" id="yescheckBox10">
                                        <label for="yescheckBox">Yes</label>
                                    </td>
                                    <td><textarea class="form-control" name="remarks10" id="remarks" cols="10" rows="1"></textarea></td>
                                </tr>
                            </tbody>
                            
                        </table>
                                                
                        <br>
                    <label for="digital_signature">Upload Digital Signature:</label>
                    <input type="file" name="signature_data" id="signature_data" accept="image/*"><br>
                    </div>
                <?php elseif ($currentProcess == 'process2') : ?>
                    <!-- Default fallback or additional conditions -->
                    <div class="mt-3">
                        <h6>&nbsp;&nbsp;Electrical QMS Form - ORDER NO: <?php echo htmlspecialchars($unit); ?> </h6>
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
                            <tbody id="tableBodyElectrical">
                                <tr>
                                    <td>1</td>
                                    <td>ELECTRICAL CONNECTIONS</td>
                                    <td>Shall Conform to standard AS/NZS 3000:2018.</td>
                                    <td>
                                        <input type="checkbox" name="yescheckBox1" id="yescheckBox1">
                                        <label for="yescheckBox">Yes</label>
                                    </td>
                                    <td><textarea class="form-control" name="remarks1" id="remarks" cols="10" rows="1"></textarea></td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>EXTERNAL FIT OUTS (related to electrical installation)</td>
                                    <td>Shall Conform to Customer Specification.</td>
                                    <td>
                                        <input type="checkbox" name="yescheckBox2" id="yescheckBox2">
                                        <label for="yescheckBox">Yes</label>
                                    </td>
                                    <td><textarea class="form-control" name="remarks2" id="remarks" cols="10" rows="1"></textarea></td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>240 VOLT CONNECTION</td>
                                    <td>Australian Electrician Certiified.</td>
                                    <td>
                                        <input type="checkbox" name="yescheckBox3" id="yescheckBox3">
                                        <label for="yescheckBox">Yes</label>
                                    </td>
                                    <td><textarea class="form-control" name="remarks3" id="remarks" cols="10" rows="1"></textarea></td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>VISUAL INSPECTION</td>
                                    <td>Live wires effectively protected and warning sign attached wherever required.</td>
                                    <td>
                                        <input type="checkbox" name="yescheckBox4" id="yescheckBox4">
                                        <label for="yescheckBox">Yes</label>
                                    </td>
                                    <td><textarea class="form-control" name="remarks4" id="remarks" cols="10" rows="1"></textarea></td>
                                </tr>
                            </tbody>
                        </table>
                        <br>
                    <label for="digital_signature">Upload Digital Signature:</label>
                    <input type="file" name="signature_data" id="signature_data" accept="image/*"><br>
                    </div>
                <?php elseif ($currentProcess == 'process3') : ?>
                    <!-- Default fallback or additional conditions -->
                    <div class="mt-3">
                        <h6>&nbsp;&nbsp;Internal Fit Out QMS Form - ORDER NO: <?php echo htmlspecialchars($unit); ?> </h6>
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
                            <tbody id="tableBodyInternal">
                                <tr>
                                    <td>1</td>
                                    <td>VISUAL INSPECTION</td>
                                    <td>All the internal fittings have been done in accordance to customer need.</td>
                                    <td>
                                        <input type="checkbox" name="yescheckBox1" id="yescheckBox1">
                                        <label for="yescheckBox">Yes</label>
                                    </td>
                                    <td><textarea class="form-control" name="remarks1" id="remarks" cols="10" rows="1"></textarea></td>
                                </tr>
                            </tbody>
                        </table>
                        <br>
                    <label for="digital_signature">Upload Digital Signature:</label>
                    <input type="file" name="signature_data" id="signature_data" accept="image/*"><br>
                    </div>
                <?php endif; ?>
                <div class="modal-footer">
                    <a class="btn btn-primary mt-3" href="vendorsDashboard.php">Back</a>
                    <button class="btn btn-primary mt-3" name="submitWork" type="submit">Submit</button>

                </div>

                </form>
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
</script>

<?php include './INCLUDES/footer.php'  ?>