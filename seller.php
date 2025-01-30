<?php
session_start();

// Include the database connection file
include('connect.php');

// Check if the user is logged in, if not, redirect to index.php
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit(); // Make sure to stop further script execution
}

// Fetch the user data from the session
$user_id = $_SESSION['user_id'];  // Assuming the user_id is stored in session

// Fetch total sales (sum of total_amount from orders)
$sales_query = "SELECT SUM(total_amount) as total_sales FROM orders WHERE user_id = ? AND order_status = 'Delivered'";
$stmt = $con->prepare($sales_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($total_sales);
$stmt->fetch();
$stmt->close();

// Fetch products listed
$products_query = "SELECT COUNT(*) as products_count FROM products WHERE user_id = ?";
$stmt = $con->prepare($products_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($products_count);
$stmt->fetch();
$stmt->close();

// Fetch pending orders
$orders_query = "SELECT COUNT(*) as pending_orders FROM orders WHERE user_id = ? AND order_status != 'Delivered'";
$stmt = $con->prepare($orders_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($pending_orders);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="sellerstyle.css">
    <link rel="shortcut icon" type="x-icon" href="logo.webp">
    <title>Hello Seller</title>
</head>

<body>
    <header>
        <a href="home.php" style="text-decoration: none; color: inherit;">
            <h1 id="fixed-typing-effect">Hello Seller! Welcome to BlinkBasket</h1>
        </a>
        <nav>
            <a href="seller.php">Home</a>
            <a href="product_management.php">Product Management</a>
            <a href="order_history.php">Order History</a>
            <a href="account_settings.php">Account Settings</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>
    <main>
        <div class="dashboard">
            <div class="card">
                <a href="seller.php">
                    <h2>Total Sales</h2>
                    <p>$<?php echo number_format($total_sales, 2); ?></p>
                </a>
            </div>
            <div class="card">
                <a href="product_management.php">
                    <h2>Products Listed</h2>
                    <p><?php echo $products_count; ?></p>
                </a>
            </div>
            <div class="card">
                <a href="order_history.php">
                    <h2>Pending Orders</h2>
                    <p><?php echo $pending_orders; ?></p>
                </a>
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; 2025 E-commerce Platform | All Rights Reserved</p>
    </footer>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const textElement = document.getElementById("fixed-typing-effect");
            const text = textElement.textContent; // Original text
            textElement.textContent = ''; // Clear the content
            textElement.style.visibility = "visible"; // Make the container visible

            let index = 0;

            const typeLetter = () => {
                if (index < text.length) {
                    textElement.textContent += text[index]; // Add the next letter
                    index++;
                    setTimeout(typeLetter, 100); // Adjust typing speed (100ms)
                }
            };

            typeLetter(); // Start the typing animation
        });
    </script>
</body>

</html>