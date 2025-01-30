<?php
session_start();

// Check if the user is logged in, if not, redirect to index.php
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit(); // Stop further script execution
}

// Fetch the user data from the session
$user_id = $_SESSION['user_id'];

// Include database connection
include "connect.php";

// Get the seller's products from the database
$query = "SELECT * FROM products WHERE user_id = '$user_id'";
$result = mysqli_query($con, $query);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="sellerstyle.css">
    <link rel="shortcut icon" type="x-icon" href="logo.webp">
    <title>Product Management</title>
</head>

<body>
    <header>
        <h1>Product Management</h1>
        <nav>
            <a href="seller.php">Home</a>
            <a href="product_management.php">Product Management</a>
            <a href="order_history.php">Order History</a>
            <a href="account_settings.php">Account Settings</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>

    <main>
        <div class="container">
            <div class="product-list">
                <h2>Your Products</h2>
                <?php
                if (mysqli_num_rows($result) > 0) {
                    echo '<table>';
                    echo '<tr><th>Sr. No.</th><th>Name</th><th>Description</th><th>Price</th><th>Total Stock</th><th>Actions</th></tr>';
                    $i = 1;
                    while ($row = mysqli_fetch_assoc($result)) {
                        // Calculate the total stock based on the individual size stocks
                        $total_stock = (int) $row['size1_stock'] + (int) $row['size2_stock'] + (int) $row['size3_stock'];

                        echo '<tr>';
                        echo '<td>' . $i . '</td>';
                        echo '<td>' . htmlspecialchars($row['name']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['description']) . '</td>';
                        echo '<td>$' . number_format($row['price'], 2) . '</td>';
                        echo '<td>' . $total_stock . '</td>';
                        echo '<td><a href="edit_product.php?id=' . $row['id'] . '">Edit</a> | <a href="delete_product.php?id=' . $row['id'] . '">Delete</a></td>';
                        echo '</tr>';
                        $i++;
                    }
                    echo '</table>';
                } else {
                    echo '<p class="empty-message">No products listed yet. Start adding some!</p>';
                }
                ?>
            </div>

            <div class="add-product">
                <h2>Add New Product</h2>
                <form action="add_product.php" method="POST" autocomplete="off" enctype="multipart/form-data">
                    <label for="name">Product Name:</label>
                    <input type="text" id="name" name="name" placeholder="Enter Product Name" required>

                    <label for="description">Description:</label>
                    <textarea id="description" name="description" rows="4" placeholder="Enter Product Description" required></textarea>

                    <label for="price">Price:</label>
                    <input type="number" id="price" name="price" placeholder="Enter Product Price" required min="1" step="1">

                    <label for="size1">Size 1:</label>
                    <input type="text" id="size1" name="size1" placeholder="Enter Size 1">

                    <label for="size1_stock">Stock for Size 1:</label>
                    <input type="number" id="size1_stock" name="size1_stock" placeholder="Enter Stock for Size 1" min="0">

                    <label for="size2">Size 2:</label>
                    <input type="text" id="size2" name="size2" placeholder="Enter Size 2">

                    <label for="size2_stock">Stock for Size 2:</label>
                    <input type="number" id="size2_stock" name="size2_stock" placeholder="Enter Stock for Size 2" min="0">

                    <label for="size3">Size 3:</label>
                    <input type="text" id="size3" name="size3" placeholder="Enter Size 3">

                    <label for="size3_stock">Stock for Size 3:</label>
                    <input type="number" id="size3_stock" name="size3_stock" placeholder="Enter Stock for Size 3" min="0">

                    <label for="image1">Product Image 1:</label>
                    <input type="file" id="image1" name="image1" required>

                    <label for="image2">Product Image 2:</label>
                    <input type="file" id="image2" name="image2" required>

                    <label for="image3">Product Image 3:</label>
                    <input type="file" id="image3" name="image3" required>

                    <button type="submit" name="submit_product">Add Product</button>
                </form>
            </div>
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