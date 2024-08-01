<?php
include "connection.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $reportId = $_POST["reportId"];
    $status = $_POST["status"];

    $query = "UPDATE report SET status = '$status' WHERE id = $reportId";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo "Status updated successfully";
    } else {
        echo "Error updating status";
    }

    mysqli_close($conn);
}
?>
