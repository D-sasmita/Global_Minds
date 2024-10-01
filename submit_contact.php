<?php

$host = 'localhost'; 
$db = 'crowd_sourcing'; 
$user = 'root';
$pass = '';


$conn = new mysqli($host, $user, $pass, $db);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $fullName = $conn->real_escape_string(trim($_POST['fullName']));
    $email = $conn->real_escape_string(trim($_POST['email']));
    $message = $conn->real_escape_string(trim($_POST['message']));


    $sql = "INSERT INTO contact_messages (full_name, email, message) VALUES ('$fullName', '$email', '$message')";
    
    if ($conn->query($sql) === TRUE) {

        header('Location: contact.html?success=1');
        exit();
    } else {

        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}


$conn->close();
?>
