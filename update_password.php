<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include 'conn.php'; 

if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $new_password = $_POST['new_password'];
    // Update the password in the users table
    $sql = "UPDATE user SET password = ? WHERE email = ?";
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "ss", $hashed_password, $email);
        if (mysqli_stmt_execute($stmt)) {
            // Delete the token from password_resets table after resetting the password
            $sql = "DELETE FROM login WHERE email = ?";
            if ($stmt = mysqli_prepare($conn, $sql)) {
                mysqli_stmt_bind_param($stmt, "s", $email);
                mysqli_stmt_execute($stmt);
                echo "Password successfully updated.";
            }
        } else {
            echo "Error updating password.";
        }
    }
    mysqli_stmt_close($stmt);
}
mysqli_close($conn);
?>
!