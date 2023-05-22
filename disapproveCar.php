<?php
include('db.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', 'error.log');

if (isset($_GET['owner_id'])) {
    $id = $_GET['owner_id'];

    // Update the car status to "Approved"
    $status = "Declined";
    $stmt = $db->prepare("UPDATE car SET status = ? WHERE owner_id = ?");
    $stmt->bind_param("si", $status, $id);

    if ($stmt->execute()) {
        echo '<script>window.location.href = "https://tarasabay.000webhostapp.com/user/pendingCars.php";</script>';
        exit();
    } else {
        echo '<div style="text-align: center;"><h5 style="color: red">Failed</h5></div>';
        echo 'Error: ' . $stmt->error;
    }
}
