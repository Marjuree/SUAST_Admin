<?php
session_start();
require_once "../../configuration/config.php";

if (!isset($_SESSION['role'])) {
    header("Location: ../../php/error.php?welcome=Please login to access this page");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id = intval($_POST['id']);

    // Set the correct status based on the button clicked
    if (isset($_POST['approve'])) {
        $status = "Approved";
    } elseif (isset($_POST['disapprove'])) {
        $status = "Rejected"; // ✅ Changed 'Disapproved' to 'Rejected'
    } else {
        error_log("Invalid action received for ID: " . $id);
        header("Location: dashboard.php?error=Invalid action");
        exit();
    }

    // Debugging: Log the status update attempt
    error_log("Updating ID $id to status: $status");

    // Start a transaction to ensure both updates are done together
    $con->begin_transaction();

    try {
        // Update the status in tbl_clearance_requests
        $query = "UPDATE tbl_clearance_requests SET status = ? WHERE id = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("si", $status, $id);
        
        if (!$stmt->execute()) {
            throw new Exception("Failed to update clearance request status: " . $stmt->error);
        }

        // Retrieve the student_id from tbl_clearance_requests for the given request
        $query = "SELECT student_id FROM tbl_clearance_requests WHERE id = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $student_data = $result->fetch_assoc();

        if ($student_data) {
            $student_id = $student_data['student_id'];

            // Update the status in tbl_student_users (existing status column)
            $query = "UPDATE tbl_student_users SET status = ? WHERE school_id = ?";
            $stmt = $con->prepare($query);

            // Since both student_id and school_id are strings (CHAR and VARCHAR), use "ss" for bind_param
            $stmt->bind_param("ss", $status, $student_id);

            if (!$stmt->execute()) {
                throw new Exception("Failed to update student status: " . $stmt->error);
            }
        } else {
            throw new Exception("Student ID not found for clearance request ID: $id");
        }

        // Commit the transaction if both updates succeed
        $con->commit();
        error_log("Update successful for ID: $id");

        // Redirect back with a success message
        header("Location: AccountingDashboard.php?success=Status updated successfully to $status");
        exit();

    } catch (Exception $e) {
        // Rollback the transaction if any error occurs
        $con->rollback();
        error_log("Error updating status: " . $e->getMessage());
        header("Location: dashboard.php?error=" . urlencode($e->getMessage()));
        exit();
    } finally {
        // Close the statement and connection
        $stmt->close();
        $con->close();
    }

} else {
    error_log("Invalid request detected");
    header("Location: dashboard.php?error=Invalid request");
    exit();
}
?>
