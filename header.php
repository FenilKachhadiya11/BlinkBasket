<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="shortcut icon" href="logo.webp" type="image/x-icon">
    <title>BlinkBasket</title>
    <style>
        /* Header Styling */
        .header {
            background-color: #222;
            color: #fff;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .header .logo {
            font-size: 24px;
            font-weight: bold;
            color: #f39c12;
            text-decoration: none;
        }

        .header .nav {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .header .nav a {
            color: #fff;
            text-decoration: none;
            font-size: 16px;
            position: relative;
            transition: color 0.3s;
        }

        .header .nav a:hover {
            color: #f39c12;
        }

        /* Icons */
        .header .icons {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .header .icons i {
            font-size: 20px;
            color: #fff;
            transition: transform 0.3s;
        }

        .header .icons i:hover {
            transform: scale(1.2);
            color: #f39c12;
        }
    </style>
</head>

<body>
    <header class="header">
        <a href="home.php" class="logo">BlinkBasket</a>
        <nav class="nav">
            <div class="icons">
                <a href="orders.php"><i class="fas fa-box"></i></a>
                <a href="buyer.php"><i class="fas fa-home"></i></a>
                <a href="favorites.php"><i class="fas fa-heart"></i></a>
                <a href="contact.html"><i class="fas fa-phone"></i></a>
            </div>
        </nav>
    </header>
</body>

</html>