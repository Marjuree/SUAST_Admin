<?php
require_once "../../configuration/config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Retrieve the request ID and the new stage from the form
    $request_id = intval($_POST['request_id']);
    $new_stage = trim($_POST['current_stage']);

    // Get the 'faculty' value
    $stmt = $con->prepare("SELECT faculty FROM tbl_leave_requests WHERE id = ?");
    $stmt->bind_param("i", $request_id);
    $stmt->execute();
    $stmt->bind_result($faculty);
    $stmt->fetch();
    $stmt->close();

    // Check if request exists
    if (!$faculty) {
        die("Request not found.");
    }

    // Define allowed stages based on faculty value
    if (strtolower($faculty) === 'faculty') {
        $allowed_stages = ['HR', 'VP ACAD', 'HR Received', 'For Releasing'];
    } else {
        $allowed_stages = ['HR', 'VP Finance', 'HR Received', 'For Releasing'];
    }

    // Validate the selected stage
    if (!in_array($new_stage, $allowed_stages)) {
        die("Invalid stage selected.");
    }

    // Prepare the update query to set the new stage
    $stmt = $con->prepare("UPDATE tbl_leave_requests SET current_stage = ? WHERE id = ?");
    $stmt->bind_param("si", $new_stage, $request_id);
    
    if ($stmt->execute()) {
        // Redirect to leave_request.php with a success message
        header("Location: leave_request.php?updated=success");
        exit();
    } else {
        // Redirect to leave_request.php with an error message
        header("Location: leave_request.php?updated=error");
        exit();
    }
} else {
    // Redirect to leave_request.php if the request method is not POST
    header("Location: leave_request.php");
    exit();
}
?>
