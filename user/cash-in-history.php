<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    echo '<script>window.location.href = "https://tarasabay.000webhostapp.com/login.php";</script>';
    exit();
}

include('../db.php');

$userid = $_SESSION['user_id'];

$stmt = $db->prepare("SELECT * FROM user_profile WHERE user_id = ?");
$stmt->bind_param("s", $userid);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $id = $row['user_id'];
    $first_name = $row['first_name'];
    $last_name = $row['last_name'];
    $email = $row['email'];
    $pswd = $row['pswd'];
}

$stmt = $db->prepare("SELECT * FROM user_identification WHERE user_id = ?");
$stmt->bind_param("s", $userid);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $idtype = $row['identity_type'];
    $validid = $row['user_identity_num'];
}

if (isset($_POST['submit'])) {
    $pswd1 = $db->real_escape_string($_POST['pswd1']);
    $pswd2 = password_hash($_POST['pswd2'], PASSWORD_DEFAULT);

    $stmt = $db->prepare("SELECT * FROM user_profile WHERE user_id = ?");
    $stmt->bind_param("s", $userid);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if (password_verify($pswd1, $user['pswd'])) {
            $stmt = $db->prepare("UPDATE user_profile SET pswd = ? WHERE user_id = ?");
            $stmt->bind_param("ss", $pswd2, $userid);
            $result = $stmt->execute();

            if ($result) {
                echo '<div style="text-align: center;"><h5 style="color: green">Password Changed</h5></div>';
            } else {
                echo '<div style="text-align: center;"><h5 style="color: red">Password Change Failed</h5></div>';
            }
        } else {
            echo '<div style="text-align: center;"><h5 style="color: red">Current Password is Incorrect</h5></div>';
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Vehicle Booking System Transport Saccos, Matatu Industry">
    <meta name="author" content="MartDevelopers">
    <link rel="icon" href="../assets/img/logo.png" type="images" />

    <title>Dashboard</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

    <!-- Page level plugin CSS-->
    <link href="vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="vendor/css/sb-admin.css" rel="stylesheet">

</head>

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

                <!-- Breadcrumbs-->
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="#">Transactions</a>
                    </li>
                    <li class="breadcrumb-item active">Cash-In History</li>
                </ol>

                <?php
                if ($_SESSION['role'] == "admin") {
                ?>
                    <!--Bookings-->
                    <div class="card mb-3">
                        <div class="card-header">
                            <i class="fas fa-table"></i>
                            Cash-In Transaction History
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Transaction ID</th>
                                            <th>Name</th>
                                            <th>Amount</th>
                                            <th>Convenience Fee</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $query = "SELECT cico.wallet_id, cico.gcash_mobile_number, cico.amount, cico.convenience_fee, cico.reference_number, cico.created_at, up.first_name, up.last_name, cico.status
                                                FROM cico
                                                INNER JOIN user_profile up ON cico.user_id = up.user_id
                                                WHERE cico.amount > 0 AND cico.transaction_type = 'cash-in'
                                                AND cico.status = 'Approved'
                                                ORDER BY cico.created_at ASC";
                                        $stmt = $db->prepare($query);
                                        $stmt->execute();
                                        $result = $stmt->get_result();
                                        $totalAmount = 0;
                                        $totalConvenienceFee = 0;

                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td>" . $row['wallet_id'] . "</td>";
                                            echo "<td>" . $row['first_name'] . " " . $row['last_name'] . "</td>";
                                            echo "<td>" . number_format($row['amount'], 2) . "</td>";
                                            echo "<td>" . number_format($row['convenience_fee'], 2) . "</td>";
                                            echo "</tr>";
                                            $totalAmount += $row['amount'];
                                            $totalConvenienceFee += $row['convenience_fee'];
                                        }
                                        ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="2"><b>Total</b></td>
                                            <td><?php echo number_format($totalAmount, 2); ?></td>
                                            <td><?php echo number_format($totalConvenienceFee, 2); ?></td>
                                        </tr>
                                    </tfoot>
                                </table>

                            </div>
                        </div>
                        <div class="card-footer small text-muted">
                            <?php
                            date_default_timezone_set("Africa/Nairobi");
                            echo "Generated: " . date("h:i:sa");
                            ?>
                        </div>
                    </div>
                <?php
                } else {
                ?>
                    <!--Bookings-->
                    <div class="card mb-3">
                        <div class="card-header">
                            <i class="fas fa-table"></i>
                            Cash-In Transaction History
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Transaction #</th>
                                            <th>GCash Mobile Number</th>
                                            <th>Amount</th>
                                            <th>Conv. Fee</th>
                                            <th>Reference Number</th>
                                            <th>Created At</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $user_id = $userid; // Replace with the desired user ID

                                        $stmt = $db->prepare("SELECT c.wallet_id, up.first_name, up.last_name, c.amount, c.convenience_fee, c.status, c.gcash_mobile_number, c.reference_number, c.created_at
                                                FROM cico c
                                                INNER JOIN user_profile up ON c.user_id = up.user_id
                                                WHERE c.user_id = ? AND c.transaction_type = 'cash-in'
                                                ORDER BY c.wallet_id");

                                        $stmt->bind_param("i", $user_id);
                                        $stmt->execute();
                                        $result = $stmt->get_result();
                                        $count = 1; // Initialize the count variable

                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td>" . $count . "</td>"; // Display the incrementing number
                                            echo "<td>" . $row['gcash_mobile_number'] . "</td>";
                                            echo "<td>" . number_format($row['amount'], 2) . "</td>";
                                            echo "<td>" . number_format($row['convenience_fee'], 2) . "</td>";
                                            echo "<td>" . $row['reference_number'] . "</td>";
                                            echo "<td>" . $row['created_at'] . "</td>";
                                            echo "<td>";
                                            if ($row['status'] == "Pending") {
                                                echo '<span class="badge badge-warning">' . $row['status'] . '</span>';
                                            } elseif ($row['status'] == "Approved") {
                                                echo '<span class="badge badge-success">' . $row['status'] . '</span>';
                                            } elseif ($row['status'] == "Denied") {
                                                echo '<span class="badge badge-danger">' . $row['status'] . '</span>';
                                            }
                                            echo "</td>";
                                            echo "</tr>";
                                            $count++; // Increment the count variable
                                        }
                                        ?>

                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>


                    <div class="card-footer small text-muted">
                        <?php
                        date_default_timezone_set("Africa/Nairobi");
                        echo "Generated: " . date("h:i:sa");
                        ?>
                    </div>
            </div>



        <?php
                }
        ?>




        <!-- /.container-fluid -->

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

</body>

</html>