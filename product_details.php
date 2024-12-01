<?php
session_start();

// Database connection settings
require 'conn.php';

// Fetch product details
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$sql = "SELECT * FROM products WHERE pid='$product_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $product = $result->fetch_assoc();
} else {
    echo "Product not found.";
    exit();
}

// Fetch reviews for the product
$reviews_sql = "SELECT reviews.review_text, reviews.rating, reviews.review_date, users.username 
                FROM reviews 
                JOIN users ON reviews.user_id = users.id 
                WHERE reviews.product_id='$product_id' 
                ORDER BY reviews.review_date DESC";
$reviews_result = $conn->query($reviews_sql);
$reviews = [];
$total_rating = 0;
$review_count = 0;
if ($reviews_result->num_rows > 0) {
    while ($row = $reviews_result->fetch_assoc()) {
        $reviews[] = $row;
        $total_rating += $row['rating'];
        $review_count++;
    }
}
$average_rating = $review_count > 0 ? $total_rating / $review_count : 0;

// Handle add to cart
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_to_cart'])) {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }

    $user_id = $_SESSION['user_id'];
    $sql = "SELECT * FROM cart WHERE user_id='$user_id' AND product_id='$product_id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<script>
            if (confirm('Product is already in the cart. Do you wish to increase the quantity?')) {
                window.location.href = 'increase_quantity.php?product_id=$product_id';
            }
        </script>";
    } else {
        $sql = "INSERT INTO cart (user_id, product_id, quantity) VALUES ('$user_id', '$product_id', 1)";
        if ($conn->query($sql) === TRUE) {
            header("Location: cart.php");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}
// Handle buy now
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['buy_now'])) {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }

    $user_id = $_SESSION['user_id'];
    header("Location: payment_by_button.php?product_id=$product_id");
    exit();
}

// Handle review submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_review'])) {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }

    $user_id = $_SESSION['user_id'];
    $review_text = $conn->real_escape_string($_POST['review_text']);
    $rating = intval($_POST['rating']);

    $sql = "INSERT INTO review (product_id, user_id, review_text, rating) VALUES ('$product_id', '$user_id', '$review_text', '$rating')";
    if ($conn->query($sql) === TRUE) {
        header("Location: product_details.php?id=$product_id");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['product_name']); ?></title>
    <link rel="stylesheet" href="detail.css">
</head>
<style>
    /* General Styles */
    body {
        font-family: 'Arial', sans-serif;
        margin: 0;
        padding: 0;
        background: #f3f3f3;
        color: #333;
    }

    /* Header */
    header {
        background-color: #232f3e;
        color: #fff;
        padding: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
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
        transition: color 0.3s;
    }

    header nav ul li a:hover {
        color: #ffa41c;
    }

    /* Main Container */
    main {
        width: 90%;
        margin: 30px auto;
    }

    /* Product Details Section */
    .product-details {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        background: #fff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        animation: fadeIn 0.5s ease-in-out;
    }

    .product-images img {
        width: 100%;
        max-width: 500px;
        border-radius: 10px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        cursor: pointer;
    }

    .product-images img:hover {
        transform: scale(1.05);
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
    }

    .product-info h1 {
        color: #232f3e;
        font-size: 2em;
        margin-bottom: 10px;
    }

    .product-info p {
        margin: 15px 0;
        font-size: 1.1em;
        color: #555;
    }

    .product-info .price {
        color: #b12704;
        font-size: 1.8em;
        font-weight: bold;
    }

    .product-info form {
        display: flex;
        gap: 15px;
        margin-top: 20px;
    }

    .product-info button {
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

    .product-info button:hover {
        background-color: #cc8500;
    }

    .product-info button:focus {
        outline: 2px solid #b12704;
    }

    /* Additional Details */
    .product-features,
    .product-specifications,
    .customer-reviews {
        margin-top: 20px;
        padding: 20px;
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        animation: fadeIn 0.5s ease-in-out;
    }

    .product-features h2,
    .product-specifications h2,
    .customer-reviews h2 {
        color: #232f3e;
        margin-bottom: 10px;
        font-size: 1.5em;
    }

    .product-features ul {
        list-style: none;
        padding: 0;
        color: #555;
    }

    .product-features ul li {
        padding: 5px 0;
        border-bottom: 1px solid #eee;
    }

    .product-specifications table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
    }

    .product-specifications th,
    .product-specifications td {
        border: 1px solid #ddd;
        padding: 10px;
        font-size: 0.9em;
    }

    .product-specifications th {
        background-color: #232f3e;
        color: #fff;
        text-align: left;
    }

    .customer-reviews .review {
        padding: 15px;
        border: 1px solid #ddd;
        border-radius: 8px;
        margin-bottom: 10px;
        background-color: #f9f9f9;
    }

    .customer-reviews .review strong {
        color: #232f3e;
    }

    .customer-reviews .review small em {
        color: #888;
    }

    .star-rating {
        color: #ffa41c;
        font-size: 1.5em;
    }

    .loader {
        border: 6px solid #f3f3f3;
        border-top: 6px solid #232f3e;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        animation: spin 1s linear infinite;
        margin: 20px auto;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
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

    /* Animation */
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
        .product-details {
            grid-template-columns: 1fr;
        }

        .product-images img {
            max-width: 100%;
        }

        .product-info form {
            flex-direction: column;
            gap: 10px;
        }
    }
