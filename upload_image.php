<?php
include 'conn.php';

if (isset($_POST['submit'])) {

    // Check if form data is set
    if (isset($_POST['name'], $_POST['description'], $_POST['promocode'], $_POST['price'], $_FILES['image'])) {

        // Get form data
        $name = $_POST['name'];
        $description = $_POST['description'];
        $promocode = $_POST['promocode'];
        $price = $_POST['price'];  // Get price from the form

        // Debugging: print form data
        echo "Name: $name<br>";
        echo "Description: $description<br>";
        echo "Promocode: $promocode<br>";
        echo "Price: $price<br>";  // Display price

        // Ensure the image is uploaded
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['image'])) {

            // Get the uploaded file details
            $image_name = $_FILES['image']['name'];
            $image_tmp_name = $_FILES['image']['tmp_name'];
            $image_type = pathinfo($image_name, PATHINFO_EXTENSION);  // Get file extension

            // Debugging: print image details
            echo "Image Name: $image_name<br>";
            echo "Image Temp Name: $image_tmp_name<br>";
            echo "Image Type: $image_type<br>";

            // Define upload directory
            $target_dir = "images/";
            $target_file = $target_dir . basename($image_name);

            // Validate if the uploaded file is an image
            $check = getimagesize($image_tmp_name);
            if ($check === false) {
                echo "File is not an image.";
                exit;
            }

            // Create the target directory if it doesn't exist
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true); // Create the directory if it doesn't exist
            }

            // Move the uploaded image to the target directory
            if (move_uploaded_file($image_tmp_name, $target_file)) {

                // Prepare and execute the SQL query to include price
                $stmt = $conn->prepare("INSERT INTO images (image, name, description, promocode, price) VALUES (?, ?, ?, ?, ?)");

                if ($stmt === false) {
                    die("Error preparing the query: " . $conn->error);
                }

                // Bind parameters to the query (all are strings in this case)
                $stmt->bind_param("sssss", $target_file, $name, $description, $promocode, $price);

                // Execute the query and check for success
                if ($stmt->execute()) {
                    echo "Image uploaded and data inserted successfully!";
                } else {
                    echo "Error uploading image: " . $stmt->error;  // Show the MySQL error
                }

                // Close the statement
                $stmt->close();
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    } else {
        echo "All fields are required.";
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Product</title>
    <link rel="stylesheet" href="upload_product.css">
</head>

<body>
    <div class="main_page">
        <div class="main_h4">
            <!-- Title for the form (optional) -->
            <h4>Upload Product</h4>
        </div>
        <div class="main_form">
            <!-- Form for product upload -->
            <form method="POST" enctype="multipart/form-data">

                <!-- Input for image upload -->
                <input type="file" name="image" id="image" placeholder="Product Image" required><br><br>

                <!-- Input for product name -->
                <input type="text" name="name" id="name" placeholder="Product Name" required><br><br>

                <!-- Input for product description -->
                <input type="text" name="description" id="description" placeholder="Product Description"
                    required><br><br>

                <!-- Input for promo code -->
                <input type="text" name="promocode" id="promocode" placeholder="Promo Code" required><br><br>

                <!-- Input for product price -->
                <input type="text" name="price" id="price" placeholder="Product Price" required><br><br>

               

                <!-- Submit button -->
                <div class="main_button">
                    <button type="submit" name="submit">Upload Product</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>