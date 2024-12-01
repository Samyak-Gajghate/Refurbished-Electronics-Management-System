<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Database connection settings
require 'conn.php';

// Fetch cart items for the logged-in user
$user_id = $_SESSION['user_id'];
$sql = "SELECT products.pid, products.product_name AS product_name, products.price, cart.quantity 
        FROM cart 
        JOIN products ON cart.product_id = products.pid 
        WHERE cart.user_id='$user_id'";
$result = $conn->query($sql);

$products = [];
$total_price = 0;  // Variable to hold the total price of items in the cart
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
        $total_price += $row['price'] * $row['quantity']; // Calculate total price
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
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

        /* Cart Section */
        .cart-container {
            padding: 20px;
            margin: 20px auto;
            max-width: 1000px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 500px;
        }

        .cart-container h1 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 32px;
            color: #232f3e;
        }

        .cart-container ul {
            list-style-type: none;
            padding: 0;
        }

        .cart-container li {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            border-bottom: 1px solid #ddd;
        }

        .cart-container li a {
            text-decoration: none;
            color: #232f3e;
        }

        .cart-container li span {
            margin-left: 10px;
        }

        .buy-now {
            background-color: #ffa41c;
            border: none;
            color: #fff;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .buy-now:hover,
        .buy-all:hover {
            background-color: #e77d00;
        }

        .buy-all {
            background-color: #ffa41c;
            border: none;
            color: #fff;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            margin-left: 670px;
        }

        .total-price h3{
            display: inline;
        }

        .buy-now-form{
            display: inline;
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

            .cart-container {
                padding: 15px;
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
                <li><a href="profile.php">Profile</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <div class="cart-container">
        <h1>Your Cart</h1>

        <?php if (empty($products)): ?>
            <p>Your cart is empty.</p>
        <?php else: ?>
            <ul>
                <?php foreach ($products as $product): ?>
                    <li>
                        <a href="product_details.php?pid=<?php echo $product['pid']; ?>">
                            <?php echo htmlspecialchars($product['product_name']); ?>
                        </a>
                        <span>Price: <?php echo htmlspecialchars($product['price']); ?></span>
                        <span>Quantity: <?php echo htmlspecialchars($product['quantity']); ?></span>
                        <form method="post" action="buy_now.php">
                            <input type="hidden" name="product_id" value="<?php echo $product['pid']; ?>">
                            <input type="hidden" name="quantity" value="<?php echo $product['quantity']; ?>">
                            <button type="submit" class="buy-now">Buy Now</button>
                        </form>
                    </li>
                <?php endforeach; ?>
            </ul>

            <!-- Total Price Section -->
            <div class="total-price">
                <h3>Total Price: <?php echo number_format($total_price, 2); ?> USD</h3>
                <!-- Buy All Button -->
               <!-- <form method="post" action="buy_all.php" class="buy-now-form">
                    <button type="submit" class="buy-all">Buy All</button>
                </form> -->
            </div>
        <?php endif; ?>
    </div>

    <footer>
        © <?php echo date("Y"); ?> ReTech Hub. All rights reserved.
    </footer>
</body>

</html> 