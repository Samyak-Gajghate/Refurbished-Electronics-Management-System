<?php 
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_user'])) {
    header("Location: admin.php");
    exit();
}

// Database connection settings
require 'conn.php'; // Include your DB connection settings

// Fetch product details from the sell table
$sql = "SELECT sell.id, sell.product_name, sell.description, sell.category, sell.price,sell.image, sell.created_at, users.username 
        FROM sell 
        JOIN users ON sell.user_id = users.id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Store the results in an array for later use
    $products = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $products = [];
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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

        /* Dashboard Container */
        .dashboard-container {
            padding: 30px;
            text-align: center;
        }

        .dashboard-container h2 {
            font-size: 28px;
            margin-bottom: 20px;
            color: #232f3e;
            font-weight: bold;
        }

        .dashboard-container p {
            font-size: 18px;
            color: #555;
        }

        /* Product Listings Table */
        table {
            width: 100%;
            margin-top: 30px;
            border-collapse: collapse;
        }

        table th,
        table td {
            padding: 15px;
            text-align: center;
            border: 1px solid #ddd;
            font-size: 16px;
        }

        table th {
            background-color: #232f3e;
            color: #fff;
            font-weight: bold;
        }

        table tr:nth-child(even) {
            background-color: #f7f7f7;
        }

        table img {
            max-width: 100px;
            border-radius: 8px;
        }

        .approve {
        background-color: #ffa41c;
        color: #fff;
        border: none;
        padding: 12px 20px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 1em;
        transition: background-color 0.3s ease;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .approve:hover {
        background-color: #cc8500;
    }

    .approve:focus {
        outline: 2px solid #b12704;
    }

        /* Footer */
        footer {
            text-align: center;
            padding: 20px;
            background: #232f3e;
            color: #fff;
            font-size: 14px;
            margin-top: 30px;
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

        /* Responsive Design */
        @media (max-width: 768px) {
            .dashboard-container {
                padding: 20px;
            }

            table {
                font-size: 14px;
            }

            table td,
            table th {
                padding: 10px;
            }
        }
    </style>
</head>

<body>
    <header>
        <div class="logo">ReTech Hub</div>
        <nav>
            <ul>
                <li><a href="admin_logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <div class="dashboard-container">
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION['admin_user']); ?>!</h2>
        <p>This is the admin dashboard. Below are the product listings:</p>

        <!-- Product Listings Table -->
        <?php if (!empty($products)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Product ID</th>
                        <th>Product Name</th>
                        <th>Description</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Image</th>
                        <th>Listed By</th>
                        <th>Listed On</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($product['id']); ?></td>
                            <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                            <td><?php echo htmlspecialchars($product['description']); ?></td>
                            <td><?php echo htmlspecialchars($product['category']); ?></td>
                            <td><?php echo htmlspecialchars($product['price']); ?></td>
                            <td><img src="<?php echo htmlspecialchars($product['image']); ?>" alt="Product Image" width="100"></td>
                            <td><?php echo htmlspecialchars($product['username']); ?></td>
                            <td><?php echo htmlspecialchars($product['created_at']); ?></td>
                            <td>
                                <form action="approve_admin.php" method="POST">
                                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                    <button type="submit" name="approve" class="approve">Approve</button>
                                    
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No products have been listed for sale yet.</p>
        <?php endif; ?>
    </div>

    <footer>
        <p>&copy; 2024 ReTech Hub. All Rights Reserved.</p>
    </footer>
</body>

</html>