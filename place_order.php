<?php
include "conn.php";

session_start(); // Start the session

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $quantity = $_POST['quantity'];
    $total_price = $_POST['total_price'];
    $total_quantity = $_POST['total_quantity'];

    // Prepare the SQL query
    $stmt = $conn->prepare("INSERT INTO orders (name, email, address,quantity, total_price, total_quantity) VALUES (?, ?, ?, ?, ?)");

    // Check if $stmt is valid
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    // Bind parameters
    if (!$stmt->bind_param("sssdi", $name, $email, $address, $total_price, $total_quantity)) {
        die("Bind failed: " . $stmt->error);
    }

    // Execute the statement
    if ($stmt->execute()) {
        echo "<p>Order placed successfully!</p>";

        // Clear the cart
        unset($_SESSION['images']);

        echo "<a href='display.php'>Return to Home</a>";
    } else {
        echo "<p>Error placing order: " . $stmt->error . "</p>";
    }

    $stmt->close();
    $conn->close();
}
?>

