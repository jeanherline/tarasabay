<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];
    $amount = $_POST['amount'];
    $processing_fee = floor($amount / 1000) * 20;
    $total_tickets = $amount + $processing_fee;

    $sql = "SELECT acc_balance FROM user_profile WHERE user_id=$user_id";
    $result = $db->query($sql);
    $row = $result->fetch_assoc();
    $acc_balance = $row['acc_balance'];

    if ($acc_balance < $total_tickets) {
        echo "Insufficient balance";
    } else {
        $sql = "UPDATE user_profile SET acc_balance=acc_balance - $total_tickets WHERE user_id=$user_id";
        $db->query($sql);

        $sql = "INSERT INTO encashment (user_id, e_mobile_num, encash_amount, encash_status)
        VALUES ($user_id, '1234567890', $amount, 'success')";
        $db->query($sql);

        echo "Cash out successful. Your new balance is " . ($acc_balance - $total_tickets);
    }
} else {
    ?>
    <style>
        form {
            width: 300px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
            border-radius: 5px;
        }
        select, input[type="number"], input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
        }
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
    <form method="post" action="cash-in.php">
        User ID: <input type="number" name="user_id"><br>
        Amount: 
        <select name="amount">
            <option value="50">50 pesos</option>
            <option value="100">100 pesos</option>
            <option value="250">250 pesos</option>
            <option value="500">500 pesos</option>
        </select><br>
        Role: <input type="text" name="role"><br>
        <input type="submit" value="Submit">
    </form>
    <?php
}
?>