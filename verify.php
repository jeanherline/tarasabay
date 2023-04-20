<?php
include('db.php');

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Find the record in the `user_temp` table
    $sql = "SELECT * FROM user_temp WHERE token='$token'";
    $result = $db->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Move the user data to the `user_profile` table
        $first_name = $row['first_name'];
        $last_name = $row['last_name'];
        $email = $row['email'];
        $pswd = $row['pswd'];

        $sql_insert = "INSERT INTO user_profile (first_name, last_name, email, pswd) VALUES ('$first_name', '$last_name', '$email', '$pswd')";

        if ($db->query($sql_insert) === TRUE) {
            // Delete the record from the `user_temp` table
            $sql_delete = "DELETE FROM user_temp WHERE token='$token'";
            $db->query($sql_delete);
            header("Location: http://carpooling-application-ph.rf.gd/notif/notif-verified.html");
            exit;
        } else {
            echo '<div style="text-align: center;"><h5 style="color: red">Error:</h5></div><div style="text-align: center;">' . $sql . "<br>" . $db->error . '</div>';
        }
    } else {
        header("Location: http://carpooling-application-ph.rf.gd/notif/notif-failed.html");
        exit;
    }
}
