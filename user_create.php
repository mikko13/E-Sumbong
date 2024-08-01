<?php
include_once "connection.php";
session_start();

if (!isset($_SESSION["admin_email"]) && !isset($_SESSION["superadmin_email"])) {
    header("Location: index.php");
    exit();
}

$success_message = "";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eSumbong Create User Account</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="admin_create.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="shortcut icon" type="image/png" href="favicon.png">

</head>
<body>
    <header class="header-1">
        <div class="headerlogo">
            <p class="hello"> HELLO ADMIN!</p>
        </div>
        <nav class="navbar">
            <div class="burger-menu" id="burger-menu">
                <div class="bar"></div>
                <div class="bar"></div>
                <div class="bar"></div>
            </div>
            
            <div class="navbar-buttons" id="navbar-buttons">
                <a href="user-accounts-table.php"><button>Go back</button></a>
            </div>
        </nav>
    </header>  


    <div class="container">
        <?php if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $firstname = $_POST["firstname"];
            $lastname = $_POST["lastname"];
            $username = $_POST["username"];
            $recipient_email = $_POST["recipient_email"];
            $typepassword = $_POST["typepassword"];

            $hashed_password = password_hash($typepassword, PASSWORD_DEFAULT);

            $sql = "INSERT INTO users_acc (firstname, lastname, username, recipient_email, typepassword)
            VALUES ('$firstname', '$lastname', '$username', '$recipient_email', '$hashed_password')";

            if ($conn->query($sql) === true) {
                header("Location: user-accounts-table.php");
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }

            $conn->close();
        } ?>
        <form class="form" id="registration-form" method="post" action="<?php echo htmlspecialchars(
            $_SERVER["PHP_SELF"],
        ); ?>">
            <p class="title">Create User Account </p>
            <div class="flex">
                <label>
                    <input required="" placeholder="" type="text" class="input" id="firstname" name="firstname" autocomplete="off">
                    <span>Firstname</span>
                </label>

                <label>
                    <input required="" placeholder="" type="text" class="input" id="lastname" name="lastname" autocomplete="off">
                    <span>Lastname</span>
                </label>
            </div> 
            
            <label>
                <input required="" placeholder="" type="text" class="input" id="username" name="username" autocomplete="off">
                <span>Username</span>
            </label>

            <label>
                <input required="" placeholder="" type="email" class="input" id="recipient_email" name="recipient_email" autocomplete="off">
                <span>Email</span>
            </label> 
                
            <label class="password-input">
                <input required="" placeholder="" type="text" class="input" id="typepassword" name="typepassword" autocomplete="off">
                <span>Password</span>
            </label>

            <button type="submit" class="submit" name="send_email">Create Account</button>
        </form>
    </div>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/a560976b07.js" crossorigin="anonymous"></script>
</body>
</html>
