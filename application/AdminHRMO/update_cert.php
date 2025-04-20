<?php
session_start();
require_once "../../configuration/config.php"; // Ensure this includes a valid $con connection

if (!isset($_SESSION['username'])) {
    header("Location: ../../php/error.php?welcome=Please login to access this page");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET['request_id'])) {
    $request_id = $_GET['request_id'];
    $status = isset($_GET['approve']) ? 'Approved' : (isset($_GET['disapprove']) ? 'Disapproved' : null);

    if ($status) {
        $query = "UPDATE tbl_certification_requests SET request_status = ? WHERE id = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("si", $status, $request_id);

        if ($stmt->execute()) {
            $_SESSION['message'] = "Certification request has been $status successfully.";
        } else {
            $_SESSION['error'] = "Failed to update certification request.";
        }

        $stmt->close();
    }
    $con->close();
}

header("Location: certification_request.php"); // Redirect back to the certification requests page
exit();
