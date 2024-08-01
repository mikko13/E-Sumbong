<?php
session_start();
$authCode = isset($_SESSION["auth_code"]) ? $_SESSION["auth_code"] : "";

if (empty($authCode)) {
    header("Location: registration.php");
    exit();
}

include "connection.php";

if (isset($_SESSION["registration_data"])) {
    $registrationData = $_SESSION["registration_data"];

    $firstname = $registrationData["firstname"];
    $lastname = $registrationData["lastname"];
    $recipient_email = $registrationData["recipient_email"];
    $username = $registrationData["username"];
    $typepassword = $registrationData["typepassword"];
} else {
    echo "Registration data not found in session.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eSumbong OTP</title>
    <link rel="stylesheet" type="text/css" href="authenstyles.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="shortcut icon" type="image/png" href="favicon.png">
</head>
<body>
    <div class="container">
    </div>
    <div class="overlay" id="overlay">
        <div class="form" id="otpForm">
            <div class="content">
                <p align="center" class="bold-text">Enter your OTP Code</p>
                <p align="center" class="normal-text">We've sent you an Email</p>
                <div class="inp">
                    <input id="otp1" maxlength="1" class="input" type="password" pattern="\d*" inputmode="numeric" placeholder="" autocomplete="off">
                    <input id="otp2" maxlength="1" class="input" type="password" pattern="\d*" inputmode="numeric" placeholder="" autocomplete="off"> 
                    <input id="otp3" maxlength="1" class="input" type="password" pattern="\d*" inputmode="numeric" placeholder="" autocomplete="off"> 
                    <input id="otp4" maxlength="1" class="input" type="password" pattern="\d*" inputmode="numeric" placeholder="" autocomplete="off">
                    <input id="otp5" maxlength="1" class="input" type="password" pattern="\d*" inputmode="numeric" placeholder="" autocomplete="off"> 
                    <input id="otp6" maxlength="1" class="input" type="password" pattern="\d*" inputmode="numeric" placeholder="" autocomplete="off"> 
                </div>
                <button class="verify-btn" id="verify-btn">Submit</button>
                <button class="close-btn" id="close-btn">Back</button>
                <p id="otp-display" align="center"></p>
            </div>
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

    document.querySelectorAll('.input').forEach(function(input, index, inputs) {
        input.addEventListener('input', function() {
            this.value = this.value.replace(/\D/g, '');
            if (this.value && index < inputs.length - 1) {
                inputs[index + 1].focus();
            }
        });

        input.addEventListener('keydown', function(event) {
            if (event.key === 'Backspace' && this.value === '' && index > 0) {
                inputs[index - 1].focus();
            }
        });

        input.addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                submitForm();
            }
        });
    });

    document.getElementById('close-btn').addEventListener('click', function() {
        window.location.href = 'registration.php';
    });

    document.getElementById('verify-btn').addEventListener('click', function() {
        submitForm();
    });

    function submitForm() {
        var otpCode = '';
        var otpInputs = document.querySelectorAll('.input');
        var allFilled = true;

        otpInputs.forEach(function(input) {
            if (input.value === '') {
                allFilled = false;
            }
            otpCode += input.value;
        });

        if (allFilled) {
        var authCode = "<?php echo $authCode; ?>";

        if (otpCode === authCode) {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    window.location.href = 'login.php';
                }
            };
            xhttp.open("POST", "insert_data.php", true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send("firstname=<?php echo $firstname; ?>&lastname=<?php echo $lastname; ?>&username=<?php echo $username; ?>&recipient_email=<?php echo $recipient_email; ?>&typepassword=<?php echo $typepassword; ?>");
            } else {
                document.getElementById('otpForm').classList.add('shake');
                setTimeout(function() {
                    document.getElementById('otpForm').classList.remove('shake');
                }, 1000);

                document.getElementById('otp-display').innerText = "";
                otpInputs.forEach(function(input) {
                    input.style.borderColor = "red";
                });
                setTimeout(function() {
                    otpInputs.forEach(function(input) {
                        input.value = '';
                        input.style.borderColor = "";
                    });
                }, 800);
            }
        } else {
            document.getElementById('otpForm').classList.add('shake');
            setTimeout(function() {
                document.getElementById('otpForm').classList.remove('shake');
            }, 1000);
            
            document.getElementById('otp-display').innerText = "";
                otpInputs.forEach(function(input) {
                    input.style.borderColor = "red";
                });
                setTimeout(function() {
                    otpInputs.forEach(function(input) {
                        input.value = '';
                        input.style.borderColor = "";
                    });
                }, 800);
        }
    }
</script>
</body>
</html>
