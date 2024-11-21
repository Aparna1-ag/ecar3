<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.6/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.6/dist/sweetalert2.all.min.js"></script>
    <title>MFA</title>
</head>

<style>
    body {
        background-color: #0D6EFD;
        color: white;
    }

    .box {
        display: flex;
        height: 80vh;
        justify-content: center;
        align-items: center;
    }

    form input {
        display: inline-block;
        width: 5vh;
        height: 5vh;
        text-align: center;


    }

    form input:focus {
        width: 6vh;
        height: 6vh;
    }
</style>

<body>
    <?php
    ob_start();
    session_start();
    require '../CLASS/admin.php';
    $obj_admin = new appControl();
    if (isset($_GET['logout'])) {
        $obj_admin->admin_logout();
    }
    require '../conn.php';
    // auth check
    $user_id = $_SESSION['admin_id'];
    $user_name = $_SESSION['name'];
    $security_key = $_SESSION['security_key'];
    if ($user_id == NULL || $security_key == NULL) {
        header('Location: index.php');
    }
    // check admin
    $user_role = $_SESSION['user_role'];
    if (isset($_GET['logout'])) {
        $obj_admin->admin_logout();
    }

    if ($_GET['MFA'] === "accounts") {

        $sql_select = "SELECT email_id FROM accounts WHERE user_id = ?";
    } elseif ($_GET['MFA'] === "suppliers") {

        $sql_select = "SELECT email_id FROM suppliers WHERE supplier_id = ?";
    } elseif ($_GET['MFA'] === "vendors") {

        $sql_select = "SELECT email_id FROM vendors WHERE vendor_id = ?";
    }


    $stmt_select = $conn->prepare($sql_select);
    $stmt_select->bind_param("i", $user_id); // Assuming user_id is an integer
    $stmt_select->execute();
    $stmt_select->bind_result($userMailId); // Bind the result to a variable
    $stmt_select->fetch(); // Fetch the result
    $stmt_select->close();;

    if (isset($_SESSION['OTP'])) {

        header("Location: index.php");
    }

    ?>

    <script>
        let digitValidate = function(ele) {
            console.log(ele.value);
            ele.value = ele.value.replace(/[^0-9]/g, '');
        }

        let tabChange = function(val) {
            let ele = document.querySelectorAll('input');
            if (ele[val - 1].value != '') {
                ele[val].focus()
            } else if (ele[val - 1].value == '') {
                ele[val - 2].focus()
            }
        }
    </script>
    <div class="container ">

        <div class="box">
            <div class="">

                <div class="card-body">
                    <div class="d-flex">
                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                            <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                            <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1" />
                        </svg>
                        <h2 class="ms-3">Welcome <?php echo $user_name ?>,</h2>
                        <form method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                            <button type="submit" class="btn btn-danger  ms-5" name="logout">Logout</button>
                        </form>

                    </div>

                    <form class="mt-5" method="POST">
                        <p>Please enter the one time password
                            to verify your account <br> <span class="text-warning">A code has been sent to <?php echo $userMailId; ?>
                            </span> </p><br>
                        <input class="otp" name="v1" type="text" oninput='digitValidate(this)' onkeyup='tabChange(1)' maxlength=1>
                        <input class="otp" name="v2" type="text" oninput='digitValidate(this)' onkeyup='tabChange(2)' maxlength=1>
                        <input class="otp" name="v3" type="text" oninput='digitValidate(this)' onkeyup='tabChange(3)' maxlength=1>
                        <input class="otp" name="v4" type="text" oninput='digitValidate(this)' onkeyup='tabChange(4)' maxlength=1>
                        <input class="otp" name="v5" type="text" oninput='digitValidate(this)' onkeyup='tabChange(5)' maxlength=1>
                        <input class="otp" name="v6" type="text" oninput='digitValidate(this)' onkeyup='tabChange(6)' maxlength=1>

                        <br>
                        <button class="btn btn-success mt-5 " name="otpCheck">Submit</button>
                        <button class="btn btn-outline-secondary text-white mt-5 " onclick="resendOTP();" id="resend_otp">Resend OTP</button>

                    </form>
                </div>

            </div>
        </div>


    </div>

    <script>
        const resendOTP = () => {
            event.preventDefault();
            var params = new URLSearchParams(window.location.search);

            var MFAParam = params.get('MFA');


            if (MFAParam === "accounts") {
                window.location.href = './mfaMail.php?MFA=accounts';
            }

            if (MFAParam === "vendors") {
                window.location.href = './mfaMail.php?MFA=vendors';
            }
            if (MFAParam === "suppliers") {
                window.location.href = './mfaMail.php?MFA=suppliers';
            }
        };
    </script>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        include '../conn.php';

        if ($_GET['MFA'] === "accounts") {

            $sql = "SELECT OTP FROM accounts WHERE user_id = $user_id";
        } elseif ($_GET['MFA'] === "vendors") {

            $sql = "SELECT OTP FROM vendors WHERE vendor_id = $user_id";
        } elseif ($_GET['MFA'] === "suppliers") {

            $sql = "SELECT OTP FROM suppliers WHERE supplier_id = $user_id";
        }
        $info = $obj_admin->manage_all_info($sql);

        $serial  = 1;

        $num_row = $info->rowCount();

        while ($row = $info->fetch(PDO::FETCH_ASSOC)) {
            $generatedOTP = $row['OTP'];
        }




        if (isset($_POST['otpCheck'])) {
            $otp_value1 = $_POST['v1'];
            $otp_value2 = $_POST['v2'];
            $otp_value3 = $_POST['v3'];
            $otp_value4 = $_POST['v4'];
            $otp_value5 = $_POST['v5'];
            $otp_value6 = $_POST['v6'];

            $otp_value = $otp_value1 . $otp_value2 . $otp_value3 . $otp_value4 . $otp_value5 . $otp_value6;



            if ($otp_value == $generatedOTP) {
                $_SESSION['OTP'] = $otp_value;
                if ($_GET['MFA'] === "accounts") {

                    header('Location: ../Dashboard.php');
                } elseif ($_GET['MFA'] === "vendors") {

                    header('Location: ../vendorsDashboard.php');
                } elseif ($_GET['MFA'] === "suppliers") {

                    header('Location: ../supplierDashboard.php');
                }
                exit();
            } else {
                echo '
                
                <script>

                         Swal.fire({
                         icon: "error",
                         title: "Retry",
                            text: "OTP went wrong!",
  
                        });
                </script>
                
                
                
                ';
            }
        }
        if (isset($_POST['resend_otp'])) {
            echo "Resend OTP functionality triggered!";
        }
    }
    ?>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>

</html>