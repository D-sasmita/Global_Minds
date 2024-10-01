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


header('Content-Type: application/json');


if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $sql = "SELECT id, title, description, votes, comments, category FROM solutions";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $solutions = [];
        while ($row = $result->fetch_assoc()) {
            $solutions[] = $row;
        }
        echo json_encode(['status' => 'success', 'data' => $solutions]);
    } else {
        echo json_encode(['status' => 'success', 'data' => []]);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['title']) && isset($data['description']) && isset($data['category'])) {
        $title = $data['title'];
        $description = $data['description'];
        $category = $data['category'];

        $stmt = $conn->prepare("INSERT INTO solutions (title, description, votes, comments, category) VALUES (?, ?, 0, 0, ?)");
        $stmt->bind_param("sss", $title, $description, $category);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Solution added successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error adding solution: ' . $stmt->error]);
        }
        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid input data']);
    }
}


$conn->close();
?>
