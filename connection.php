<?php
// db_connect.php

$servername = "localhost"; // Usually localhost
$username = "root"; // Default username for XAMPP
$password = "mysql"; // Default password is empty for XAMPP
$dbname = "stpol"; // The name of your database



//db sofia
// pswd: mysql
// dbname: stpol

//db marco
// pswd: 
// dbname: stpol


// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);




// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
echo "";
?>