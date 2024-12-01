<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Database connection settings
require 'conn.php';

// Fetch user details from the database
$user_id = $_SESSION['user_id'];
$sql = "SELECT username, email FROM users WHERE id='$user_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "User not found.";
    exit();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <style>
        /* Global Styles */
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to right, #f3f3f3, #ffffff);
            color: #333;
        }

        /* Header */
        header {
            background-color: #232f3e;
            color: #fff;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        header .logo {
            font-size: 28px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #ffa41c;
        }

        header nav ul {
            list-style: none;
            display: flex;
            gap: 20px;
            margin: 0;
            padding: 0;
        }

        header nav ul li a {
            color: #fff;
            text-decoration: none;
            font-size: 16px;
            transition: color 0.3s ease;
        }

        header nav ul li a:hover {
            color: #ffa41c;
        }

        /* Profile Section */
        .profile-container {
            max-width: 800px;
            margin: 50px auto;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            margin-bottom: 340px;
        }

        .profile-container h2 {
            font-size: 28px;
            color: #232f3e;
            margin-bottom: 15px;
        }

        .profile-container p {
            font-size: 18px;
            color: #555;
            margin: 10px 0;
        }

        .profile-container .highlight {
            color: #ffa41c;
            font-weight: bold;
        }

        /* Footer */
        footer {
            text-align: center;
            padding: 20px;
            background: #232f3e;
            color: #fff;
            font-size: 14px;
            margin-top: 30px;
            position: relative;
            bottom: 0;
            
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            header .logo {
                font-size: 24px;
            }

            header nav ul {
                flex-direction: column;
                gap: 10px;
            }

            .profile-container {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">ReTech Hub</div>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="cart.php">Cart</a></li>
                <li><a href="sell.php">Sell</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    <div class="profile-container">
        <h2>Welcome, <span class="highlight"><?php echo htmlspecialchars($user['username']); ?></span>!</h2>
        <p>Here are your profile details:</p>
        <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
    </div>
    <footer>
        © <?php echo date("Y"); ?> ReTech Hub. All rights reserved.
    </footer>
</body>
</html>
