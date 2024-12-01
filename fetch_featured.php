<?php
require 'conn.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Featured Products</title>
    <style>
                /* styles.css */
        .product {
            border: 1px solid #ddd;
            padding: 15px;
            margin: 15px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 300px;
        }
        
        .product img {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
        }
        
        .product h3 {
            font-size: 1.5em;
            margin: 10px 0;
        }
        
        .product p {
            font-size: 1em;
            color: #555;
        }
        
        .product a {
            text-decoration: none;
            color: #007bff;
        }
        
        .product a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<?php
// Fetch featured products
$sql = "SELECT * FROM products WHERE is_featured = 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $product_id = $row['pid'];
        echo "<div class='product'>
                <img src='" . htmlspecialchars($row['images']) . "' alt='" . htmlspecialchars($row['product_name']) . "' />
                <h3><a href='product_details.php?id=$product_id'>" . htmlspecialchars($row['product_name']) . "</a></h3>
                <p>" . htmlspecialchars($row['description']) . "</p>
                <p>Price: ₹" . htmlspecialchars($row['price']) . "</p>
              </div>";
    }
} else {
    echo "<p>No featured products available.</p>";
}

$conn->close();
?>
</body>
</html>