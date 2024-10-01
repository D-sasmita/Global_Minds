<?php
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "crowd_sourcing";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$stmt = $conn->prepare("SELECT name, password FROM users WHERE email = ?");
$stmt->bind_param("s", $email);

$email = $_POST['email'];
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($name, $hashed_password);
$stmt->fetch();

if ($stmt->num_rows > 0 && password_verify($_POST['password'], $hashed_password)) {

    echo json_encode(["success" => true]);
} else {
    echo json_encode(["error" => "Invalid email or password!"]);
}

$stmt->close();
$conn->close();
?>
