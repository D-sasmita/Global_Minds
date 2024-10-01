<?php

$servername = "localhost"; 
$username = "root";
$password = "";
$dbname = "crowd_sourcing";


$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
    

    $data = file_get_contents('php://input');
    $decodedData = json_decode($data, true);

    $title = $decodedData['title'] ?? 'No title provided';
    $description = $decodedData['description'] ?? 'No description provided';

    $stmt = $conn->prepare("INSERT INTO news (title, description) VALUES (?, ?)");
    $stmt->bind_param("ss", $title, $description);


    if ($stmt->execute()) {

        $response = [
            'status' => 'success',
            'message' => 'News submitted successfully',
            'news' => [
                'title' => $title,
                'description' => $description
            ]
        ];
    } else {

        $response = [
            'status' => 'error',
            'message' => 'Error submitting news: ' . $stmt->error
        ];
    }


    $stmt->close();
    $conn->close();

    header('Content-Type: application/json');
    echo json_encode($response);
} else {

    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
