<?php
session_start();
require_once "../../configuration/config.php"; // Ensure database connection

// Redirect if not logged in
if (!isset($_SESSION['role'])) {
    header("Location: ../../php/error.php?welcome=Please login to access this page");
    exit();
}

// Check if ID is provided and is valid
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $contactId = intval($_GET['id']);

    // Prepare the SQL query to delete the contact
    $query = "DELETE FROM tbl_contact WHERE id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $contactId);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Contact deleted successfully!";
    } else {
        $_SESSION['error'] = "Error deleting contact: " . $stmt->error;
    }
    $stmt->close();
} else {
    $_SESSION['error'] = "Invalid contact ID!";
}

header("Location: contact.php");
exit();
?>
