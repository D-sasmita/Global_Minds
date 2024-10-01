<?php
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "crowd_sourcing"; 


$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $name, $email, $password);

$name = $_POST['name'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hashing the password

if ($stmt->execute()) {

    echo json_encode(["success" => true]);
} else {

    echo json_encode(["success" => false, "error" => "Email already exists!"]);
}

$stmt->close();
$conn->close();
?>
