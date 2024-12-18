<?php

session_start();
include 'conn.php';


// Example login script
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Replace this with your actual database validation
    if ($username == 'test' && $password == '1234') {
        $_SESSION['user_id'] = 1; // Store user ID in session
        $_SESSION['username'] = $username; // Store username in session
        header('Location: display.php'); // Redirect to display page
        exit();
    } else {
        echo "Invalid login credentials.";
    }
}

if (isset($_POST['login'])) {
    // Get user inputs
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    // Check if the email exists in the database
    $sql = "SELECT * FROM login WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    // Check if we found a user with the given email
    if (mysqli_num_rows($result) > 0) {
        // Fetch the user data
        $user = mysqli_fetch_assoc($result);

        // Compare the entered password with the hashed password stored in the database
        if (password_verify($password, $user['password'])) {
            // Password is correct, set session variables
            $_SESSION['user_id'] = $user['id'];  // Store the user's ID in session
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['last_name'] = $user['last_name'];
            $_SESSION['email'] = $user['email'];

            // Redirect to a dashboard or home page
            header("Location: display.php"); // Or any page you want to redirect to
            exit();
        } else {
            echo "Invalid password!";
        }
    } else {
        echo "No user found with that email!";
    }
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="login_page.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
    <div class="main-container">
        <div class="container-login">
            <div class="peragraph">
                <h1>Login To Your Account</h1>
                <p>Login Using Social Network</p>
                <div class="social-links">
                    <div class="social_links_div">
                        <a href="https://regex.global/" class="facebook"><i class="fa fa-facebook"></i></a>
                        <a href="#" class="google"><i class="fa fa-google"></i></a>
                    </div>
                    <div class="login-form">
                        <form method="POST">

                            <input type="email" placeholder="Email" name="email" id="email" required>
                            <div style="position: relative;">
    <input type="password" placeholder="Password" name="password" id="password" required>
    <i id="eyeIcon" class="fa fa-eye-slash" onclick="togglePassword()"></i> <!-- Eye Icon to toggle visibility -->
</div>
                           
                            <button type="submit" name="login">Login</button>

                            <button onclick="location.href='forgot_password.php'">Forgot Password</button>

                        </form>
                    </div>
                </div>
            </div>
            <h7>OR</h7>
            <div style="text-align: center;">
                <a href="logout.php">Logout</a>
            </div>

        </div>
        <div class="container-signup">
            <div class="peragraph">
                <h1>New Here ?</h1>
                <p>By creating and/or using your account, you
                    agree to our Terms of Use and Privacy Policy.</p>
            </div>
            <div class="button-signup">
                <button onclick="location.href='signup.php'">SIGN UP</button>
            </div>
        </div>
    </div>
    <script>
        // Get references to password field and eye icon
        const togglePassword = document.getElementById('eyeIcon');
        const passwordField = document.getElementById('password');

        // Add click event to the eye icon
        togglePassword.addEventListener('click', function () {
            // Toggle the password field type between password and text
            const type = passwordField.type === 'password' ? 'text' : 'password';
            passwordField.type = type;

            // Toggle the eye icon (fa-eye to fa-eye-slash and vice versa)
            togglePassword.classList.toggle('fa-eye-slash');
            togglePassword.classList.toggle('fa-eye');
        });
       

    </script>
   
</body>

</html>