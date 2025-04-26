<?php
session_start();
require_once "../../configuration/config.php";
?>

<!DOCTYPE html>
<html>
<head>
    <title>Updating Status</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<?php
if (!isset($_SESSION['username'])) {
    echo "
    <script>
        Swal.fire({
            icon: 'warning',
            title: 'Unauthorized',
            text: 'Please login to access this page.'
        }).then(() => {
            window.location.href = '../../php/error.php?welcome=Please login to access this page';
        });
    </script>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['request_id'], $_POST['approval_status'])) {
    $request_id = (int) $_POST['request_id'];
    $status = $_POST['approval_status'];

    $query = "UPDATE tbl_leave_requests SET approval_status = ? WHERE id = ?";
    $stmt = $con->prepare($query);

    if (!$stmt) {
        echo "
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Database Error',
                text: 'Prepare failed: " . addslashes($con->error) . "'
            }).then(() => {
                window.location.href = document.referrer;
            });
        </script>";
        exit();
    }

    $stmt->bind_param("si", $status, $request_id);

    if ($stmt->execute()) {
        echo "
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Status Updated!',
                text: 'The request has been marked as \"$status\".',
                confirmButtonColor: '#3085d6'
            }).then(() => {
                window.location.href = 'leave_request.php';
            });
        </script>";
    } else {
        echo "
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Update Failed',
                text: '" . addslashes($stmt->error) . "'
            }).then(() => {
                window.location.href = document.referrer;
            });
        </script>";
    }

    $stmt->close();
    $con->close();
} else {
    echo "
    <script>
        Swal.fire({
            icon: 'warning',
            title: 'Invalid Request',
            text: 'Missing or invalid parameters.'
        }).then(() => {
            window.location.href = document.referrer;
        });
    </script>";
}
?>

</body>
</html>
