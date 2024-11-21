<?php
require './authentication.php';
include './INCLUDES/header.php';
$pagename = 'Production/QC';

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

$production_id = $_GET['productionId'];
$unit = $_GET['unit'];

?>


<head>
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


<script>
    document.addEventListener('DOMContentLoaded', function() {
        var myModal = new bootstrap.Modal(document.getElementById('exampleModalCenter'));
        myModal.show();
    });
</script>

<!-- Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered " role="document">
        <div class="modal-content px-5">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Vehicle Approval Checklist - ORDER NO: <?php echo htmlspecialchars($unit); ?> </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form method="POST" enctype="multipart/form-data" action="generateQMS.php?production_id=<?php echo $production_id ?>&unit=<?php echo $unit ?>" id="qmsForm" name="qmsForm">
                <table id="dataTable" >
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
                        <input type="hidden" name="production_id" value="<?php echo htmlspecialchars($production_id); ?>">
                        <input type="hidden" name="unit" value="<?php echo htmlspecialchars($unit); ?>">
                        <tr>
                            <td>1</td>
                            <td>COUPLING, SAFETY CHAIN AND OTHER COMPONENTS</td>
                            <td>Shall confirms to the Approved components data.</td>
                            <td>
                                <input type="checkbox" name="yescheckBox1" id="yescheckBox1">
                                <label for="yescheckBox">Yes</label>
                            </td>
                            <td><textarea class="form-control" name="remarks1" id="remarks" cols="10" rows="1"></textarea></td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>MECHANICAL BUILD OF VEHICLE</td>
                            <td>Shall Confirms to Mechanical installation checklist.</td>
                            <td>
                                <input type="checkbox" name="yescheckBox2" id="yescheckBox2">
                                <label for="yescheckBox">Yes</label>
                            </td>
                            <td><textarea class="form-control" name="remarks2" id="remarks" cols="10" rows="1"></textarea></td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>ELECTRICAL CONNECTIONS AND INSTALLATION</td>
                            <td>Shall Confirms to Electrical Installation Checklist.</td>
                            <td>
                                <input type="checkbox" name="yescheckBox3" id="yescheckBox3">
                                <label for="yescheckBox">Yes</label>
                            </td>
                            <td><textarea class="form-control" name="remarks3" id="remarks" cols="10" rows="1"></textarea></td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>EXTERNAL FITOUTS(D-SHACKLES,BELTS ETC)</td>
                            <td>Installed as per drawing specification.</td>
                            <td>
                                <input type="checkbox" name="yescheckBox4" id="yescheckBox4">
                                <label for="yescheckBox">Yes</label>
                            </td>
                            <td><textarea class="form-control" name="remarks4" id="remarks" cols="10" rows="1"></textarea></td>
                        </tr>
                        <tr>
                            <td>5</td>
                            <td>VISUAL INSPECTION</td>
                            <td>Should be clean and detailed.</td>
                            <td>
                                <input type="checkbox" name="yescheckBox5" id="yescheckBox5">
                                <label for="yescheckBox">Yes</label>
                            </td>
                            <td><textarea class="form-control" name="remarks5" id="remarks" cols="10" rows="1"></textarea></td>
                        </tr>
                        <tr>
                            <td>6</td>
                            <td>SAFETY</td>
                            <td>Is the vehicle safe for road use?</td>
                            <td>
                                <input type="checkbox" name="yescheckBox6" id="yescheckBox6">
                                <label for="yescheckBox">Yes</label>
                            </td>
                            <td><textarea class="form-control" name="remarks6" id="remarks" cols="10" rows="1"></textarea></td>
                        </tr>

                    </tbody>
                </table>
                <br>
                <label for="digital_signature">Upload Digital Signature:</label>
                    <input type="file" name="signature_data" id="signature_data" accept="image/*"><br>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="window.history.back()">Back</button>
                    <button type="submit" name="final" class="btn btn-primary">Finalise Unit</button>
                </div>
            </form>



        </div>
    </div>
</div>

<script>
var canvas = document.getElementById('signature_canvas');
var ctx = canvas.getContext('2d');
var signatureDataInput = document.getElementById('signature_data');


var drawing = false;
var lastX, lastY;

canvas.addEventListener('mousedown', function(e) {
    drawing = true;
    lastX = e.offsetX;
    lastY = e.offsetY;
});

canvas.addEventListener('mousemove', function(e) {
    if (drawing === true) {
        ctx.beginPath();
        ctx.moveTo(lastX, lastY);
        ctx.lineTo(e.offsetX, e.offsetY);
        ctx.strokeStyle = '#000'; // Signature color
        ctx.lineWidth = 2; // Signature thickness
        ctx.stroke();
        lastX = e.offsetX;
        lastY = e.offsetY;
    }
});

canvas.addEventListener('mouseup', function() {
    drawing = false;
});

// Function to clear the signature
document.getElementById('clear_signature').addEventListener('click', function() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
});

// Function to capture signature data and update hidden input field
function captureSignatureData(event) {
            event.preventDefault(); // Prevent form submission
            var signatureData = canvas.toDataURL(); // Convert canvas content to data URL (PNG by default)
            console.log('Signature Data:', signatureData); // Debugging statement
            signatureDataInput.value = signatureData; // Set hidden input field value to signature data

            // Manually submit the form after capturing the signature data
            document.getElementById('qmsForm').submit();
        }

        // Event listener to capture signature data when the form is submitted
        document.getElementById('qmsForm').addEventListener('submit', captureSignatureData);
    </script>


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
</script>


<?php include './INCLUDES/footer.php'; ?>