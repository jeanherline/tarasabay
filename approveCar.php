<?php
include('db.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', 'error.log');

if (isset($_GET['owner_id'])) {
    $id = $_GET['owner_id'];

    // Update the car status to "Approved"
    $status = "Approved";
    $stmt = $db->prepare("UPDATE car SET status = ? WHERE owner_id = ?");
    $stmt->bind_param("si", $status, $id);

    if ($stmt->execute()) {

        // Check if this is the first approved car registration for the user
        $stmt = $db->prepare("SELECT COUNT(*) FROM car WHERE owner_id = ? AND status = 'Approved'");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($carCount);
        $stmt->fetch();
        $stmt->close();

        if ($carCount === 1) {
            // Get the user's current ticket balance
            $stmt = $db->prepare("SELECT acc_balance FROM user_profile WHERE user_id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->bind_result($acc_balance);
            $stmt->fetch();
            $stmt->close();

            // Add 40 free tickets to the user's balance
            $new_acc_balance = $acc_balance + 40;
            $stmt = $db->prepare("UPDATE user_profile SET acc_balance = ? WHERE user_id = ?");
            $stmt->bind_param("di", $new_acc_balance, $id);
            $stmt->execute();
            $stmt->close();
        }
        echo '<script>window.location.href = "https://tarasabay.000webhostapp.com/user/pendingCars.php";</script>';
        exit();

    } else {
        echo '<div style="text-align: center;"><h5 style="color: red">Failed</h5></div>';
        echo 'Error: ' . $stmt->error;
    }
}
