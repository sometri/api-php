<?php
header("Content-Type: application/json"); // for json style view
include_once '../config/database.php';

// $servername = "localhost";
// $username = "root";
// $password = "Sometri@123";
// $dbname = "myapi_db";

// // Create connection
// $conn = new mysqli($servername, $username, $password, $dbname);

// // Check connection
// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// }

$request_method = $_SERVER["REQUEST_METHOD"];

switch ($request_method) {
    case 'GET':
        // Retrieve users
        if (!empty($_GET["id"])) {
            $id = intval($_GET["id"]);
            get_users($id);
        } else {
            get_users();
        }
        break;
    case 'POST':
        // Insert user
        insert_user();
        break;
    case 'PUT':
        // Update user
        $id = intval($_GET["id"]);
        update_user($id);
        break;
    case 'DELETE':
        // Delete user
        $id = intval($_GET["id"]);
        delete_user($id);
        break;
    default:
        // Invalid Request Method
        header("HTTP/1.0 405 Method Not Allowed");
        break;
}

function get_users($id = 0)
{
    global $conn;
    $query = "SELECT * FROM users";
    if ($id != 0) {
        $query .= " WHERE id=" . $id . " LIMIT 1";
    }
    $response = array();
    $result = $conn->query($query);
    while ($row = $result->fetch_assoc()) {
        $response[] = $row;
    }
    echo json_encode($response);
}

function insert_user()
{
    global $conn;
    $data = json_decode(file_get_contents('php://input'), true);
    $name = $data["name"];
    $email = $data["email"];
    $query = "INSERT INTO users(name, email) VALUES('$name', '$email')";
    if ($conn->query($query) === TRUE) {
        $response = array(
            'status' => 1,
            'status_message' => 'User Added Successfully.'
        );
    } else {
        $response = array(
            'status' => 0,
            'status_message' => 'User Addition Failed.'
        );
    }
    echo json_encode($response);
}

function update_user($id)
{
    global $conn;
    $data = json_decode(file_get_contents("php://input"), true);
    $name = $data["name"];
    $email = $data["email"];
    $query = "UPDATE users SET name='$name', email='$email' WHERE id=$id";
    if ($conn->query($query) === TRUE) {
        $response = array(
            'status' => 1,
            'status_message' => 'User Updated Successfully.'
        );
    } else {
        $response = array(
            'status' => 0,
            'status_message' => 'User Update Failed.'
        );
    }
    echo json_encode($response);
}

function delete_user($id)
{
    global $conn;
    $query = "DELETE FROM users WHERE id=$id";
    if ($conn->query($query) === TRUE) {
        $response = array(
            'status' => 1,
            'status_message' => 'User Deleted Successfully.'
        );
    } else {
        $response = array(
            'status' => 0,
            'status_message' => 'User Deletion Failed.'
        );
    }
    echo json_encode($response);
}

$conn->close();
?>
