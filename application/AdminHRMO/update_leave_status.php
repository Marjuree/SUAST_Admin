<?php
// Include your database connection
require_once "../../configuration/config.php"; // Ensure this includes a valid $con connection
?>

<!DOCTYPE html>
<html>
<head>
    <title>Updating Status</title>
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requestId = $_POST['request_id'] ?? null;
    $status = $_POST['status'] ?? null;

    if ($requestId && $status) {
        // Update query for tbl_leave_requests
        $stmt = $con->prepare("UPDATE tbl_leave_requests SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $requestId);

        if ($stmt->execute()) {
            // SweetAlert success
            echo "
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Status Updated!',
                    text: 'The completion status has been updated.',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = 'service_request.php'; // Redirect to service_request.php
                });
            </script>";
        } else {
            // SweetAlert error
            echo "
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Update Failed',
                    text: 'Error updating status: " . addslashes($stmt->error) . "' 
                }).then(() => {
                    window.location.href = 'service_request.php'; // Redirect to service_request.php in case of failure
                });
            </script>";
        }

        $stmt->close();
    } else {
        echo "
        <script>
            Swal.fire({
                icon: 'warning',
                title: 'Missing Data',
                text: 'Request ID or status not found.'
            }).then(() => {
                window.location.href = 'service_request.php'; // Redirect to service_request.php in case of missing data
            });
        </script>";
    }
} else {
    echo "
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Invalid Request',
            text: 'Only POST requests are allowed.'
        }).then(() => {
            window.location.href = 'service_request.php'; // Redirect to service_request.php in case of invalid request method
        });
    </script>";
}

$con->close();
?>

</body>
</html>
