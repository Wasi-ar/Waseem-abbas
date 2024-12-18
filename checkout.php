<?php
include "conn.php";
session_start(); // Start the session

// Add to Cart functionality
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_image = $_POST['product_image'];
    $quantity = $_POST['quantity'];

    // If the product is already in the cart, update the quantity
    if (isset($_SESSION['images'][$product_id])) {
        $_SESSION['images'][$product_id]['quantity'] += $quantity;
    } else {
        // Add the product to the cart session
        $_SESSION['images'][$product_id] = [
            'name' => $product_name,
            'price' => $product_price,
            'quantity' => $quantity,
            'image' => $product_image
        ];
    }
    // Redirect to cart page or stay on the same page
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

echo '<a href="display.php">
        <button type="button" class="submit-btn">Continue Shopping</button>
      </a>';

// Checkout Section
if (isset($_SESSION['images']) && count($_SESSION['images']) > 0) {
    echo "<div class='parent-container'>"; // Parent container starts

    // Combined Checkout & Payment container
    echo "<div class='checkout-payment-container'>";
    echo "<h2>Checkout</h2>"; // Checkout section header

    $total_price = 0;
    $total_quantity = 0;

    // Checkout table with product details
    echo "<form method='POST' action='' class='checkout-form'>";
    echo "<table class='checkout-table'>
            <tr>
                <th>Product Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Subtotal</th>
            </tr>";

    foreach ($_SESSION['images'] as $product_id => $product_details) {
        if (isset($product_details['name'], $product_details['price'], $product_details['quantity'], $product_details['image'])) {
            $subtotal = $product_details['price'] * $product_details['quantity'];
            $total_price += $subtotal;
            $total_quantity += $product_details['quantity'];

            echo "<tr>
                    <td>" . $product_details['name'] . "</td>
                    <td>$" . $product_details['price'] . "</td>
                    <td>" . $product_details['quantity'] . "</td>
                    <td>$" . $subtotal . "</td>
                  </tr>";
        }
    }

    echo "</table>";
    echo "</form>";

    echo "<div class='checkout-summary'>";
    echo "<p><strong>Total Quantity:</strong> " . $total_quantity . "</p>";
    echo "<p><strong>Total Price:</strong> $" . $total_price . "</p>";
    echo "</div>";

    // Payment Information form
    echo "<h3>Payment Information</h3>";
    echo "<form action='' method='POST' class='payment-form'>";
    echo "<label for='card_number'>Card Number:</label>";
    echo "<input type='text' id='card_number' name='card_number' required placeholder='Enter your card number' maxlength='16'>";
    echo "<label for='exp_date'>Expiration Date:</label>";
    echo "<input type='text' id='exp_date' name='exp_date' required placeholder='MM/YY'>";
    echo "<label for='cvv'>CVV:</label>";
    echo "<input type='text' id='cvv' name='cvv' required placeholder='Enter your CVV' maxlength='3'>";
    echo "<label for='billing_zip'>Billing Zip Code:</label>";
    echo "<input type='text' id='billing_zip' name='billing_zip' required placeholder='Enter your billing zip code'>";
    echo '<a href="review_order.php">
       
      </a>';
    echo "</form>";

    echo "</div>"; // End of checkout-payment-container

    // Billing Address form placed separately
    echo "<div class='billing-container'>";
    echo "<h3>Billing Address</h3>";
    echo "<form action='' method='POST' class='billing-form'>";
    echo "<label for='full_name'>Full Name:</label>";
    echo "<input type='text' id='full_name' name='full_name' required placeholder='Enter your full name'>";
    echo "<label for='address'>Address:</label>";
    echo "<input type='text' id='address' name='address' required placeholder='Enter your address'>";
    echo "<label for='city'>City:</label>";
    echo "<input type='text' id='city' name='city' required placeholder='Enter your city'>";
    echo "<label for='state'>State:</label>";
    echo "<input type='text' id='state' name='state' required placeholder='Enter your state'>";
    echo "<label for='zip'>Zip Code:</label>";
    echo "<input type='text' id='zip' name='zip' required placeholder='Enter your zip code'>";
   
    echo "<button type='submit' class='submit-btn'>Submit Shipping Information</button>";
    echo "</form>";
    echo "</div>"; // End of billing-container

    echo "</div>"; // End of parent-container
} else {
    echo "<p>Your cart is empty.</p>";
    exit;
}

// Processing form submission to store shipping address in session
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['full_name'])) {
    $_SESSION['shipping_address'] = [
        'full_name' => $_POST['full_name'],
        'address' => $_POST['address'],
        'city' => $_POST['city'],
        'state' => $_POST['state'],
        'zip' => $_POST['zip'],
        'phone' => $_POST['Phone']
    ];
    // Redirect to review order page
    header("Location: review_order.php");
    exit();
}
?>

<script>
    // Your JavaScript code for dropdown handling can be kept as is.
</script>

<style>
    /* Styling as per previous code for checkout, forms, and buttons */
    .parent-container {
        display: flex;
        flex-direction: row-reverse;
        gap: 20px;
    }

    .checkout-payment-container {
        border: 1px solid #ddd;
        padding: 20px;
        background-color: #fff;
        width: 50%;
        margin-top: 20px;
        margin-right: 20px;
    }

    .checkout-form,
    .payment-form,
    .billing-form {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .checkout-table {
        width: 100%;
        border-collapse: collapse;
    }

    .checkout-table th,
    .checkout-table td {
        padding: 8px;
        text-align: left;
    }

    .checkout-summary {
        margin-top: 20px;
    }

    .billing-container {
        border: 1px solid #ddd;
        padding: 20px;
        background-color: #fff;
        margin-top: 20px;
        width: 50%;
        margin-left: 20px;
    }

    .billing-form label,
    .payment-form label {
        margin-top: 10px;
    }

    .billing-form input,
    .payment-form input {
        width: 100%;
        padding: 10px;
        margin-top: 5px;
    }

    .submit-btn {
        margin-top: 20px;
        padding: 10px;
        background-color: #4CAF50;
        color: white;
        border: none;
        cursor: pointer;
    }

    .submit-btn:hover {
        background-color: #45a049;
    }
</style>