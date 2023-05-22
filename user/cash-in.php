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
<title>Cash-in</title>

<link rel="icon" href="../assets/img/logo.png" type="images" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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
                        <a href="#">Trasactions</a>
                    </li>
                    <li class="breadcrumb-item active">Cash-In</li>
                </ol>
                <hr>
                <!-- Icon Cards-->
                <div class="row">
                    <div class="col-xl-3 col-sm-6 mb-3">
                        <div class="card text-white" style="background-color: #EAAA00;">
                            <div class="card-body">
                                <div class="card-body-icon">
                                    <i class="fas fa-fw fa-ticket-alt"></i>
                                </div>
                                <?php
                                // Code for counting the ticket balance by user ID
                                $result = $db->query("SELECT acc_balance FROM user_profile WHERE user_id = '$userid'");
                                $ticketBalance = $result->fetch_row()[0];
                                ?>
                                <div class="mr-5"><span class="badge" style="background-color: #EAAA00;"><i class="fa fa-ticket"></i>&nbsp;&nbsp;<?php echo $ticketBalance; ?></span> Ticket Balance</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <b>GCash: </b><em>Cash-In</em>
                    </div>
                    <div class="card-body">
                        <!-- Add User Form -->
                        <form method="POST">

                            <label for="reference" class="form-label"> Reference Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="reference" minlength="8" maxlength="8" id="reference" required>
                            <br>
                            <label for="mobile_no" class="form-label"> GCash Mobile Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="mobile_no" id="mobile_no" minlength="11" maxlength="11" required>
                            <br>
                            <label for="amount" class="form-label">Amount</label>
                            <select class="form-control" name="amount" id="amount" aria-label="Default select example">
                                <option value="50" selected>₱50.00</option>
                                <option value="100">₱100.00</option>
                                <option value="250">₱250.00</option>
                                <option value="500">₱500.00</option>
                            </select>
                            <br>
                            <label for="tickets" class="form-label">Tickets</label>
                            <input type="text" value="40" name="tickets" id="tickets" class="form-control" readonly>

                            <script>
                                $(document).ready(function() {
                                    $('select').on('change', function() {
                                        if (this.value == '50') {
                                            $("#tickets").val('40');
                                        } else if (this.value == '100') {
                                            $("#tickets").val('80');
                                        } else if (this.value == '250') {
                                            $("#tickets").val('200');
                                        } else {
                                            $("#tickets").val('450');
                                        }
                                    });
                                });
                            </script>
                            <!-- <br>
                            <div id="emailHelp" class="form-text">
                                <b>Note:</b> Selling of Ticket Currency<br><br>
                                <em>Conversion of Peso to Ticket currency</em><br><br>
                                • 50 pesos = 40 tickets<br>
                                • 100 pesos = 80 tickets<br>
                                • 250 pesos = 200 tickets<br>
                                • 500 pesos = 450 tickets<br>
                            </div> -->
                            
                            <br><br>
                            <?php
                            if (isset($_POST['submit'])) {
                                $reference = $_POST['reference'];
                                $mobileNo = $_POST['mobile_no'];
                                $amount = $_POST['amount'];
                                $status = 'Pending'; // Set initial status as 'Pending'

                                // Calculate convenience fee based on amount
                                $confee = "";
                                if ($amount == 500) {
                                    $confee = 50.00;
                                } elseif ($amount == 250) {
                                    $confee = 50.00;
                                } elseif ($amount == 100) {
                                    $confee = 20.00;
                                } elseif ($amount == 50) {
                                    $confee = 10.00;
                                }

                                $stmt = $db->prepare("INSERT INTO cico (user_id, transaction_type, gcash_mobile_number, amount, processing_fee, convenience_fee, reference_number, status, created_at, updated_at)
                                VALUES (?, 'cash-in', ?, ?, 0.0, ?, ?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)");
                                $stmt->bind_param("isisds", $userid, $mobileNo, $amount, $confee, $reference, $status);

                                $result = $stmt->execute();

                                if ($result) {
                                    echo '<div style="text-align: center;"><h5 style="color: green; font-size:16px;">Cash-in transaction pending!</h5></div>';
                                } else {
                                    echo "Error: " . $stmt->error;
                                }
                            }
                            ?>

                            <button type="submit" name="submit" class="btn btn-success">Cash In</button>
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