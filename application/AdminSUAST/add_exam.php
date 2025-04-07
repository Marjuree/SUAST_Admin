<?php
require_once "../../configuration/config.php";

// ✅ Environment-based error display (for safety)
if ($_SERVER['SERVER_NAME'] === 'localhost') {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    error_reporting(0);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ✅ Sanitize inputs
    $exam_name = mysqli_real_escape_string($con, $_POST['exam_name']);
    $exam_date = mysqli_real_escape_string($con, $_POST['exam_date']);
    $exam_time = mysqli_real_escape_string($con, $_POST['exam_time']);
    $venue     = mysqli_real_escape_string($con, $_POST['venue']);
    $room      = mysqli_real_escape_string($con, $_POST['room']);

    // ✅ Check slot capacity
    $checkSlotsQuery = "
        SELECT COUNT(*) AS total_slots 
        FROM tbl_exam_schedule 
        WHERE room = '$room' AND exam_date = '$exam_date'
    ";
    $result = mysqli_query($con, $checkSlotsQuery);
    $data = mysqli_fetch_assoc($result);

    if ($data['total_slots'] >= 30) {
        echo json_encode([
            "status" => "error",
            "message" => "❌ This room already has 30 exams scheduled on this date."
        ]);
        exit();
    }

    // ✅ Insert into DB
    $insertQuery = "
        INSERT INTO tbl_exam_schedule (exam_name, exam_date, exam_time, venue, room) 
        VALUES ('$exam_name', '$exam_date', '$exam_time', '$venue', '$room')
    ";

    if (mysqli_query($con, $insertQuery)) {
        echo json_encode([
            "status" => "success",
            "message" => "✅ Exam schedule successfully added!"
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "❌ Database error: " . mysqli_error($con)
        ]);
    }

    mysqli_close($con);
}
?>
