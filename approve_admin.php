<?php
// Include database connection
include 'conn.php';

// Check if the product_id and approve button are set
if (isset($_POST['approve'])) {
    $product_id = $_POST['product_id'];

    // Fetch product details from the 'sell' table
    $query = "SELECT * FROM sell WHERE id = $product_id";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("Database query failed: " . mysqli_error($conn));
    }

    $product = mysqli_fetch_assoc($result);

    if ($product) {
        // Prepare the insert query to add the product to the 'products' table
        $insert_query = "INSERT INTO products (product_name, description, category, images, price) 
                         VALUES ('" . mysqli_real_escape_string($conn, $product['product_name']) . "', 
                                 '" . mysqli_real_escape_string($conn, $product['description']) . "', 
                                 '" . mysqli_real_escape_string($conn, $product['category']) . "', 
                                 '" . mysqli_real_escape_string($conn, $product['image']) . "', 
                                 '" . mysqli_real_escape_string($conn, $product['price']) . "')"; // Default price, change this as needed.

        // Execute the insert query
        if (mysqli_query($conn, $insert_query)) {
            // Delete the product from the 'sell' table after transferring
            $delete_query = "DELETE FROM sell WHERE id = $product_id";
            if (mysqli_query($conn, $delete_query)) {
                // Redirect to admin dashboard after successful approval
                header("Location: admin_dashboard.php");
                exit();
            } else {
                die("Error deleting product from sell table: " . mysqli_error($conn));
            }
        } else {
            die("Error moving product to products table: " . mysqli_error($conn));
        }
    } else {
        die("Product not found.");
    }
} else {
    echo "Error: No product ID found.";
}

// Close database connection
mysqli_close($conn);
?>
