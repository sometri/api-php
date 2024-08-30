<?php
header("Content-Type: application/json"); // for json style view
include_once './config/database.php'; // configure with mysql workbench

$request_method = $_SERVER["REQUEST_METHOD"];
// var_dump($request_method);
// die();
switch ($request_method) {
    case 'GET':
        // Retrieve users
        $id = isset($_GET["id"]) ? intval($_GET["id"]) : 0;
        get_users($id);
        break;
    case 'POST':
        // Insert user
        insert_user();
        break;
    case 'PUT':
        // Update user
        $id = isset($_GET["id"]) ? intval($_GET["id"]) : 0;
        update_user($id);
        break;
    case 'DELETE':
        // Delete user
        $id = isset($_GET["id"]) ? intval($_GET["id"]) : 0;
        delete_user($id);
        break;
    default:
        // Invalid Request Method
        header("HTTP/1.0 405 Method Not Allowed");
        echo json_encode(array("error" => "Method Not Allowed"));
        break;
}

function get_users($id = 0)
{
    global $conn;
    $query = "SELECT * FROM users";
    if ($id != 0) {
        $query .= " WHERE id=? LIMIT 1";
    }
    $stmt = $conn->prepare($query);
    if ($id != 0) {
        $stmt->bind_param("i", $id);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $response = array();
    while ($row = $result->fetch_assoc()) {
        $response[] = $row;
    }
    echo json_encode($response);
    $stmt->close();
}

function insert_user()
{
    global $conn;
    $data = json_decode(file_get_contents('php://input'), true);
    if (isset($data["name"]) && isset($data["email"])) {
        $name = $data["name"];
        $email = $data["email"];
        $query = "INSERT INTO users (name, email) VALUES (?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $name, $email);
        if ($stmt->execute()) {
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
        $stmt->close();
    } else {
        $response = array(
            'status' => 0,
            'status_message' => 'Invalid Input.'
        );
    }
    echo json_encode($response);
}

function update_user($id)
{
    global $conn;
    if ($id > 0) {
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data["name"]) && isset($data["email"])) {
            $name = $data["name"];
            $email = $data["email"];
            // Update user in the database
            $query = "UPDATE users SET name=?, email=? WHERE id=?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ssi", $name, $email, $id);
            if ($stmt->execute()) {
                // Query the updated user data
                $query = "SELECT id, name, email FROM users WHERE id=?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("i", $id);
                if ($stmt->execute()) {
                    $result = $stmt->get_result();
                    if ($result->num_rows > 0) {
                        $user = $result->fetch_assoc();
                        $response = array(
                            'status' => 1,
                            'status_message' => 'User Updated Successfully.',
                            'data' => $user
                        );
                    } else {
                        $response = array(
                            'status' => 0,
                            'status_message' => 'User Not Found.'
                        );
                    }
                } else {
                    $response = array(
                        'status' => 0,
                        'status_message' => 'Error querying user data.'
                    );
                }
            } else {
                $response = array(
                    'status' => 0,
                    'status_message' => 'User Update Failed.'
                );
            }
            $stmt->close();
        } else {
            $response = array(
                'status' => 0,
                'status_message' => 'Invalid Input.'
            );
        }
    } else {
        $response = array(
            'status' => 0,
            'status_message' => 'Invalid ID.'
        );
    }
    echo json_encode($response);
}

function delete_user($id)
{
    global $conn;
    if ($id > 0) {
        $query = "DELETE FROM users WHERE id=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
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
        $stmt->close();
    } else {
        $response = array(
            'status' => 0,
            'status_message' => 'Invalid ID.'
        );
    }
    echo json_encode($response);
}

$conn->close();
?>
