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
<title>Registered Cars</title>

<link rel="icon" href="../assets/img/logo.png" type="images" />
<?php include('vendor/inc/head.php'); ?>

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Vehicle Booking System Transport Saccos, Matatu Industry">
  <meta name="author" content="MartDevelopers">
  <link rel="icon" href="../assets/img/logo.png" type="images" />

  <title>Pending Car Registrations</title>

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


        <!--Bookings-->

        <?php
        if ($_SESSION['role'] == "admin") {
        ?>
          <!-- Breadcrumbs-->
          <ol class="breadcrumb">
            <li class="breadcrumb-item">
              <a href="#">Car Registrants</a>
            </li>
            <li class="breadcrumb-item active">Registered Cars</li>
          </ol>

          <div class="card mb-3">
            <div class="card-header">
              <i class="fas fa-users"></i>
              &nbsp;&nbsp;Approved Car Registrations
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Name</th>
                      <th>Brand</th>
                      <th>Model</th>
                      <th>Color</th>
                      <th>Seat Count</th>
                      <th>License Plate</th>
                      <th>CR Number</th>
                      <th>OR Number</th>
                      <th>Reg Exp Date</th>
                      <th>Created At</th>
                      <th>Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $ret = "SELECT c.brand, c.model, c.color, c.seat_count, ci.car_identity_num, ci.cr_number, ci.or_number, ci.reg_exp_date, c.created_at, c.status, up.first_name, up.last_name
                    FROM car c
                    JOIN car_identification ci ON c.car_id = ci.car_id
                    JOIN user_profile up ON c.owner_id = up.user_id
                    WHERE c.status = 'approved'";
                    $stmt = $db->prepare($ret);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $cnt = 1;
                    while ($row = $result->fetch_assoc()) {
                      echo "<tr>";
                      echo "<td>" . $row['first_name'] . " " . $row['last_name'] . "</td>";
                      echo "<td>" . $row['brand'] . "</td>";
                      echo "<td>" . $row['model'] . "</td>";
                      echo "<td>" . $row['color'] . "</td>";
                      echo "<td>" . $row['seat_count'] . "</td>";
                      echo "<td>" . $row['car_identity_num'] . "</td>";
                      echo "<td>" . $row['cr_number'] . "</td>";
                      echo "<td>" . $row['or_number'] . "</td>";
                      echo "<td>" . $row['reg_exp_date'] . "</td>";
                      echo "<td>" . $row['created_at'] . "</td>";
                      echo "<td>";
                      if ($row['status'] == "Pending") {
                        echo '<span class="badge badge-warning">' . $row['status'] . '</span>';
                      } else {
                        echo '<span class="badge badge-success">' . $row['status'] . '</span>';
                      }
                      echo "</td>";
                      echo "</tr>";
                      $cnt++;
                    }
                    ?>
                  </tbody>
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
        } else if ($_SESSION['role'] == "driver") {
        ?>
          <ol class="breadcrumb">
            <li class="breadcrumb-item">
              <a href="#">Apply</a>
            </li>
            <li class="breadcrumb-item active">Registered Cars</li>
          </ol>
          <div class="card mb-3">
            <div class="card-header">
              <i class="fas fa-users"></i>
              &nbsp;&nbsp;Approved Car Registrations
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Brand</th>
                      <th>Model</th>
                      <th>Color</th>
                      <th>Seat Count</th>
                      <th>License Plate</th>
                      <th>CR Number</th>
                      <th>OR Number</th>
                      <th>Reg Exp Date</th>
                      <th>Created At</th>
                      <th>Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $ret = "SELECT c.brand, c.model, c.color, c.seat_count, ci.car_identity_num, ci.cr_number, ci.or_number, ci.reg_exp_date, c.created_at, c.status
                        FROM car c
                        JOIN car_identification ci ON c.car_id = ci.car_id
                        JOIN user_profile up ON c.owner_id = up.user_id
                        WHERE up.user_id = $userid
                        AND c.status = 'approved'";
                    $stmt = $db->prepare($ret);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $cnt = 1;
                    while ($row = $result->fetch_assoc()) {
                      echo "<tr>";
                      echo "<td>" . $row['brand'] . "</td>";
                      echo "<td>" . $row['model'] . "</td>";
                      echo "<td>" . $row['color'] . "</td>";
                      echo "<td>" . $row['seat_count'] . "</td>";
                      echo "<td>" . $row['car_identity_num'] . "</td>";
                      echo "<td>" . $row['cr_number'] . "</td>";
                      echo "<td>" . $row['or_number'] . "</td>";
                      echo "<td>" . $row['reg_exp_date'] . "</td>";
                      echo "<td>" . $row['created_at'] . "</td>";
                      echo "<td>";
                      if ($row['status'] == "Pending") {
                        echo '<span class="badge badge-warning">' . $row['status'] . '</span>';
                      } else {
                        echo '<span class="badge badge-success">' . $row['status'] . '</span>';
                      }
                      echo "</td>";
                      echo "</tr>";
                      $cnt++;
                    }
                    ?>
                  </tbody>
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