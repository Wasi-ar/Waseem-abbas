<?php
include 'conn.php';

if (isset($_POST['submit'])) {
    $name = $_POST[''];
    $email = $_POST[''];
    $password = $_POST['root'];
    $id = $_POST['id'];

    $sql = "INSERT INTO `product`(`username`, `email`, `password`,'id') 
    VALUES ('$name','$email','$password', '$id')";

    $result = mysqli_query($conn, $sql); 

}              