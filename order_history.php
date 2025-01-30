<?php
session_start();

// Check if the user is logged in, if not, redirect to index.php
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Fetch the user data from the session
$user_id = $_SESSION['user_id'];

// Include database connection
include "connect.php";

// Query to fetch the order history for the seller, excluding delivered orders
$query = "SELECT o.id AS order_id, o.order_date, p.name AS product_name, oi.quantity, oi.subtotal, o.order_status, 
                 u.user AS buyer_name, u.email AS buyer_email
          FROM orders o
          JOIN order_items oi ON o.id = oi.order_id
          JOIN products p ON oi.product_id = p.id
          JOIN login u ON o.user_id = u.id
          WHERE p.user_id = '$user_id' AND o.order_status != 'Delivered'  -- Exclude delivered orders
          ORDER BY o.order_date DESC";

// Execute the query
$result = mysqli_query($con, $query);

// Check if the query execution failed
if (!$result) {
    echo "<script>alert('Error: " . mysqli_error($con) . "');</script>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="x-icon" href="logo.webp">
    <title>Order History</title>
    <link rel="stylesheet" href="sellerstyle.css">
</head>

<body>
    <header>
        <h1>Order History</h1>
        <nav>
            <a href="seller.php">Home</a>
            <a href="product_management.php">Product Management</a>
            <a href="order_history.php">Order History</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>
    <main>
        <div class="container" style="max-width: 90vw;">
            <h2>Your Orders</h2>

            <?php
            // Check if there are orders for the seller
            if (mysqli_num_rows($result) > 0) {
                echo '<table style="width: 100%; table-layout: fixed; word-wrap: break-word;">';
                echo '<tr><th>Order ID</th><th>Order Date</th><th>Product Name</th><th>Quantity</th><th>Total Amount</th><th>Order Status</th><th>Buyer Name</th><th>Buyer Email</th><th>Update Status</th></tr>';

                // Fetch and display each order and its products
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($row['order_id']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['order_date']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['product_name']) . '</td>';
                    echo '<td>' . (int) $row['quantity'] . '</td>';
                    echo '<td>$' . number_format($row['subtotal'], 2) . '</td>';
                    echo '<td>' . htmlspecialchars($row['order_status']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['buyer_name']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['buyer_email']) . '</td>';
                    echo '<td>';

                    // Display the status update form for non-delivered orders
                    ?>
                    <form method="POST" action="update_status.php">
                        <input type="hidden" name="order_id" value="<?= htmlspecialchars($row['order_id']) ?>">
                        <input type="hidden" name="buyer_email" value="<?= htmlspecialchars($row['buyer_email']) ?>">
                        <select name="status" required>
                            <option value="" disabled selected>Select Status</option>
                            <?php
                            $statuses = ["Pending", "Order Confirmed", "Shipped", "Out for Delivery", "Delivered"];
                            $current_status = $row['order_status'];
                            $valid_statuses = [];

                            // Define valid status flow based on current status
                            switch ($current_status) {
                                case "Pending":
                                    $valid_statuses = ["Order Confirmed"];
                                    break;
                                case "Order Confirmed":
                                    $valid_statuses = ["Shipped"];
                                    break;
                                case "Shipped":
                                    $valid_statuses = ["Out for Delivery"];
                                    break;
                                case "Out for Delivery":
                                    $valid_statuses = ["Delivered"];
                                    break;
                                default:
                                    $valid_statuses = []; // No further status if already delivered
                                    break;
                            }

                            // Display the valid status options based on the current order status
                            foreach ($valid_statuses as $status) {
                                echo '<option value="' . $status . '">' . $status . '</option>';
                            }
                            ?>
                        </select>
                        <button type="submit">Update & Send Email</button>
                    </form>
                    <?php
                    echo '</td>';
                    echo '</tr>';
                }

                echo '</table>';
            } else {
                echo '<p class="empty-message">No orders have been placed yet.</p>';
            }
            ?>
        </div>
    </main>

    <footer>
        <p>&copy; 2025 E-commerce Platform | All Rights Reserved</p>
    </footer>
</body>

</html>

<?php
mysqli_close($con);
?>