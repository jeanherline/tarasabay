<?php
include('db.php');

if (isset($_GET['wallet_id']) && isset($_GET['amount'])) {
    $walletId = $_GET['wallet_id'];
    $amount = $_GET['amount'];
    $confee = "";

    // Retrieve the user_id associated with the wallet_id
    $stmt = $db->prepare("SELECT user_id FROM cico WHERE wallet_id = ?");
    $stmt->bind_param("i", $walletId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $userId = $row['user_id'];

    $status = "Approved";
    $conversionRate = 0;

    if ($amount == 50) {
        $conversionRate = 40;
    } elseif ($amount == 100) {
        $conversionRate = 80;
    } elseif ($amount == 250) {
        $conversionRate = 200;
    } elseif ($amount == 500) {
        $conversionRate = 450;
    }

    // Update the status and convenience_fee of cico table
    $stmt = $db->prepare("UPDATE cico SET status = ? WHERE wallet_id = ?");
    $stmt->bind_param("si", $status, $walletId);
    $stmt->execute();

    // Update the acc_balance and ticket_balance of user_profile table
    $stmt = $db->prepare("UPDATE user_profile SET acc_balance = acc_balance + ? WHERE user_id = ?");
    $stmt->bind_param("di", $conversionRate, $userId);
    $result = $stmt->execute();

    if ($result) {
        echo '<script>window.location.href = "https://tarasabay.000webhostapp.com/user/cash-in-manage.php";</script>';
        exit();
    } else {
        echo '<div style="text-align: center;"><h5 style="color: red">Failed</h5></div>';
    }
}
