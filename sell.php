<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Database connection settings
require 'conn.php';

// Initialize success message variable
$success_message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $product_name = $conn->real_escape_string($_POST['product_name']);
    $description = $conn->real_escape_string($_POST['description']);
    $category = $conn->real_escape_string($_POST['category']);
    $price = $conn->real_escape_string($_POST['product_price']);

    // Handle image upload
    $target_dir = "uploads/";
    $timestamp = time();
    $imageFileType = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
    $target_file = $target_dir . $user_id . "_" . $timestamp . "." . $imageFileType;
    $uploadOk = 1;

    // Check if image file is an actual image or fake image
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if ($check === false) {
        echo "<script>alert('File is not an image.');</script>";
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["image"]["size"] > 500000) {
        echo "<script>alert('Sorry, your file is too large.');</script>";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if (!in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
        echo "<script>alert('Sorry, only JPG, JPEG, PNG & GIF files are allowed.');</script>";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "<script>alert('Sorry, your file was not uploaded.');</script>";
    } else {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image = $target_file;
            $sql = "INSERT INTO sell (user_id, product_name, description, category,price, image) VALUES ('$user_id', '$product_name', '$description', '$category','$price', '$image')";
            if ($conn->query($sql) === TRUE) {
                $success_message = "Product listed for sale successfully.";
            } else {
                echo "<script>alert('Error: " . $conn->error . "');</script>";
            }
        } else {
            echo "<script>alert('Sorry, there was an error uploading your file.');</script>";
        }
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sell Product - ReTech Hub</title>
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

        /* Form Section Styling */
        .sell-section {
            text-align: center;
            padding: 50px 20px;
            background: #f9f9f9;
            animation: fadeIn 1s ease-in-out;
        }

        .sell-section h2 {
            font-size: 32px;
            margin-bottom: 20px;
            color: #232f3e;
            font-weight: bold;
        }

        .sell-form {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .sell-form label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
            color: #333;
            font-size: 16px;
        }

        .sell-form input,
        .sell-form textarea,
        .sell-form select {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            outline: none;
            transition: box-shadow 0.3s ease;
        }

        .sell-form input:focus,
        .sell-form textarea:focus,
        .sell-form select:focus {
            box-shadow: 0 0 10px #ffa41c;
        }

        .sell-form button {
            display: block;
            width: 100%;
            padding: 12px;
            font-size: 18px;
            background-color: #ffa41c;
            color: #fff;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .sell-form button:hover {
            background-color: #cc8500;
            transform: scale(1.05);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sell-form {
                padding: 20px;
            }
        }

        .sell-form.hidden {
            display: none;
        }

        .success-message {
            text-align: center;
            margin-top: 20px;
        }

        .success-message button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #ffa41c;
            color: #fff;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .success-message button:hover {
            background-color: #cc8500;
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
    </style>
    <script>
        function showForm() {
            document.querySelector('.sell-form').classList.remove('hidden');
            document.querySelector('.success-message').classList.add('hidden');
        }
    </script>
</head>

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
    <section class="sell-section">
        <h2>List Your Product for Sale</h2>

        <?php if (!empty($success_message)): ?>
            <div class="success-message">
                <p><?= $success_message ?></p>
                <button onclick="showForm()">Sell Another Product</button>
            </div>
        <?php endif; ?>

        <form class="sell-form <?= !empty($success_message) ? 'hidden' : '' ?>" action="sell.php" method="POST" enctype="multipart/form-data">
            <label for="product_name">Product Name</label>
            <input type="text" id="product_name" name="product_name" placeholder="Enter product name" required>

            <label for="description">Description</label>
            <textarea id="description" name="description" rows="4" placeholder="Describe the product" required></textarea>

            <label for="category">Category</label>
            <select id="category" name="category" required>
                <option value="">Select a category</option>
                <option value="smartphones">Smartphones</option>
                <option value="laptops">Laptops</option>
                <option value="headphones">Headphones</option>
                <option value="accessories">Accessories</option>
                <option value="camera">Camera</option>
                <option value="audio">Audio</option>
            </select>

            <label for="product_price">Enter Price</label>
            <input type="text" id="product_price" name="product_price" placeholder="Enter product price" required>

            <label for="image">Product Image</label>
            <input type="file" id="image" name="image" accept="image/*" required>

            <button type="submit">List Product</button>
        </form>
    </section>
</main>
<footer>
    <p>&copy; 2024 ReTech Hub. All Rights Reserved.</p>
</footer>
</body>


</html>