<?php
header("Content-Type: application/json"); // for json style view

// Database connection details
$servername = "localhost";
$username = "root";
$password = "your password";
$dbname = "myapi_db";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die(json_encode(array("error" => "Connection failed: " . $conn->connect_error)));
}

// SQL to create database
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) === TRUE) {
    echo json_encode(array("status" => 1, "message" => "Database created successfully."));
} else {
    die(json_encode(array("error" => "Error creating database: " . $conn->error)));
}

// Select the database
$conn->select_db($dbname);

// SQL to create table
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo json_encode(array("status" => 1, "message" => "Table created successfully."));
} else {
    die(json_encode(array("error" => "Error creating table: " . $conn->error)));
}

// Close the connection
$conn->close();
?>
