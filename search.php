<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
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
        }

        header .nav-container {
            display: flex;
            align-items: center;
            gap: 20px;
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
            transition: color 0.3s;
        }

        header nav ul li a:hover {
            color: #f39c12;
        }

        header .search-form {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        header .search-form input {
            padding: 10px 15px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        header .search-form button {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            background-color: #f39c12;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        header .search-form button:hover {
            background-color: #e67e22;
        }

        /* Search Results Section */
        .search-results {
            padding: 40px 20px;
            text-align: center;
        }

        .search-results-heading {
            font-size: 2.5rem;
            margin-bottom: 20px;
            color: #333;
            animation: fadeIn 1.5s;
        }

        .product-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
            
        }

        .product {
            background-color: #fff;
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .product:hover {
            transform: translateY(-10px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
        }

        .product img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            margin-bottom: 15px;
        }

        .product h3 {
            font-size: 1.4rem;
            color: #333;
        }

        .product p {
            font-size: 1rem;
            color: #666;
            margin: 10px 0;
        }

        .product a {
            text-decoration: none;
            color: inherit;
            /* Inherit color from parent */
        }

        .product a:hover {
            text-decoration: none;
            /* Remove underline on hover */
        }


        .product .price {
            font-size: 1.2rem;
            color: #e74c3c;
            font-weight: bold;
        }

        /* Footer */
        footer {
            text-align: center;
            padding: 20px;
            background: #333;
            color: #fff;
            font-size: 14px;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }
    </style>
</head>

<body>
    <?php
    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'electronics');

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get the search query from the URL
    $query = isset($_GET['query']) ? $conn->real_escape_string($_GET['query']) : '';
    ?>
    <header>
        <div class="logo">ReTech Hub</div>
        <form action="search.php" method="GET" class="search-form">
            <input type="text" name="query" placeholder="Search..." value="<?php echo htmlspecialchars($query); ?>">
            <button type="submit">Search</button>
        </form>
        <div class="nav-container">
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="categories.php">Categories</a></li>
                    <li><a href="cart.php">Cart</a></li>
                    <li><a href="profile.php">Profile</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <main>
        <section class="search-results">
            <h1 class="search-results-heading">Results</h1>
            <div class="product-list">
                <?php
                // Fetch matching products from the database
                $sql = "SELECT * FROM products WHERE product_name LIKE '%$query%'";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $product_id = $row['pid'];
                        echo "<div class='product'>
                            <a href='product_details.php?id=$product_id'>
                                <h3>" . htmlspecialchars($row['product_name']) . "</h3>
                                <img src='" . htmlspecialchars($row['images']) . "' alt='" . htmlspecialchars($row['product_name']) . "' />
                                <p>" . htmlspecialchars($row['description']) . "</p>
                                <p class='price'>Price: ₹" . htmlspecialchars($row['price']) . "</p>
                            </a>
                          </div>";
                    }
                } else {
                    echo "<p>No products found matching your query.</p>";
                }

                $conn->close();
                ?>
            </div>
        </section>
    </main>
    <footer>
        <p>&copy; 2024 ReTech Hub. All Rights Reserved.</p>
    </footer>
</body>


</html>