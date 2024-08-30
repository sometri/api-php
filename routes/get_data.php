<?php
header("Content-Type: application/json"); // for json style view
include_once '../config/database.php';

// SQL query to fetch data from the table
$sql = "SELECT * FROM users"; // your actual table name
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $data = array();
    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    echo json_encode($data);
} else {
    echo json_encode(array("message" => "No records found"));
}

$conn->close();
?>
