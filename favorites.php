<?php
session_start();
include "connect.php";

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch favorite products for the logged-in user
$query = "SELECT p.* FROM products p
          INNER JOIN favorites f ON p.id = f.product_id
          WHERE f.user_id = '$user_id'";
$result = $con->query($query);

// Check if the remove button is clicked
if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];

    // Remove the product from favorites
    $remove_query = "DELETE FROM favorites WHERE user_id = '$user_id' AND product_id = '$product_id'";
    if ($con->query($remove_query)) {
        // Redirect back to the same page to refresh the product list
        header("Location: favorites.php");
        exit();
    } else {
        echo "Error removing product from favorites.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="buyerstyle.css">
    <link rel="shortcut icon" type="x-icon" href="logo.webp">
    <title>Your Favorite Items</title>
</head>

<body>
    <style>
        /* Favorites Page Styles */
        .favorites-container {
            width: 80%;
            margin: 50px auto;
            font-family: Arial, sans-serif;
        }

        .favorites-container h2 {
            font-size: 2rem;
            margin-bottom: 20px;
            text-align: center;
        }

        .product-card {
            border: 1px solid #ddd;
            border-radius: 10px;
            overflow: hidden;
            width: 250px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            margin-bottom: 20px;
            text-align: center;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 12px rgba(0, 0, 0, 0.2);
        }

        .product-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .product-card h4 {
            margin: 10px 0;
            font-size: 18px;
            color: #333;
        }

        .product-card p {
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
        }

        .product-card .price {
            font-size: 16px;
            font-weight: bold;
            color: #f39c12;
        }

        .remove-icon {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 20px;
            color: #f44336;
        }

        .remove-icon:hover {
            color: #d32f2f;
        }

        footer {
            text-align: center;
            /* margin-top: 50px; */
            font-size: 0.9rem;
            padding: 20px;
            background-color: #f1f1f1;
        }

        /* Flexbox layout for the product cards */
        .product-cards-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            /* Space between cards */
            justify-content: space-around;
        }

        .product-card {
            flex: 1 1 calc(33.33% - 20px);
            /* 3 items per row, with spacing */
            max-width: calc(33.33% - 20px);
        }
    </style>
    <!-- header -->
    <header class="header">
        <a href="home.php" class="logo">BlinkBasket</a>
        <nav class="nav">
            <div class="icons">
                <a href="buyer.php"><i class="fas fa-home"></i></a>
                <a href="cart.php"><i class="fas fa-shopping-cart"></i></a>
                <a href="contact.html"><i class="fas fa-phone"></i></a>
            </div>
        </nav>
    </header>

    <main>
        <div class="favorites-container">
            <h2>Your Favorite Products</h2>

            <?php if ($result->num_rows > 0): ?>
                <div class="product-cards-container">
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <div class="product-card">
                            <!-- X Icon for Remove -->
                            <a href="favorites.php?product_id=<?php echo $row['id']; ?>" class="remove-icon">
                                <i class="fas fa-times"></i>
                            </a>

                            <?php
                            // Split the image_urls into an array if there are multiple images
                            $image_urls = explode(',', $row['image_urls']);
                            ?>
                            <div class="product-images">
                                <?php foreach ($image_urls as $image): ?>
                                    <img src="<?php echo trim($image); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>"
                                        style="width: 100px; height: auto; margin-bottom: 10px;">
                                <?php endforeach; ?>
                            </div>
                            <h4><?php echo htmlspecialchars($row['name']); ?></h4>
                            <p><?php echo htmlspecialchars($row['description']); ?></p>
                            <div class="price">$<?php echo number_format($row['price'], 2); ?></div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <p>No favorite products found. <a href='buyer.php'>Browse products</a> to add items to your favorite.</p>
            <?php endif; ?>
        </div>
    </main>

    <?php include "footer.php"; ?>
</body>

</html>