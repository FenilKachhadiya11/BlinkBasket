<?php
// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);

    // Mailtrap SMTP settings
    $host = 'smtp.mailtrap.io';
    $port = 587;
    $username = 'your_mailtrap_username';
    $password = 'your_mailtrap_password';

    $to = 'blinkbasketcustomercare@gmail.com';
    $subject = 'New Message from Contact Us Form';
    $body = "
    <html>
    <head>
        <title>$subject</title>
    </head>
    <body>
        <p><strong>Name:</strong> $name</p>
        <p><strong>Email:</strong> $email</p>
        <p><strong>Message:</strong></p>
        <p>$message</p>
    </body>
    </html>
    ";

    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8" . "\r\n";
    $headers .= "From: $email" . "\r\n";

    // Send email using SMTP
    $smtp = fsockopen($host, $port);
    if ($smtp) {
        fputs($smtp, "HELO " . $host . "\r\n");
        fputs($smtp, "AUTH LOGIN\r\n");
        fputs($smtp, base64_encode($username) . "\r\n");
        fputs($smtp, base64_encode($password) . "\r\n");
        fputs($smtp, "MAIL FROM: <$email>\r\n");
        fputs($smtp, "RCPT TO: <$to>\r\n");
        fputs($smtp, "DATA\r\n");
        fputs($smtp, $body . "\r\n");
        fputs($smtp, ".\r\n");
        fputs($smtp, "QUIT\r\n");
        fclose($smtp);
        echo "<script>alert('Message sent successfully'); window.location.href = 'contact.html';</script>";
    } else {
        echo "<script>alert('Error sending email. Please try again later.'); window.location.href = 'contact.html';</script>";
    }
}
?>
