<?php
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';
require '../PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();

if (!isset($_SESSION['user_id'])) {
    echo '<script>window.location.href = "https://tarasabay.000webhostapp.com/login.php";</script>';
    exit();
}

include('../db.php');

$userid = $_SESSION['user_id'];

?>

<!DOCTYPE html>
<html lang="en">
<title>Add Car</title>

<link rel="icon" href="../assets/img/logo.png" type="images" />

<?php include('vendor/inc/head.php'); ?>

<body id="page-top">
    <!--Start Navigation Bar-->
    <?php include("vendor/inc/nav.php"); ?>
    <!--Navigation Bar-->

    <div id="wrapper">

        <!-- Sidebar -->
        <?php include("vendor/inc/sidebar.php"); ?>
        <!--End Sidebar-->
        <div id="content-wrapper">

            <div class="container-fluid">
                <?php if (isset($succ)) { ?>
                    <!--This code for injecting an alert-->
                    <script>
                        setTimeout(function() {
                                swal("Success!", "<?php echo $succ; ?>!", "success");
                            },
                            100);
                    </script>

                <?php } ?>
                <?php if (isset($err)) { ?>
                    <!--This code for injecting an alert-->
                    <script>
                        setTimeout(function() {
                                swal("Failed!", "<?php echo $err; ?>!", "Failed");
                            },
                            100);
                    </script>

                <?php } ?>

                <!-- Breadcrumbs-->
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="#">Apply</a>
                    </li>
                    <li class="breadcrumb-item active">Add Car</li>
                </ol>
                <hr>
                <div class="card">
                    <div class="card-header">
                        <b>Car Registration</b>
                    </div>
                    <div class="card-body">
                        <!--Add User Form-->
                        <form method="POST">
                            <div class="form-group">
                                <label for="brand">Brand<span class="text-danger"> *</span></label>
                                <input type="text" class="form-control" placeholder="Enter Car Brand" name="brand" required><br>
                            </div>

                            <div class="form-group">
                                <label for="model">Model<span class="text-danger"> *</span></label>
                                <input type="text" class="form-control" placeholder="Enter Car Model" name="model" required><br>
                            </div>

                            <div class="form-group">
                                <label for="color">Color<span class="text-danger"> *</span></label>
                                <input type="text" class="form-control" placeholder="Enter Car Color" name="color" required><br>
                            </div>

                            <div class="form-group">
                                <label for="seat_count">Seat Count<span class="text-danger"> *</span></label>
                                <input type="number" class="form-control" placeholder="Enter Seat Count" name="seat_count" required><br>
                            </div>

                            <div class="form-group">
                                <label for="car_identity_num">License Plate Number<span class="text-danger"> *</span></label>
                                <input type="text" class="form-control" placeholder="Enter Plate Number" name="car_identity_num" required><br>
                            </div>

                            <div class="form-group">
                                <label for="cr_number">LTO Certificate of Registration (CR) Number<span class="text-danger"> *</span></label>
                                <input type="text" class="form-control" placeholder="Enter CR Number" name="cr_number" required><br>
                            </div>

                            <div class="form-group">
                                <label for="or_number">LTO Official Receipt (OR) Number<span class="text-danger"> *</span></label>
                                <input type="text" class="form-control" placeholder="Enter OR number" name="or_number" required><br>
                            </div>

                            <div class="form-group">
                                <label for="reg_exp_date">Registration Expiration Date<span class="text-danger"> *</span></label>
                                <input type="date" class="form-control" placeholder="Enter Registration Expiration Date" name="reg_exp_date" required><br>
                            </div>

                            <?php

                            if (isset($_POST['submit'])) {
                                include('../db.php');

                                // Get form data
                                $brand = $_POST["brand"];
                                $model = $_POST["model"];
                                $color = $_POST["color"];
                                $seat_count = $_POST["seat_count"];

                                $car_identity_num = $_POST["car_identity_num"];

                                $cr_number = !empty($_POST["cr_number"]) ? $_POST["cr_number"] : "";


                                $or_number = $_POST["or_number"];
                                $reg_exp_date = $_POST["reg_exp_date"];

                                // Insert user_identification data
                                // Insert car data
                                $sql = "INSERT INTO car (owner_id, brand, model, color, seat_count)
                                VALUES ('{$_SESSION['user_id']}', '$brand', '$model', '$color', '$seat_count')";
                                if ($db->query($sql) === TRUE) {
                                    $last_car_id = $db->insert_id;

                                    // Insert car_identification data
                                    $sql = "INSERT INTO car_identification (car_id, car_identity_num, cr_number, or_number, reg_exp_date) 
                                    VALUES ('$last_car_id', '$car_identity_num', '$cr_number', '$or_number', '$reg_exp_date')";
                                    if ($db->query($sql) === TRUE) {
                                        $mail = new PHPMailer(true);

                                        $email = $_SESSION['email'];

                                        try {

                                            $mail->SMTPDebug = 0;
                                            $mail->isSMTP();
                                            $mail->Host = 'smtp.gmail.com';
                                            $mail->SMTPAuth = true;
                                            $mail->Username = 'carpoolapp01@gmail.com';
                                            $mail->Password = 'wzspvmmnnxhtbuxd';
                                            $mail->SMTPSecure = 'tls';
                                            $mail->Port = 587;

                                            $mail->setFrom('noreply@tarasabay.com', 'TaraSabay PH');
                                            $mail->addAddress($email);
                                            $mail->addCustomHeader('X-Priority', '1');
                                            $mail->addCustomHeader('Importance', 'High');

                                            $mail->isHTML(true);
                                            $mail->Subject = 'Email Verification';
                                            $mail->Body = "
                                                <html>
                                                <head>
                                                <style>
                                                    body {
                                                    font-family: Arial, sans-serif;
                                                    font-size: 16px;
                                                    line-height: 1.6;
                                                    color: #444;
                                                    }
                                                    h1 {
                                                    font-size: 24px;
                                                    font-weight: bold;
                                                    color: #333;
                                                    margin: 0 0 30px;
                                                    text-align: center;
                                                    }
                                                    p {
                                                    margin: 0 0 20px;
                                                    }
                                                    a {
                                                    color: #0072C6;
                                                    text-decoration: none;
                                                    }
                                                    a:hover {
                                                    text-decoration: underline;
                                                    }
                                                    .container {
                                                    max-width: 600px;
                                                    margin: 0 auto;
                                                    }
                                                </style>
                                                </head>
                                                <body>
                                                <div class=\"container\">
                                                <h1>Car Verification Required for Your TaraSabay App</h1>
                                                <p>Dear valued user,</p>
                                                <p>Thank you for choosing TaraSabay to find rides or offer your own. To ensure the security of your account, we need to verify your email address before you can start using the app.</p>
                                                <p>Please go to this certain location: Location$213@12 on May 10, 2023 at 10:00 AM to deliver the necessary documents as verification listed below: </p><br>
                                                <ul>
                                                <li>Original copy of the Certificate of Registration (CR)</li>
                                                <li>Original copy of the Official Receipt (OR)</li>
                                                <li>Photocopy of the driver's valid government-issued ID</li>
                                                <li>Photocopy of the TIN (Tax Identification Number) ID</li>
                                                </ul>
                                                <p>If you have any questions or concerns, please don't hesitate to contact us at support@tarasabay.com.</p>
                                                <p>Best regards,</p>
                                                <p>TaraSabay PH Team</p>
                                                </div>
                                                </body>
                            
                                                </html>";
                                            $mail->send();
                                            echo "<div style=\"text-align: center; font-family: 'Poppins', sans-serif; background-color: #FFFFFF; padding: 20px; border-radius: 10px; max-width: 600px; margin: 0 auto;\">
                                                <h5 style=\"color: #4CAF50; font-size: 24px; margin-bottom: 20px;\">Car registration successful!</h5>
                                                <p style=\"color: #333333; font-size: 16px; margin-bottom: 20px;\">An email has been sent to your email address to verify your car registration.</p>
                                                <p style=\"color: #333333; font-size: 16px; margin-bottom: 10px;\">Please bring the following documents for car registration:</p>
                                                <ul style=\"list-style: disc; margin-left: 30px; color: #333333; font-size: 16px;\">
                                                    <li>Original copy of the Certificate of Registration (CR)</li>
                                                    <li>Original copy of the Official Receipt (OR)</li>
                                                    <li>Photocopy of the driver's valid government-issued ID</li>
                                                    <li>Photocopy of the TIN (Tax Identification Number) ID</li>
                                                </ul>
                                            </div>";
                                        } catch (Exception $e) {
                                            echo '<div style="text-align: center;">
                                            <h5 style="color: red">Error sending verification email: </h5>' . $mail->ErrorInfo . '
                                            </div>';
                                        }
                                    } else {
                                        echo "Error: " . $sql . "<br>" . $db->error;
                                    }
                                } else {
                                    echo "Error: " . $sql . "<br>" . $db->error;
                                }
                                $db->close();
                            }
                            ?>
                            <button type="submit" name="submit" class="btn btn-success">Register</button>
                        </form>
                        <!-- End Form-->
                    </div>
                </div>
                <hr>
                <!-- Sticky Footer -->
                <?php include("vendor/inc/footer.php"); ?>

            </div>
            <!-- /.content-wrapper -->

        </div>
        <!-- /#wrapper -->

        <!-- Scroll to Top Button-->
        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fas fa-angle-up"></i>
        </a>

        <!-- Logout Modal-->
        <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                        <a class="btn btn-danger" href="https://tarasabay.000webhostapp.com/logout.php">Logout</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bootstrap core JavaScript-->
        <script src="vendor/jquery/jquery.min.js"></script>
        <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

        <!-- Core plugin JavaScript-->
        <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

        <!-- Page level plugin JavaScript-->
        <script src="vendor/chart.js/Chart.min.js"></script>
        <script src="vendor/datatables/jquery.dataTables.js"></script>
        <script src="vendor/datatables/dataTables.bootstrap4.js"></script>

        <!-- Custom scripts for all pages-->
        <script src="vendor/js/sb-admin.min.js"></script>

        <!-- Demo scripts for this page-->
        <script src="vendor/js/demo/datatables-demo.js"></script>
        <script src="vendor/js/demo/chart-area-demo.js"></script>
        <!--INject Sweet alert js-->
        <script src="vendor/js/swal.js"></script>

</body>

</html>