<?php
$servername = "localhost";
$username = "root";
$password = "Sometri@123";
$dbname = "myapi_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(array("error" => "Connection failed: " . $conn->connect_error)));
}
?>
