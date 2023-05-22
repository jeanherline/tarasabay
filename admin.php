<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo '<script>window.location.href = "https://tarasabay.000webhostapp.com/login.php";</script>';
    exit();
}

include('db.php');

$userid = $_SESSION['user_id'];


?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin</title>
    <link rel="icon" href="assets/img/logo.png" type="images" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-+rDd/RzjK1yX9zi0V/kdA/dW5ygFsdwR6jZIZV7cDI6W8U6jpkMqgjJnkCv3OkH8jOGYw2q3OEMvpeOyyxnz8g==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700;800&display=swap");

        *,
        *::before,
        *::after {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }

        body,
        input {
            font-family: "Poppins", sans-serif;
            margin: 0 auto;
            max-width: 2000px;
            max-height: 2000px;
            margin-top: 50px;
            margin-bottom: 5px;
            padding: 0px 15px 0 15px;
            background-color: #EAAA00;
        }

        form {
            background-color: #FFFFFF;
            padding: 20px;
            border-radius: 10px;
        }

        input[type=text],
        input[type=password],
        input[type=number] {
            width: 97%;
            padding: 10px;
            margin: 5px 0 22px 0;
            display: inline-block;
            border: none;
            background: #F5F5F5;
        }

        hr {
            border: 1px solid #e6e6e6;
            margin-bottom: 5px;
        }

        .registerbutton {
            background-color: black;
            color: white;
            padding: 15px 20px;
            margin: 10px 0px;
            border: none;
            cursor: pointer;
            width: 70%;
            font-size: 18px;
            margin-left: 15%;
        }

        h1,
        p {
            text-align: center;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }

        th,
        td {
            text-align: left;
            padding: 8px;
            border: 1px solid black;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <form action="" method="post">
        <h1>Pending Car Registrations</h1>
        <br>
        <hr>
        <br>
        <?php
        $stmt = $db->prepare("SELECT c.car_id, c.owner_id, c.brand, c.model, ci.car_identity_num, ci.cr_number, ci.or_number, ci.reg_exp_date, c.status, c.color, c.seat_count, c.created_at, up.first_name, up.last_name, up.email, up.role
        FROM car c
        JOIN car_identification ci ON c.car_id = ci.car_id
        JOIN user_profile up ON c.owner_id = up.user_id
        WHERE up.role IN ('user', 'driver')
        AND c.status = 'pending'");
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) { ?>
            <table>
                <thead>
                    <tr>
                        <th>Owner First Name</th>
                        <th>Owner Last Name</th>
                        <th>Owner Email</th>
                        <th>Brand</th>
                        <th>Model</th>
                        <th>Color</th>
                        <th>Seat Count</th>
                        <th>License Plate</th>
                        <th>CR Number</th>
                        <th>OR Number</th>
                        <th>Reg Exp Date</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($row = $result->fetch_assoc()) {
                        $id = $row['owner_id'];
                        echo "<tr>";
                        echo "<td>" . $row['first_name'] . "</td>";
                        echo "<td>" . $row['last_name'] . "</td>";
                        echo "<td>" . $row['email'] . "</td>";
                        echo "<td>" . $row['brand'] . "</td>";
                        echo "<td>" . $row['model'] . "</td>";
                        echo "<td>" . $row['color'] . "</td>";
                        echo "<td>" . $row['seat_count'] . "</td>";
                        echo "<td>" . $row['car_identity_num'] . "</td>";
                        echo "<td>" . $row['cr_number'] . "</td>";
                        echo "<td>" . $row['or_number'] . "</td>";
                        echo "<td>" . $row['reg_exp_date'] . "</td>";
                        echo "<td>" . $row['created_at'] . "</td>";
                    ?>
                        <td class="action-buttons">
                            <a href="https://tarasabay.000webhostapp.com/approveCar.php?owner_id=<?php echo $row['owner_id']; ?>">✅</a>
                            <a href="https://tarasabay.000webhostapp.com/disapproveCar.php?owner_id=<?php echo $row['owner_id']; ?>">❎</a>

                        </td>

                    <?php
                        echo "</tr>";
                    }
                    ?>
                    <style>
                        .action-buttons a {
                            border: none;
                            text-align: center;
                            text-decoration: none;
                            font-size: 18 px;
                            width: 80px;
                        }
                    </style>
                </tbody>
            </table>

        <?php } else { ?>
            <p>You have no pending car registrations.</p>
        <?php } ?>
    </form>
    <br><br>
    <form action="" method="post">
        <h1>Approved Car Registrations</h1>
        <br>
        <hr>
        <br>
        <?php
        $stmt = $db->prepare("SELECT c.car_id, c.owner_id, c.brand, c.model, ci.car_identity_num, ci.cr_number, ci.or_number, ci.reg_exp_date, c.status, c.color, c.seat_count, c.created_at, up.first_name, up.last_name, up.email, up.role
        FROM car c
        JOIN car_identification ci ON c.car_id = ci.car_id
        JOIN user_profile up ON c.owner_id = up.user_id
        WHERE up.role IN ('user', 'driver')
        AND c.status = 'approved'");
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) { ?>
            <table>
                <thead>
                    <tr>
                        <th>Owner First Name</th>
                        <th>Owner Last Name</th>
                        <th>Owner Email</th>
                        <th>Brand</th>
                        <th>Model</th>
                        <th>Color</th>
                        <th>Seat Count</th>
                        <th>License Plate</th>
                        <th>LTO Certificate of Registration (CR) Number</th>
                        <th>LTO Official Receipt (OR) Number</th>
                        <th>Registration Expiration Date</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($row = $result->fetch_assoc()) {
                        $id = $row['owner_id'];
                        echo "<tr>";
                        echo "<td>" . $row['first_name'] . "</td>";
                        echo "<td>" . $row['last_name'] . "</td>";
                        echo "<td>" . $row['email'] . "</td>";
                        echo "<td>" . $row['brand'] . "</td>";
                        echo "<td>" . $row['model'] . "</td>";
                        echo "<td>" . $row['color'] . "</td>";
                        echo "<td>" . $row['seat_count'] . "</td>";
                        echo "<td>" . $row['car_identity_num'] . "</td>";
                        echo "<td>" . $row['cr_number'] . "</td>";
                        echo "<td>" . $row['or_number'] . "</td>";
                        echo "<td>" . $row['reg_exp_date'] . "</td>";
                        echo "<td>" . $row['created_at'] . "</td>";
                    ?>
                        <td class="action-buttons">
                            <a href="https://tarasabay.000webhostapp.com/disapproveCar.php?owner_id=<?php echo $row['owner_id']; ?>">❎</a>

                        </td>

                    <?php
                        echo "</tr>";
                    }
                    ?>

                    <style>
                        .action-buttons a {
                            border: none;
                            text-align: center;
                            text-decoration: none;
                            font-size: 18 px;
                            width: 80px;
                        }
                    </style>
                </tbody>
            </table>

        <?php } else { ?>
            <p>You have no approved car registrations.</p>
        <?php } ?>
    </form>
    <br><br>
    <form action="" method="post">
        <h1>Declined Car Registrations</h1>
        <br>
        <hr>
        <br>
        <?php
        $stmt = $db->prepare("SELECT c.car_id, c.owner_id, c.brand, c.model, ci.car_identity_num, ci.cr_number, ci.or_number, ci.reg_exp_date, c.status, c.color, c.seat_count, c.created_at, up.first_name, up.last_name, up.email, up.role
        FROM car c
        JOIN car_identification ci ON c.car_id = ci.car_id
        JOIN user_profile up ON c.owner_id = up.user_id
        WHERE up.role IN ('user', 'driver')
        AND c.status = 'declined'");
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) { ?>
            <table>
                <thead>
                    <tr>
                        <th>Owner First Name</th>
                        <th>Owner Last Name</th>
                        <th>Owner Email</th>
                        <th>Brand</th>
                        <th>Model</th>
                        <th>Color</th>
                        <th>Seat Count</th>
                        <th>License Plate</th>
                        <th>CR Number</th>
                        <th>OR Number</th>
                        <th>Reg Exp Date</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($row = $result->fetch_assoc()) {
                        $id = $row['owner_id'];
                        echo "<tr>";
                        echo "<td>" . $row['first_name'] . "</td>";
                        echo "<td>" . $row['last_name'] . "</td>";
                        echo "<td>" . $row['email'] . "</td>";
                        echo "<td>" . $row['brand'] . "</td>";
                        echo "<td>" . $row['model'] . "</td>";
                        echo "<td>" . $row['color'] . "</td>";
                        echo "<td>" . $row['seat_count'] . "</td>";
                        echo "<td>" . $row['car_identity_num'] . "</td>";
                        echo "<td>" . $row['cr_number'] . "</td>";
                        echo "<td>" . $row['or_number'] . "</td>";
                        echo "<td>" . $row['reg_exp_date'] . "</td>";
                        echo "<td>" . $row['created_at'] . "</td>";
                    ?>
                        <td class="action-buttons">
                            <a href="https://tarasabay.000webhostapp.com/approveCar.php?owner_id=<?php echo $row['owner_id']; ?>">✅</a>
                        </td>

                    <?php
                        echo "</tr>";
                    }
                    ?>

                    <style>
                        .action-buttons a {
                            border: none;
                            text-align: center;
                            text-decoration: none;
                            font-size: 18 px;
                            width: 80px;
                        }
                    </style>
                </tbody>
            </table>

        <?php } else { ?>
            <p>You have no declined car registrations.</p>
        <?php } ?>
    </form>
</body