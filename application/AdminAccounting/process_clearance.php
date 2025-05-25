<?php
session_start();
require_once "../../configuration/config.php";

// Redirect if not logged in
if (!isset($_SESSION['role'])) {
    showSweetAlert('Please login to access this page', 'error', '../../php/error.php');
    exit();
}

// Only allow POST with ID and status
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id']) && isset($_POST['status'])) {
    $id = intval($_POST['id']);
    $status = trim($_POST['status']);

    // Validate allowed statuses
    $allowedStatuses = ["For Signature", "For Payment", "Cleared"];
    if (!in_array($status, $allowedStatuses)) {
        showSweetAlert('Invalid status received.', 'error', 'clearance.php');
        exit();
    }

    // Start transaction
    $con->begin_transaction();

    try {
        // Update clearance request status
        $query = "UPDATE tbl_clearance_requests SET status = ? WHERE id = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("si", $status, $id);
        if (!$stmt->execute()) {
            throw new Exception("Failed to update clearance request status: " . $stmt->error);
        }

        // Get student ID
        $query = "SELECT student_id FROM tbl_clearance_requests WHERE id = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $student_data = $result->fetch_assoc();

        if (!$student_data) {
            throw new Exception("Student ID not found for request ID: $id");
        }

        $student_id = $student_data['student_id'];

        // Update student status
        $query = "UPDATE tbl_student_users SET status = ? WHERE school_id = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("ss", $status, $student_id);
        if (!$stmt->execute()) {
            throw new Exception("Failed to update student status: " . $stmt->error);
        }

        // Commit transaction
        $con->commit();
        showSweetAlert("Status updated successfully to '$status'", 'success', 'clearance.php');

    } catch (Exception $e) {
        $con->rollback();
        error_log("Update error: " . $e->getMessage());
        showSweetAlert("Error: " . $e->getMessage(), 'error', 'clearance.php');
    } finally {
        $stmt->close();
        $con->close();
    }

} else {
    showSweetAlert('Invalid request.', 'error', 'clearance.php');
    exit();
}

// SweetAlert2 wrapper
function showSweetAlert($message, $type, $redirect) {
    $icon = $type === 'success' ? 'success' : 'error';
    echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
        Swal.fire({
            icon: '$icon',
            title: '".ucfirst($type)."',
            text: `$message`,
            confirmButtonText: 'OK',
            allowOutsideClick: false
        }).then(() => {
            window.location.href = '$redirect';
        });
    </script>";
}
?>
