<?php

session_start();  // Start session to check login status
include "conn.php";

if (!isset($_SESSION['images']) || count($_SESSION['images']) === 0) {
  $_SESSION['images'] = []; // Dummy data if cart is empty
}

// Check if the user is logged in, else redirect to login page
if (!isset($_SESSION['user_id'])) {
  header('Location: login.php');
  exit();
}
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "imageupload";

$conn = new mysqli($servername, $username, $password, $dbname);

$product_id = 1;
$product_name = 'Product A';
$product_price = 100;
$product_quantity = 2;

$_SESSION['images'][$product_id] = [
  'name' => $product_name,
  'price' => $product_price,
  'quantity' => $product_quantity,
  
];



if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
// Assuming product_id is passed when adding to cart
if (isset($_POST['add_to_cart'])) {
  $product_id = $_POST['product_id'];  // Get product_id from form

  // Fetch product details from the "images" table
  $query = "SELECT name, price, image FROM images WHERE id = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("i", $product_id);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $product = $result->fetch_assoc();
    $product_name = $product['name'];
    $product_price = $product['price'];
    $product_image = $product['image'];  // Get the image URL

    // Check if 'images' session exists, if not, initialize it
    if (!isset($_SESSION['images'])) {
      $_SESSION['images'] = array();
    }

    // If product already in cart, update quantity
    if (isset($_SESSION['images'][$product_id])) {
      $_SESSION['images'][$product_id]['quantity'] += 1;
    } else {
      $_SESSION['images'][$product_id] = array(
        'name' => $product_name,
        'price' => $product_price,
        'quantity' => 1,
        'image' => $product_image  // Store the image URL in session
      );
    }

    // Redirect to cart page
    header("Location: display.php");
    exit;
  } else {
    echo "Product not found!";
  }
}

// new product card

// // Check if the cart has products
// if (isset($_SESSION['images']) && count($_SESSION['images']) > 0) {
//   echo "<h2>Your Cart</h2>";

//   $total_price = 0;  // Variable to store the total price
//   $total_quantity = 0;  // Variable to store the total quantity

//   //     // Loop through the cart items
//   foreach ($_SESSION['images'] as $product_id => $product_details) {
//     // Check if the necessary keys exist in the array
//     if (isset($product_details['name'], $product_details['price'], $product_details['quantity'], $product_details['image'])) {
//       echo "<div class='cart-item'>";
//       echo "<h4>Product Details</h4>"; // Add heading for product details
// //             // Display product image
//       echo "<img src='" . $product_details['image'] . "' alt='" . $product_details['name'] . "' width='100'>";  // Adjust width as needed
//       echo "<h3>" . $product_details['name'] . "</h3>";
//       echo "<p>Price: $" . $product_details['price'] . "</p>";
//       echo "<p>Quantity: " . $product_details['quantity'] . "</p>";
//       echo "</div>";

//       //             // Add the product's total price and quantity to the totals
//       $total_price += $product_details['price'] * $product_details['quantity'];
//       $total_quantity += $product_details['quantity'];
//     } else {
//       echo "<p>Product details are missing for product ID: $product_id</p>";
//     }
//   }

//   //     // Display total quantity with heading
//   echo "<h4>Cart Summary</h4>"; // Add heading for cart summary
//   echo "<p><strong>Total Quantity:</strong> " . $total_quantity . "</p>";

//   //     // Display total price with heading
//   echo "<p><strong>Total Price:</strong> $" . $total_price ."</p>";

