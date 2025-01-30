<?php
session_start();
include "connect.php";
// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
    $user_id = $_SESSION['user_id']; // Assuming user is logged in
    
    // Remove product from cart
    $query = "DELETE FROM cart WHERE user_id = '$user_id' AND product_id = '$product_id'";

    if ($con->query($query)) {
        header("Location: cart.php"); // Redirect to cart page after removal
    } else {
        echo "Error removing product from cart.";
    }
} else {
    echo "Product ID is required!";
}
?>
