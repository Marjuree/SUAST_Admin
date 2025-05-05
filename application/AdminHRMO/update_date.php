<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once "../../configuration/config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['request_id'], $_POST['new_date'], $_POST['stage'], $_POST['faculty'])) {
        $requestId = intval($_POST['request_id']);
        $newDate = mysqli_real_escape_string($con, $_POST['new_date']);
        $stage = strtolower(trim($_POST['stage']));
        $type = isset($_POST['type']) ? strtolower(trim($_POST['type'])) : '';
        $faculty = strtolower(trim($_POST['faculty']));

        // Determine allowed stages based on Faculty or Staff
        $allowedStages = ($faculty === 'faculty') 
            ? ['hr', 'vp_acad', 'hr_received', 'for_releasing', 'completed']
            : ['hr', 'vp_finance', 'hr_received', 'for_releasing', 'completed'];

        $allowedTypes = ['submitted', 'received', ''];

        if (!in_array($stage, $allowedStages) || !in_array($type, $allowedTypes)) {
            echo 'Invalid stage or type';
            exit;
        }

        // Determine the correct column name with clear ternary nesting
        $column = ($stage === 'completed') ? 'completed_date' :
                  (($stage === 'date_of') ? 'date_of_submitted' :
                  (($stage === 'date') ? 'date_received' :
                  "{$stage}_{$type}"));

        // Ensure datetime format
        if (strpos($newDate, ':') === false) {
            $newDate .= " 00:00:00";
        }

        $query = "UPDATE tbl_leave_requests SET `$column` = ? WHERE id = ?";
        $stmt = $con->prepare($query);

        if ($stmt) {
            $stmt->bind_param('si', $newDate, $requestId);
            if ($stmt->execute()) {
                echo 'success';
            } else {
                echo 'Error executing query: ' . $stmt->error;
            }
            $stmt->close();
        } else {
            echo 'Error preparing query: ' . $con->error;
        }
    } else {
        echo 'Missing POST data';
    }
} else {
    echo 'Invalid request method';
}