//   echo "<a href='checkout.php'>Proceed to Checkout</a>"; // Checkout link
// } else {
//   echo "<p>Your cart is empty.</p>";
// }

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Product Search</title>
  <link rel="stylesheet" href="project.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
  <div class="header">
    <div class="container">
      <div class="row" style="display: flex; justify-content: space-between; align-items: center;">
        <!-- Left Button (Logo) -->
        <div class="col-md-4" style="flex: 1;">
          <div class="logo">
            <img src="logo.webp" alt="">
          </div>
        </div>

        <div class="col-md-4" style="flex: 1; text-align: center;">
          <div class="search-container" style="position: relative;">
            <!-- Only the search icon is initially visible -->
            <i class="fas fa-search search-icon" onclick="toggleSearchBox()"
              style="cursor: pointer; font-size: 24px;"></i>
            <!-- Hidden search input box, will appear when the search icon is clicked -->
            <input type="text" id="searchInput" placeholder="Search for products..." onkeyup="searchProducts()"
              style="width: 100%; padding: 10px; font-size: 16px; display: none; margin-top: 10px;">
          </div>
        </div>

        <!-- Right Buttons (Logout and Contact Us) -->
        <div class="col-md-4" style="flex: 1; display: flex; justify-content: flex-end;">
          <form method="POST" action="logout.php">
            <button type="submit" name="logout">Logout</button>
          </form>
          <a id="openPopup" href="contact.php">Contact Us</a>
        </div>
        <!-- slide cart start -->
<!-- Cart Button -->
<?php


// Update Quantity
if (isset($_POST['update_quantity'])) {
  $product_id = $_POST['product_id'];
  $new_quantity = $_POST['quantity'];

  // Check if the product exists in the session cart
  if (isset($_SESSION['images'][$product_id])) {
    $_SESSION['images'][$product_id]['quantity'] = $new_quantity;
  }
}

// Remove Product from Cart
if (isset($_POST['remove_product'])) {
  $product_id = $_POST['product_id'];
  unset($_SESSION['images'][$product_id]);
}

?>
<button class="cart-btn" onclick="toggleCart()">
  <i class="fas fa-shopping-cart"></i>
</button>

<!-- Cart Panel -->
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

          // Display Quantity and Update Form
          // This is your original code wrapped inside a cart display loop
          echo "<form method='POST' action='' id='updateForm_$product_id' class='update-form'>";
          echo "<label for='quantity_" . $product_id . "'>Quantity:</label>";
          echo "<input type='number' id='quantity_" . $product_id . "' name='quantity' min='1' value='" . $product_details['quantity'] . "'>";
          echo "<input type='hidden' name='product_id' value='" . $product_id . "'>";
          echo "<button type='button' class='update-btn' onclick='updateQuantity($product_id)'>Update</button>"; // Using onclick to trigger AJAX
          echo "</form>";

          echo "<p>Quantity: " . $product_details['quantity'] . "</p>";
          echo "<p class='subtotal'>Subtotal: $" . $subtotal . "</p>";

          // Remove Product Button (AJAX)
          echo "<form method='POST' action='' id='removeForm_$product_id' class='remove-form'>";
          echo "<input type='hidden' name='product_id' value='" . $product_id . "'>";
          echo "<button type='button' class='remove_product' onclick='removeProduct($product_id)'>Remove</button>"; // Using onclick to trigger AJAX
          echo "</form>";

          echo "</div>";
          $total_price += $subtotal;
          $total_quantity += $product_details['quantity'];
        }
      }

      echo "<div class='cart-summary'>";
      echo "<p>Total Quantity: " . $total_quantity . "</p>";
      echo "<p>Total Price: $" . $total_price . "</p>";
      echo "</div>";

      echo "<a href='checkout.php'>Proceed to Checkout</a>";
    } else {
      echo "<p>Your cart is empty.</p>";
    }
    ?>
  </div>
</div>
<!-- slide cart end -->
     </div>
    </div>
  </div>

  <!-- Slideshow Section -->
  <section class="slider_container">
    <section class="slider">
      <div class="slide one">
        <img src="slide3.jpg" alt="" />
      </div>

      <div class="slide two">
        <img src="slide4.jpg" alt="" />
      </div>

      <div class="slide three">
        <img src="slide1.jpg" alt="" />
      </div>

      <div class="slide four">
        <img src="slide5.jpg" alt="" />
      </div>

      <div class="slide five">
        <img src="slide3.jpg" alt="" />
      </div>

    </section>
  </section>
  <div id="searchResults"></div>
  <script>
