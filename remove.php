<?php
session_start();

if (isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];

    // Check if the product exists in the cart
    if (isset($_SESSION['images'][$product_id])) {
        unset($_SESSION['images'][$product_id]); // Remove product from session
    }
}

header('Location: cart.php'); // Redirect back to the cart page
exit();
?>