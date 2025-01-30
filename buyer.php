<?php
session_start(); // Start the session
include "connect.php"; // Include the database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header("Location: index.php");
    exit();
}

// Fetch user details using session data
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM login WHERE id='$user_id'";
$result = $con->query($query);

if ($result && $result->num_rows > 0) {
    $user = $result->fetch_assoc(); // Fetch user details
} else {
    echo "<script>alert('User not found. Please log in again.'); window.location.href='index.php';</script>";
    exit();
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
    <title>Blink Basket</title>
</head>

<body>
    <!-- header -->
    <header class="header">
        <!-- Logo -->
        <a href="home.php" class="logo">BlinkBasket</a>

        <!-- Navigation -->
        <nav class="nav">
            <a href="buyer.php">Home</a>
            <a href="buyer.php">Shop</a>
            <div class="dropdown">
                <a href="#categories">Categories</a>
                <div class="dropdown-content">
                    <a href="footer/shopcategorieselectronics.html">Electronics</a>
                    <a href="footer/shopcategorifashion.html">Fashion</a>
                    <a href="footer/shopcategorihome&kitchen.html">Home & Kitchen</a>
                    <a href="footer/shopcategorisports&fitness.html">Sports & Fitness</a>
                </div>
            </div>
            <a href="contact.html">Contact</a>
            <a href="footer/aboutusOurStory.html">About Us</a>
            <a href="addBlog.html">Blog</a>
        </nav>

        <!-- Search Bar -->
        <div class="search-bar">
            <form method="GET" action="buyer.php" style="display: flex; align-items: center;">
                <input type="text" name="search" placeholder="Search products..."
                    value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                <button type="submit"><i class="fas fa-search"></i></button>
            </form>
        </div>

        <!-- Icons -->
        <div class="icons">
            <a href="orders.php"><i class="fas fa-box"></i></a>
            <a href="favorites.php"><i class="fas fa-heart"></i></a>
            <a href="cart.php"><i class="fas fa-shopping-cart"></i></a>
            <a href="#profile"><i class="fas fa-user"></i></a>
            <p style="color: #f39c12; font-weight: bold;"><label>Hello</label> <span><?php echo htmlspecialchars($user['user']); ?></span></p>
        </div>
    </header>

    <!-- Profile Section -->
    <div id="profile-section" class="profile-container hidden">
        <h2>Your Profile</h2>
        <div class="profile-details">
            <p><label>Username:</label> <span><?php echo htmlspecialchars($user['user']); ?></span></p>
            <p><label>Email:</label> <span><?php echo htmlspecialchars($user['email']); ?></span></p>
        </div>
        <div class="action-buttons">
            <a href="update_profile.php" class="update-btn">Update Profile</a>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </div>

    <!-- Product Section -->
    <div class="product-container">
        <?php
        // Check if a search query exists
        $searchQuery = isset($_GET['search']) ? $con->real_escape_string($_GET['search']) : '';

        // Fetch products from the database
        if (!empty($searchQuery)) {
            $query = "SELECT * FROM products WHERE name LIKE '%$searchQuery%' OR description LIKE '%$searchQuery%'";
        } else {
            $query = "SELECT * FROM products";
        }
        $result = $con->query($query);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $productName = htmlspecialchars($row['name']);
                $description = htmlspecialchars($row['description']);
                $price = number_format($row['price'], 2);
                $stock = $row['stock'];

                // Extract the first image from the image_urls column
                $imageUrls = explode(',', $row['image_urls']);
                $image = !empty($imageUrls[0]) ? trim($imageUrls[0]) : 'images/not.webp'; // Default image if no image is available
        
                $query = "SELECT * FROM favorites WHERE user_id = '$user_id' AND product_id = {$row['id']}";
                $isFavorite = $con->query($query)->num_rows > 0;
                $favoriteIconClass = $isFavorite ? 'fas fa-heart' : 'far fa-heart';

                // Display each product
                echo "
                <div class='product-card' onclick='openProductModal({$row['id']})'>
                    <img src='$image' alt='$productName'>
                    <div class='details'>
                        <h4>$productName</h4>
                        <p>$description</p>
                        <div class='price'>Price: $$price</div>
                        <div class='stock " . ($stock > 0 ? "" : "out-of-stock") . "'>
                            " . ($stock > 0 ? "In Stock" : "Out of Stock") . "
                        </div>
                        <!-- Disable the buttons if out of stock -->
                        <button class='favorite-btn' onclick='toggleFavorite({$row['id']})' " . ($stock <= 0 ? "disabled" : "") . ">
                            <i class='$favoriteIconClass'></i> Favorite
                        </button>
                        <button class='add-to-cart-btn' onclick='addToCart({$row['id']})' " . ($stock <= 0 ? "disabled" : "") . ">
                            Add to Cart
                        </button>
                    </div>
                </div>";
            }
        } else {
            echo "<p>No products found.</p>";
        }
        ?>
    </div>

    <!-- Modal for displaying product details -->
    <div id="productModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <div id="modal-product-details">
                <!-- Product details will be inserted here dynamically -->
            </div>
        </div>
    </div>

    <!-- Cart Modal -->
    <div id="cartModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2 id="modal-product-name"></h2>
            <p id="modal-product-price"></p>
            <p id="modal-product-stock"></p>

            <div class="size-quantity-container">
                <!-- Size Selection -->
                <label for="size-options">Size:</label>
                <select id="size-options">
                    <option value="">Select Size</option>
                    <!-- Sizes will be populated dynamically -->
                </select>

                <!-- Quantity Input -->
                <label for="quantity">Quantity:</label>
                <input type="number" id="quantity" value="1" min="1">
            </div>

            <!-- Add to Cart Button -->
            <button id="add-to-cart-modal-btn">Add to Cart</button>
        </div>
    </div>

    <!-- footer -->
    <footer class="footer-area">
        <div class="container">
            <!-- Top Footer -->
            <div class="footer-top">
                <div class="footer-col">
                    <h4>Customer Support</h4>
                    <ul>
                        <li><a href="footer/helpcenter.html">Help Center</a></li>
                        <li><a href="footer/trackyourorder.html">Track Your Order</a></li>
                        <li><a href="footer/retuens&refunds.html">Returns & Refunds</a></li>
                        <li><a href="footer/shippinginformation.html">Shipping Info</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Shop Categories</h4>
                    <ul>
                        <li><a href="footer/shopcategorieselectronics.html">Electronics</a></li>
                        <li><a href="footer/shopcategorifashion.html">Fashion</a></li>
                        <li><a href="footer/shopcategorihome&kitchen.html">Home & Kitchen</a></li>
                        <li><a href="footer/shopcategorisports&fitness.html">Sports & Fitness</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>About Us</h4>
                    <ul>
                        <li><a href="footer/aboutusOurStory.html">Our Story</a></li>
                        <li><a href="footer/aboutusCarrers.html">Careers</a></li>
                        <li><a href="footer/aboutusprivacypolicy.html">Privacy Policy</a></li>
                        <li><a href="footer/aboutusT&C.html">Terms & Conditions</a></li>
                    </ul>
                </div>
                <div class="footer-col newsletter">
                    <h4>Subscribe to Newsletter</h4>
                    <p>Get the latest updates on new products and upcoming sales.</p>
                    <form class="newsletter-form" action="mailto:blinkbasketcustomercare@gmail.com" method="post"
                        enctype="text/plain">
                        <input type="email" name="email" id="email" placeholder="Enter your email" required>
                        <button type="button" onclick="subscribe()">Subscribe</button>
                    </form>
                </div>
            </div>

            <!-- Bottom Footer -->
            <div class="footer-bottom">
                <p>&copy;
                    <script>document.write(new Date().getFullYear());</script> BlinkBasket. All Rights Reserved.
                </p>
                <div class="social-links">
                    <a href="https://www.facebook.com/home.php">
                        <img src="footerimg/facebook.webp" alt="Facebook"></a>
                    <a href="https://x.com/?lang=en&mx=2">
                        <img src="footerimg/twitter.avif" alt="twitter"></i></a>
                    <a href="https://www.instagram.com/">
                        <img src="footerimg/instagram.jpg" alt="instagram"></i></a>
                    <a href="https://www.linkedin.com/home/?originalSubdomain=in">
                        <img src="footerimg/linkedin.avif" alt="linkedin"></i></a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        function subscribe() {
            const emailInput = document.getElementById("email");
            const emailValue = emailInput.value;

            if (emailValue) {
                // Open mailto link
                window.location.href = `mailto:blinkbasketcustomercare@gmail.com?subject = Newsletter Subscription & body=New subscription from: ${ emailValue }`;

                // Reset the form after mailto opens
                emailInput.value = "";
            } else {
                alert("Please enter a valid email address.");
            }
        }

        // Close the product modal when the user clicks the close button
        document.getElementById("productModal").querySelector(".close").onclick = function () {
            document.getElementById("productModal").style.display = "none";
        };

        // Close the cart modal when the user clicks the close button
        document.getElementById("cartModal").querySelector(".close").onclick = function () {
            document.getElementById("cartModal").style.display = "none";
        };

        // Close the modal when the user clicks outside of the modal content
        window.onclick = function (event) {
            if (event.target === document.getElementById("cartModal")) {
                document.getElementById("cartModal").style.display = "none";
            } else if (event.target === document.getElementById("productModal")) {
                document.getElementById("productModal").style.display = "none";
            }
        };

        // Prevent product modal from opening when clicking on Add to Cart or Favorite button
        document.querySelectorAll('.add-to-cart-btn, .favorite-btn').forEach(function (button) {
            button.addEventListener('click', function (event) {
                event.stopPropagation(); // Stop the click from bubbling up to the product card
            });
        });

        // Close the modal when the user clicks the close button
        document.getElementsByClassName("close")[0].onclick = function () {
            document.getElementById("cartModal").style.display = "none";
        }

        // Close the modal when the user clicks outside of the modal content
        window.onclick = function (event) {
            if (event.target === document.getElementById("cartModal")) {
                document.getElementById("cartModal").style.display = "none";
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            const profileIcon = document.querySelector('.icons a[href="#profile"]');
            const profileSection = document.getElementById('profile-section');

            profileIcon.addEventListener('click', function (event) {
                event.preventDefault(); // Prevent the default anchor behavior
                profileSection.classList.toggle('hidden'); // Toggle the hidden class
            });
        });

        // Get the modal and close button
        var modal = document.getElementById("productModal");
        var span = document.getElementsByClassName("close")[0];

        // Open the modal and load product details
        function openProductModal(productId) {
            // Make an AJAX request to fetch product details by ID
            fetch("get_product_details.php?id=" + productId)
                .then(response => response.json())
                .then(data => {
                    // Prepare all images for display
                    let imagesHtml = '<div style="display: flex; gap: 10px; overflow-x: auto;">';
                    const images = data.image_urls.split(','); // Split image URLs into an array
                    images.forEach(image => {
                        imagesHtml += `<img src="${image.trim()}" alt="Product Image" style="width: 100%; height: auto; margin-bottom: 10px;">`;
                    });
                    imagesHtml += '</div>';

                    // Check if sizes exist
                    let sizeDisplay = '';
                    if (data.size1 || data.size2 || data.size3) {
                        const sizes = [];
                        if (data.size1) sizes.push(data.size1);
                        if (data.size2) sizes.push(data.size2);
                        if (data.size3) sizes.push(data.size3);
                        sizeDisplay = `<p><strong>Sizes:</strong> ${sizes.join(', ')}</p>`;
                    }

                    // Populate modal with product data
                    document.getElementById("modal-product-details").innerHTML = `
                        <h2>${data.name}</h2>
                        ${imagesHtml}
                        <p><strong>Description:</strong> ${data.description}</p>
                        <p><strong>Price:</strong> $${data.price}</p>
                        <p><strong>Stock Status:</strong> ${data.stock > 0 ? 'In Stock' : 'Out of Stock'}</p>
                        ${sizeDisplay}
                    `;
                })
                .catch(error => console.error('Error fetching product details:', error));

            // Show the modal
            modal.style.display = "block";
        }

        // Close the modal when the user clicks the close button
        span.onclick = function () {
            modal.style.display = "none";
        }

        // Close the modal when the user clicks outside of the modal content
        window.onclick = function (event) {
            if (event.target === modal) {
                modal.style.display = "none";
            }
        }

        function toggleFavorite(productId) {
            // Prevent the event from bubbling up and opening the product modal
            event.stopPropagation();

            fetch('toggle_favorite.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `product_id=${productId}`
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload(); // Reload the page to reflect changes
                    } else {
                        alert('Error occurred while adding/removing from favorites.'); // Show error if unsuccessful
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while processing your request.');
                });
        }

        // Add to Cart Modal
        function addToCart(productId) {
            // Prevent the event from bubbling up and opening the product modal
            event.stopPropagation();

            fetch("get_product_details.php?id=" + productId)
                .then(response => response.json())
                .then(data => {
                    // Check if sizes are available
                    let sizeOptions = '';
                    if (data.size1 || data.size2 || data.size3) {
                        if (data.size1) sizeOptions += `<option value="${data.size1}">${data.size1}</option>`;
                        if (data.size2) sizeOptions += `<option value="${data.size2}">${data.size2}</option>`;
                        if (data.size3) sizeOptions += `<option value="${data.size3}">${data.size3}</option>`;
                        // Show the size selection if sizes are available
                        document.getElementById("size-options").style.display = "block";
                    } else {
                        // Hide the size selection if no sizes are available
                        document.getElementById("size-options").style.display = "none";
                    }

                    // Display the modal with quantity and size options
                    const cartModal = document.getElementById("cartModal");
                    document.getElementById("modal-product-name").innerText = data.name;
                    document.getElementById("modal-product-price").innerText = `Price: $${data.price}`;
                    document.getElementById("modal-product-stock").innerText = data.stock > 0 ? `In Stock (${data.stock} available)` : "Out of Stock";
                    document.getElementById("size-options").innerHTML = sizeOptions;
                    document.getElementById("quantity").value = 1; // Set default quantity to 1

                    // Show the modal
                    cartModal.style.display = "block";

                    // Add event listener to add product to cart
                    document.getElementById("add-to-cart-modal-btn").onclick = function () {
                        const selectedSize = document.getElementById("size-options").value;
                        const quantity = document.getElementById("quantity").value;

                        // Only check for size if sizes are available
                        if ((data.size1 || data.size2 || data.size3) && (!selectedSize || quantity <= 0)) {
                            alert("Please select a size and quantity.");
                        } else if (quantity > 0) {
                            // Proceed to add to cart
                            addToCartBackend(productId, selectedSize, quantity);
                            cartModal.style.display = "none"; // Close modal after adding to cart
                        } else {
                            alert("Please select a quantity.");
                        }
                    };
                });
        }

        // Backend call to add the product to the cart
        function addToCartBackend(productId, size, quantity) {
            fetch("add_to_cart.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                },
                body: `product_id=${productId}&size=${size}&quantity=${quantity}`
            })
                .then(response => response.text())
                .then(data => {
                    alert(data);  // Show success or failure message
                    window.location.href = "cart.php"; // Redirect to cart page
                });
        }

    </script>
</body>

</html>