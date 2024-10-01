<?php
session_start();


$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "crowd_sourcing"; 


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $email = $_POST['email'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $organization = $_POST['organization'];
    $region = $_POST['region'];
    $challengeAreas = isset($_POST['challenge_areas']) ? implode(", ", $_POST['challenge_areas']) : '';


    $stmt = $conn->prepare("INSERT INTO challenge_notifications (email, first_name, last_name, organization, region, challenge_areas) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $email, $firstName, $lastName, $organization, $region, $challengeAreas);


    if ($stmt->execute()) {
        $_SESSION['successMessage'] = "Your submission has been received successfully!";
    } else {
        $_SESSION['successMessage'] = "Error: " . $stmt->error;
    }

    $stmt->close();


    header("Location: social_news.html"); 
    exit();
}


$conn->close();
?>
