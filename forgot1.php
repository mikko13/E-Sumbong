<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
require 'connection.php';

// Function to generate random 6-digit number
function generateRandomNumber() {
    return mt_rand(100000, 999999);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Fetch recipient email from form
    $recipient_email = $_POST['recipient_email'];

    // Establish database connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare SQL statement to fetch first name and last name based on email
    $stmt = $conn->prepare("SELECT firstname, lastname FROM users_acc WHERE recipient_email = ?");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("s", $recipient_email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // First name and last name fetched successfully
        $row = $result->fetch_assoc();
        $firstname = $row['firstname'];
        $lastname = $row['lastname'];

        // Generate random 6-digit number
        $auth_code = generateRandomNumber();

        // Store auth_code and recipient_email in session
        $_SESSION['auth_code'] = $auth_code;
        $_SESSION['recipient_email'] = $recipient_email;

        // Create an instance; passing `true` enables exceptions
        $mail = new PHPMailer(true);

        try {
            $mail->SMTPDebug = 0;
            $mail->isSMTP();                                            // Send using SMTP
            $mail->Host       = 'smtp.hostinger.com';                       // Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
            $mail->Username   = 'esumbong@esumbong.site';      // SMTP username
            $mail->Password   = 'Overlord123!';                          // SMTP password
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;                                    // TCP port to connect to
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
            $mail->setFrom('esumbong@esumbong.site', 'E-SUMBONG');
            $mail->addAddress($recipient_email);                       // Add the recipient email entered by the user

            $mail->isHTML(true);                                       // Set email format to HTML
            $mail->Subject = 'Authentication Code';
            $mail->Body    =  "Hello " . $firstname . " " . $lastname . ",
            <br><br>
                              It seems that you've requested to change your password 
                              <br><br>
                              Here is your Authentication Code: <b style='font-size: 20px;'>$auth_code</b>";

            $mail->send();

            // Redirect after sending email
            header("Location: forgot2.php");
            exit();
        } catch (Exception $e) {
            // Handle exceptions here
        }
    } else {
        $error_message = "Email does not exist!";
    }
    $stmt->close();
    $conn->close();
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eSumbong Forgot Password</title>
    <link rel="stylesheet" type="text/css" href="forgot1.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="shortcut icon" type="image/png" href="favicon.png">
</head>
<body>

    <div class="container">
    </div>
    <div class="overlay" id="overlay">
        <div class="form-container">
            
            <div class="logo-container">
                Forgot Password
            </div>
            <form class="form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <div class="form-group">
        <input class="email" type="email" id="recipient_email" name="recipient_email" placeholder="Enter your email" required="">
    </div>

    <button class="form-submit-btn" type="submit">Authenticate Email</button>
</form>
            <a href="login.php" class="signup-link link"> Go Back</a>
            <?php if(isset($error_message)) { ?>
        <div class="error-message"><?php echo $error_message; ?></div>
    <?php } ?>
        </div>
    </div>
    
    <footer>
    </footer>

    <script src="https://kit.fontawesome.com/a560976b07.js" crossorigin="anonymous"></script>
    <script>
        window.addEventListener('load', function(){
            setTimeout(function(){
                var loadingScreen = document.getElementById('loader');
                loadingScreen.classList.add('fade-out');
                setTimeout(function() {
                    loadingScreen.classList.add('hidden');
                }, 200);
            }, 2000);
        });
    </script>
</body>
</html>