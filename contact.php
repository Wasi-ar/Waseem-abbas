<?php
include "conn.php";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_contact_form'])) {

    $user_name = htmlspecialchars($_POST['username']);
    $user_email = htmlspecialchars($_POST['email']);
    $user_message = htmlspecialchars($_POST['message']);

    if (filter_var($user_email, FILTER_VALIDATE_EMAIL)) {

        $stmt = $conn->prepare("INSERT INTO contact (username, email, message) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $user_name, $user_email, $user_message);

        if ($stmt->execute()) {

            echo "<script>alert('Your message has been sent successfully!');</script>";
        } else {
            echo "<script>alert('There was an error sending your message. Please try again later.');</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('Invalid email address.');</script>";
    }
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_contact_form'])) {
      
        $username = $_POST['username'];
        $email = $_POST['email'];
        $message = $_POST['message'];
       
        header("Location: display.php");
        exit(); 
    }
} ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="ecweb.css">
   <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

    <title>Document</title>
</head>

<body> 


    <div id="popupForm" class="popup">
        <div class="popup-content">
            <span id="closePopup" class="close">&times;</span>
            <h2>Contact Us</h2>
            <form method="POST" action="">
                <div class="input-container">
                    <i class="fas fa-user"></i> 
                    <input type="text" id="username" name="username" placeholder="Name" required>
                </div>
                <div class="input-container">
                    <i class="fas fa-envelope"></i> 
                    <input type="email" id="email" name="email" placeholder="Email" required>
                </div>
                <div class="input-container">
                    <i class="fas fa-comment"></i> 
                    <textarea id="message" name="message" rows="4" placeholder="Message" required></textarea>
                </div>
                <button type="submit" name="submit_contact_form">Send</button>
            </form>
        </div>
    </div>
    <script>    
document.getElementById('openPopup').addEventListener('click', function () {
    document.getElementById('popupForm').style.display = 'flex'; 
});

document.getElementById('closePopup').addEventListener('click', function () {
    document.getElementById('popupForm').style.display = 'none'; 
});

    </script>
</body>
</html>