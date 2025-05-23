<?php
require_once "../../configuration/config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Retrieve the request ID and the new stage from the form
    $request_id = intval($_POST['request_id']);
    $new_stage = trim($_POST['current_stage']);

    // Get the 'faculty' value for certification (adjust logic if needed)
    $stmt = $con->prepare("SELECT faculty FROM tbl_certification_requests WHERE id = ?");
    $stmt->bind_param("i", $request_id);
    $stmt->execute();
    $stmt->bind_result($faculty);
    $stmt->fetch();
    $stmt->close();

    // Check if request exists
    if (!$faculty) {
        die("Request not found.");
    }

    // Define allowed stages based on faculty value (adjust if certification has specific stages)
    if (strtolower($faculty) === 'faculty') {
        $allowed_stages = ['Fill out Form', 'Pay Cashier', 'Present Request', 'Preparing Certification Record', 'HR Director Signs', 'Record in Logbook', 'For Releasing', 'Completed'];
    } else {
        $allowed_stages = ['Fill out Form', 'Pay Cashier', 'Present Request', 'Preparing Certification Record', 'HR Director Signs', 'Record in Logbook', 'For Releasing', 'Completed'];
    }

    // Validate the selected stage
    if (!in_array($new_stage, $allowed_stages)) {
        die("Invalid stage selected.");
    }

    // Prepare the update query to set the new stage for certification requests
    $stmt = $con->prepare("UPDATE tbl_certification_requests SET current_stage = ? WHERE id = ?");
    $stmt->bind_param("si", $new_stage, $request_id);
    
    if ($stmt->execute()) {
        // Redirect to certification_request.php with a success message
        header("Location: certification_request.php?updated=success");
        exit();
    } else {
        // Redirect to certification_request.php with an error message
        header("Location: certification_request.php?updated=error");
        exit();
    }
} else {
    // Redirect to certification_request.php if the request method is not POST
    header("Location: certification_request.php");
    exit();
}
?>
