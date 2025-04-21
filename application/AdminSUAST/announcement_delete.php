<?php
require_once "../../configuration/config.php";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["id"])) {
    $id = intval($_POST["id"]);
    
    // Perform the deletion query
    $query = mysqli_query($con, "DELETE FROM tbl_announcement WHERE id = '$id'");

    if ($query) {
        echo "success"; // Return success if deletion was successful
    } else {
        echo "error"; // Return error if deletion failed
    }
} else {
    echo "invalid"; // If no valid ID was provided, return invalid
}
?>
