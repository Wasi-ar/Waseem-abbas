<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include "conn.php";
if (isset($_POST['submit'])) {

    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];


    if (empty($password)) {
        die('Password cannot be empty!');
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);


    $sql = "INSERT INTO login (first_name, last_name, email, password) 
            VALUES (?, ?, ?, ?)";


    if ($stmt = mysqli_prepare($conn, $sql)) {


        mysqli_stmt_bind_param($stmt, "ssss", $first_name, $last_name, $email, $password);


        if (mysqli_stmt_execute($stmt)) {

            header("Location: hello.php?msg=New record created successfully");
            exit();
        } else {
            echo "Error executing SQL:" . mysqli_error($conn);
        }


        mysqli_stmt_close($stmt);
    } else {
        echo "Error preparing SQL: " . mysqli_error($conn);
    }
}


mysqli_close($conn);
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="signup_page.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
    <div class="main-container">
        <div class="container-login">
            <div class="peragraph">
                <h1>Welcome Back !</h1>
                <p>To Keep Connected with us please <br>
                    login with your parsonal info</p>
                <div class="social-links">
                    <div class="login-form">
                       
                        <button onclick="location.href='login.php'">SIGN IN</button>

                        </form>

                    </div>
                </div>


            </div>
          
        </div>

        <div class="container-signup">
            <div class="peragraph">
                <h1>Create Account</h1>

                <div class="signup-form">

                    <div class="social-links">
                        <a href="https://regex.global/" class="facebook"><i class="fa fa-facebook"></i></a>
                        <a href="#" class="google"><i class="fa fa-google"></i></a>
                    </div>
                    <h7>OR</h7>
                    <form method="POST" action="signup.php">
                        <!-- First Name -->
                        <input type="text" name="first_name" placeholder="First Name" required>

                        <!-- Last Name -->
                        <input type="text" name="last_name" placeholder="Last Name" required>

                        <!-- Email -->
                        <input type="email" name="email" placeholder="Email" required>

                        <!-- Password -->
                        <!-- <div class="password-container">
                            <input type="password" name="password" id="password" placeholder="Password" required>
                            <i id="eyeIcon" class="fa fa-eye"></i>
                        </div> -->
                         <div style="position: relative;">
    <input type="password" placeholder="Password" name="password" id="password" required>
    <i id="eyeIcon" class="fa fa-eye-slash" onclick="togglePassword()"></i> <!-- Eye Icon to toggle visibility -->
</div>



                        <div class="button-signup">
                            <button type="submit" name="submit">Sign Up</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>

    </div>

    </div>
    <script>
        // Password visibility toggle functionality
        const togglePassword = document.getElementById('eyeIcon');
        const passwordField = document.getElementById('password');

        togglePassword.addEventListener('click', function () {
            const type = passwordField.type === 'password' ? 'text' : 'password';
            passwordField.type = type;

            // Toggle eye icon
            togglePassword.classList.toggle('fa-eye');
            togglePassword.classList.toggle('fa-eye-slash');
        });
    </script>

</body>

</html>