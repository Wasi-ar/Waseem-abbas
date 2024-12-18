<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

function sendResetEmail($email, $reset_link)
{
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'localhost';  // Assuming XAMPP's mail server is configured to use localhost
        $mail->SMTPAuth = true;    // No authentication for local mail server
        $mail->Port = 587;           // Default SMTP port for localhost

        $mail->setFrom('your-email@example.com', 'Your Name');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = 'Password Reset Request';
        $mail->Body = 'Click the following link to reset your password: <a href="' . $reset_link . '">' . $reset_link . '</a>';

        $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>