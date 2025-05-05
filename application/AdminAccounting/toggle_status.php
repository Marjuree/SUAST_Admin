<?php
require_once "../../configuration/config.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $current_enabled = intval($_POST['current_enabled']);

    // Toggle the enabled value
    $new_enabled = ($current_enabled === 1) ? 0 : 1;

    // Begin transaction to ensure both tables are updated atomically
    $con->begin_transaction();

    try {
        // Update tbl_clearance_requests
        $stmt1 = $con->prepare("UPDATE tbl_clearance_requests SET enabled = ? WHERE id = ?");
        $stmt1->bind_param("ii", $new_enabled, $id);
        $stmt1->execute();

        // Update tbl_student_users based on the student_id from tbl_clearance_requests
        $stmt2 = $con->prepare("UPDATE tbl_student_users SET enabled = ? WHERE school_id = (SELECT student_id FROM tbl_clearance_requests WHERE id = ?)");
        $stmt2->bind_param("ii", $new_enabled, $id);
        $stmt2->execute();

        // If both updates are successful, commit the transaction
        $con->commit();

        $_SESSION['swal'] = [
            'type' => 'success',
            'message' => $new_enabled ? 'Clearance and Student Account Enabled' : 'Clearance and Student Account Disabled'
        ];
    } catch (Exception $e) {
        // If any update fails, roll back the transaction
        $con->rollback();

        $_SESSION['swal'] = [
            'type' => 'error',
            'message' => 'Status update failed: ' . $e->getMessage()
        ];
    }

    // Close the prepared statements
    $stmt1->close();
    $stmt2->close();
}

header("Location: clearance_requests.php");
exit();
