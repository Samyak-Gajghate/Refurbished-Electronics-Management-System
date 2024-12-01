<?php
session_start();

// Database connection settings
require 'conn.php';

if (!empty($_POST['username']) && !empty($_POST['password'])) {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];

    // Fetch user from the database
    $sql = "SELECT id, username, password FROM users WHERE username='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id']; // Set session variable
            $_SESSION['username'] = $row['username']; // Set username session variable
            header("Location: profile.php");
        } else {
            echo "Invalid username or password";
        }
    } else {
        echo "Invalid username or password";
    }
} else {
    echo "Please fill in all fields.";
}

$conn->close();
?>