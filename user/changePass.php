<?php
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
<title>View Profile</title>

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
                        <a href="#">Profile</a>
                    </li>
                    <li class="breadcrumb-item active">Change Password</li>
                </ol>
                <hr>
                <div class="card">
                    <div class="card-header">
                        <b>Change Password</b>
                    </div>
                    <div class="card-body">
                        <!--Add User Form-->
                        <form method="POST">
                            <div class="form-group">
                                <label for="current">Current Password</label>
                                <input type="password" class="form-control" placeholder="Type current password" id="current" name="current" required><br>
                            </div>
                            <div class="form-group">
                                <label for="new">New Password</label>
                                <input type="password" class="form-control" placeholder="Type new password" name="new" id="new" required><br>
                            </div>
                            <div class="form-group">
                                <label for="retype">Retype New Password</label>
                                <input type="password" class="form-control" placeholder="Confirm Password" name="retype" id="retype" required><br>
                            </div>
                            <?php
                            $stmt = $db->prepare("SELECT * FROM user_profile WHERE user_id = ?");
                            $stmt->bind_param("i", $userid);
                            $stmt->execute();
                            $result = $stmt->get_result();

                            if ($result->num_rows == 1) {
                                $row = $result->fetch_assoc();
                                $currentPassword = $row['pswd'];
                            }

                            if (isset($_POST['submit'])) {
                                $currentPasswordInput = $db->real_escape_string($_POST['current']);
                                $newPassword = $db->real_escape_string($_POST['new']);
                                $retypePassword = $db->real_escape_string($_POST['retype']);

                                if (password_verify($currentPasswordInput, $currentPassword)) {
                                    if ($newPassword === $retypePassword) {
                                        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

                                        $stmt = $db->prepare("UPDATE user_profile SET pswd = ? WHERE user_id = ?");
                                        $stmt->bind_param("si", $hashedPassword, $userid);
                                        $result = $stmt->execute();

                                        if ($result) {
                                            echo '<div style="text-align: center;"><h5 style="color: green; font-size:16px;">Password Changed</h5></div>';
                                        } else {
                                            echo '<div style="text-align: center;"><h5 style="color: red; font-size:16px;">Password Change Failed</h5></div>';
                                        }
                                    } else {
                                        echo '<div style="text-align: center;"><h5 style="color: red; font-size:16px;">New Passwords do not match</h5></div>';
                                    }
                                } else {
                                    echo '<div style="text-align: center;"><h5 style="color: red; font-size:16px;">Current Password is Incorrect</h5></div>';
                                }
                            }
                            ?>
                            <button type="submit" name="submit" class="btn btn-success">Save Changes</button>
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