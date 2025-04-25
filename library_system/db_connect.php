<?php
$host = 'localhost';
$db = 'library_db';
$user = 'root';
$pass = ''; // use your MySQL password if any

// Create connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
