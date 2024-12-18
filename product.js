$(document).ready(function () {
  if (localStorage.getItem("searchQuery")) {
    var searchQuery = localStorage.getItem("searchQuery");
    $("#searchInput").val(searchQuery);
    searchProducts(); // Perform search based on saved query
  } else {
    fetchAllProducts(); // If no query, fetch all products
  }

  // Close search bar if clicked outside
  $(document).click(function (event) {
    if (!$(event.target).closest(".search-container").length) {
      $("#searchInput").hide(); // Hide the search input when clicking outside
    }
  });
});

// Function to toggle the search box visibility
function toggleSearchBox() {
  const searchInput = document.getElementById("searchInput");
  if (
    searchInput.style.display === "none" ||
    searchInput.style.display === ""
  ) {
    searchInput.style.display = "block"; // Show the search input
    searchInput.focus(); // Focus on the input field
  } else {
    searchInput.style.display = "none"; // Hide the search input
  }
}

// Function to perform search
function searchProducts() {
  var query = document.getElementById("searchInput").value;
  // Save search query to localStorage
  localStorage.setItem("searchQuery", query);
  if (query.length > 0) {
    // Trigger search when query has content
    $.ajax({
      url: "search.php", // PHP script to handle search
      method: "GET",
      data: { search: query }, // Send search query to PHP
      success: function (response) {
        $("#searchResults").html(response); // Display search results
      },
      error: function () {
        $("#searchResults").html("<p>Error fetching search results.</p>"); // Handle errors
      },
    });
  } else if (query.length === 0) {
    // If input is empty
    fetchAllProducts(); // Fetch all products if the input is empty
  }
}

// Function to fetch all products when the search box is empty
function fetchAllProducts() {
  $.ajax({
    url: "search.php", // PHP script to fetch all products
    method: "GET",
    success: function (response) {
      $("#searchResults").html(response); // Display all products in the results section
    },
    error: function () {
      $("#searchResults").html("<p>Error fetching all products.</p>"); // Handle errors
    },
  });
}


  function toggleCart() {
    const cartPanel = document.getElementById("cartPanel");
    cartPanel.classList.toggle("open");
}
  

function updateQuantity(productId, action) {
  var quantityInput = document.getElementById("quantity_" + productId);
  var currentQuantity = parseInt(quantityInput.value);

  if (action === "increase") {
    quantityInput.value = currentQuantity + 1;
  } else if (action === "decrease" && currentQuantity > 1) {
    quantityInput.value = currentQuantity - 1;
  }
}

function submitForm(productId) {
  var quantity = document.getElementById("quantity_" + productId).value;
  var productIdField = document.querySelector(
    `#updateForm_${productId} input[name='product_id']`
  ).value;

  var formData = new FormData();
  formData.append("product_id", productIdField);
  formData.append("quantity", quantity);

  // Perform AJAX request to update the quantity
  var xhr = new XMLHttpRequest();
  xhr.open("POST", "update_cart.php", true);
  xhr.onload = function () {
    if (xhr.status === 200) {
      var response = JSON.parse(xhr.responseText);
      if (response.success) {
        // Update the displayed quantity and subtotal
        document.getElementById("quantity-display-" + productId).innerText =
          response.new_quantity;
        document.getElementById("subtotal-" + productId).innerText =
          response.new_subtotal;
      } else {
        alert("Failed to update quantity");
      }
    }
  };
  xhr.send(formData);
}

















