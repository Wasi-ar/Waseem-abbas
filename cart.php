<?php
include "conn.php";

session_start();  // Start the session

// Check if the cart has products
if (isset($_SESSION['images']) && count($_SESSION['images']) > 0) {
    echo "<h2>Your Cart</h2>";

    $total_price = 0;  // Variable to store the total price
    $total_quantity = 0;  // Variable to store the total quantity

    // Loop through the cart items
    foreach ($_SESSION['images'] as $product_id => $product_details) {
        // Check if the necessary keys exist in the array
        if (isset($product_details['name'], $product_details['price'], $product_details['quantity'], $product_details['image'])) {
            echo "<div class='cart-item'>";
            // Display product image
            echo "<img src='" . $product_details['image'] . "' alt='" . $product_details['name'] . "' width='100'>";  // Adjust width as needed
            echo "<h3>" . $product_details['name'] . "</h3>";
            echo "<p>Price: $" . $product_details['price'] . "</p>";
            echo "<p>Quantity: " . $product_details['quantity'] . "</p>";
            echo "</div>";
          
            // Add the product's total price and quantity to the totals
            $total_price += $product_details['price'] * $product_details['quantity'];
            $total_quantity += $product_details['quantity'];
        } else {
            echo "<p>Product details are missing for product ID: $product_id</p>";
        }
    }
    

    echo "<p>Total Quantity: " . $total_quantity . "</p>";  // Display total quantity
    echo "<p>Total Price: $" . $total_price . "</p>";  // Display total price
    echo "<a href='checkout.php'>Proceed to Checkout</a>";  // Checkout link
} else {
    echo "<p>Your cart is empty.</p>";
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cart'])) {
    $product_id = intval($_POST['product_id']);
    $new_quantity = intval($_POST['quantity']);

    if (isset($_SESSION['images'][$product_id]) && $new_quantity > 0) {
        $_SESSION['images'][$product_id]['quantity'] = $new_quantity;
        echo "<script>alert('Quantity updated successfully!');</script>";
    } elseif ($new_quantity <= 0) {
        // Remove item if quantity is set to 0 or less
        unset($_SESSION['images'][$product_id]);
        echo "<script>alert('Product removed from cart!');</script>";
    }

    // Redirect back to cart
    echo "<script>window.location.href = 'cart.php';</script>";
    exit;
}
?>



<style>

    /* ---------product card start------- */
    body {
    font-family: Arial, sans-serif;
    background-color: #f9f9f9;
    margin: 0;
    padding: 0;
    line-height: 1.6;
}

h2 {
    text-align: center;
    color: #333;
    margin-top: 20px;
}

.cart-item {
    
    align-items: center;
    background-color: #fff;
    margin: 10px auto;
    padding: 15px;
    border: 1px solid #ddd;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    max-width: 300px;
    height: 40%;
}


.cart-item img {
    margin-right: 20px;
    border-radius: 5px;
    border: 1px solid #ccc;
    width: 300px;
}

.cart-item h3 {
    color: #555;
    margin: 0;
    font-size: 1.2rem;
}

.cart-item p {
    margin: 5px 0;
    color: #666;
}

p {
    text-align: center;
}

a {
    display: inline-block;
    text-decoration: none;
    color: #fff;
    background-color: #007bff;
    padding: 10px 20px;
    border-radius: 5px;
    margin: 20px auto;
    text-align: center;
}

a:hover {
    background-color: #0056b3;
}

.total-info {
    text-align: center;
    background-color: #fff;
    margin: 20px auto;
    padding: 15px;
    border: 1px solid #ddd;
    border-radius: 5px;
    max-width: 400px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.total-info p {
    font-size: 1.1rem;
    color: #333;
}

    /* ---------product card end------- */

</style>