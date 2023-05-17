<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];
    $amount = $_POST['amount'];
    $role = $_POST['role'];

    $tickets = 0;
    if ($amount == 50) {
        $tickets = 40;
    } elseif ($amount == 100) {
        $tickets = 80;
    } elseif ($amount == 250) {
        $tickets = 200;
    } elseif ($amount == 500) {
        $tickets = 450;
    }

    $sql = "UPDATE user_profile SET acc_balance=acc_balance + $tickets WHERE user_id=$user_id";
    $db->query($sql);

    $sql = "INSERT INTO reload (user_id, r_mobile_num, reload_amount, reload_status)
    VALUES ($user_id, '1234567890', $amount, 'success')";
    $db->query($sql);

    echo "Cash in successful. Your new balance is " . ($acc_balance + $tickets);
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