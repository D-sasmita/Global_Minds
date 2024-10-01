<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);


$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "crowd_sourcing";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    

    $data = file_get_contents('php://input');
    $decodedData = json_decode($data, true);

    if (isset($decodedData['title']) && isset($decodedData['description'])) {
        $title = $decodedData['title'];
        $description = $decodedData['description'];


        $stmt = $conn->prepare("INSERT INTO challenges (title, description) VALUES (?, ?)");
        $stmt->bind_param("ss", $title, $description);

        if ($stmt->execute()) {

            $response = [
                'status' => 'success',
                'message' => 'Challenge submitted successfully',
                'data' => [
                    'title' => $title,
                    'description' => $description
                ]
            ];
        } else {

            $response = [
                'status' => 'error',
                'message' => 'Error submitting challenge: ' . $stmt->error
            ];
        }


        $stmt->close();
    } else {

        $response = [
            'status' => 'error',
            'message' => 'Invalid input data'
        ];
    }


    $conn->close();


    header('Content-Type: application/json');
    echo json_encode($response);
} else {

    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
