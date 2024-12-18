<?php
session_start(); // Ensure session is started

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Update Quantity
    if (isset($_POST['update_quantity']) && isset($_POST['product_id']) && isset($_POST['quantity'])) {
        $productId = $_POST['product_id'];
        $quantity = $_POST['quantity'];

        // Update the session data (your cart array)
        if (isset($_SESSION['images'][$productId])) {
            $_SESSION['images'][$productId]['quantity'] = $quantity;
            echo json_encode(['success' => true]); // Respond with success
        } else {
            echo json_encode(['success' => false]); // Respond with failure
        }
    }

    // Remove Product
    elseif (isset($_POST['remove_product']) && isset($_POST['product_id'])) {
        $productId = $_POST['product_id'];

        // Remove the product from the session cart
        if (isset($_SESSION['images'][$productId])) {
            unset($_SESSION['images'][$productId]);
            echo json_encode(['success' => true]); // Respond with success
        } else {
            echo json_encode(['success' => false]); // Respond with failure
        }
    }
}
?>