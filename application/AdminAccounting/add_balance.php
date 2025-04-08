<?php
session_start();
ob_start();

// Check if the user is logged in
if (!isset($_SESSION['role'])) {
    header("Location: ../../php/error.php?welcome=Please login to access this page");
    exit();
}

// Include configuration file
require_once "../../configuration/config.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and collect POST data
    $clearance_id = $_POST['clearance_id'];
    $balance = $_POST['balance'];

    // Step 1: Get student_id from the clearance request
    $query = "SELECT cr.student_id, su.id AS user_id 
              FROM tbl_clearance_requests cr
              LEFT JOIN tbl_student_users su ON cr.student_id = su.school_id 
              WHERE cr.id = ?";
    
    $stmt = $con->prepare($query);
    $stmt->bind_param('i', $clearance_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $student_school_id = $row['student_id'];  // cr.student_id is school_id
        $student_user_id = $row['user_id'];       // su.id

        // Step 2: Update balance in tbl_clearance_requests
        $update_query = "UPDATE tbl_clearance_requests SET balance = ? WHERE id = ?";
        $update_stmt = $con->prepare($update_query);
        $update_stmt->bind_param('di', $balance, $clearance_id);
        $update_stmt->execute();

        // Step 3: Recalculate total balance for the student
        $total_query = "SELECT SUM(balance) AS total_balance FROM tbl_clearance_requests WHERE student_id = ?";
        $total_stmt = $con->prepare($total_query);
        $total_stmt->bind_param('s', $student_school_id);
        $total_stmt->execute();
        $total_result = $total_stmt->get_result();
        $total_data = $total_result->fetch_assoc();
        $total_balance = $total_data['total_balance'] ?? 0;

        // Step 4: Update the student's balance in tbl_student_users
        $update_student_balance = "UPDATE tbl_student_users SET balance = ? WHERE id = ?";
        $update_student_stmt = $con->prepare($update_student_balance);
        $update_student_stmt->bind_param('di', $total_balance, $student_user_id);
        $update_student_stmt->execute();

        // Redirect on success
        header("Location: student_balances.php?successfully=updated");
        exit();
    } else {
        // If no matching record is found
        header("Location: student_balances.php?error=Clearance ID not found");
        exit();
    }
}
?>
