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
    $profile_picture = $row['profile_picture'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle uploaded file
    if (isset($_FILES['new_profile_pic'])) {
        $file_name = $_FILES['new_profile_pic']['name'];
        $file_tmp = $_FILES['new_profile_pic']['tmp_name'];
        $file_type = $_FILES['new_profile_pic']['type'];
        $file_size = $_FILES['new_profile_pic']['size'];
        $file_error = $_FILES['new_profile_pic']['error'];

        // Define allowed image file types
        $allowed_types = array('image/jpeg', 'image/png', 'image/gif');

        // Check if file type is allowed
        if (in_array($file_type, $allowed_types)) {
            // Check if file is uploaded without errors
            if ($file_error === 0) {
                // Move uploaded file to destination folder
                $destination = 'profilepics/' . $file_name;
                if (move_uploaded_file($file_tmp, $destination)) {
                    // Update profile picture in database
                    $update_query = "UPDATE users_acc SET profile_picture='$destination' WHERE id='$id'";
                    if (mysqli_query($conn, $update_query)) {
                        // Redirect to dashboard.php after successful update
                        header("Location: dashboard.php");
                        exit();
                    } else {
                        echo "Error updating profile picture: " . mysqli_error($conn);
                    }
                } else {
                    echo "Error moving uploaded file";
                }
            } else {
                echo "Error uploading file";
            }
        }
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
    <link rel="stylesheet" type="text/css" href="change_picture.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="shortcut icon" type="image/png" href="favicon.png">
</head>
<body>
    <div class="container">
        <form class="form" id="registration-form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
            <p class="title">Change Picture</p>
            <div class="profile-pic-container">
                <label for="new-profile-pic" class="profile-pic-label">
                    <input type="file" id="new-profile-pic" name="new_profile_pic" style="display: none;" onchange="previewProfilePicture(event)">
                    <img src="<?php echo $profile_picture ? $profile_picture : 'profilepics/userphoto.jpeg'; ?>" alt="Profile Picture" class="profile-pic" id="profile-pic">
                </label>
            </div>
            <button type="submit" class="submit" name="send_email">Save Changes</button>
            <button type="button" class="back" onclick="window.location.href='update_profile.php'">Go Back</button>
        </form>
    </div>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/a560976b07.js" crossorigin="anonymous"></script>
    <script>
        function previewProfilePicture(event) {
            var input = event.target;
            var reader = new FileReader();
            reader.onload = function(){
                var profilePic = document.getElementById('profile-pic');
                profilePic.src = reader.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    </script>
</body>
</html>
