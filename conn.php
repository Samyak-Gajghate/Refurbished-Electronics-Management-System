<?php
// Database credentials
$servername = "localhost"; // Change if your database server is not on localhost
$username = "root";        // Replace with your MySQL username
$password = "";            // Replace with your MySQL password
$database = "electronics"; // Replace with your database name

// Create a connection
$conn = new mysqli($servername, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Uncomment the line below to confirm the connection in development
// echo "Connected successfully";

?>