<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: X-Requested-With, Authorization, Content-Type");

include('function.php');

$requestMethod = $_SERVER["REQUEST_METHOD"] ?? 'GET'; 


$id = isset($_GET['id']) ? intval($_GET['id']) : null;

if ($requestMethod == "GET") {
    if ($id !== null) {
      
        $user = getUserById($id);
        echo $user;
    } else {
       
        $userList = getUserList();
        echo $userList;
    }
} elseif ($requestMethod == "POST") {
    
    $data = json_decode(file_get_contents("php://input"), true);
    echo insertUser($data);
} elseif ($requestMethod == "PUT") {
    
    if ($id !== null) {
        $data = json_decode(file_get_contents("php://input"), true);
        echo updateUser($id, $data);
    } else {
        http_response_code(400);
        echo json_encode(['status' => 400, 'message' => 'User ID is required for update']);
    }
} elseif ($requestMethod == "DELETE") {
    
    if ($id !== null) {
        echo deleteUser($id);
    } else {
        http_response_code(400);
        echo json_encode(['status' => 400, 'message' => 'User ID is required for deletion']);
    }
} else {
    $data = [
        'status' => 405,
        'message' => $requestMethod . ' Method Not Allowed',
    ];

    http_response_code(405);
    echo json_encode($data);
}
?>
