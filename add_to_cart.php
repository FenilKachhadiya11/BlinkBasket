<?php
session_start(); // Start the session
include "connect.php"; // Include the database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Get user ID from session
$user_id = $_SESSION['user_id'];

// Get product details from POST request
$product_id = isset($_POST['product_id']) ? (int) $_POST['product_id'] : 0;
$size = isset($_POST['size']) ? $_POST['size'] : '';
$quantity = isset($_POST['quantity']) ? (int) $_POST['quantity'] : 0;

if ($product_id > 0 && $quantity > 0) {
    // Check if product already exists in the cart for the user
    $checkQuery = "SELECT * FROM cart WHERE user_id = '$user_id' AND product_id = '$product_id' AND size = '$size'";
    $checkResult = $con->query($checkQuery);

    if ($checkResult && $checkResult->num_rows > 0) {
        // If the product exists in the cart, update the quantity
        $row = $checkResult->fetch_assoc();
        $newQuantity = $row['quantity'] + $quantity;
        $updateQuery = "UPDATE cart SET quantity = '$newQuantity' WHERE id = " . $row['id'];
        if ($con->query($updateQuery)) {
            echo "Product quantity updated in your cart.";
        } else {
            echo "Error updating cart.";
        }
    } else {
        // If the product doesn't exist in the cart, insert a new entry
        $insertQuery = "INSERT INTO cart (user_id, product_id, size, quantity) VALUES ('$user_id', '$product_id', '$size', '$quantity')";
        if ($con->query($insertQuery)) {
            echo "Product added to your cart.";
        } else {
            echo "Error adding product to cart.";
        }
    }
} else {
    echo "Invalid product or quantity.";
}
?>