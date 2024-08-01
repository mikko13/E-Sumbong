<?php
// Assuming you have a database connection established

// Check if the email exists in the database
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['recipient_email'])) {
    $recipient_email = $_POST['recipient_email'];

    // Your database query to check for duplicate emails
    $stmt = $conn->prepare("SELECT COUNT(*) FROM users_acc WHERE recipient_email = ?");
    $stmt->bind_param("s", $recipient_email);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    // Return the count of duplicate emails
    echo $count;
}
?>
