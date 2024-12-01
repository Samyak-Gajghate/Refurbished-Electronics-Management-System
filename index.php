<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReTech Hub - Homepage</title>
    <link rel="stylesheet" href="index.css">
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

        /* Search Section */
        .search-section {
            text-align: center;
            padding: 60px 20px;
            background: linear-gradient(to right, #232f3e, #39495e);
            color: #fff;
            animation: fadeIn 1s ease-in-out;
        }

        .search-section h1 {
            font-size: 38px;
            margin-bottom: 20px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .search-section form {
            display: flex;
            justify-content: center;
            margin-top: 30px;
            gap: 10px;
        }

        .search-section input {
            padding: 15px;
            font-size: 18px;
            width: 400px;
            border: 1px solid #ddd;
            border-radius: 8px;
            outline: none;
            transition: box-shadow 0.3s ease;
        }

        .search-section input:focus {
            box-shadow: 0 0 10px #ffa41c;
        }

        .search-section button {
            padding: 15px 30px;
            font-size: 18px;
            background-color: #ffa41c;
            color: #fff;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .search-section button:hover {
            background-color: #cc8500;
            transform: scale(1.05);
        }

        /* Categories Section */
        .categories {
            text-align: center;
            padding: 50px 20px;
            background: #f7f7f7;
            animation: fadeIn 1s ease-in-out;
        }

        .categories h2 {
            font-size: 32px;
            margin-bottom: 20px;
            color: #333;
            font-weight: bold;
        }

        .category-list {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
            margin-top: 20px;
        }

        .category-list a {
            text-decoration: none;
            padding: 15px 30px;
            background-color: #fff;
            border: 2px solid #ddd;
            color: #333;
            font-size: 18px;
            border-radius: 8px;
            font-weight: bold;
            transition: transform 0.2s ease, box-shadow 0.3s ease, border-color 0.3s ease;
        }

        .category-list a:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
            border-color: #ffa41c;
            color: #ffa41c;
        }

        /* Featured Products */
        .featured-products {
            text-align: center;
            padding: 50px 20px;
            background: #fff;
            animation: fadeIn 1s ease-in-out;
        }

        .featured-products h2 {
            font-size: 32px;
            margin-bottom: 20px;
            color: #232f3e;
            font-weight: bold;
        }

        .product-list {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
            margin-top: 20px;
        }

        .product-list .product {
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 8px;
            background: #f9f9f9;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 250px;
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .product-list .product h3 {
            font-size: 20px;
            margin-bottom: 10px;
            color: #333;
            font-weight: bold;
        }

        .product-list .product p {
            margin: 5px 0;
            color: #777;
            font-size: 16px;
        }

        .product-list .product:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
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
            .search-section input {
                width: 80%;
            }

            .category-list a {
                padding: 10px 20px;
                font-size: 16px;
            }

            .product-list .product {
                width: 100%;
                max-width: 300px;
            }
        }
    </style>
</head>

<body>
    <header>
        <div class="logo">ReTech Hub</div>
        <nav>
            <ul>
                <li><a href="sell.php">Sell</a></li>
                <li><a href="index.php">Home</a></li>
                <li><a href="categories.php">Categories</a></li>
                <li><a href="cart.php">Cart</a></li>
                <li><a href="profile.php">Profile</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <section class="search-section">
            <h1>Find Your Next Device</h1>
            <form action="search.php" method="GET">
                <input type="text" name="query" placeholder="Search for products..." required>
                <button type="submit">Search</button>
            </form>
        </section>
        <section class="categories">
            <h2>Browse by Categories</h2>
            <div class="category-list">
                <a href="categories.php?category=electronics">Electronics</a>
                <a href="categories.php?category=computers">Computers</a>
                <a href="categories.php?category=audio">Audio</a>
            </div>
        </section>
        <section class="featured-products">
            <h2>Featured Products</h2>
            <div class="product-list">
                <?php include 'fetch_featured.php'; ?>
            </div>
        </section>
    </main>
    <footer>
        <p>&copy; 2024 ReTech Hub. All Rights Reserved.</p>
    </footer>
</body>

</html>