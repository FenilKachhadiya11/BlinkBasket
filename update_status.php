<?php
session_start();
include "connect.php";
// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
// Include PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Ensure this path points to your PHPMailer autoload file

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = (int)$_POST['order_id'];
    $new_status = mysqli_real_escape_string($con, $_POST['status']);
    $buyer_email = mysqli_real_escape_string($con, $_POST['buyer_email']);

    // Update the order status in the database
    $update_query = "UPDATE orders SET order_status = '$new_status' WHERE id = $order_id";
    if (mysqli_query($con, $update_query)) {
        // Email logic using PHPMailer
        try {
            // Create a new PHPMailer instance
            $mail = new PHPMailer(true);

            // Set up the mailer
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'blinkbasketcustomercare@gmail.com'; // Your email
            $mail->Password = 'dqwcqplhdesekvgg'; // Your email password or app-specific password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Set the sender and recipient
            $mail->setFrom('blinkbasketcustomercare@gmail.com', 'BlinkBasket');
            $mail->addAddress($buyer_email); // Recipient's email

            // Set the subject and body
            $mail->Subject = 'Your Order Status Has Been Updated!';
            $mail->Body    = "Dear Customer,\n\nYour order (ID: $order_id) status has been updated to: $new_status.\n\nThank you for shopping with us!\n\nRegards,\nBlinkBasket Team";

            // Send the email
            if ($mail->send()) {
                echo "<script>alert('Status updated and email sent successfully.'); window.location.href = 'order_history.php';</script>";
            } else {
                echo "<script>alert('Status updated but failed to send email.'); window.location.href = 'order_history.php';</script>";
            }
        } catch (Exception $e) {
            echo "<script>alert('Failed to send email. Error: {$mail->ErrorInfo}'); window.location.href = 'order_history.php';</script>";
        }
    } else {
        echo "<script>alert('Failed to update status: " . mysqli_error($con) . "'); window.location.href = 'order_history.php';</script>";
    }
}
?>
