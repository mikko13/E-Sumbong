<?php
include 'connection.php';

$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];
$recipient_email = $_POST['recipient_email'];
$username = $_POST['username'];
$typepassword = $_POST['typepassword'];

$hashed_password = password_hash($typepassword, PASSWORD_DEFAULT);

$sql = "INSERT INTO users_acc (firstname, lastname, username, recipient_email, typepassword) VALUES ('$firstname', '$lastname', '$username', '$recipient_email', '$hashed_password')";

if ($conn->query($sql) === TRUE) {
    session_start();
    session_destroy();
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
