<?php
require_once "../../configuration/config.php";

if (!isset($_POST['ids']) || !is_array($_POST['ids'])) {
    $_SESSION['swal'] = ['type' => 'error', 'message' => 'No records selected.'];
    header("Location: clearance.php");
    exit();
}

$ids = array_map('intval', $_POST['ids']);
$idList = implode(',', $ids);

if (isset($_POST['bulk_enable'])) {
    $query = "
        UPDATE tbl_clearance_requests cr
        JOIN tbl_student_users su ON cr.student_id = su.school_id
        SET cr.enabled = 1, su.enabled = 1
        WHERE cr.id IN ($idList)";
} elseif (isset($_POST['bulk_disable'])) {
    $query = "
        UPDATE tbl_clearance_requests cr
        JOIN tbl_student_users su ON cr.student_id = su.school_id
        SET cr.enabled = 0, su.enabled = 0
        WHERE cr.id IN ($idList)";
} else {
    $_SESSION['swal'] = ['type' => 'error', 'message' => 'Invalid action.'];
    header("Location: clearance.php");
    exit();
}

if ($con->query($query)) {
    $_SESSION['swal'] = ['type' => 'success', 'message' => 'Bulk update successful.'];
} else {
    $_SESSION['swal'] = ['type' => 'error', 'message' => 'Database update failed.'];
}

header("Location: clearance.php");
exit();
