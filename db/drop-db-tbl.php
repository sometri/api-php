<?php
header("Content-Type: application/json"); // for json style view
include_once '../config/database.php';

// SQL to drop table
$sql = "DROP TABLE IF EXISTS $dbname.users";
if ($conn->query($sql) === TRUE) {
    echo json_encode(array("status" => 1, "message" => "Table dropped successfully."));
} else {
    die(json_encode(array("error" => "Error dropping table: " . $conn->error)));
}

// SQL to drop database
$sql = "DROP DATABASE IF EXISTS $dbname";
if ($conn->query($sql) === TRUE) {
    echo json_encode(array("status" => 1, "message" => "Database dropped successfully."));
} else {
    die(json_encode(array("error" => "Error dropping database: " . $conn->error)));
}

// Close the connection
$conn->close();
?>
