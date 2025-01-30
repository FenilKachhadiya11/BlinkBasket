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

// Check if the product ID is set
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Fetch the product data
    $query = "SELECT * FROM products WHERE id = '$product_id' AND user_id = '$user_id'";
    $result = mysqli_query($con, $query);
    $product = mysqli_fetch_assoc($result);

    // If product exists, display the form
    if ($product) {
        $images = explode(',', $product['image_urls']);
        echo '<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="sellerstyle.css">
            <link rel="shortcut icon" type="x-icon" href="logo.webp">
            <title>Edit Product</title>
        </head>
        <body>
            <header>
                <h1>Edit Product</h1>
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
                    <div class="add-product">
                        <h2>Edit Product: ' . htmlspecialchars($product['name']) . '</h2>
                        <form action="update_product.php" method="POST" autocomplete="off" enctype="multipart/form-data">
                            <input type="hidden" name="product_id" value="' . $product['id'] . '">
                            
                            <label for="name">Product Name:</label>
                            <input type="text" id="name" name="name" value="' . htmlspecialchars($product['name']) . '" required>

                            <label for="description">Description:</label>
                            <textarea id="description" name="description" rows="4" required>' . htmlspecialchars($product['description']) . '</textarea>

                            <label for="price">Price:</label>
                            <input type="number" id="price" name="price" value="' . $product['price'] . '" required min="0.01" step="0.01">

                            <label for="size1">Size 1:</label>
                            <input type="text" id="size1" name="size1" value="' . htmlspecialchars($product['size1']) . '">

                            <label for="size1_stock">Stock for Size 1:</label>
                            <input type="number" id="size1_stock" name="size1_stock" value="' . $product['size1_stock'] . '" min="0">

                            <label for="size2">Size 2:</label>
                            <input type="text" id="size2" name="size2" value="' . htmlspecialchars($product['size2']) . '">

                            <label for="size2_stock">Stock for Size 2:</label>
                            <input type="number" id="size2_stock" name="size2_stock" value="' . $product['size2_stock'] . '" min="0">

                            <label for="size3">Size 3:</label>
                            <input type="text" id="size3" name="size3" value="' . htmlspecialchars($product['size3']) . '">

                            <label for="size3_stock">Stock for Size 3:</label>
                            <input type="number" id="size3_stock" name="size3_stock" value="' . $product['size3_stock'] . '" min="0">

                            <label for="image1">Product Image 1:</label>
                            <input type="file" id="image1" name="image1">

                            <label for="image2">Product Image 2:</label>
                            <input type="file" id="image2" name="image2">

                            <label for="image3">Product Image 3:</label>
                            <input type="file" id="image3" name="image3">';
                            
                            // Display current images
                            for ($i = 0; $i < 3; $i++) {
                                if (isset($images[$i])) {
                                    echo '<p>Current Image ' . ($i + 1) . ': <a href="' . $images[$i] . '" target="_blank">View Image</a></p>';
                                }
                            }

                            echo '<button type="submit" name="update_product">Update Product</button>
                        </form>
                    </div>
                </div>
            </main>

            <footer>
                <p>&copy; 2025 E-commerce Platform | All Rights Reserved</p>
            </footer>
        </body>
        </html>';
    } else {
        echo 'script>alert("Product not found."); window.location.href="product_management.php";</script>';
    }
}

mysqli_close($con);
?>
