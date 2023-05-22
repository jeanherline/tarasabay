<?php
include('db.php');

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Find the record in the `user_temp` table
    $sql = "SELECT * FROM user_temp WHERE token=?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Move the user data to the `user_profile` table
        $first_name = $row['first_name'];
        $last_name = $row['last_name'];
        $email = $row['email'];
        $pswd = $row['pswd'];
        $id = $row['identity_type'];
        $idnum = $row['user_identity_num'];

        $sql_insert = "INSERT INTO user_profile (first_name, last_name, email, pswd) VALUES (?,?,?,?)";
        $stmt = $db->prepare($sql_insert);
        $stmt->bind_param("ssss", $first_name, $last_name, $email, $pswd);
        $stmt->execute();
        if ($stmt->error) {
            die("Error executing query: " . $stmt->error);
        }

        $user_id = $stmt->insert_id;

        $sql = "INSERT INTO user_identification (user_id, identity_type, user_identity_num) VALUES (?,?,?)";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("iss", $user_id, $id, $idnum);
        $stmt->execute();
        if ($stmt->error) {
            die("Error executing query: " . $stmt->error);
        }

        $sql_delete = "DELETE FROM user_temp WHERE token=?";
        $stmt = $db->prepare($sql_delete);
        $stmt->bind_param("s", $token);
        $stmt->execute();

        // Add 10 free tickets to the user
        $sql_update_tickets = "UPDATE user_profile SET acc_balance = acc_balance + 10 WHERE user_id = ?";
        $stmt = $db->prepare($sql_update_tickets);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        if ($stmt->error) {
            die("Error executing query: " . $stmt->error);
        }

        session_start();
        $_SESSION['user_id'] = $user_id;

        header("Location: https://tarasabay.000webhostapp.com/login.php");
        exit;
    } else {
        header("Location: https://tarasabay.000webhostapp.com/notif/notif-failed.html");
        exit;
    }
}
