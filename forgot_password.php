<?php
require 'vendor/autoload.php';  // For Composer-based installation

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'conn.php'; // Or include the PHPMailer files if you're not using Composer

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    // Check if email exists in the database
    $sql = "SELECT id FROM login WHERE email = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // User exists, send password reset email
            $reset_token = bin2hex(random_bytes(16));  // Generate a random token

            // Store token in the database with an expiration time (optional)
            $sql_update = "UPDATE login SET reset_token = ?, token_expiry = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE email = ?";
            if ($stmt_update = $conn->prepare($sql_update)) {
                $stmt_update->bind_param('ss', $reset_token, $email);
                $stmt_update->execute();

                // Send reset link to user's email using PHPMailer
                $reset_link = "http://localhost/product/reset_password.php?token=" . $reset_token;

                // Create a new PHPMailer instance
                $mail = new PHPMailer(true);

                try {
                    // Set up SMTP
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com'; // Gmail SMTP server
                    $mail->SMTPAuth = true;
                    $mail->Username = 'regexpractice@gmail.com'; // Your Gmail address
                    $mail->Password = 'cibxlcvkvxafjonw'; // Your Gmail password or app password
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;

                    // Set email parameters
                    $mail->setFrom('regexpractice@gmail.com', 'Your Name');
                    $mail->addAddress($email);
                    $mail->Subject = 'Password Reset Request';
                    $mail->Body = "Click the following link to reset your password: $reset_link";

                    // Send the email
                    $mail->send();
                    echo "A password reset link has been sent to your email.";
                } catch (Exception $e) {
                    echo "Error sending email: " . $mail->ErrorInfo;
                }
            } else {
                // Handle error in preparing the update query
                echo "Error preparing the update query: " . $conn->error;
            }
        } else {
            echo "No user found with that email address.";
        }
        $stmt->close();
    } else {
        // Handle error in preparing the select query
        echo "Error preparing the query: " . $conn->error;
    }

    $conn->close();
}
?>

 <!-- Forgot Password Form -->
 <link rel="stylesheet" href="fstyle.css">
 <div class="fotgot">
    <form method="POST" action="forgot_password.php">
       <label for="email">Email:</label>
       <input type="email" name="email" required>
       <!-- <button type="submit">Forgot Email</button> -->
       <button type="button" onclick="window.location.href='reset_password.php'">Forgot Password</button>
        <!-- <button type="button" onclick="window.location.href='logout.php'">Logout</button> -->
    </form>
</div>
<head>
    <style>
        /* Main Forgot Section */
        .fotgot {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f7fc;
            padding: 20px;
        }

        /* Form Container */
        .fotgot form {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* Label Styling */
        .fotgot label {
            font-size: 18px;
            color: #333;
            margin-bottom: 10px;
            text-align: left;
            width: 100%;
        }

        /* Input Field Styling */
        .fotgot input[type="email"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            background-color: #f9f9f9;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        /* Input Focus Effects */
        .fotgot input[type="email"]:focus {
            border-color: #007BFF;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        /* Button Styling */
        .fotgot button {
            background-color: #007BFF;
            color: #fff;
            font-size: 16px;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            width: 100%;
            cursor: pointer;
            transition: background-color 0.3s ease;
            text-transform: uppercase;
        }

        /* Button Hover Effects */
        .fotgot button:hover {
            background-color: #0056b3;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .fotgot form {
                padding: 25px;
            }

            .fotgot label {
                font-size: 16px;
            }
        }
    </style>
</head>
