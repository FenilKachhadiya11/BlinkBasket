<?php
session_start();
include "connect.php";
// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$product_id = $_POST['product_id'];

// Check if product is already favorited
$query = "SELECT * FROM favorites WHERE user_id = '$user_id' AND product_id = '$product_id'";
$result = $con->query($query);

if ($result->num_rows > 0) {
    // Remove from favorites
    $deleteQuery = "DELETE FROM favorites WHERE user_id = '$user_id' AND product_id = '$product_id'";
    if ($con->query($deleteQuery)) {
        echo json_encode(['success' => true, 'message' => 'Removed from favorites']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error removing from favorites']);
    }
} else {
    // Add to favorites
    $insertQuery = "INSERT INTO favorites (user_id, product_id) VALUES ('$user_id', '$product_id')";
    if ($con->query($insertQuery)) {
        echo json_encode(['success' => true, 'message' => 'Added to favorites']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error adding to favorites']);
    }
}
?>
