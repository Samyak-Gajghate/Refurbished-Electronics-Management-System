<?php
// Database connection settings
require 'conn.php';

// Initialize variables
$message = "";
$showForm = true; // To toggle the visibility of the form

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['username']) && !empty($_POST['email']) && !empty($_POST['password'])) {
        $username = $conn->real_escape_string($_POST['username']);
        $email = $conn->real_escape_string($_POST['email']);
        $contact_no = isset($_POST['contact_no']) ? $conn->real_escape_string($_POST['contact_no']) : '';
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash the password

        // Check if username or email already exists
        $checkQuery = "SELECT id FROM users WHERE username='$username' OR email='$email' OR contact_no='$contact_no'";
        $result = $conn->query($checkQuery);

        if ($result->num_rows > 0) {
            $message = "Username or email already exists. Please try again.";
        } else {
            // Insert user into the database
            $sql = "INSERT INTO users (username, email, password, contact_no) VALUES ('$username', '$email', '$password', '$contact_no')";

            if ($conn->query($sql) === TRUE) {
                $message = "Registration successful! <a href='login.php'>Login here</a>";
                $showForm = false; // Hide the form after successful registration
            } else {
                $message = "Error: " . $conn->error;
            }
        }
    } else {
        $message = "Please fill in all fields.";
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
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
            background: linear-gradient(135deg, #ff7e5f, #feb47b);
            overflow: hidden;
            animation: gradientBG 8s infinite alternate;
        }

        @keyframes gradientBG {
            0% {
                background: linear-gradient(135deg, #ff7e5f, #feb47b);
            }
            50% {
                background: linear-gradient(135deg, #43cea2, #185a9d);
            }
            100% {
                background: linear-gradient(135deg, #ff6f91, #f9f586);
            }
        }

        /* Register Container */
        .register-container {
            background: rgba(255, 255, 255, 0.95);
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
        input[type="email"],
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
        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: #ff7e5f;
            outline: none;
            box-shadow: 0 0 10px rgba(255, 126, 95, 0.3);
        }

        button {
            background: linear-gradient(135deg, #feb47b, #ff7e5f);
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
            background: linear-gradient(135deg, #ff7e5f, #feb47b);
            transform: translateY(-3px);
        }

        p {
            margin-top: 20px;
            font-size: 0.9rem;
            color: #555;
        }

        a {
            color: #ff7e5f;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s;
        }

        a:hover {
            color: #feb47b;
        }

        .message {
            font-size: 1rem;
            margin-top: 10px;
        }

        .success {
            color: green;
        }

        .error {
            color: red;
        }
    </style>
</head>

<body>
    <div class="register-container">
        <h1>Register</h1>

        <!-- Show form only if registration is not successful -->
        <?php if ($showForm): ?>
        <form action="" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <label for="contact">Contact Number:</label>
            <input type="text" id="contact_no" name="contact_no" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">Register</button>
        </form>
        <?php endif; ?>

        <!-- Display the success or error message -->
        <?php if (!empty($message)): ?>
        <p class="message <?= strpos($message, 'successful') !== false ? 'success' : 'error' ?>">
            <?= $message ?>
        </p>
        <?php endif; ?>
    </div>
</body>

</html>