// AJAX function to update quantity
// AJAX function to update quantity
function updateQuantity(productId) {
    var quantity = document.getElementById('quantity_' + productId).value;

    // Check if the quantity is valid
    if (quantity < 1) {
        alert('Quantity must be greater than 0');
        return;
    }

    var formData = new FormData();
    formData.append('product_id', productId);
    formData.append('quantity', quantity);
    formData.append('update_quantity', true); // Flag for update action

    // AJAX request to update cart
    fetch('cart_action.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert("Cart updated successfully!");
            location.reload(); // Reload the page to reflect changes
        } else {
            alert("Failed to update the cart.");
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('There was an error processing the update.');
    });
}

// AJAX function to remove product from cart
function removeProduct(productId) {
    var formData = new FormData();
    formData.append('product_id', productId);
    formData.append('remove_product', true); // Flag for remove action

    // AJAX request to remove product
    fetch('cart_action.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert("Product removed from cart!");
            location.reload(); // Reload the page to reflect changes
        } else {
            alert("Failed to remove product.");
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('There was an error processing the removal.');
    });
}


 function toggleCart() {
            const cartPanel = document.getElementById('cartPanel');
            cartPanel.classList.toggle('open');
        }


    $(document).ready(function () {
      if (localStorage.getItem('searchQuery')) {
        var searchQuery = localStorage.getItem('searchQuery');
        $('#searchInput').val(searchQuery);
        searchProducts(); // Perform search based on saved query
      } else {
        fetchAllProducts(); // If no query, fetch all products
      }

      // Close search bar if clicked outside
      $(document).click(function (event) {
        if (!$(event.target).closest('.search-container').length) {
          $('#searchInput').hide(); // Hide the search input when clicking outside
        }
      });
    });

    // Function to toggle the search box visibility
    function toggleSearchBox() {
      const searchInput = document.getElementById("searchInput");
      if (searchInput.style.display === "none" || searchInput.style.display === "") {
        searchInput.style.display = "block"; // Show the search input
        searchInput.focus(); // Focus on the input field
      } else {
        searchInput.style.display = "none"; // Hide the search input
      }
    }

    // Function to perform search
    function searchProducts() {
      var query = document.getElementById('searchInput').value;
      // Save search query to localStorage
      localStorage.setItem('searchQuery', query);
      if (query.length > 0) { // Trigger search when query has content
        $.ajax({
          url: 'search.php',  // PHP script to handle search
          method: 'GET',
          data: { search: query }, // Send search query to PHP
          success: function (response) {
            $('#searchResults').html(response); // Display search results
          },
          error: function () {
            $('#searchResults').html('<p>Error fetching search results.</p>'); // Handle errors
          }
        });
      } else if (query.length === 0) {  // If input is empty
        fetchAllProducts(); // Fetch all products if the input is empty
      }
    }

    // Function to fetch all products when the search box is empty
    function fetchAllProducts() {
      $.ajax({
        url: 'search.php',  // PHP script to fetch all products
        method: 'GET',
        success: function (response) {
          $('#searchResults').html(response); // Display all products in the results section
        },
        error: function () {
          $('#searchResults').html('<p>Error fetching all products.</p>'); // Handle errors
        }
      });
    }


    // card button
    function toggleCart() {
      const cartDropdown = document.getElementById("shoppingCartDropdown");
      const closePopup = document.getElementById("closeCartPopup");

      // Toggle the visibility of the shopping cart dropdown
      cartDropdown.classList.toggle("show");

      // Toggle close icon visibility
      if (cartDropdown.classList.contains("show")) {
        closePopup.style.display = "block";  // Show close icon
      } else {
        closePopup.style.display = "none";  // Hide close icon
      }
    }

    function toggleCart() {
      const cartPanel = document.getElementById('cartPanel');
      cartPanel.classList.toggle('open'); // 'open' class add/remove karta hai
    }

  </script>

  <footer>
    <div class="footer-container">
      <div class="footer-section">
        <h3>Company</h3>
        <ul>
          <li><a href="http://localhost/product/about.php">About Us</a></li>
          <li><a href="http://localhost/product/about.php">Careers</a></li>
          <li><a href="http://localhost/product/about.php">Press</a></li>
          <li><a href="http://localhost/product/about.php">Blog</a></li>
        </ul>
      </div>

      <div class="footer-section">
        <h3>Customer Service</h3>
        <ul>
          <li><a href="#">Contact Us</a></li>
          <li><a href="#">Order Status</a></li>
          <li><a href="#">Shipping & Returns</a></li>
          <li><a href="#">Privacy Policy</a></li>
        </ul>
      </div>

      <div class="footer-section">
        <h3>Quick Links</h3>
        <ul>
          <li><a href="#">FAQ</a></li>
          <li><a href="#">Terms & Conditions</a></li>
          <li><a href="#">Site Map</a></li>
          <li><a href="#">Gift Cards</a></li>
        </ul>
      </div>

      <div class="footer-section">
        <h3>Follow Us</h3>
        <ul class="social-links">
          <li><a href="https://www.facebook.com/regexdot/" class="facebook"><i class="fab fa-facebook-f"></i></a></li>
          <li><a href="#" class="https://www.linkedin.com/company/regexdot/"><i class="fab fa-twitter"></i></a></li>
          <li><a href="https://www.instagram.com/regexdot/" class="instagram"><i class="fab fa-instagram"></i></a></li>
          <li><a href="https://regex.global/" class="youtube"><i class="fab fa-youtube"></i></a></li>
        </ul>
      </div>
    </div>

    <div class="footer-bottom">
      <p>&copy; E-commerce Site. All Rights Reserved.</p>
    </div>
  </footer>
  <style>
    .logo img {
      border-radius: 50%;
      overflow: hidden;
      margin: 0;
      text-align: left;
      margin-left: -65%;
      width: 70px;
    }

    /* footer */
    footer {
      background-color: #2c3e50;
      color: white;
      padding: 40px 20px;
    }

    .footer-container {
      display: flex;
      justify-content: space-between;
      flex-wrap: wrap;
    }

    .footer-section {
      flex: 1;
      min-width: 220px;
      margin: 10px;
    }

    .footer-section h3 {
      font-size: 1.2em;
      margin-bottom: 20px;
      text-transform: uppercase;
    }

    .footer-section ul {
      list-style-type: none;
      padding: 0;
    }

    .footer-section ul li {
      margin-bottom: 10px;
    }

    .footer-section ul li a {
      color: #ecf0f1;
      text-decoration: none;
      font-size: 0.9em;
    }

    .footer-section ul li a:hover {
      color: #3498db;
    }

    .social-links {
      display: flex;
      gap: 10px;
      flex-direction: row;
      justify-content: space-between;
    }


    .social-links li {
      list-style-type: none;
    }

    .social-links li a {
      color: #ecf0f1;
      font-size: 1.3em;
      text-decoration: none;
      display: flex;
      align-items: center;
    }

    .social-links li a i {
      margin-right: 8px;
      /* Space between icon and text */
    }

    .social-links li a:hover {
      color: #3498db;
    }

    .footer-bottom {
      text-align: center;
      margin-top: 20px;
    }

    .footer-bottom p {
      font-size: 25px;
    }


    /* slide */
    .slider_container {
      position: relative;
      width: 100%;
      height: 500px;
      display: flex;
      justify-content: flex-start;
      align-items: center;
      overflow: hidden;
    }

    .slider {
      position: relative;
      width: 100%;
      height: 500px;
      display: flex;
      flex-direction: row;
      align-items: center;
      justify-content: flex-start;
      animation: sliding 9s infinite ease-in-out;
    }

    .slide {
      position: relative;
      width: 100%;
      height: 500px;
      flex: 0 0 100%;
    }

    .slide img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .slide.one {
      background: rgb(182, 19, 109);
    }

    .slide.two {
      background: rgb(255, 64, 64);
    }

    .slide.three {
      background: rgb(11, 173, 188);
    }

    .slide.four {
      background: rgb(11, 188, 14);
    }

    .slide.five {
      background: rgb(173, 11, 188);
    }

    @keyframes sliding {
      0% {
        transform: translateX(0%);
      }

      20% {
        transform: translateX(0%);
      }

      25% {
        transform: translateX(-100%);
      }

      45% {
        transform: translateX(-100%);
      }

      50% {
        transform: translateX(-200%);
      }

      70% {
        transform: translateX(-200%);
      }

      75% {
        transform: translateX(-300%);
      }

      95% {
        transform: translateX(-300%);
      }

      100% {
        transform: translateX(-400%);
      }
    }

    /* search box */
    .search-icon {
      position: absolute;
      right: 10px;
      top: 20%;
      transition: background-color 0.3s ease;
      cursor: pointer;
      color: white;
    
    }

    .search-icon:hover {
      color: whitesmoke;
    }

    #searchBox {
      position: absolute;
      top: 0;
      left: 30px;
      display: none;
      /* Hidden by default */
      width: 200px;
      
    }

    .search-box input {
      width: 100%;
      padding: 8px 12px;
      font-size: 16px;
      border: 1px solid #ccc;
      border-radius: 4px;
      outline: none;
      color: #0056b3;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    @media(max-width: 800px) {
      .mySlides img {
        width: 100%;
        height: 500px;
        object-fit: cover;
      }

      .slideshow-container {
        position: relative;
      }

      .prev,
      .next {
        position: absolute;
        top: 50%;
        padding: 12px;
        background-color: rgba(0, 0, 0, 0.5);
        color: white;
        font-size: 18px;
        border: none;
        cursor: pointer;
        z-index: 1;
        transition: background-color 0.3s;
      }

      .prev:hover,
      .next:hover {
        background-color: rgba(0, 0, 0, 0.8);
      }

      .prev {
        left: 0;
      }

      .next {
        right: 0;
      }

      .search-icon {
        position: absolute;
        left: 9em;
        top: 20%;
        transition: background-color 0.3s ease;
        cursor: pointer;
        color: white;
      }
    }

    @media(max-width: 500px) {
      .mySlides img {
        width: 100%;
        height: 200px;
        object-fit: cover;
      }

      .slideshow-container {
        position: relative;
      }

      .prev,
      .next {
        position: absolute;
        top: 50%;
        padding: 12px;
        background-color: rgba(0, 0, 0, 0.5);
        color: white;
        font-size: 18px;
        border: none;
        cursor: pointer;
        z-index: 1;
        transition: background-color 0.3s;
      }

      .prev:hover,
      .next:hover {
        background-color: rgba(0, 0, 0, 0.8);
      }

      .prev {
        left: 0;
      }

      .next {
        right: 0;
      }

      .search-icon {
        position: absolute;
        left: em;
        top: 20%;
        transition: background-color 0.3s ease;
        cursor: pointer;
        color: white;
      }
    }

    @media(max-width: 600px) {
      .slider_container {
        position: relative;
        width: 100%;
        height: 200px;
        display: flex;
        justify-content: flex-start;
        align-items: center;
        overflow: hidden;
      }

      .slider {
        position: relative;
        width: 100%;
        height: 200px;
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
        animation: 30s cubic-bezier(1, 0.95, 0.565, 1) sliding infinite;
      }

      .slide {
        position: relative;
        min-width: 100%;
        height: 200px;
      }

      .slide img {
        width: 104%;
        height: 200px;
      }
    }


    /* ---------product card start------- */
 /* Cart Panel Styles */
