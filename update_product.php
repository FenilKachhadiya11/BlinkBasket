<?php
session_start();
include "connect.php";
// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
// Check if the form is submitted
if (isset($_POST['update_product'])) {
    $product_id = $_POST['product_id'];
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $description = mysqli_real_escape_string($con, $_POST['description']);
    $price = mysqli_real_escape_string($con, $_POST['price']);
    $size1_stock = mysqli_real_escape_string($con, $_POST['size1_stock']);
    $size2_stock = mysqli_real_escape_string($con, $_POST['size2_stock']);
    $size3_stock = mysqli_real_escape_string($con, $_POST['size3_stock']);
    $total_stock = $size1_stock + $size2_stock + $size3_stock;

    // Handle file uploads for 3 images (if any new images are uploaded)
    $image_urls = [];
    for ($i = 1; $i <= 3; $i++) {
        if (isset($_FILES["image$i"]) && $_FILES["image$i"]['error'] == 0) {
            $image_url = 'images/' . $_FILES["image$i"]['name'];
            move_uploaded_file($_FILES["image$i"]['tmp_name'], $image_url);
            $image_urls[] = $image_url;
        }
    }

    // If no new images, retain existing ones
    if (empty($image_urls)) {
        $query = "SELECT image_urls FROM products WHERE id = '$product_id'";
        $result = mysqli_query($con, $query);
        $product = mysqli_fetch_assoc($result);
        $image_urls = explode(',', $product['image_urls']);
    }

    // Convert the image URLs to a string, separated by commas
    $image_urls_str = implode(',', $image_urls);

    // Update the product in the database
    $query = "UPDATE products SET name = '$name', description = '$description', price = '$price', stock = '$total_stock', size1_stock = '$size1_stock', size2_stock = '$size2_stock', size3_stock = '$size3_stock', 
              image_urls = '$image_urls_str' WHERE id = '$product_id' AND user_id = '" . $_SESSION['user_id'] . "'";
    if (mysqli_query($con, $query)) {
        echo "<script>alert('Product updated successfully!'); window.location.href='product_management.php';</script>";
    } else {
        echo "<script>alert('Error updating product. Please try again.'); window.location.href='product_management.php';</script>";
    }
}

mysqli_close($con);
?>