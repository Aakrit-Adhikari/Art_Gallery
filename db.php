<?php
$host = 'localhost';
$db = 'art_gallery';
$user = 'root'; // Set your MySQL username here
$pass = ''; // Set your MySQL password here

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>