.cart-panel {
    position: fixed;
    top: 0;
    right: -650px; /* Initially hidden */
    width: 650px;
    height: 100%;
    background: linear-gradient(to bottom, #ffffff, #f3f3f3);
    box-shadow: -3px 0 10px rgba(0, 0, 0, 0.2);
    overflow-y: auto;
    transition: right 0.4s ease-in-out;
    z-index: 1000;
    border-left: 2px solid #007bff;
}

.cart-panel.open {
    right: 0; /* Show the cart */
}

.cart-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px;
    background: #007bff;
    color: white;
    position: sticky;
    top: 0;
    z-index: 10;
    border-bottom: 1px solid #0056b3;
}

.cart-header h2 {
    margin: 0;
    font-size: 20px;
    text-transform: uppercase;
    background: none;
}

.close-btn {
    cursor: pointer;
    font-size: 22px;
    font-weight: bold;
    transition: color 0.3s ease;
}

.close-btn:hover {
    color: #ff0000;
}

.cart-item {
    display: flex;
    align-items: center;
    padding: 15px;
    border-bottom: 1px solid #ddd;
    gap: 10px;
    transition: background-color 0.3s ease;
}

.cart-item:hover {
    background-color: #f1f1f1;
}

.cart-item img {
    max-width: 60px;
    height: auto;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.cart-item-details {
    flex: 1;
}

.cart-item-details h4 {
    margin: 0 0 5px;
    font-size: 16px;
    color: #333;
}
h3 {
    margin: 0 0 5px;
    font-size: 15px;
    color: #333;
}

p {
    margin: 0 0 5px;
    font-size: 14px;
    color: #333;
}

.cart-item-details p {
    margin: 0;
    font-size: 14px;
    color: #666;
}

.cart-summary {
    padding: 20px;
    color: white;
    font-weight: bold;
    text-align: center;
    text-transform: uppercase;
    position: sticky;
    bottom: 0;
    line-height: 35px;
}

.cart-summary p {
    margin: 5px 0;
    font-size: 16px;
}

/* Open Cart Button */
.open-cart-btn {
    position: fixed;
    bottom: 20px;
    right: 20px;
    padding: 12px 18px;
    background: #007bff;
    color: white;
    border: none;
    cursor: pointer;
    border-radius: 50px;
    font-size: 16px;
    font-weight: bold;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    transition: all 0.3s ease;
    z-index: 1001;
}

.open-cart-btn:hover {
    background: #0056b3;
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
}

/* Header Cart Button */
.header .cart-btn {
    cursor: pointer;
    color: white;
    font-size: 25px;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    background: transparent;
    transition: color 0.3s ease;
    width: 60px;
    right: 20px;
}

/* Subtotal Style */
.cart-item .subtotal {
    font-size: 12px;
    
    color: #007bff;
    margin-top: 5px;
    padding: 2px 0;
    border-top: 1px dashed #ccc;
    text-align: right;
}


.header .cart-btn i {
    font-size: 30px;
    color: inherit;
}

.header .cart-btn:hover {
    color: #ff9800;
    transform: scale(1.1);
}
form label {
    font-size: 12px;
   
    margin-right: 5px;
    display: block;
    margin-top: 10px;
    
}


form .update-btn {
    background-color: #007BFF;
    color: white;
    border: none;
    text-align: center;
    display: inline-block; 
    font-size: 11px;
    cursor: pointer;
    border-radius: 5px; 
    transition: background-color 0.3s ease; 
   width: 62px;
   
}

/* Hover effect for the Update button */
form .update-btn:hover {
    background-color: #0056b3; /* Darker green when hovered */
}
form .remove_product {
   
    color: black;
    border: none;
    text-align: center;
    display: inline-block; 
    font-size: 11px;
    background: none;
    cursor: pointer;
    border-radius: 5px; 
    transition: background-color 0.3s ease; 
   width: 62px;
   
}
form .remove_product:hover {
   
    background: none;
   
}

input[type="number" i] {
    padding-block: 1px;
    padding-inline: 2px;
   width: 130px;
}



/* Responsive Design */
@media (max-width: 768px) {
    .cart-panel {
        width: 100%;
       
    }

    .open-cart-btn {
        font-size: 14px;
        padding: 10px 15px;
    }

    .cart-item img {
        max-width: 60px;
    }

    .cart-item-details h4 {
        font-size: 14px;
    }

    .cart-summary {
        font-size: 14px;
    }
}

    /* ---------product card end------- */
  </style>
</body>

</html>
<?php
$conn->close();
?>