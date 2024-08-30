<?php
header("Content-Type: application/json"); // for json style view
include_once '../config/database.php';

// Sample data to insert
$data = [
    ['name' => 'John Doe', 'email' => 'john@example.com'],
    ['name' => 'Jane Smith', 'email' => 'jane@example.com'],
    ['name' => 'Sometri Oeng', 'email' => 'sometri@example.com'],
    ['name' => 'Obama Barak', 'email' => 'obama@example.com'],
    ['name' => 'Donal Trum', 'email' => 'trum@example.com'],
    ['name' => 'Mak Zuck', 'email' => 'zuck@example.com']
];

// Prepare the SQL statement
$stmt = $conn->prepare("INSERT INTO users (name, email) VALUES (?, ?)");

// Check if the statement was prepared successfully
if (!$stmt) {
    die(json_encode(array("error" => "Statement preparation failed: " . $conn->error)));
}

// Bind parameters and execute the statement for each record
foreach ($data as $record) {
    $stmt->bind_param("ss", $record['name'], $record['email']);
    if (!$stmt->execute()) {
        echo json_encode(array("error" => "Insertion failed: " . $stmt->error));
        exit;
    }
}

// Close the statement
$stmt->close();

// Close the connection
$conn->close();

// Return success message
echo json_encode(array("status" => 1, "message" => "Users added successfully."));
?>
