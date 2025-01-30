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

// Check if the form is submitted
if (isset($_POST['submit_product'])) {
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $description = mysqli_real_escape_string($con, $_POST['description']);
    $price = mysqli_real_escape_string($con, $_POST['price']);
    $size1 = mysqli_real_escape_string($con, $_POST['size1']);
    $size2 = mysqli_real_escape_string($con, $_POST['size2']);
    $size3 = mysqli_real_escape_string($con, $_POST['size3']);
    $size1_stock = (int) ($_POST['size1_stock'] ?? 0);
    $size2_stock = (int) ($_POST['size2_stock'] ?? 0);
    $size3_stock = (int) ($_POST['size3_stock'] ?? 0);
    $total_stock = $size1_stock + $size2_stock + $size3_stock;
    $user_id = $_SESSION['user_id'];  // The logged-in seller's ID

    // Handle file uploads for 3 images
    $image_urls = [];
    for ($i = 1; $i <= 3; $i++) {
        if (isset($_FILES["image$i"]) && $_FILES["image$i"]['error'] == 0) {
            $image_url = 'images/' . $_FILES["image$i"]['name'];
            move_uploaded_file($_FILES["image$i"]['tmp_name'], $image_url);
            $image_urls[] = $image_url;
        } else {
            echo "Error uploading image $i. Please ensure all images are uploaded correctly.";
            exit;
        }
    }

    // Convert the image URLs to a string, separated by commas
    $image_urls_str = implode(',', $image_urls);

    // Insert the product into the database
    $query = "INSERT INTO products (user_id, name, description, price, stock, size1, size2, size3, size1_stock, size2_stock, size3_stock, image_urls) 
          VALUES ('$user_id', '$name', '$description', '$price', '$total_stock', '$size1', '$size2', '$size3', '$size1_stock', '$size2_stock', '$size3_stock', '$image_urls_str')";

    if (mysqli_query($con, $query)) {
        echo "<script>alert('Product added successfully!'); window.location.href='product_management.php';</script>";
    } else {
        echo "<script>alert('Error: " . mysqli_error($con) . "'); window.location.href='product_management.php';</script>";
    }
}

mysqli_close($con);
?>