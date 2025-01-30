<?php
session_start();
include "connect.php";
include "header.php";
// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
$user_id = $_SESSION['user_id']; // Assuming user is logged in

// Fetch the products in the cart for this user along with the selected size
$query = "SELECT c.*, p.name, p.price, p.image_urls, c.size FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = '$user_id'";
$result = $con->query($query);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="shortcut icon" type="x-icon" href="logo.webp">
    <link rel="stylesheet" href="buyerstyle.css">
    <title>Your Cart - BlinkBasket</title>
</head>

<body>
    <style>
        /* Cart Page Styles */
        .cart-container {
            width: 80%;
            margin: 50px auto;
            font-family: Arial, sans-serif;
        }

        .cart-container h2 {
            font-size: 2rem;
            margin-bottom: 20px;
            text-align: center;
        }

        .cart-items {
            border-top: 1px solid #ddd;
        }

        .cart-item {
            display: flex;
            justify-content: space-between;
            border-bottom: 1px solid #ddd;
            padding: 15px 0;
            align-items: center;
        }

        .cart-item img {
            width: 100px;
            height: auto;
            margin-right: 20px;
        }

        .cart-item-name h3 {
            margin: 0;
        }

        .cart-item-quantity,
        .cart-item-subtotal {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .remove-item-btn {
            background-color: #f44336;
            color: white;
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 10px;
        }

        .remove-item-btn:hover {
            background-color: #d32f2f;
        }

        .cart-total {
            margin-top: 30px;
            text-align: right;
        }

        .cart-total h3 {
            font-size: 1.5rem;
            margin-bottom: 10px;
        }

        .checkout-btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            font-size: 1.1rem;
            border-radius: 5px;
        }

        .checkout-btn:hover {
            background-color: #388E3C;
        }

        footer {
            text-align: center;
            /* margin-top: 50px; */
            font-size: 0.9rem;
            padding: 20px;
            background-color: #f1f1f1;
        }
    </style>

    <main>
        <div class="cart-container">
            <h2>Your Cart</h2>

            <?php
            if ($result->num_rows > 0) {
                echo "<div class='cart-items'>";
                $totalPrice = 0;

                while ($row = $result->fetch_assoc()) {
                    $productName = htmlspecialchars($row['name']);
                    $price = number_format($row['price'], 2);
                    $quantity = $row['quantity'];
                    $subtotal = $price * $quantity;
                    $totalPrice += $subtotal;

                    // Extract the first image from the image_urls column
                    $imageUrls = explode(',', $row['image_urls']);
                    $image = !empty($imageUrls[0]) ? trim($imageUrls[0]) : 'images/bg.jpg'; // Default image if no image is available
            
                    // Display selected size
                    $selectedSize = $row['size'] ? htmlspecialchars($row['size']) : 'N/A';

                    echo "
                    <div class='cart-item'>
                        <img src='$image' alt='$productName'>
                        <div class='cart-item-name'>
                            <h3>$productName</h3>
                            <p>Price: $$price</p>
                            <p>Size: $selectedSize</p>
                        </div>
                        <div class='cart-item-quantity'>
                            <p>Quantity: $quantity</p>
                        </div>
                        <div class='cart-item-subtotal'>
                            <p>Subtotal: $$subtotal</p>
                        </div>
                        <a href='remove_from_cart.php?product_id={$row['product_id']}' class='remove-item-btn'>Remove</a>
                    </div>";
                }

                echo "</div>";
                echo "<div class='cart-total'>
                        <h3>Total: $$totalPrice</h3>
                        <a href='checkout.php' class='checkout-btn'>Proceed to Checkout</a>
                    </div><br><br>";
            } else {
                echo "<p>Your cart is empty. <a href='buyer.php'>Browse products</a> to add items to your cart.</p>";
            }
            ?>
        </div>
    </main>

    <!-- footer -->
    <?php include "footer.php";
    ?>
</body>

</html>