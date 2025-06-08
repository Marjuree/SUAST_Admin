<?php
require_once "../../configuration/config.php";

if (!isset($_POST['rooms'], $_POST['status'])) {
    http_response_code(400);
    exit('Invalid request.');
}

$rooms = $_POST['rooms'];
$status = $_POST['status'] === 'disabled' ? 1 : 0;

if (!is_array($rooms)) {
    $rooms = [$rooms];
}

$roomList = array_map(function($room) use ($con) {
    return "'" . mysqli_real_escape_string($con, $room) . "'";
}, $rooms);

$roomListStr = implode(',', $roomList);

$query = "UPDATE tbl_reservation SET room_disabled = $status WHERE room IN ($roomListStr)";
if (mysqli_query($con, $query)) {
    echo $status ? "Selected room(s) disabled successfully." : "Selected room(s) enabled successfully.";
} else {
    http_response_code(500);
    echo "Failed to update room status.";
}
?>
