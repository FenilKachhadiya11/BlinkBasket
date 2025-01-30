<?php
session_start();

// Check if the user is logged in, if not, redirect to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Database connection
include "connect.php";

// Fetch user data from the database
$user_id = $_SESSION['user_id'];
$query = "SELECT user, email, password FROM login WHERE id = '$user_id'";

$result = mysqli_query($con, $query);

if (!$result) {
    echo "<script>alert('Error fetching user data: " . mysqli_error($con) . "');</script>";
    exit();
}

$user_data = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Update account details if form is submitted
    $new_username = mysqli_real_escape_string($con, $_POST['username']);
    $new_email = mysqli_real_escape_string($con, $_POST['email']);
    // Validate password if new password is provided
    $new_password = $_POST['password'] ? $_POST['password'] : '';

    // Server-side validation for password
    if ($new_password) {
        $password_pattern = '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/'; // Min 8 characters, at least 1 number, 1 lowercase, 1 uppercase

        if (!preg_match($password_pattern, $new_password)) {
            echo "<script>alert('Password must be at least 8 characters long and include at least one number, one lowercase letter, and one uppercase letter.');</script>";
            exit();
        }
        $new_password = password_hash($new_password, PASSWORD_DEFAULT); // Hash the password
    } else {
        $new_password = $user_data['password']; // Retain old password if not updated
    }

    // Update query
    $update_query = "UPDATE login SET user = '$new_username', email = '$new_email', password = '$new_password' WHERE id = '$user_id'";

    if (mysqli_query($con, $update_query)) {
        echo "<script>alert('Account details updated successfully!'); window.location.href='seller.php';</script>";
    } else {
        echo "<script>alert('Error updating account details: " . mysqli_error($con) . "');</script>";
    }

}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="x-icon" href="logo.webp">
    <title>Account Settings</title>
    <link rel="stylesheet" href="sellerstyle.css">
</head>

<body>

    <header>
        <h1>Account Settings</h1>
        <nav>
            <a href="seller.php">Home</a>
            <a href="product_management.php">Product Management</a>
            <a href="order_history.php">Order History</a>
            <a href="account_settings.php">Account Settings</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>

    <main>
        <div class="container">
            <h2>Update Your Account</h2>

            <form action="account_settings.php" method="POST" id="account-settings-form">
                <label for="username">Username</label>
                <input type="text" id="username" name="username"
                    value="<?php echo htmlspecialchars($user_data['user']); ?>" required>

                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user_data['email']); ?>"
                    required>

                <label for="password">New Password</label>
                <input type="password" id="password" name="password">

                <div id="password-message" class="message"></div>

                <button type="submit">Save Changes</button>
            </form>
        </div>
    </main>

    <footer>
        <p>&copy; 2025 E-commerce Platform | All Rights Reserved</p>
    </footer>

    <script>
        // Password validation function
        function validatePassword() {
            const password = document.getElementById('password').value;
            const messageElement = document.getElementById('password-message');
            const passwordPattern = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/; // Min 8 characters, 1 number, 1 lowercase, 1 uppercase

            if (password && !passwordPattern.test(password)) {
                messageElement.textContent = 'Password must be at least 8 characters long and include at least one number, one lowercase letter, and one uppercase letter.';
                return false; // Invalid password, prevent form submission
            } else {
                messageElement.textContent = ''; // Clear the message if password is valid
                return true; // Valid password, allow form submission
            }
        }

        // Handle form submission
        document.getElementById('account-settings-form').addEventListener('submit', function (e) {
            // If validation fails, prevent form submission
            if (!validatePassword()) {
                e.preventDefault(); // Prevent form submission if password is invalid
            }
        });

        // Retain form values after page reload
        window.onload = function () {
            const storedUsername = localStorage.getItem('username');
            const storedEmail = localStorage.getItem('email');

            if (storedUsername) {
                document.getElementById('username').value = storedUsername;
            }
            if (storedEmail) {
                document.getElementById('email').value = storedEmail;
            }
        }

        // Save form values to localStorage before submission
        document.getElementById('account-settings-form').addEventListener('submit', function () {
            const username = document.getElementById('username').value;
            const email = document.getElementById('email').value;

            // Store form values in localStorage before submission
            localStorage.setItem('username', username);
            localStorage.setItem('email', email);
        });
    </script>
</body>

</html>

<?php
// Close database connection
mysqli_close($con);
?>