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

// Get the product ID from the GET request
$product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : 0;

// Fetch product details
$sql = "SELECT * FROM products WHERE pid = $product_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $product = $result->fetch_assoc();
    $total_amount = $product['price'];
} else {
    // Redirect to index page if product not found
    header("Location: index.php");
    exit();
}

// Initialize order status
$order_placed = false;
$order_error = '';

// Handle order confirmation
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['confirm_order'])) {
    // Begin transaction
    $conn->begin_transaction();

    try {
        // Insert order details into the orders table
        $insert_order_sql = "INSERT INTO orders (user_id, product_id, order_amount, order_quantity) VALUES ($user_id, $product_id, $total_amount, 1)";
        if (!$conn->query($insert_order_sql)) {
            throw new Exception("Error inserting order: " . $conn->error);
        }

        // Remove item from products table
        $delete_product_sql = "DELETE FROM products WHERE pid = $product_id";
        if (!$conn->query($delete_product_sql)) {
            throw new Exception("Error deleting product: " . $conn->error);
        }

        // Commit transaction
        $conn->commit();
        $order_placed = true;
    } catch (Exception $e) {
        // Rollback transaction
        $conn->rollback();
        $order_error = "Failed to place order: " . $e->getMessage();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
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

        /* Payment Container */
        .payment-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .payment-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .hidden {
            display: none;
        }

        table th,
        table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        table th {
            background-color: #f3f3f3;
        }

        table td {
            background-color: #fff;
        }

        .buy-button {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            background-color: #ffa41c;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .buy-button:hover {
            background-color: #e67e22;
        }

        .success-message {
            color: green;
            font-size: 18px;
            text-align: center;
            margin-top: 20px;
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
            width: 100%;
        }

        /* Responsive */
        @media (max-width: 768px) {
            header {
                padding: 15px;
            }

            header .logo {
                font-size: 24px;
            }

            header nav ul {
                gap: 10px;
            }

            header nav ul li a {
                font-size: 14px;
            }

            .payment-container {
                padding: 10px;
            }

            .payment-container h2 {
                font-size: 24px;
            }

            table th,
            table td {
                padding: 8px;
            }

            .buy-button {
                padding: 8px 16px;
                font-size: 14px;
            }

            footer {
                padding: 15px;
            }
        }

        .hidden {
            display: none;
        }
    </style>
</head>

<body>
    <header>
        <div class="logo">ReTech Hub</div>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="categories.php">Categories</a></li>
                <li><a href="cart.php">Cart</a></li>
                <li><a href="profile.php">Profile</a></li>
            </ul>
        </nav>
    </header>
    <div class="payment-container">
        <?php if ($order_placed): ?>
            <div class="success-message">
                <p>Order placed successfully! Thank you for your purchase.</p>
            </div>
        <?php else: ?>
            <div id="cart-section">
                <h2>Order Summary</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                            <td>1</td>
                            <td>₹<?php echo htmlspecialchars($product['price']); ?></td>
                            <td>₹<?php echo htmlspecialchars($product['price']); ?></td>
                        </tr>
                    </tbody>
                </table>
                <h3>Total Amount: ₹<?php echo htmlspecialchars($total_amount); ?></h3>

                <!-- Form for placing the order -->
                <div id="order-form" class="<?php echo $order_placed ? 'hidden' : ''; ?>">
                    <form method="POST">
                        <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product_id); ?>">
                        <button type="submit" name="confirm_order" class="buy-button">Buy Now</button>
                    </form>
                <?php endif; ?>

                </div>
                <footer>
                    <p>&copy; 2024 ReTech Hub. All Rights Reserved.</p>
                </footer>
</body>

</html>