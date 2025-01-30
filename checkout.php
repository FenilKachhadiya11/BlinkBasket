<?php
session_start();
include "connect.php";
include "header.php";
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer autoload file
require 'vendor/autoload.php';

// Include Endroid QR Code Library
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
$user_id = $_SESSION['user_id']; // Assuming user is logged in

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Capture user details
    $full_name = $con->real_escape_string($_POST['full_name']);
    $delivery_address = $con->real_escape_string($_POST['delivery_address']);
    $email = $con->real_escape_string($_POST['email']);
    $phone_no = $con->real_escape_string($_POST['phone_no']);
    $payment_method = $_POST['payment_method']; // Payment method selected

    // Validate user details
    if (empty($full_name) || empty($delivery_address) || empty($email) || empty($phone_no) || empty($payment_method)) {
        echo "<script>alert('All fields are required. Please fill in the form completely.');</script>";
        exit;
    }

    // Fetch items from the cart
    $query = "SELECT c.*, p.name, p.price, p.size1_stock, p.size2_stock, p.size3_stock, p.size1, p.size2, p.size3 
              FROM cart c 
              JOIN products p ON c.product_id = p.id 
              WHERE c.user_id = '$user_id'";
    $result = $con->query($query);

    if ($result->num_rows > 0) {
        $orderSuccess = true;
        $totalAmount = 0;
        $orderItems = [];

        while ($row = $result->fetch_assoc()) {
            $product_id = $row['product_id'];
            $quantity = $row['quantity'];
            $size = $row['size'];
            $price = $row['price'];
            $subtotal = $price * $quantity;
            $totalAmount += $subtotal;

            // Validate stock
            $sizeStockColumn = '';
            if ($size === $row['size1']) {
                $sizeStockColumn = 'size1_stock';
            } elseif ($size === $row['size2']) {
                $sizeStockColumn = 'size2_stock';
            } elseif ($size === $row['size3']) {
                $sizeStockColumn = 'size3_stock';
            }

            if ($sizeStockColumn && $row[$sizeStockColumn] >= $quantity) {
                // Deduct stock
                $updateStockQuery = "UPDATE products SET $sizeStockColumn = $sizeStockColumn - $quantity WHERE id = '$product_id'";
                if (!$con->query($updateStockQuery)) {
                    $orderSuccess = false;
                    echo "<script>alert('Error updating stock for product: {$row['name']}'); window.location.href='buyer.php';</script>";
                    break;
                }

                // Save order item details
                $orderItems[] = [
                    'product_id' => $product_id,
                    'quantity' => $quantity,
                    'subtotal' => $subtotal,
                    'size' => $size
                ];
            } else {
                $orderSuccess = false;
                echo "<script>alert('Insufficient stock for product: {$row['name']}'); window.location.href='buyer.php';</script>";
                break;
            }
        }

        if ($orderSuccess) {
            // Handle payment method
            if ($payment_method === 'online') {
                // Generate a QR code for online payment
                $payment_link = "https://paymentgateway.com/online?order_id=" . $order_id . "&amount=" . $totalAmount; // Sample payment link
                $qrCode = new QrCode($payment_link);
                $writer = new PngWriter();

                // Write the QR code to a string
                $result = $writer->write($qrCode);
                $qrCodeImage = $result->getString();

                // Send email with PHPMailer
                $mail = new PHPMailer(true);
                try {
                    // Server settings
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com'; // Set mail server (e.g., Gmail)
                    $mail->SMTPAuth = true;
                    $mail->Username = 'blinkbasketcustomercare@gmail.com'; // SMTP username
                    $mail->Password = 'dqwcqplhdesekvgg'; // SMTP password
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;

                    // Recipients
                    $mail->setFrom('blinkbasketcustomercare@gmail.com', 'BlinkBasket');
                    $mail->addAddress($email, $full_name); // Add the recipient's email

                    // Attach the QR code image (without saving it to the server)
                    $mail->addStringAttachment($qrCodeImage, 'payment_qr_code.png', 'base64', 'image/png');

                    // Content
                    $mail->isHTML(true);
                    $mail->Subject = 'Your Order Details';
                    $mail->Body = "
                        <h2>Order Confirmation</h2>
                        <p>Thank you for your order, $full_name!</p>
                        <p><strong>Order ID:</strong> $order_id</p>
                        <p><strong>Total Amount:</strong> $totalAmount</p>
                        <p><strong>Payment Method:</strong> Online</p>
                        <p>Click the link below to make the payment:</p>
                        <p><a href='$payment_link'>$payment_link</a></p>
                        <p>Once your payment is successful, your order will be processed.</p>
                        <p>Please scan the attached QR code to proceed with the payment.</p>
                    ";

                    // Send the email
                    $mail->send();
                    echo "<script>alert('Order placed successfully! Please check your email for payment details.'); window.location.href = 'orders.php';</script>";
                } catch (Exception $e) {
                    echo "<script>alert('Message could not be sent. Mailer Error: {$mail->ErrorInfo}');</script>";
                }
                $clearCartQuery = "DELETE FROM cart WHERE user_id = '$user_id'";
                $con->query($clearCartQuery);
            } elseif ($payment_method === 'cod') {
                // Insert order into `orders` table for COD
                $insertOrderQuery = "INSERT INTO orders (user_id, total_amount, order_status, payment_method, full_name, delivery_address, email, phone_no) 
                                     VALUES ('$user_id', '$totalAmount', 'Pending', 'COD', '$full_name', '$delivery_address', '$email', '$phone_no')";
                if ($con->query($insertOrderQuery)) {
                    $order_id = $con->insert_id;

                    // Insert each item into order items for COD
                    foreach ($orderItems as $item) {
                        $insertItemQuery = "INSERT INTO order_items (order_id, product_id, quantity, size, subtotal) 
                                            VALUES ('$order_id', '{$item['product_id']}', '{$item['quantity']}', '{$item['size']}', '{$item['subtotal']}')";
                        if (!$con->query($insertItemQuery)) {
                            echo "<script>alert('Error adding item to order. Please try again.'); window.location.href='buyer.php';</script>";
                            break;
                        }
                    }

                    $clearCartQuery = "DELETE FROM cart WHERE user_id = '$user_id'";
                    $con->query($clearCartQuery);

                    echo "<script>alert('Order placed successfully! You can pay on delivery.'); window.location.href = 'orders.php';</script>";
                }
            }
        }
    } else {
        echo "<script>alert('Your cart is empty. Please browse products.'); window.location.href='buyer.php';</script>";
    }
} else {
    // Display form for user details
    echo "
    <html>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <link rel='shortcut icon' type='x-icon' href='logo.webp'>
            <title>Checkout</title>
        </head>

        <body>
            <style>
                .checkout-form {
                    max-width: 600px;
                    margin: 30px auto;
                    padding: 20px;
                    background-color: #fff;
                    border-radius: 10px;
                    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                    border: 1px solid #ddd;
                }

                .checkout-form h2 {
                    font-size: 24px;
                    color: #333;
                    margin-bottom: 20px;
                    text-align: center;
                }

                .checkout-form label {
                    font-size: 16px;
                    color: #555;
                    margin-bottom: 8px;
                    display: block;
                }

                .checkout-form .form-input {
                    width: 100%;
                    padding: 12px;
                    margin-bottom: 20px;
                    border: 1px solid #ccc;
                    border-radius: 5px;
                    font-size: 16px;
                    box-sizing: border-box;
                }

                .checkout-form textarea.form-input {
                    resize: vertical;
                }

                .checkout-form .submit-btn {
                    background-color: #f39c12;
                    color: white;
                    padding: 15px 25px;
                    font-size: 16px;
                    border: none;
                    border-radius: 5px;
                    cursor: pointer;
                    width: 100%;
                    text-align: center;
                    transition: background 0.3s;
                }

                .checkout-form .submit-btn:hover {
                    background-color: #f39d12dd;
                }
            </style>
            <form method='POST' action='' class='checkout-form'>
                <h2>Enter Delivery Details</h2>
                <label for='full_name'>Full Name:</label>
                <input type='text' name='full_name' id='full_name' required class='form-input' placeholder='Enter your Full Name'><br>

                <label for='delivery_address'>Delivery Address:</label>
                <textarea name='delivery_address' id='delivery_address' required class='form-input' placeholder='Enter your Delivery Address'></textarea><br>

                <label for='email'>Email:</label>
                <input type='email' name='email' id='email' required class='form-input' placeholder='Enter your Email Address'><br>

                <label for='phone_no'>Phone Number:</label>
                <input type='text' name='phone_no' id='phone_no' required class='form-input' pattern='^\d{10}$' title='Phone number must be 10 digits' placeholder='Enter your Mobile Number'><br>

                <h3>Select Payment Method</h3>
                <label for='payment_method'>Choose Payment Method:</label>
                <select name='payment_method' id='payment_method' required class='form-input'>
                    <option value='cod'>Cash on Delivery</option>
                    <option value='online'>Online</option>
                </select><br>

                <button type='submit' class='submit-btn'>Place Order</button>
            </form>
        </body>
    </html>";
}
include 'footer.php';
?>