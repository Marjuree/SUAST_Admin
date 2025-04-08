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

    // Query to get the student's information from tbl_clearance_requests
    $query = "SELECT cr.student_id, su.full_name 
              FROM tbl_clearance_requests cr
              LEFT JOIN tbl_student_users su ON cr.student_id = su.school_id 
              WHERE cr.id = '$clearance_id'";

    $result = mysqli_query($con, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        
        // Gather student data
        $student_id = $row['student_id'];
        $student_name = $row['full_name'];

        // Update the balance in tbl_clearance_requests
        $update_query = "UPDATE tbl_clearance_requests 
                         SET balance = '$balance' 
                         WHERE id = '$clearance_id'";

        if (mysqli_query($con, $update_query)) {
            // Success message and redirection
            header("Location: student_balances.php?successfully=added");
            exit(); // Don't forget to call exit() to stop further execution
        } else {
            // Error handling and redirection
            header("Location: student_balances.php?error=" . urlencode(mysqli_error($con)));
            exit();
        }
    } else {
        // Error if clearance ID not found
        header("Location: student_balances.php?error=Clearance ID not found");
        exit();
    }
}
?>
