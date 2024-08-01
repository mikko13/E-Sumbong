<?php
session_start();

if (!isset($_SESSION["recipient_email"])) {
    header("Location: login.php");
    exit();
}

include "connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_password = $_POST["password"];

    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    $recipient_email = $_SESSION["recipient_email"];

    $sql = "UPDATE users_acc SET typepassword = ? WHERE recipient_email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $hashed_password, $recipient_email);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $_SESSION = [];
        session_destroy();
        header("Location: login.php");
        exit();
    } else {
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eSumbong Forgot Password</title>
    <link rel="stylesheet" type="text/css" href="forgot3.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="shortcut icon" type="image/png" href="favicon.png">
    <style>
        .error {
            border: 1px solid red;
        }
    </style>
</head>
<body>
    <div class="container">
    </div>
    <div class="overlay" id="overlay">
        <div class="form-container">
            <div class="logo-container">
                Forgot Password
            </div>
            <form id="form" class="form" method="post">
                <div class="form-group">
                    <input class="password" type="password" id="password" name="password" placeholder="New Password" required="">
                    <i class="fas fa-eye eye-icon" id="eyePassword"></i> <!-- Eye icon -->
                </div>
                <div class="form-group" >
                    <input class="confirmpassword" type="password" id="confirmpassword" name="confirmpassword" placeholder="Confirm Password" required="">
                    <i class="fas fa-eye eye-icon" id="eyeConfirmPassword"></i> <!-- Eye icon -->
                </div>
                <button class="form-submit-btn" type="submit">Change Password</button>
            </form>
            <a href="login.php" class="signup-link link"> Go Back</a>
        </div>
    </div>
    
    <footer>
    </footer>

    <script src="https://kit.fontawesome.com/a560976b07.js" crossorigin="anonymous"></script>
    <script>
        function togglePasswordVisibility(inputId, eyeIconId) {
            const passwordInput = document.getElementById(inputId);
            const eyeIcon = document.getElementById(eyeIconId);

            eyeIcon.addEventListener('click', function () {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                eyeIcon.classList.toggle('fa-eye-slash');
            });
        }
        window.addEventListener('load', function(){
            togglePasswordVisibility('password', 'eyePassword');
            togglePasswordVisibility('confirmpassword', 'eyeConfirmPassword');

            setTimeout(function(){
                var loadingScreen = document.getElementById('loader');
                loadingScreen.classList.add('fade-out');
                setTimeout(function() {
                    loadingScreen.classList.add('hidden');
                }, 200);
            }, 2000);
        });

        document.getElementById("form").addEventListener("submit", function(event) {
            var password = document.getElementById("password").value;
            var confirmpassword = document.getElementById("confirmpassword").value;
            var passwordRegex = /^(?=.*\d)(?=.*[a-zA-Z])(?=.*[^a-zA-Z\d]).{5,}$/;

            if (password !== confirmpassword) {
                event.preventDefault();
                alert("Passwords do not match!");
                document.getElementById("password").style.borderColor = "red";
                document.getElementById("confirmpassword").style.borderColor = "red";
                setTimeout(function() {
                    document.getElementById("password").style.borderColor = "";
                    document.getElementById("confirmpassword").style.borderColor = "";
                }, 5000);
            } else if (!password.match(passwordRegex)) {
                event.preventDefault();
                alert("Password must contain at least 1 uppercase or lowercase letter, 1 symbol, 1 number, and be at least 5 characters long!");
                document.getElementById("password").style.borderColor = "red";
                setTimeout(function() {
                    document.getElementById("password").style.borderColor = "";
                }, 5000);
            }
        });
    </script>
</body>
</html>
