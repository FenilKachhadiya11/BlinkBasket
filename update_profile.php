<?php
session_start();
include "connect.php";

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch the current user details
$query = "SELECT * FROM login WHERE id='$user_id'";
$result = $con->query($query);
$user = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $username = $_POST['username'];
    $email = $_POST['email'];

    // Validate inputs
    if (empty($username) || empty($email)) {
        echo "<script>alert('Please fill in all fields.');</script>";
    } else {
        // Update the database
        $updateQuery = "UPDATE login SET user='$username', email='$email' WHERE id='$user_id'";
        if ($con->query($updateQuery)) {
            echo "<script>alert('Profile updated successfully.'); window.location.href='buyer.php';</script>";
        } else {
            echo "<script>alert('Error updating profile. Please try again.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="x-icon" href="logo.webp">
    <title>Update Profile</title>
    <style>
        .profile-container {
            max-width: 500px;
            margin: 30px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background: #f9f9f9;
        }

        .profile-container h2 {
            text-align: center;
            font-size: 24px;
            margin-bottom: 20px;
        }

        .profile-details label {
            font-weight: bold;
        }

        .profile-details input {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .action-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .update-btn,
        .logout-btn {
            padding: 10px 20px;
            text-decoration: none;
            background-color: #3498db;
            color: white;
            border-radius: 5px;
        }

        .update-btn:hover,
        .logout-btn:hover {
            background-color: #2980b9;
        }
    </style>
</head>

<body>

    <!-- Profile Update Form -->
    <div class="profile-container">
        <h2>Update Your Profile</h2>
        <form method="POST">
            <div class="profile-details">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" value="<?php echo htmlspecialchars($user['user']); ?>"
                    required><br><br>

                <label for="email">Email</label>
                <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>"
                    required><br><br>
            </div>

            <div class="action-buttons">
                <button type="submit" class="update-btn">Save Changes</button>
                <a href="buyer.php" class="logout-btn">Cancel</a>
            </div>
        </form>
    </div>

</body>

</html>