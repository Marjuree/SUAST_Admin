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
    $status = $_POST['completion_status'] ?? null;

    if ($requestId && $status) {
        // Prepare the statement
        $stmt = $con->prepare("UPDATE tbl_service_requests SET completion_status = ? WHERE id = ?");

        if ($stmt) {
            $stmt->bind_param("si", $status, $requestId);

            if ($stmt->execute()) {
                // Success
                echo "
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Status Updated!',
                        text: 'The completion status has been updated.',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.href = 'service_request.php';
                    });
                </script>";
            } else {
                // Execution error
                echo "
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Update Failed',
                        text: 'Execution Error: " . addslashes($stmt->error) . "'
                    }).then(() => {
                        window.location.href = 'service_request.php';
                    });
                </script>";
            }

            $stmt->close();
        } else {
            // Preparation error (this is the REAL cause)
            echo "
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Query Error',
                    text: 'Preparation failed: " . addslashes($con->error) . "'
                }).then(() => {
                    window.location.href = 'service_request.php';
                });
            </script>";
        }

    } else {
        echo "
        <script>
            Swal.fire({
                icon: 'warning',
                title: 'Missing Data',
                text: 'Request ID or status not found.'
            }).then(() => {
                window.location.href = 'service_request.php';
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
            window.location.href = 'service_request.php';
        });
    </script>";
}

$con->close();
?>

</body>
</html>
