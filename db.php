<?php
$servername = "localhost";
$username = "root";
$password = ""; // biasanya kosong di XAMPP default
$dbname = "elibrary";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

