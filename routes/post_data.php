<?php
header("Content-Type: application/json"); // for json style view
include_once '../config/database.php';

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // var_dump($_POST);
    // die();
    $name = $_POST['name']; // Example POST field
    $email = $_POST['email']; // Example POST field

    // Basic validation
    if (empty($name) || empty($email)) {
        echo json_encode(array("error" => "Name and email are required"));
        exit();
    }

    // SQL query to insert data
    $sql = "INSERT INTO users (name, email) VALUES ('$name', '$email')"; // your actual table name

    if ($conn->query($sql) === TRUE) {
        echo json_encode(array("message" => "Record added successfully"));
    } else {
        echo json_encode(array("error" => "Error: " . $conn->error));
    }

    $conn->close();
} else {
    echo json_encode(array("error" => "Invalid request method"));
}
?>
