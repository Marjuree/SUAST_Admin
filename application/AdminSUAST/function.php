<?php
require_once "../../configuration/config.php";

// Update exam schedule
if ($_POST['action'] === "edit") {
    $id = mysqli_real_escape_string($con, $_POST['id']);
    $name = mysqli_real_escape_string($con, $_POST['exam_name']);
    $date = mysqli_real_escape_string($con, $_POST['exam_date']);
    $time = mysqli_real_escape_string($con, $_POST['exam_time']);
    $subject = mysqli_real_escape_string($con, $_POST['subject']);
    $room = mysqli_real_escape_string($con, $_POST['room']);

    mysqli_query($con, "UPDATE tbl_exam_schedule 
        SET exam_name='$name', exam_date='$date', exam_time='$time', subject='$subject', room='$room' 
        WHERE id='$id'");

    echo "Exam schedule updated successfully!";
    exit();
}

// Delete exam schedule
if ($_POST['action'] === "delete") {
    $id = mysqli_real_escape_string($con, $_POST['id']);
    mysqli_query($con, "DELETE FROM tbl_exam_schedule WHERE id='$id'");
    echo "Exam schedule deleted successfully!";
    exit();
}

// Toggle schedule status (Enable/Disable)
if ($_POST['action'] === "toggle_status") {
    $id = mysqli_real_escape_string($con, $_POST['id']);
    $status = mysqli_real_escape_string($con, $_POST['status']);

    mysqli_query($con, "UPDATE tbl_exam_schedule SET status='$status' WHERE id='$id'");
    echo "Status updated to '$status'";
    exit();
}
?>
