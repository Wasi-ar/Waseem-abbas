<?php
require 'conn.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Check if token is valid and not expired
    $sql = "SELECT id FROM login WHERE reset_token = ? AND token_expiry > NOW()";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('s', $token);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // Token is valid, show the reset password form
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $new_password = $_POST['password'];
                $confirm_password = $_POST['confirm_password'];

                // Check if the new password and confirm password match
                if ($new_password !== $confirm_password) {
                    echo "Passwords do not match. Please try again.";
                } else {
                    // Hash the password
                    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

                    // Update the password in the database and clear the reset token
                    $sql_update = "UPDATE login SET password = ?, reset_token = NULL, token_expiry = NULL WHERE reset_token = ?";
                    if ($stmt_update = $conn->prepare($sql_update)) {
                        $stmt_update->bind_param('ss', $hashed_password, $token);
                        if ($stmt_update->execute()) {
                            echo "Password has been reset successfully.";
                        } else {
                            echo "Error updating the password.";
                        }
                    }
                }
            }

            // Show reset password form with both New Password and Confirm Password fields
            echo '<form method="POST" action="">
                    <label for="password">Enter new password:</label>
                    <input type="password" name="password" required><br><br>
                    <label for="confirm_password">Confirm new password:</label>
                    <input type="password" name="confirm_password" required><br><br>
                    <button type="submit">Reset Password</button>
                  </form>';

        } else {
            echo "Invalid or expired token!";
        }
        $stmt->close();
    } else {
        echo "Error preparing the query: " . $conn->error;
    }

    $conn->close();
} else {
    echo "No token provided!";
}
?>
<link rel="stylesheet" href="style.css">
