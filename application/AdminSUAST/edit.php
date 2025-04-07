<?php
session_start();
require_once "../../configuration/config.php";

function redirectWithMessage($message, $type = 'success') {
    $_SESSION['flash'] = [
        'message' => $message,
        'type' => $type // success, danger, warning, etc.
    ];
    echo "<script>window.location.href = 'exam_schedule.php';</script>";
    exit();
}

if ($_POST['action'] == "edit") {
    $id = $_POST['id'];
    $name = $_POST['exam_name'];
    $date = $_POST['exam_date'];
    $time = $_POST['exam_time'];
    $venue = $_POST['venue'];
    $room = $_POST['room'];

    $update = mysqli_query($con, "UPDATE tbl_exam_schedule 
        SET exam_name='$name', exam_date='$date', exam_time='$time', venue='$venue', room='$room' 
        WHERE id='$id'");

    if ($update) {
        redirectWithMessage("✅ Exam schedule updated successfully!");
    } else {
        redirectWithMessage("❌ Failed to update exam schedule.", 'danger');
    }
}

if ($_POST['action'] == "delete") {
    $id = $_POST['id'];
    $delete = mysqli_query($con, "DELETE FROM tbl_exam_schedule WHERE id='$id'");

    if ($delete) {
        redirectWithMessage("🗑️ Exam schedule deleted successfully!");
    } else {
        redirectWithMessage("❌ Failed to delete exam schedule.", 'danger');
    }
}
?>
