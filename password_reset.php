<?php
include "connect.php";
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';  // Load PHPMailer

// Set the time zone to your preferred time zone (example: 'Asia/Kolkata' for Indian Standard Time)
date_default_timezone_set('Asia/Kolkata'); // Change to your desired time zone

// Generate OTP function
function generateOtp($length = 6)
{
    $characters = '0123456789';
    $otp = '';
    for ($i = 0; $i < $length; $i++) {
        $otp .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $otp;
}

// Check if the email is submitted for OTP generation
if (isset($_POST['submit_email'])) {
    $email = mysqli_real_escape_string($con, $_POST['email']);

    // Check if the email exists in the database
    $query = "SELECT * FROM login WHERE email='$email'";
    $result = mysqli_query($con, $query);

    if (mysqli_num_rows($result) > 0) {
        // Generate OTP
        $otp = generateOtp();
        $expiry = date("Y-m-d H:i:s", strtotime('+10 minutes')); // OTP expires in 10 minutes

        // Update OTP and expiration in the database
        $updateQuery = "UPDATE login SET reset_token='$otp', reset_token_expiration='$expiry' WHERE email='$email'";
        if (mysqli_query($con, $updateQuery)) {
            // Send OTP to the user via email
            $mail = new PHPMailer(true);
            try {
                //Server settings
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'blinkbasketcustomercare@gmail.com'; // SMTP username
                $mail->Password = 'dqwcqplhdesekvgg'; // SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                //Recipients
                $mail->setFrom('blinkbasketcustomercare@gmail.com', 'Blink Basket');
                $mail->addAddress($email);

                // Content
                $mail->isHTML(true);
                $mail->Subject = 'Password Reset OTP';
                $mail->Body = 'Your OTP to reset your password is: <strong>' . $otp . '</strong>';

                $mail->send();
                echo "<script>alert('OTP has been sent to your email.'); window.location.href='password_reset.php?step=verify&email=$email';</script>";

            } catch (Exception $e) {
                echo "<script>alert('Message could not be sent. Mailer Error: {$mail->ErrorInfo}');</script>";
            }
        } else {
            echo "<script>alert('Error updating OTP.');</script>";
        }
    } else {
        echo "<script>alert('No account found with this email address.');</script>";
    }
}

// Check if OTP is submitted for verification
if (isset($_POST['verify_otp'])) {
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $otp = mysqli_real_escape_string($con, $_POST['otp']);

    // Check if the OTP is valid and not expired
    $query = "SELECT * FROM login WHERE email='$email' AND reset_token='$otp' AND reset_token_expiration > NOW()";
    $result = mysqli_query($con, $query);

    if (mysqli_num_rows($result) > 0) {
        // OTP is valid, now allow the user to reset their password
        echo "<script>alert('OTP verified successfully. Now, you can reset your password.'); window.location.href='password_reset.php?step=reset&email=$email&token=$otp';</script>";
    } else {
        echo "<script>alert('Invalid OTP or OTP has expired.');</script>";
    }
}

// Check if the password reset is submitted
if (isset($_POST['reset_password'])) {
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $new_password = mysqli_real_escape_string($con, $_POST['new_password']);
    $token = mysqli_real_escape_string($con, $_POST['token']);

    // Server-side password validation for reset
    $passwordPattern = '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/'; // Min 8 characters, 1 number, 1 lowercase, 1 uppercase
    if (!preg_match($passwordPattern, $new_password)) {
        echo "<script>alert('Password must be at least 8 characters long and include at least one number, one lowercase letter, and one uppercase letter.');</script>";
        exit();
    }
    
    // Hash the new password before storing it
    $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

    // Update password with hashed password, and clear reset token and expiration
    $update_query = "UPDATE login SET password='$hashed_password', reset_token=NULL, reset_token_expiration=NULL WHERE reset_token='$token' AND email='$email'";

    if (mysqli_query($con, $update_query)) {
        echo "<script>alert('Password reset successful! You can now login.'); window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('Error resetting password. Try again later.');</script>";
    }
}

mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
    <link rel="shorcut icon" type="x-icon" href="logo.webp">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="wrapper">
        <span class="rotate-bg"></span>

        <div class="form-box pwd">
            <?php if (!isset($_POST['step'])) { ?>
                <h2>Enter your email to receive OTP</h2>
                <form method="POST">
                    <div class="input-box">
                        <input type="email" name="email" required placeholder="Enter your email">
                    </div>
                    <button type="submit" name="submit_email">Send OTP</button>
                    <!-- Back Button -->
                    <button type="button" onclick="window.location.href='index.php';" style="margin-top: 10px;">Back</button>
                </form>
            <?php } elseif ($_POST['step'] == 'verify') { ?>
                <h2>Enter OTP sent to your email</h2>
                <form method="POST">
                    <div class="input-box">
                        <input type="email" name="email" required placeholder="Enter your email"
                            value="<?php echo $_POST['email']; ?>" readonly>
                    </div>
                    <div class="input-box">
                        <input type="text" name="otp" required placeholder="Enter OTP">
                    </div>
                    <button type="submit" name="verify_otp">Verify OTP</button>
                </form>
            <?php } elseif ($_POST['step'] == 'reset') { ?>
                <h2>Enter your new password</h2>
                <form method="POST">
                    <div class="input-box">
                        <input type="email" name="email" required placeholder="Enter your email"
                            value="<?php echo $_POST['email']; ?>" readonly>
                    </div>
                    <div class="input-box">
                        <input type="password" name="new_password" required placeholder="New Password" minlength="6">
                    </div>
                    <input type="hidden" name="token" value="<?php echo $_POST['token']; ?>">
                    <button type="submit" name="reset_password">Reset Password</button>
                </form>
            <?php } ?>
        </div>

        <div class="info-text login">
            <h2 class="animation" style="--i:0; --j:20">Welcome Back!</h2>
            <p class="animation" style="--i:1; --j:21">Enjoy Online Shopping with the help of BlinkBasket.</p>
        </div>
    </div>
</body>

</html>