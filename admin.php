<?php
session_start();

// Database connection
$conn = new mysqli('localhost', 'root', '', 'electronics');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle admin login
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['admin_login'])) {
    $admin_user = $conn->real_escape_string($_POST['admin_user']);
    $admin_password = $conn->real_escape_string($_POST['admin_password']);

    // Fetch admin details from the database
    $sql = "SELECT * FROM admin WHERE admin_user = '$admin_user' AND admin_password = '$admin_password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Admin authenticated successfully
        $_SESSION['admin_user'] = $admin_user;
        header("Location: admin_dashboard.php");
        exit();
    } else {
        $error_message = "Invalid admin credentials.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <style>
        /* Body and Background */
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #8e44ad, #3498db);
            overflow: hidden;
            animation: gradientBG 8s infinite alternate;
        }

        @keyframes gradientBG {
            0% {
                background: linear-gradient(135deg, #8e44ad, #3498db);
            }

            50% {
                background: linear-gradient(135deg, #e74c3c, #f1c40f);
            }

            100% {
                background: linear-gradient(135deg, #16a085, #2ecc71);
            }
        }

        /* Login Container */
        .login-container {
            background: rgba(255, 255, 255, 0.9);
            padding: 30px 25px;
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
            text-align: center;
            max-width: 350px;
            width: 100%;
            animation: fadeIn 1.5s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        h1 {
            font-size: 2rem;
            color: #333;
            margin-bottom: 20px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        label {
            font-size: 1rem;
            color: #555;
            text-align: left;
            display: block;
            margin-bottom: 8px;
        }

        input[type="text"],
        input[type="password"] {
            width: calc(100% - 20px);
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 1rem;
            margin-bottom: 20px;
            transition: border 0.3s;
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            border-color: #3498db;
            outline: none;
            box-shadow: 0 0 10px rgba(52, 152, 219, 0.3);
        }

        button {
            background: linear-gradient(135deg, #3498db, #8e44ad);
            color: #fff;
            font-size: 1.1rem;
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: transform 0.3s, background 0.3s;
            width: 100%;
        }

        button:hover {
            background: linear-gradient(135deg, #8e44ad, #3498db);
            transform: translateY(-3px);
        }

        p {
            margin-top: 20px;
            font-size: 0.9rem;
            color: #555;
        }

        a {
            color: #3498db;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s;
        }

        a:hover {
            color: #8e44ad;
        }

        /* Error Message */
        .error {
            color: #e74c3c;
            font-size: 0.9rem;
            margin-bottom: 20px;
        }
    </style>
    </style>
</head>
<body>
    <!-- <header>
        <div class="logo">ReTech Hub</div>
    </header> -->
    <div class="login-container">
        <h2>Admin Login</h2>
        <?php if (isset($error_message)): ?>
            <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
        <?php endif; ?>
        <form method="POST" action="admin.php">
            <label for="admin_user">Admin User:</label>
            <input type="text" id="admin_user" name="admin_user" required>
            <label for="admin_password">Password:</label>
            <input type="password" id="admin_password" name="admin_password" required>
            <button type="submit" name="admin_login">Login</button>
        </form>
        <a href="login.php">Login as user</a>
    </div>
    <!-- <footer>
        <p>&copy; 2024 ReTech Hub. All Rights Reserved.</p>
    </footer> -->
</body>
</html>