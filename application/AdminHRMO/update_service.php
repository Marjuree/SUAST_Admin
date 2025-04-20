<?php
session_start();

if (!isset($_SESSION['role'])) {
    header("Location: ../../php/error.php?welcome=Unauthorized access");
    exit();
}

require_once "../../configuration/config.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!isset($_POST['request_id'])) {
        header("Location: service_request.php?error=Missing Request ID");
        exit();
    }

    $request_id = intval($_POST['request_id']); // sanitize just in case
    $status = '';

    if (isset($_POST['approve'])) {
        $status = 'Approved';
    } elseif (isset($_POST['disapprove'])) {
        $status = 'Disapproved';
    } else {
        header("Location: service_request.php?error=Invalid Action");
        exit();
    }

    $query = "UPDATE tbl_service_requests SET request_status = ? WHERE id = ?";
    $stmt = $con->prepare($query);

    if (!$stmt) {
        die("Prepare failed: " . $con->error);
    }

    $stmt->bind_param("si", $status, $request_id);

    if ($stmt->execute()) {
        header("Location: service_request.php?success=Request $status successfully");
    } else {
        header("Location: service_request.php?error=Failed to update request");
    }

    $stmt->close();
    $con->close();
} else {
    header("Location: service_request.php");
    exit();
}
