<?php
session_start();

include "connection.php";

// Redirect to login page if not logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: index.php");
    exit();
}

// Fetch user data
$email = $_SESSION['recipient_email'];
$query = "SELECT * FROM users_acc WHERE recipient_email = '$email'";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $id = $row['id'];
    $firstname = $row['firstname'];
    $lastname = $row['lastname'];
    $username = $row['username'];
    $recipient_email = $row['recipient_email'];
    $profile_picture = $row['profile_picture'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Process form data when form is submitted
    $new_firstname = $_POST["firstname"];
    $new_lastname = $_POST["lastname"];
    $new_username = $_POST["username"];
    $new_recipient_email = $_POST["recipient_email"];

    
    // Update user data
    $update_query = "UPDATE users_acc SET firstname='$new_firstname', lastname='$new_lastname', username='$new_username', recipient_email='$new_recipient_email' WHERE id='$id'";
    
    if (mysqli_query($conn, $update_query)) {
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Error updating profile: " . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eSumbong Update Account Info</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="update_profile.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="shortcut icon" type="image/png" href="favicon.png">
</head>
<body>
    <div class="container">
        <form class="form" id="registration-form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <p class="title">Edit Account Info</p>
            <div class="profile-pic-container">
                <a href="change_picture.php"> <!-- Change this URL to the appropriate one -->
                    <img src="<?php echo $profile_picture ? $profile_picture : 'profilepics/userphoto.jpeg'; ?>" alt="Profile Picture" class="profile-pic" id="profile-pic">
                </a>
            </div>
            <div class="flex">
                <label>
                    <input required="" placeholder="" type="text" class="input" id="firstname" name="firstname" autocomplete="off" value="<?php echo $firstname; ?>">
                    <span>Firstname</span>
                </label>

                <label>
                    <input required="" placeholder="" type="text" class="input" id="lastname" name="lastname" autocomplete="off" value="<?php echo $lastname; ?>">
                    <span>Lastname</span>
                </label>
            </div> 
            
            <label>
                <input required="" placeholder="" type="text" class="input" id="username" name="username" autocomplete="off" value="<?php echo $username; ?>">
                <span>Username</span>
            </label>

            <label>
                <input required="" placeholder="" type="email" class="input" id="recipient_email" name="recipient_email" autocomplete="off" value="<?php echo $recipient_email; ?>">
                <span>Email</span>
            </label> 

            <div class="changepass-container">
                <a href="change_password.php" class="changepass">Change Password</a>
            </div>
                
            <button type="submit" class="submit" name="send_email">Update Account</button>
            <button type="button" class="back" onclick="window.location.href='dashboard.php'">Go Back</button>
        </form>
    </div>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/a560976b07.js" crossorigin="anonymous"></script>
    <script>
        // Add hover effect to profile picture
        var profilePic = document.getElementById("profile-pic");
        profilePic.addEventListener("mouseover", function() {
            this.style.filter = "brightness(70%)";
        });
        profilePic.addEventListener("mouseout", function() {
            this.style.filter = "brightness(100%)";
        });
    </script>
</body>
</html>
