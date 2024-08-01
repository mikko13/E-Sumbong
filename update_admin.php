<?php
include_once 'connection.php';
session_start();

if (!isset($_SESSION['superadmin_email'])) {
    header("Location: index.php");
    exit();
}

$success_message = "";
$error_message = "";
$user_id = "";

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $user_id = $_GET['id'];

    $sql = "SELECT * FROM admin_acc WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $firstname = $row['firstname'];
        $lastname = $row['lastname'];
        $username = $row['username'];
        $admin_email = $row['admin_email']; 
    }
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $username = $_POST['username'];
    $admin_email = $_POST['admin_email'];

    $sql = "UPDATE admin_acc SET firstname=?, lastname=?, username=?, admin_email=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $firstname, $lastname, $username, $admin_email, $user_id);

    if ($stmt->execute()) {
        header("Location: admin-accounts-table_super.php");
    } else {
        $error_message = "Error updating record: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eSumbong Update User Account</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="update_user.css">
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
        <form class="form" id="registration-form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id=" . $user_id); ?>">
            <p class="title">Update User Account</p>
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
                <input required="" placeholder="" type="email" class="input" id="admin_email" name="admin_email" autocomplete="off" value="<?php echo $admin_email; ?>"> <!-- Changed recipient_email to admin_email -->
                <span>Admin Email</span>
            </label> 
                
            <button type="submit" class="submit" name="send_email">Update Account</button>
        </form>
    </div>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/a560976b07.js" crossorigin="anonymous"></script>
</body>
</html>
