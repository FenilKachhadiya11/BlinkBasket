<?php
session_start();
include "connect.php";
include "header.php"; // Include header file

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Check if order_id is passed in the URL
if (!isset($_GET['order_id'])) {
    echo "<p>Invalid order ID.</p>";
    exit;
}

$order_id = $_GET['order_id'];

// Fetch the order details
$orderQuery = "SELECT * FROM orders WHERE id = '$order_id' AND user_id = '$user_id'";
$orderResult = $con->query($orderQuery);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css'>
    <link rel='stylesheet' href='buyerstyle.css'>
    <link rel='shortcut icon' type='x-icon' href='logo.webp'>
    <title>Order Details</title>
    <style>
        .order-details {
            max-width: 800px;
            margin: 30px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border: 1px solid #ddd;
        }

        .order-details h2 {
            font-size: 24px;
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }

        .order-details .info {
            font-size: 16px;
            margin-bottom: 15px;
        }

        .order-details .info span {
            font-weight: bold;
        }

        .order-item-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        .order-item-table th,
        .order-item-table td {
            padding: 10px;
            border: 1px solid #ddd;
        }

        .order-item-table th {
            background-color: #f39c12;
            color: white;
        }

        .order-item-table td {
            background-color: #f9f9f9;
        }
    </style>
</head>

<body>
    <div class="order-details">
        <h2>Order Details</h2>
        <?php
        if ($orderResult->num_rows > 0) {
            $order = $orderResult->fetch_assoc();
            $total_amount = $order['total_amount'];
            $order_status = $order['order_status'];
            $full_name = $order['full_name'];
            $delivery_address = $order['delivery_address'];
            $email = $order['email'];
            $phone_no = $order['phone_no'];
            $order_date = date("d-m-Y", strtotime($order['order_date']));

            echo "<div class='info'>
                    <p><span>Order ID:</span> $order_id</p>
                    <p><span>Order Date:</span> $order_date</p>
                    <p><span>Status:</span> $order_status</p>
                    <p><span>Total Amount:</span> ₹$total_amount</p>
                </div>
                <div class='info'>
                    <p><span>Full Name:</span> $full_name</p>
                    <p><span>Email:</span> $email</p>
                    <p><span>Phone Number:</span> $phone_no</p>
                    <p><span>Delivery Address:</span> $delivery_address</p>
                </div>";

            // Fetch the order items
            $orderItemsQuery = "SELECT oi.*, p.name, oi.size FROM order_items oi 
                                JOIN products p ON oi.product_id = p.id
                                WHERE oi.order_id = '$order_id'";
            $orderItemsResult = $con->query($orderItemsQuery);

            if ($orderItemsResult->num_rows > 0) {
                echo "<h3>Items in this Order</h3>
                      <table class='order-item-table'>
                          <thead>
                              <tr>
                                  <th>Product Name</th>
                                  <th>Size</th>
                                  <th>Quantity</th>
                                  <th>Subtotal</th>
                              </tr>
                          </thead>
                          <tbody>";
                while ($item = $orderItemsResult->fetch_assoc()) {
                    echo "<tr>
                            <td>{$item['name']}</td>
                            <td>{$item['size']}</td>
                            <td>{$item['quantity']}</td>
                            <td>₹{$item['subtotal']}</td>
                        </tr>";
                }
                echo "</tbody>
                    </table>";
            }
        } else {
            echo "<tr><td colspan='5' style='text-align: center;'>Order not found or you do not have access to this order. <a href='buyer.php'>Browse products</a> to place an order.</td></tr>";
        }
        ?>
    </div>
</body>

</html>
<?php include 'footer.php'; // Include footer ?>
