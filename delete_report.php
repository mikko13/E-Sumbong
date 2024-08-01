<?php
include "connection.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $reportId = $_POST["reportId"];
    $status = $_POST["status"];

    $query = "DELETE FROM report WHERE id = '$reportId'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo "Deleted successfully";
    } else {
        echo "Error Deleting";
    }

    mysqli_close($conn);
}
?>

