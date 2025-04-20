<?php
require_once "../../configuration/config.php";

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id']) && isset($_POST['status'])) {
    $id = intval($_POST['id']);
    $status = $_POST['status'];

    // Prepare the SQL statement to update the status
    $stmt = $con->prepare("UPDATE tbl_reservation SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $id);

    // Execute the statement and check if it is successful
    if ($stmt->execute()) {
        $message = "✅ Status updated successfully.";
        $alertType = 'success';  // SweetAlert2 Success
    } else {
        $message = "❌ Error updating status.";
        $alertType = 'error';    // SweetAlert2 Error
    }

    // Close the statement
    $stmt->close();
} else {
    $message = "❌ Invalid request.";
    $alertType = 'error';  // SweetAlert2 Error
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Processing...</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert2 CDN -->
    <script>
        window.onload = function() {
            Swal.fire({
                icon: '<?php echo $alertType; ?>',
                title: '<?php echo htmlspecialchars($message); ?>',
                showConfirmButton: false,
                timer: 2000 // Close the alert after 2 seconds
            }).then(function() {
                window.location.href = "manage_reservations.php"; // Redirect after alert
            });
        };
    </script>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin-top: 100px;
        }
    </style>
</head>
<body>
    <!-- The body will not display anything as the alert is shown via SweetAlert2 -->
</body>
</html>
