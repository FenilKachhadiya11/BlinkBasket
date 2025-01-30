<?php
include "connect.php";
session_start(); // Start session if not already started

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Check if 'id' parameter is set in the GET request
if (isset($_GET['id'])) {
    $productId = $_GET['id'];

    // Prepare a SQL statement to prevent SQL injection
    $stmt = $con->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $productId); // 'i' for integer type

    if ($stmt->execute()) {
        $result = $stmt->get_result();

        // Check if product is found
        if ($result->num_rows > 0) {
            $product = $result->fetch_assoc();

            // Prepare the response data with all relevant fields
            $response = [
                'id' => $product['id'],
                'name' => $product['name'],
                'description' => $product['description'],
                'price' => number_format($product['price'], 2),
                'stock' => $product['stock'],
                'image_urls' => $product['image_urls'],
                'size1' => $product['size1'],  // Add size fields
                'size2' => $product['size2'],  // Add size fields
                'size3' => $product['size3']   // Add size fields
            ];
            
            header('Content-Type: application/json');
            echo json_encode($response); // Send product data as JSON
        } else {
            // Product not found
            header($_SERVER['SERVER_PROTOCOL'] . " 404 Not Found");
            echo json_encode(["error" => "Product not found"]);
        }
    } else {
        // Query execution failed
        header($_SERVER['SERVER_PROTOCOL'] . " 500 Internal Server Error");
        echo json_encode(["error" => "Failed to fetch product details"]);
    }

    // Close the prepared statement
    $stmt->close();
} else {
    // Invalid request
    header($_SERVER['SERVER_PROTOCOL'] . " 400 Bad Request");
    echo json_encode(["error" => "Invalid request"]);
}

// Close the database connection
$con->close();
?>
