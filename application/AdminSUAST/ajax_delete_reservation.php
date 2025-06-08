<?php
require_once "../../configuration/config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ids'])) {
    $ids = $_POST['ids'];

    // Make sure $ids is an array
    if (!is_array($ids)) {
        http_response_code(400);
        echo "Invalid input.";
        exit;
    }

    // Sanitize and filter IDs
    $safeIds = array_filter(array_map('intval', $ids));

    if (empty($safeIds)) {
        http_response_code(400);
        echo "No valid IDs provided.";
        exit;
    }

    $idList = implode(',', $safeIds);

    $query = "DELETE FROM tbl_reservation WHERE id IN ($idList)";

    if (mysqli_query($con, $query)) {
        echo "Reservation(s) deleted successfully.";
    } else {
        http_response_code(500);
        echo "Failed to delete reservations.";
    }
} else {
    http_response_code(400);
    echo "Invalid request.";
}
?>
