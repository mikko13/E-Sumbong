<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eSumbong Delete User</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="admin_create.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="shortcut icon" type="image/png" href="favicon.png">

    <style>
        @font-face {
            font-family: "Geometos";
            src: url('Geometos.ttf') format('truetype');
        }

        body {
            background: linear-gradient(
                270deg,
                #2b3467,
                #bad7e9
            );
            overflow: hidden;
        }

        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            margin: 50px auto;
            font-family: 'Century Gothic', Arial, sans-serif;
        }

        form p {
            margin: 10px 0;
        }

        form button {
            padding: 10px 20px;
            margin-right: 10px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        button[name='confirm'] {
            background-color: #dc3545;
            color: #fff;
        }

        button[name='cancel'] {
            background-color: #007bff;
            color: #fff;
        }

        button[name='confirm']:hover,
        button[name='cancel']:hover {
            opacity: 0.8;
        }
    </style>
</head>
<body>
<?php
include_once "connection.php";

if (isset($_GET["id"])) {
    $id = $_GET["id"];

    $sql_user = "SELECT * FROM users_acc WHERE id = $id";
    $result_user = $conn->query($sql_user);

    if ($result_user->num_rows == 1) {
        $row = $result_user->fetch_assoc();
        $firstname = $row["firstname"];
        $lastname = $row["lastname"];
        $username = $row["username"];
        $recipient_email = $row["recipient_email"];

        echo "<form method='POST'>";
        echo "<p><strong>Are you sure you want to delete this user?</strong></p>";
        echo "<p><strong>First Name:</strong> $firstname</p>";
        echo "<p><strong>Last Name:</strong> $lastname</p>";
        echo "<p><strong>Username:</strong> $username</p>";
        echo "<p><strong>Email:</strong> $recipient_email</p>";
        echo "<input type='hidden' name='id' value='$id'>";
        echo "<button type='submit' name='confirm'>Confirm</button>";
        echo "<button type='submit' name='cancel'>Cancel</button>";
        echo "</form>";

        if (isset($_POST["confirm"])) {
            $delete_query = "DELETE FROM users_acc WHERE id = $id";
            if ($conn->query($delete_query) === true) {
                header("Location: user-accounts-table.php");
                exit();
            } else {
                echo "Error deleting record: " . $conn->error;
            }
        }

        if (isset($_POST["cancel"])) {
            header("Location: user-accounts-table.php");
            exit();
        }
    } else {
        echo "User not found";
    }
} else {
    echo "ID parameter missing";
}

$conn->close();
?>
</body>
</html>
