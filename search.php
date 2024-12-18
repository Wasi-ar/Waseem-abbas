<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "imageupload";

// Database connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if 'Add to Cart' button is clicked
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_to_cart'])) {
    $productId = intval($_POST['product_id']); // Sanitize product ID
    // Here, you can insert the product into a cart table or session
    echo "<script>alert('Product with ID $productId added to cart!');</script>";
}

if (isset($_GET['search']) && !empty($_GET['search'])) {
    // Sanitize search input to avoid SQL injection
    $searchQuery = $conn->real_escape_string($_GET['search']);

    $sql = "SELECT id, image, name, description, promocode, price FROM images WHERE name LIKE '%$searchQuery%' LIMIT 30";

    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        // Display matching products
        while ($row = $result->fetch_assoc()) {
            echo "<div class='product-card'>";
            echo "<img src='" . htmlspecialchars($row['image'], ENT_QUOTES, 'UTF-8') . "' alt='" . htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8') . "' class='product-image'>";
            echo "<h3 class='product-name'>" . htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8') . "</h3>";
            echo "<p class='product-description'>" . htmlspecialchars($row['description'], ENT_QUOTES, 'UTF-8') . "</p>";
            echo "<p class='product-promocode'>Promo Code: " . htmlspecialchars($row['promocode'], ENT_QUOTES, 'UTF-8') . "</p>";
            // Display Price
            echo "<p class='price'>Price $" . htmlspecialchars($row['price'], ENT_QUOTES, 'UTF-8') . "</p>";
            echo "<form method='POST' action=''>";
            echo "<input type='hidden' name='product_id' value='" . htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') . "'>";
            echo '<button type="submit" name="add_to_cart">Add to Cart</button>';
            echo "</form>";
            echo "</div>";
        }
    } else {
        echo "<div class='no-products'>No products found matching your search.</div>";
    }
} else {
    // If no search query is given, show all products
    $sql = "SELECT id, image, name, description, promocode, price FROM images LIMIT 30";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='product-card'>";
            echo "<img src='" . htmlspecialchars($row['image'], ENT_QUOTES, 'UTF-8') . "' alt='" . htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8') . "' class='product-image'>";
            echo "<h3 class='product-name'>" . htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8') . "</h3>";
            echo "<p class='product-description'>" . htmlspecialchars($row['description'], ENT_QUOTES, 'UTF-8') . "</p>";
            echo "<p class='product-promocode'>Promo Code: " . htmlspecialchars($row['promocode'], ENT_QUOTES, 'UTF-8') . "</p>";
            // Display Price
            echo "<p class='price'> $" . htmlspecialchars($row['price'], ENT_QUOTES, 'UTF-8') . "</p>";
            echo "<form method='POST' action=''>";
            echo "<input type='hidden' name='product_id' value='" . htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') . "'>";
            echo '<button type="submit" name="add_to_cart">Add to Cart</button>';
            echo "</form>";
            echo "</div>";
        }
    } else {
        echo "<div class='no-products'>No products available.</div>";
    }
}

$conn->close();
?>