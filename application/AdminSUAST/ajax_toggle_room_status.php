<?php
require_once "../../configuration/config.php";
if (!isset($_POST['room'], $_POST['action'])) {
    http_response_code(400);
    exit('Invalid request.');
}
$room = mysqli_real_escape_string($con, $_POST['room']);
$action = $_POST['action'] === 'disable' ? 1 : 0;

$query = "UPDATE tbl_reservation SET room_disabled = $action WHERE room = '$room'";
if (mysqli_query($con, $query)) {
    echo $action ? "Room disabled successfully." : "Room enabled successfully.";
} else {
    http_response_code(500);
    echo "Failed to update room status.";
}
?>
