<?php

require '../include/connection.php';

function getUserList() {
    global $conn;

    $query = "SELECT * FROM Users";
    $query_run = mysqli_query($conn, $query);

    if ($query_run) {
        if (mysqli_num_rows($query_run) > 0) {
            $res = mysqli_fetch_all($query_run, MYSQLI_ASSOC);

            $data = [
                'status' => 200,
                'message' => 'User List Fetched Successfully',
                'data' => $res, 
            ];

            header("HTTP/1.0 200 OK");
            return json_encode($data);

        } else {
            $data = [
                'status' => 404,
                'message' => 'No User Found',
            ];

            header("HTTP/1.0 404 Not Found");
            return json_encode($data);
        }

    } else {
        $data = [
            'status' => 500,
            'message' => 'Internal Server Error',
        ];

        header("HTTP/1.0 500 Internal Server Error");
        return json_encode($data);
    }
}

function getUserById($id) {
    global $conn;

    $query = "SELECT * FROM Users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result) {
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            $data = [
                'status' => 200,
                'message' => 'User fetched successfully',
                'data' => $user,
            ];

            header("HTTP/1.0 200 OK");
            return json_encode($data);
        } else {
            $data = [
                'status' => 404,
                'message' => 'User not found',
            ];

            header("HTTP/1.0 404 Not Found");
            return json_encode($data);
        }
    } else {
        $data = [
            'status' => 500,
            'message' => 'Internal Server Error',
        ];

        header("HTTP/1.0 500 Internal Server Error");
        return json_encode($data);
    }
}

function insertUser($data) {
    global $conn;

   
    if (!isset($data['firstname'], $data['lastname'], $data['is_admin'])) {
        http_response_code(400);
        return json_encode(['status' => 400, 'message' => 'Invalid input']);
    }

   
    $query = "INSERT INTO Users (firstname, lastname, is_admin) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssi", $data['firstname'], $data['lastname'], $data['is_admin']);

    if ($stmt->execute()) {
        $data = [
            'status' => 201,
            'message' => 'User created successfully',
            'user_id' => $stmt->insert_id,
        ];
        http_response_code(201);
        return json_encode($data);
    } else {
        http_response_code(500);
        return json_encode(['status' => 500, 'message' => 'Internal Server Error']);
    }
}
function updateUser($id, $data) {
    global $conn;

    
    if (!isset($data['firstname'], $data['lastname'], $data['is_admin'])) {
        http_response_code(400);
        return json_encode(['status' => 400, 'message' => 'Invalid input']);
    }

   
    $query = "UPDATE Users SET firstname = ?, lastname = ?, is_admin = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssii", $data['firstname'], $data['lastname'], $data['is_admin'], $id);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            $data = [
                'status' => 200,
                'message' => 'User updated successfully',
            ];
            http_response_code(200);
            return json_encode($data);
        } else {
            $data = [
                'status' => 404,
                'message' => 'User not found',
            ];
            http_response_code(404);
            return json_encode($data);
        }
    } else {
        http_response_code(500);
        return json_encode(['status' => 500, 'message' => 'Internal Server Error']);
    }
}
function deleteUser($id) {
    global $conn;

   
    $query = "DELETE FROM Users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            $data = [
                'status' => 200,
                'message' => 'User deleted successfully',
            ];
            http_response_code(200);
            return json_encode($data);
        } else {
            $data = [
                'status' => 404,
                'message' => 'User not found',
            ];
            http_response_code(404);
            return json_encode($data);
        }
    } else {
        http_response_code(500);
        return json_encode(['status' => 500, 'message' => 'Internal Server Error']);
    }
}


?>
