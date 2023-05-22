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

    $status = "Declined";

    $stmt = $db->prepare("UPDATE cico SET status = ? WHERE wallet_id = ?");
    $stmt->bind_param("si", $status, $walletId);
    $stmt->execute();

    if ($result) {
        echo '<script>window.location.href = "https://tarasabay.000webhostapp.com/user/cash-in-manage.php";</script>';
        exit();
    } else {
        echo '<div style="text-align: center;"><h5 style="color: red">Failed</h5></div>';
    }
}
