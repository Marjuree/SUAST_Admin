<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once "../../configuration/config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['request_id'], $_POST['new_date'], $_POST['stage'])) {
        $requestId = intval($_POST['request_id']);
        $newDate = mysqli_real_escape_string($con, $_POST['new_date']);
        $stage = strtolower(trim($_POST['stage']));
        $type = isset($_POST['type']) ? strtolower(trim($_POST['type'])) : '';

        // VALID stages and types according to your DATABASE
        $validStages = ['issuance', 'cashier', 'present_request', 'prepare_service_record', 'hr_director_signs', 'logbook', 'for_releasing', 'completed'];
        $allowedTypes = ['submitted', 'received', '']; // only submitted/received or '' for completed

        if (!in_array($stage, $validStages) || !in_array($type, $allowedTypes)) {
            echo "Invalid stage or type. Stage: $stage | Type: $type";
            exit;
        }

        // Determine correct column
        if ($stage === 'completed') {
            $column = 'completed_date'; // special case
        } else {
            $column = "{$stage}_{$type}";
        }

        // Ensure datetime format
        if (strpos($newDate, ':') === false) {
            $newDate .= " 00:00:00";
        }

        // Final query
        $query = "UPDATE tbl_service_requests SET `$column` = ? WHERE id = ?";
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
?>
