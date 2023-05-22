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
<title>Cash-out</title>

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
                    <li class="breadcrumb-item active">Cash-Out</li>
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
                        <b>GCash:</b> <em>Cash-Out</em>
                    </div>
                    <div class="card-body">
                        <!-- Cash-Out Form -->
                        <form method="POST">
                            <label for="mobile_no" class="form-label">GCash Mobile Number <span class="text-danger">*</span></label>
                            <input type="text" name="mobile_no" id="mobile_no" minlength="11" maxlength="11" class="form-control" required>
                            <br>
                            <label for="amount" class="form-label">Amount <span class="text-danger">*</span></label>
                            <input type="number" name="amount" id="amount" class="form-control" required>
                            <br>
                            <div id="emailHelp" class="form-text">
                                <b>Note:</b> 1 ticket = 1 peso<br><br>
                                <em>Processing fee: 20 tickets for every 1000 pesos Cash Out</em><br><br>
                                Ex: 2100 tickets = 2100 pesos with a processing fee of 60 tickets.<br>
                                The driver needs to have 2160 tickets to proceed with the Cash Out.
                            </div>
                            <br><br>
                            <?php
                            if (isset($_POST['submit'])) {
                                // Retrieve the user's account balance
                                $availableBalance = 0.00; // Default value
                                if (isset($userid)) {
                                    $stmt = $db->prepare("SELECT acc_balance, role FROM user_profile WHERE user_id = ?");
                                    $stmt->bind_param("i", $userid);
                                    $stmt->execute();
                                    $stmt->bind_result($accBalance, $role);

                                    if ($stmt->fetch()) {
                                        $availableBalance = $accBalance;
                                    }

                                    $stmt->close();
                                }

                                // Calculate the processing fee based on the cash-out amount
                                $amount = $_POST['amount'] ?? 0; // Retrieve the entered amount
                                $processingFee = ceil($amount / 1000) * 20;

                                echo '<div style="text-align: center; margin-bottom: 20px;">';
                                echo '<div><strong>Amount:</strong> ' . $amount . '</div>';
                                echo '<div><strong>Processing Fee:</strong> ' . $processingFee . '</div>';
                                echo '</div>';

                                // Convert the account balance to available tickets
                                $ticketValue = 1; // 1 ticket = 1 peso
                                $availableTickets = floor($availableBalance / $ticketValue);

                                $mobileNo = $_POST['mobile_no'];
                                $reference = ""; // Define the reference number
                                $status = 'Pending'; // Set initial status as 'Pending'

                                if ($role === 'driver') {
                                    if ($amount + $processingFee > $availableTickets) {
                                        echo '<div style="text-align: center;">
                                            <h5 style="color: red; font-size:16px;">Insufficient ticket balance for cash-out!</h5>
                                        </div>';
                                    } else {
                                        $stmt = $db->prepare("INSERT INTO cico (user_id, transaction_type, gcash_mobile_number, amount, processing_fee, convenience_fee, reference_number, status, created_at, updated_at)
                                                 VALUES (?, 'cash-out', ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)");

                                        $convenienceFee = 0.00;

                                        $stmt->bind_param("isdddss", $userid, $mobileNo, $amount, $processingFee, $convenienceFee, $reference, $status);

                                        $result = $stmt->execute();

                                        if ($result) {
                                            echo '<div style="text-align: center;">
                                            <h5 style="color: green; font-size:16px;">Cash-out transaction pending!</h5>
                                        </div>';
                                        } else {
                                            echo "Error: " . $stmt->error;
                                        }
                                        $stmt->close();
                                    }
                                }
                            }
                            ?>

                            <button type="submit" name="submit" class="btn btn-success">Cash Out</button>
                        </form>

                        <!-- End Cash-Out Form -->
                    </div>

                </div>


                <hr>
                <!-- Sticky Footer -->
                <?php include("vendor/inc/footer.php"); ?>

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