<?php
include "conn.php";
session_start(); // Start the session

// If no items are in the cart, redirect to the shopping page
if (!isset($_SESSION['images']) || count($_SESSION['images']) == 0) {
    header('Location: display.php');
    exit();
}

echo '<a href="display.php">
        <button type="button" class="submit-btn">Continue Shopping</button>
      </a>';

echo "<div class='review-order-container'>";
echo "<h2>Review Your Order</h2>";

$total_price = 0;
$total_quantity = 0;

echo "<form method='POST' action='checkout.php' class='review-form'>";
echo "<table class='checkout-table'>
        <tr>
            <th>Product Name</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Subtotal</th>
        </tr>";

// Debugging: Check session content
foreach ($_SESSION['images'] as $product_id => $product_details) {
    if (isset($product_details['name'], $product_details['price'], $product_details['quantity'], $product_details['image'])) {
        $subtotal = $product_details['price'] * $product_details['quantity'];
        $total_price += $subtotal;
        $total_quantity += $product_details['quantity'];

        echo "<tr>
                <td>" . htmlspecialchars($product_details['name']) . "</td>
                <td>$" . number_format($product_details['price'], 2) . "</td>
                <td>" . $product_details['quantity'] . "</td>
                <td>$" . number_format($subtotal, 2) . "</td>
              </tr>";
    } else {
        echo "<tr><td colspan='4'>Error: Missing product details for product ID $product_id.</td></tr>";
    }
}

echo "</table>";

echo "<div class='review-summary'>";
echo "<p><strong>Total Quantity:</strong> " . $total_quantity . "</p>";
echo "<p><strong>Total Price:</strong> $" . number_format($total_price, 2) . "</p>";
echo "</div>";

// Shipping address display (if available)
echo "<div class='shipping-address'>";
echo "<h3>Shipping Address</h3>";

if (isset($_SESSION['shipping_address'])) {
    $address = $_SESSION['shipping_address'];
    echo "<p><strong>Name:</strong> " . htmlspecialchars($address['full_name']) . "</p>";
    echo "<p><strong>Address:</strong> " . htmlspecialchars($address['address']) . "</p>";
    echo "<p><strong>City:</strong> " . htmlspecialchars($address['city']) . "</p>";
    echo "<p><strong>State:</strong> " . htmlspecialchars($address['state']) . "</p>";
    echo "<p><strong>Zip Code:</strong> " . htmlspecialchars($address['zip']) . "</p>";

} else {
    echo "<p>No shipping address available.</p>";
}
echo "</div>"; // End of shipping-address

// Add Stripe Payment Integration

echo "<div class='payment-options'>";
echo "<h3>Choose Payment Method</h3>";
echo "<div class='payment-method'>";
echo "<label><input type='radio' name='payment_method' value='credit_card' required> Credit Card</label><br>";
echo "<img src='visa.png' alt='Visa' class='payment-card'><img src='master.jpg' alt='Mastercard' class='payment-card'><img src='AE.png' alt='American Express' class='payment-card'>";

// Stripe Integration Section
echo "<h3>Stripe Payment</h3>";
echo "<div id='card-element'>
          <!-- A Stripe Element will be inserted here. -->
      </div>
      <div id='card-errors' role='alert'></div>
    ";

echo "</div>";
echo "<div class='payment-method'>";
echo "<label><input type='radio' name='payment_method' value='paypal' required> PayPal</label><br>";
echo "<label><input type='radio' name='payment_method' value='Cash on Delivery' required> Cash on Delivery</label>";
echo "</div>";
echo "</div>";

// Delivery types
echo "<div class='delivery-options'>";
echo "<h3>Select Delivery Method</h3>";
echo "<label><input type='radio' name='delivery_method' value='standard' required> Standard Delivery (5-7 days) - $5.00</label><br>";
echo "<label><input type='radio' name='delivery_method' value='express' required> Express Delivery (2-3 days) - $15.00</label>";
echo "</div>";

// Submit button

echo '<form id="orderForm">
        <button type="button" id="submit-btn" class="submit-btn" onclick="window.location.href=\'Thanku.php\';">Confirm Order</button>
      </form>';

echo "</form>";


echo "<a href='checkout.php'>
        <button type='button' class='submit-btn'>Edit Cart</button>
      </a>";

echo "</div>";

?>

<!-- Include Stripe.js -->
<script src="https://js.stripe.com/v3/"></script>
<script>
    var stripe = Stripe('pk_test_51QWwvxKn9sf0QjW7GV5PnbHVxj5wFoaUADMhhGBw2QPtaPe6nG9qv0BLvn6rOyUxd6wrbcEbthWj15QRA8MvtduT00pv7vhBeL'); // Replace with your own Publishable Key
    var elements = stripe.elements();

    // Create an instance of the card Element
    var card = elements.create('card');
    // Add the card Element to the DOM
    card.mount('#card-element');

    // Handle form submission
    var form = document.querySelector('form');
    form.addEventListener('submit', function(event) {
        event.preventDefault();

        stripe.createToken(card).then(function(result) {
            if (result.error) {
                // Display error in #card-errors
                var errorElement = document.getElementById('card-errors');
                errorElement.textContent = result.error.message;
            } else {
                // Append the token to the form and submit
                var token = result.token.id;
                var hiddenInput = document.createElement('input');
                hiddenInput.setAttribute('type', 'hidden');
                hiddenInput.setAttribute('name', 'stripeToken');
                hiddenInput.setAttribute('value', token);
                form.appendChild(hiddenInput);
                form.submit();
            }
        });
    });
</script>


<!-- Add styles here -->

<style>
    .shipping-address {
        background-color: #f9f9f9;
        padding: 15px;
        margin-top: 20px;
        border-radius: 4px;
    }

    .payment-options, .delivery-options {
        margin-top: 20px;
    }
     .payment-options, .delivery-options {
        margin-top: 20px;
    }

    .payment-card {
        margin: 10px;
        width: 50px;
        height: auto;
    }
    .review-order-container {
        width: 80%;
        margin: 30px auto;
        padding: 20px;
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
    }

    h2 {
        font-size: 24px;
        margin-bottom: 20px;
    }

    .checkout-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    .checkout-table th,
    .checkout-table td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    .checkout-table th {
        background-color: #f5f5f5;
        font-weight: bold;
    }

    .review-summary {
        margin-top: 20px;
        padding: 10px;
        background-color: #f9f9f9;
        border-radius: 4px;
        text-align: right;
    }

    .review-summary p {
        font-size: 18px;
        color: #555;
    }

    .submit-btn {
        margin-top: 20px;
        padding: 12px 20px;
        background-color: #4CAF50;
        color: white;
        border: none;
        cursor: pointer;
        font-size: 16px;
    }

    .submit-btn:hover {
        background-color: #45a049;
    }

    .shipping-address {
        margin-top: 20px;
        padding: 10px;
        background-color: #f9f9f9;
        border-radius: 4px;
    }

    .shipping-address h3 {
        font-size: 20px;
        margin-bottom: 10px;
    }

    .shipping-address p {
        font-size: 16px;
        color: #555;
    }
</style>