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



<div class="container">



    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var myModal = new bootstrap.Modal(document.getElementById('file'));
            myModal.show();
        });




        document.addEventListener("DOMContentLoaded", function() {
            var videoDiv = document.getElementById("videoDiv")
            var selectPhoto = document.getElementById("selectedPhoto")
            var takePhotoBtn = document.getElementById("takePhoto");
            var choosePhotoBtn = document.getElementById("choosePhoto");
            var cameraStream; // Global variable to store the camera stream

            takePhotoBtn.addEventListener('click', function() {
                videoDiv.style.display = "block";
                selectPhoto.style.display = "none";

                // Get access to the user's camera
                navigator.mediaDevices.getUserMedia({
                        video: true
                    })
                    .then(function(stream) {
                        var video = document.getElementById('video');
                        video.srcObject = stream;
                        // Save the stream for later use
                        cameraStream = stream;
                    })
                    .catch(function(error) {
                        console.error('Error accessing the camera: ', error);
                    });

                // Capture button click event
                document.getElementById('captureButton').addEventListener('click', function() {
                    var videoDiv = document.getElementById('videoDiv');
                    var canvas = document.getElementById('canvas');
                    var context = canvas.getContext('2d');

                    // Draw the current frame from the video onto the canvas
                    context.drawImage(video, 0, 0, canvas.width, canvas.height);

                    // Convert the canvas content to a data URL representing the captured image
                    var dataURL = canvas.toDataURL('image/png');

                    // Display the image or send it to the server as needed
                    console.log('Captured Image:', dataURL);

                    // Show the videoDiv
                    videoDiv.style.display = 'block';
                });
            })

            choosePhotoBtn.addEventListener('click', function() {
                videoDiv.style.display = "none";
                selectPhoto.style.display = "block";

                // Stop the camera stream when switching to choosePhoto
                stopCamera();
            })

            // Function to stop the camera stream
            function stopCamera() {
                if (cameraStream) {
                    let tracks = cameraStream.getTracks();
                    tracks.forEach(track => track.stop());
                }
            }
        })
    </script>







    <!-- Modal -->
    <div class="modal fade" id="file" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>File Attachment</p>
                    <div id="options">
                        <button id="takePhoto" class="btn btn-primary">Take Photo</button>
                        <button id="choosePhoto" class="btn btn-primary">Choose Photo</button>
                    </div>

                    <div id="videoDiv" style="display: none;">
                        <video id="video" width="400" height="480" autoplay></video>
                        <button id="captureButton">Capture</button>
                        <canvas id="canvas" width="640" height="480" style="display: none;"></canvas>

                    </div>
                    <form method="POST" enctype="multipart/form-data">

                        <input class="form-control" type="file" name="selectedPhoto" id="selectedPhoto" style="display: none;">



                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="uploadFiles" class="btn btn-dark ">Upload</button>
                </div>
                </form>

            </div>
        </div>
    </div>





    <?php
    $folder = 'uploads/';
    @$files = $folder . basename($_FILES["selectedPhoto"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($files, PATHINFO_EXTENSION));
    $allowedExtensions = array("jpg", "jpeg", "png", "gif");

    if (isset($_POST['uploadFiles'])) {
        $check = getimagesize($_FILES["selectedPhoto"]["tmp_name"]);

        if ($check !== false) {
            // File is an image.
            echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            // File is not an image.
            echo "File is not an image.";
            $uploadOk = 0;
        }

        // Check file extension
        if (!in_array($imageFileType, $allowedExtensions)) {
            echo "Only JPG, JPEG, PNG, and GIF files are allowed.";
            $uploadOk = 0;
        }

        // Upload the file if all checks pass
        if ($uploadOk == 1) {
            if (move_uploaded_file($_FILES["selectedPhoto"]["tmp_name"], $files)) {
                echo "The file has been uploaded.";
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    }
    ?>





    <?php include './INCLUDES/footer.php'  ?>