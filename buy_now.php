<?php
session_start();

// Database connection
$conn = new mysqli('localhost', 'root', '', 'electronics');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the user ID from the session (assuming the user is logged in and the user ID is stored in the session)
$user_id = $_SESSION['user_id'];

// Fetch cart items for the user
$sql = "SELECT * FROM cart WHERE user_id = $user_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $total_amount = 0;
    while ($row = $result->fetch_assoc()) {
        $total_amount += $row['price'] * $row['quantity'];
    }

    // Redirect to payment page with total amount
    header("Location: payment.php?amount=$total_amount");
    exit();
} else {
    echo "No items in cart.";
}

$conn->close();
?>