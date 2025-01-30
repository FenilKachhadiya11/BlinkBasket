<?php
session_start();
include "connect.php";
include "header.php"; // This includes the header

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch the user's orders
$query = "SELECT * FROM orders WHERE user_id = '$user_id' ORDER BY order_date DESC";
$result = $con->query($query);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="buyerstyle.css">
    <link rel="shortcut icon" type="x-icon" href="logo.webp">
    <title>Your Orders</title>
    <style>
        .order-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        .order-table th,
        .order-table td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .order-table th {
            background-color: #f39c12;
            color: white;
        }

        .order-table td {
            background-color: #f9f9f9;
        }

        .order-header {
            font-size: 24px;
            margin: 20px 0;
            text-align: center;
        }

        .order-item-table {
            width: 100%;
            margin: 20px 0;
            border-collapse: collapse;
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
    </style>
</head>

<body>
    <div class='order-header'>
        <h2>Your Orders</h2>
    </div>
    <table class='order-table'>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Total Amount</th>
                <th>Status</th>
                <th>Placed On</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $order_id = $row['id'];
                    $total_amount = $row['total_amount'];
                    $order_status = $row['order_status'];
                    $order_date = date("d-m-Y", strtotime($row['order_date']));

                    echo "<tr>
                        <td>$order_id</td>
                        <td>₹$total_amount</td>
                        <td>$order_status</td>
                        <td>$order_date</td>
                        <td><a href='view_order.php?order_id=$order_id'>View Order</a></td>
                    </tr>";

                    // Fetch the items for each order
                    $orderItemsQuery = "SELECT oi.*, p.name, oi.size FROM order_items oi 
                                        JOIN products p ON oi.product_id = p.id
                                        WHERE oi.order_id = '$order_id'";
                    $orderItemsResult = $con->query($orderItemsQuery);

                    if ($orderItemsResult->num_rows > 0) {
                        echo "<tr>
                            <td colspan='5'>
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
                                </table>
                            </td>
                        </tr>";
                    }
                }
            } else {
                echo "<tr><td colspan='5' style='text-align: center;'>You don't have any orders yet. <a href='buyer.php'>Browse products</a> to place an order.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>

</html>
<?php include "footer.php"; // This includes the footer ?>
