<?php
session_start();  // Start the session

include "connect.php";

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blink Basket</title>
    <link rel="shorcut icon" type="x-icon" href="logo.webp">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <div class="wrapper">
        <span class="rotate-bg"></span>
        <span class="rotate-bg2"></span>

        <!-- Login Form -->
        <div class="form-box login">

            <h2 class="title animation" style="--i:0; --j:21">Login</h2>
            <form method="POST" autocomplete="off">

                <div class="input-box animation" style="--i:1; --j:22">
                    <input type="text" name="user" required>
                    <label for="user">Username</label>
                    <i class='bx bxs-user'></i>
                </div>

                <div class="input-box animation" style="--i:2; --j:23">
                    <input type="password" name="password" required>
                    <label for="password">Password</label>
                    <i class='bx bxs-lock-alt'></i>
                </div>

                <div class="linkTxt animation" style="--i:4; --j:26">
                    <p><a href="password_reset.php" class="forgot-password-link">Forgot Password?</a></p>
                </div>

                <button type="submit" name="loginbtn" class="btn animation" style="--i:3; --j:24">Login</button>
                <div class="linkTxt animation" style="--i:5; --j:25">
                    <p>Don't have an account? <a href="#" class="register-link">Sign Up</a></p>
                </div>
            </form>
        </div>

        <div class="info-text login">
            <h2 class="animation" style="--i:0; --j:20">Welcome Back!</h2>
            <p class="animation" style="--i:1; --j:21">Enjoy Online Shopping with the help of BlinkBasket.</p>
        </div>

        <!-- Register model -->

        <div class="form-box register">

            <h2 class="title animation" style="--i:17; --j:0">Sign Up</h2>

            <form method="POST" autocomplete="off">

                <div class="input-box animation" style="--i:18; --j:1">
                    <input type="text" name="user" required>
                    <label for="user">Username</label>
                    <i class='bx bxs-user'></i>
                </div>

                <div class="input-box animation" style="--i:19; --j:2">
                    <input type="email" name="email" required>
                    <label for="email">Email</label>
                    <i class='bx bxs-envelope'></i>
                </div>

                <div class="input-box animation" style="--i:20; --j:3">
                    <input type="password" name="password" required>
                    <label for="password">Password</label>
                    <i class='bx bxs-lock-alt'></i>
                    <!-- Password validation message -->
                    <div id="password-message" class="message"></div>
                </div>

                <button type="submit" name="signupbtn" class="btn animation" style="--i:21;--j:4">Sign Up</button>

                <div class="linkTxt animation" style="--i:22; --j:5">
                    <p>Already have an account? <a href="#" class="login-link">Login</a></p>
                </div>

            </form>
        </div>

        <div class="info-text register">
            <h2 class="animation" style="--i:17; --j:0;">Welcome Back!</h2>
            <p class="animation" style="--i:18; --j:1;">Enjoy Online Shopping with the help of BlinkBasket.</p>
        </div>
    </div>

    <!--Script.js-->
    <script src="script.js"></script>
    <script>
        // Client-side password validation
        document.getElementById('signup-form').addEventListener('submit', function (e) {
            const password = document.getElementById('password').value;
            const messageElement = document.getElementById('password-message');
            const passwordPattern = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/; // Min 8 characters, 1 number, 1 lowercase, 1 uppercase

            if (!passwordPattern.test(password)) {
                messageElement.textContent = 'Password must be at least 8 characters long and include at least one number, one lowercase letter, and one uppercase letter.';
                e.preventDefault(); // Prevent form submission
            } else {
                messageElement.textContent = ''; // Clear message if password is valid
            }
        });
    </script>
</body>

</html>




<?php
if (isset($_POST["loginbtn"])) {
    $user = $_POST['user'];
    $password = $_POST['password'];

    // Query to fetch the user by username
    $query = "SELECT * FROM login WHERE user='$user'";
    $data = mysqli_query($con, $query);

    $total = mysqli_num_rows($data);

    if ($total == 1) {
        $row = mysqli_fetch_assoc($data);
        $hashed_password = $row['password']; // Get the hashed password from the database

        // Verify the entered password against the hashed password
        if (password_verify($password, $hashed_password)) {
            // Store user data in session (you can store more details if necessary)
            $_SESSION['user_id'] = $row['id']; // Assuming 'id' is the primary key
            $_SESSION['username'] = $row['user']; // Store username if needed

            // Redirect to the home page after login
            echo "<script>alert('Login Successful.'); window.location.href='home.php';</script>";
        } else {
            echo "<script>alert('Login Failed. Invalid password.'); window.location.href='index.php';</script>";
        }
    } else {
        echo "<script>alert('Login Failed. User not found.'); window.location.href='index.php';</script>";
    }
}

if (isset($_POST['signupbtn'])) {
    $user = mysqli_real_escape_string($con, $_POST['user']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);

    // Server-side password validation
    $passwordPattern = '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/'; // Min 8 characters, 1 number, 1 lowercase, 1 uppercase
    if (!preg_match($passwordPattern, $password)) {
        echo "<script>alert('Password must be at least 8 characters long and include at least one number, one lowercase letter, and one uppercase letter.'); window.location.href='index.php';</script>";
        exit();
    }

    // Hash the password before storing it
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Insert the user details with hashed password into the database
    $query = "INSERT INTO login(user, email, password) VALUES ('$user','$email','$hashed_password')";
    $run = mysqli_query($con, $query);

    if ($run) {
        echo "<script>alert('User registered successfully!'); window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('Error: " . mysqli_error($con) . "');</script>";
    }
}
mysqli_close($con);
?>