</style>

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
        <div class="product-details">
            <h1><?php echo htmlspecialchars($product['product_name']); ?></h1>
            <div class="product-images">
                <img src="<?php echo htmlspecialchars($product['images']); ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>">
            </div>
            <div class="product-info">
                <p><strong>Price:</strong> ₹<?php echo htmlspecialchars($product['price']); ?></p>
                <p><strong>Description:</strong> <?php echo htmlspecialchars($product['description']); ?></p>
                <p><strong>Category:</strong> <?php echo htmlspecialchars($product['category']); ?></p>
                <form method="post">
                    <button type="submit" name="buy_now" class="buy-now">Buy Now</button>
                    <button type="submit" name="add_to_cart" class="add-to-cart">Add to Cart</button>
                </form>
            </div>
            <div class="product-features">
                <h2>Product Features</h2>
                <ul>
                    <li>Feature 1</li>
                    <li>Feature 2</li>
                    <li>Feature 3</li>
                    <li>Feature 4</li>
                </ul>
            </div>
            <div class="product-specifications">
                <h2>Specifications</h2>
                <table>
                    <tr>
                        <th>Specification 1</th>
                        <td>Detail 1</td>
                    </tr>
                    <tr>
                        <th>Specification 2</th>
                        <td>Detail 2</td>
                    </tr>
                    <tr>
                        <th>Specification 3</th>
                        <td>Detail 3</td>
                    </tr>
                    <tr>
                        <th>Specification 4</th>
                        <td>Detail 4</td>
                    </tr>
                </table>
            </div>
            <!-- <div class="customer-reviews">
                <h2>Customer Reviews</h2>
                <p><strong>Overall Rating:</strong> <?php echo number_format($average_rating, 1); ?>/5</p>
                <?php if (empty($reviews)): ?>
                    <p>No reviews yet.</p>
                <?php else: ?>
                    <?php foreach ($reviews as $review): ?>
                        <div class="review">
                            <p><strong>Rating:</strong> <?php echo htmlspecialchars($review['rating']); ?>/5</p>
                            <p><?php echo htmlspecialchars($review['review_text']); ?></p>
                            <p><small><em>Reviewed by <?php echo htmlspecialchars($review['username']); ?> on <?php echo htmlspecialchars($review['review_date']); ?></em></small></p>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
                <button id="open-review-form" class="open-review-form">Write a Review</button>
            </div>
        </div> -->

        <!-- Review Form Modal -->
        <!-- <div id="review-form-modal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Write a Review</h2>
                <form method="post">
                    <label for="rating">Rating:</label>
                    <select id="rating" name="rating" required>
                        <option value="5">5 - Excellent</option>
                        <option value="4">4 - Very Good</option>
                        <option value="3">3 - Good</option>
                        <option value="2">2 - Fair</option>
                        <option value="1">1 - Poor</option>
                    </select><br><br>
                    <label for="review_text">Review:</label>
                    <textarea id="review_text" name="review_text" required></textarea><br><br>
                    <button type="submit" name="submit_review">Submit Review</button>
                </form>
            </div>
        </div>

        <script>
            // Get the modal
            var modal = document.getElementById("review-form-modal");

            // Get the button that opens the modal
            var btn = document.getElementById("open-review-form");

            // Get the <span> element that closes the modal
            var span = document.getElementsByClassName("close")[0];

            // When the user clicks the button, open the modal 
            btn.onclick = function() {
                modal.style.display = "block";
            }

            // When the user clicks on <span> (x), close the modal
            span.onclick = function() {
                modal.style.display = "none";
            }

            // When the user clicks anywhere outside of the modal, close it
            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }
        </script> -->
    </main>
    <footer>
        <p>&copy; 2024 ReTech Hub. All Rights Reserved.</p>
    </footer>
</body>

</html>