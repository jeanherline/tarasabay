<?php
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

include('db.php');
session_start();

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>TaraSabay</title>
  <link rel="stylesheet" href="css/style.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
  <link rel="icon" href="img/logo.png" type="images" />
</head>

<body>
  <main>
    <div class="box">
      <div class="inner-box">
        <div class="forms-wrap">
          <form action="" method="POST" autocomplete="off" class="sign-in-form">
            <div class="logo">
              <img src="img/logo.png" alt="TaraSabay" />
              <h4>TaraSabay</h4>
            </div>

            <div class="heading">
              <h2>Welcome Back</h2>
              <h6>Not registred yet?</h6>
              <a href="#" class="toggle">Sign up</a>
            </div>

            <div class="actual-form">
              <div class="input-wrap">
                <input type="text" id="email" name="email" minlength="4" class="input-field" autocomplete="off" required />
                <label id="email">Email</label>
              </div>

              <div class="input-wrap">
                <input type="password" id="pswd" name="pswd" minlength="4" class="input-field" autocomplete="off" required />
                <label id="pswd">Password</label>
              </div>

              <?php
              if (isset($_POST['sign-in'])) {
                $email = $_POST['email'];
                $pswd = $_POST['pswd'];

                // Check if username exists in database
                $stmt = $db->prepare("SELECT * FROM user_profile WHERE email = ?");
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows == 1) {
                  // Username exists, verify password
                  $user = $result->fetch_assoc();
                  if (password_verify($pswd, $user['pswd'])) { // Make sure to use the correct column name here
                    // Password is correct, log user in
                    $_SESSION['user_id'] = $user['id'];
                    header('Location: dashboard.php');
                    exit();
                  } else {
                    // Password is incorrect
                    echo '<div style="text-align: center;">
                        <h5 style="color: red">Invalid password</h5>
                      </div><br>';
                  }
                } else {
                  // Username doesn't exist
                  echo '<div style="text-align: center;">
                  <h5 style="color: red">Email not found</h5>
                </div><br>';
                }
              }
              if (isset($_POST['register'])) {
                $fname = $db->real_escape_string($_POST['fname']);
                $lname = $db->real_escape_string($_POST['lname']);
                $email = $db->real_escape_string($_POST['email']);
                $pswd = $db->real_escape_string(password_hash($_POST['pswd'], PASSWORD_DEFAULT));
                $token = bin2hex(random_bytes(16));

                // Check if the email already exists in the user_profile table
                $sql_email_check = "SELECT * FROM user_profile WHERE email='$email'";
                $result = $db->query($sql_email_check);

                if ($result->num_rows > 0) {
                  // Email already exists in the user_profile table
                  echo '<div style="text-align: center;">
                            <h5 style="color: red">Email address already exists. Please use a different email or log in.</h5>
                        </div><br>';
                } else {
                  // Insert the user data into the `user_temp` table
                  $sql = "INSERT INTO user_temp (first_name, last_name, email, pswd, token) VALUES ('$fname', '$lname', '$email', '$pswd', '$token')";
                  if ($db->query($sql) === TRUE) {
                    $mail = new PHPMailer(true);

                    try {
                      // Replace the placeholders with your actual Gmail email and password
                      $mail->SMTPDebug = 0;
                      $mail->isSMTP();
                      $mail->Host = 'smtp.gmail.com';
                      $mail->SMTPAuth = true;
                      $mail->Username = 'carpoolapp01@gmail.com';
                      $mail->Password = 'wzspvmmnnxhtbuxd';
                      $mail->SMTPSecure = 'tls';
                      $mail->Port = 587;

                      $mail->setFrom('noreply@tarasabay.com', 'TaraSabay PH');
                      $mail->addAddress($email);
                      $mail->addCustomHeader('X-Priority', '1');
                      $mail->addCustomHeader('Importance', 'High');

                      $mail->isHTML(true);
                      $mail->Subject = 'Email Verification';
                      $mail->Body = "
                                  <html>
                                  <head>
                                    <style>
                                      body {
                                        font-family: Arial, sans-serif;
                                        font-size: 16px;
                                        line-height: 1.6;
                                        color: #444;
                                      }
                                      h1 {
                                        font-size: 24px;
                                        font-weight: bold;
                                        color: #333;
                                        margin: 0 0 30px;
                                        text-align: center;
                                      }
                                      p {
                                        margin: 0 0 20px;
                                      }
                                      a {
                                        color: #0072C6;
                                        text-decoration: none;
                                      }
                                      a:hover {
                                        text-decoration: underline;
                                      }
                                      .container {
                                        max-width: 600px;
                                        margin: 0 auto;
                                      }
                                    </style>
                                  </head>
                                  <body>
                                    <div class=\"container\">
                                      <h1>Verification Required for Your Carpool App Registration</h1>
                                      <p>Dear valued user,</p>
                          <p>Thank you for choosing Carpool App to find rides or offer your own. To ensure the security of your account, we need to verify your email address before you can start using the app.</p>
                          <p>Please click on the button below to verify your email address and finalize your registration:</p>
                          <p><a href=\"http://carpooling-application-ph.rf.gd/verify.php?token=" . urlencode($token) . " \" style=\"display:inline-block; padding: 10px 20px; background-color: #0072C6; color: #fff; font-weight: bold; text-decoration: none;\">Verify Your Email Address</a></p>
                          <p>If you have any questions or concerns, please don't hesitate to contact us at support@carpoolapp.com.</p>
                          <p>Best regards,</p>
                          <p>TaraSabay PH Team</p>
                        </div>
                      </body>
  
                      </html>";
                      $mail->send();
                      echo '<div style="text-align: center;">
                        <h5 style="color: green">Check email to verify registration</h5><br>
                      </div>';
                    } catch (Exception $e) {
                      echo '<div style="text-align: center;">
                        <h5 style="color: red">Error sending verification email: </h5>' . $mail->ErrorInfo . '
                      </div>';
                    }
                  } else {
                    echo '<div style="text-align: center;">
                        <h5 style="color: red">Error:</h5>
                      </div>
                      <div style="text-align: center;">' . $sql . "<br>" . $db->error . '</div>';
                  }
                }
              }
              ?>

              <input type="submit" id="sign-in" name="sign-in" value="Sign In" class="sign-btn" />

              <p class="text">
                Forgotten your password or you login datails?
                <a href="#">Get help</a> signing in
              </p>
            </div>
          </form>

          <form action="" method="POST" autocomplete="off" class="sign-up-form">
            <div class="logo">
              <img src="img/logo.png" alt="easyclass" />
              <h4>TaraSabay</h4>
            </div>

            <div class="heading">
              <h2>Get Started</h2>
              <h6>Already have an account?</h6>
              <a href="#" class="toggle">Sign in</a>
            </div>

            <div class="actual-form">
              <div class="input-wrap">
                <input type="text" id="fname" name="fname" minlength="4" class="input-field" autocomplete="off" required />
                <label id="fname">First Name</label>
              </div>

              <div class="input-wrap">
                <input type="text" id="lname" name="lname" minlength="4" class="input-field" autocomplete="off" required />
                <label id="lname">Last Name</label>
              </div>

              <div class="input-wrap">
                <input type="email" id="email" name="email" class="input-field" autocomplete="off" required />
                <label id="email">Email</label>
              </div>

              <div class="input-wrap">
                <input type="password" id="pswd" name="pswd" minlength="4" class="input-field" autocomplete="off" required />
                <label id="pswd">Password</label>
              </div>

              <input type="submit" id="register" name="register" value="Sign Up" class="sign-btn" />
              <p class="text">
                By signing up, I agree to the
                <a href="#">Terms of Services</a> and
                <a href="#">Privacy Policy</a>
              </p>
            </div>
          </form>


        </div>

        <div class="carousel">
          <div class="images-wrapper">
            <img src="./img/image1.jpg" class="image img-1 show" alt="" />
            <img src="./img/image2.jpg" class="image img-2" alt="" />
            <img src="./img/image3.jpgs" class="image img-3" alt="" />
          </div>

          <div class="text-slider">
            <div class="text-wrap">
              <div class="text-group">
                <h2>Ride & Save together.</h2>
                <h2>Carpool your way!</h2>
                <h2>Commute made easy.</h2>
              </div>
            </div>

            <div class="bullets">
              <span class="active" data-value="1"></span>
              <span data-value="2"></span>
              <span data-value="3"></span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>


  <!-- Javascript file -->

  <script src="js/app.js"></script>
</body>

</html>