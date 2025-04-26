<?php
require_once "../../configuration/config.php";
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $exam_name = $_POST['exam_name'];
    $exam_date = $_POST['exam_date'];
    $exam_time = $_POST['exam_time'];
    $venue = $_POST['venue'];
    $room = $_POST['room'];

    // Check if room already has 30 exams on that date
    $checkQuery = "SELECT COUNT(*) as total FROM tbl_exam_schedule WHERE room = ? AND exam_date = ?";
    $stmt = $con->prepare($checkQuery);
    $stmt->bind_param("ss", $room, $exam_date);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count >= 30) {
        echo json_encode([
            "status" => "error",
            "message" => "This room already has 30 exams on this date."
        ]);
        exit;
    }

    // Insert new exam schedule
    $insertQuery = "INSERT INTO tbl_exam_schedule (exam_name, exam_date, exam_time, venue, room) VALUES (?, ?, ?, ?, ?)";
    $stmt = $con->prepare($insertQuery);
    $stmt->bind_param("sssss", $exam_name, $exam_date, $exam_time, $venue, $room);

    if ($stmt->execute()) {
        echo json_encode([
            "status" => "success",
            "message" => "Exam schedule added successfully."
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Database error: " . $stmt->error
        ]);
    }
    exit;
}
?>
