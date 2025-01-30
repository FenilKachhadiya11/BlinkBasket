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

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Delete the product from the database
    $query = "DELETE FROM products WHERE id = '$product_id' AND user_id = '" . $_SESSION['user_id'] . "'";
    if (mysqli_query($con, $query)) {
        echo "<script>alert('Product deleted successfully!');window.location.href='product_management.php';</script>";
    } else {
        echo "<script>alert('Error deleting product. Please try again.'); window.location.href='product_management.php';</script>";
    }
}

mysqli_close($con);
?>
