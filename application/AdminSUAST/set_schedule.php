<?php
require_once "../../configuration/config.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'], $_POST['examTime'], $_POST['examDate'])) {
    $id = intval($_POST['id']);
    $examTime = mysqli_real_escape_string($con, $_POST['examTime']);
    $examDate = mysqli_real_escape_string($con, $_POST['examDate']);

    // Basic validation
    if (empty($examTime) || empty($examDate)) {
        echo '❌ Exam time and date cannot be empty.';
        exit();
    }

    // Prepare the SQL to update both exam_time and exam_date
    $stmt = $con->prepare("UPDATE tbl_reservation SET exam_time = ?, exam_date = ? WHERE id = ?");
    
    if ($stmt === false) {
        echo '❌ Error preparing the SQL statement.';
        exit();
    }

    $stmt->bind_param("ssi", $examTime, $examDate, $id);

    if ($stmt->execute()) {
        echo '✅ Exam time and date updated successfully.';
    } else {
        echo '❌ Error updating: ' . $stmt->error;
    }

    $stmt->close();
} else {
    echo '❌ Invalid request. Please ensure all required fields are submitted.';
}
?>
