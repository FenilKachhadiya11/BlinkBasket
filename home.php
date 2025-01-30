<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if the user is not logged in
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="x-icon" href="logo.webp">
    <title>Blink Basket</title>
    <style>
        /* General Styles */
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            overflow-x: hidden;
        }

        .container {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            height: 100vh;
            position: relative;
            background-color: #f0f0f0;
            overflow: hidden;
        }

        /* Slider Section */
        .slider {
            width: 50%;
            height: 100%;
            position: absolute;
            top: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            font-size: 2rem;
            font-weight: bold;
            text-align: center;
            transition: transform 0.3s ease, filter 0.3s ease;
            text-decoration: none;
            /* Remove underline from link */
        }

        .seller {
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('images/bg.jpg') no-repeat center center/cover;
            left: -50%;
            animation: slideInLeft 2s forwards;
        }

        .buyer {
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('images/bgg.jpg') no-repeat center center/cover;
            right: -50%;
            animation: slideInRight 2s forwards;
        }

        .seller:hover {
            filter: grayscale(0);
            transform: scale(1.1);
            z-index: 2;
        }

        .buyer:hover {
            filter: grayscale(0);
            transform: scale(1.1);
            z-index: 2;
        }

        .text {
            opacity: 0;
            /* Start with text hidden */
            animation: fadeIn 6s forwards;
            /* Apply fade-in animation */
        }

        /* Keyframes for fade-in text animation */
        @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: translateY(100vh);
                /* Start from a lower position */
            }

            100% {
                opacity: 1;
                transform: translateY(0);
                /* End in the normal position */
            }
        }

        /* Keyframes for animations */
        @keyframes slideInLeft {
            0% {
                left: -50%;
            }

            100% {
                left: 0;
            }
        }

        @keyframes slideInRight {
            0% {
                right: -50%;
            }

            100% {
                right: 0;
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .slider {
                font-size: 1.5rem;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Seller Section -->
        <a href="seller.php" class="slider seller">
            <span class="text">Welcome Seller!</span>
        </a>
        <!-- Buyer Section -->
        <a href="buyer.php" class="slider buyer">
            <span class="text">Welcome Buyer!</span>
        </a>
    </div>
</body>

</html>