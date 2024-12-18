<div id="cartPanel" class="cart-panel">
    <div class="cart-header">
        <h2>Shopping Cart</h2>
        <span class="close-btn" onclick="toggleCart()">&times;</span>
    </div>
    <div class="cart-content">
        <?php
        if (isset($_SESSION['images']) && count($_SESSION['images']) > 0) {
            $total_price = 0;
            $total_quantity = 0;

            foreach ($_SESSION['images'] as $product_id => $product_details) {
                if (isset($product_details['name'], $product_details['price'], $product_details['quantity'], $product_details['image'])) {
                    // Calculate Subtotal
                    $subtotal = $product_details['price'] * $product_details['quantity'];

                    echo "<div class='cart-item'>";
                    echo "<img src='" . $product_details['image'] . "' alt='" . $product_details['name'] . "'>";
                    echo "<h3>" . $product_details['name'] . "</h3>";
                    echo "<p>Price: $" . $product_details['price'] . "</p>";

                    // Display Quantity
                    echo "<p>Quantity: " . $product_details['quantity'] . "</p>";

                    // Update Quantity Button (with AJAX)
                    echo "<form method='POST' action=''>";
                    echo "<label for='quantity_" . $product_id . "'>Quantity:</label>";
                    echo "<input type='number' id='quantity_" . $product_id . "' name='quantity' min='1' value='" . $product_details['quantity'] . "'>";
                    echo "<input type='hidden' name='product_id' value='" . $product_id . "'>";
                    echo "<button type='submit' name='update_quantity' class='update-btn'>Update</button>";
                    echo "</form>";

                    echo "<p class='subtotal'>Subtotal: $" . $subtotal . "</p>";

                    // Remove Product Button (with AJAX)
                    echo "<form method='POST' action=''>";
                    echo "<input type='hidden' name='product_id' value='" . $product_id . "'>";
                    echo "<button type='submit' name='remove_product' class='remove_product'>Remove</button>";
                    echo "</form>";

                    echo "</div>";

                    $total_price += $subtotal;
                    $total_quantity += $product_details['quantity'];
                }
            }

            // Cart Summary
            echo "<div class='cart-summary'>";
            echo "<p>Total Quantity: " . $total_quantity . "</p>";
            echo "<p>Total Price: $" . $total_price . "</p>";
            echo "</div>";

            // Proceed to Payment Button
            echo "<div class='proceed-payment'>";
            echo "<a href='checkout.php' class='proceed-btn'>Proceed to Payment</a>";
            echo "</div>";
        } else {
            echo "<p>Your cart is empty.</p>";
        }
        ?>
    </div>
</div>

<style>.proceed-btn {
    display: inline-block;
    padding: 10px 20px;
    background-color: #28a745;
    color: #fff;
    text-align: center;
    text-decoration: none;
    border-radius: 5px;
    margin-top: 15px;
    font-size: 16px;
}

.proceed-btn:hover {
    background-color: #218838;
}

.proceed-payment {
    text-align: center;
    margin-top: 20px;
}
</style>