<?php
session_start();

// Database connection
$conn = new mysqli('localhost', 'root', '', 'electronics');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all categories
$sql = "SELECT DISTINCT category FROM products";
$result = $conn->query($sql);

$categories = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row['category'];
    }
}

// Fetch products based on selected category
$selected_category = isset($_GET['category']) ? $_GET['category'] : '';
$product_sql = "SELECT * FROM products";
if ($selected_category) {
    $product_sql .= " WHERE category = '" . $conn->real_escape_string($selected_category) . "'";
}
$product_result = $conn->query($product_sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories</title>
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

        /* Categories Container */
        .categories-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .categories-container h2 {
            margin-bottom: 20px;
        }

        .categories-container form {
            margin-bottom: 20px;
        }

        .categories-container form label {
            font-size: 18px;
            font-weight: bold;
        }

        .categories-container form select {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-left: 10px;
        }

        .product-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }

        .product {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .product img {
            width: 100%;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .product h3 {
            font-size: 20px;
            margin-bottom: 10px;
        }

        .product p {
            font-size: 16px;
            color: #666;
            margin-bottom: 10px;
        }

        .product .price {
            font-size: 18px;
            font-weight: bold;
            color: #e74c3c;
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
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
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
                <li><a href="categories.php">Categories</a></li>
                <li><a href="cart.php">Cart</a></li>
                <li><a href="profile.php">Profile</a></li>
            </ul>
        </nav>
    </header>
    <div class="categories-container">
        <h2>Categories</h2>
        <form method="GET" action="categories.php">
            <label for="category">Filter by Category:</label>
            <select name="category" id="category" onchange="this.form.submit()">
                <option value="">All Categories</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo htmlspecialchars($category); ?>" <?php if ($category == $selected_category) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($category); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>
        <div class="product-list">
            <?php if ($product_result->num_rows > 0): ?>
                <?php while ($product = $product_result->fetch_assoc()): ?>
                    
                    <div class="product">
                        <h3><?php echo htmlspecialchars($product['product_name']); ?></h3>
                        <img src="<?php echo htmlspecialchars($product['images']); ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>">
                        <p><?php echo htmlspecialchars($product['description']); ?></p>
                        <p class="price">Price: ₹<?php echo htmlspecialchars($product['price']); ?></p>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No products found in this category.</p>
            <?php endif; ?>
        </div>
    </div>
    <footer>
        <p>&copy; 2024 ReTech Hub. All Rights Reserved.</p>
    </footer>
</body>
</html>