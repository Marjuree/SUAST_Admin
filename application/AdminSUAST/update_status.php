<?php
require_once "../../configuration/config.php";

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id']) && isset($_POST['status'])) {
    $id = intval($_POST['id']);
    $status = $_POST['status'];

    if ($status === 'rejected' && isset($_POST['reason'])) {
        $reason = trim($_POST['reason']);

        // Update with reason
        $stmt = $con->prepare("UPDATE tbl_reservation SET status = ?, reason = ? WHERE id = ?");
        $stmt->bind_param("ssi", $status, $reason, $id);
    } else {
        // Update without reason
        $stmt = $con->prepare("UPDATE tbl_reservation SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $id);
    }

    // Execute the statement and check if it is successful
    if ($stmt->execute()) {
        $message = "✅ Status updated successfully.";
        $alertType = 'success';
    } else {
        $message = "❌ Error updating status.";
        $alertType = 'error';
    }

    $stmt->close();
} else {
    $message = "❌ Invalid request.";
    $alertType = 'error';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Processing...</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        window.onload = function() {
            Swal.fire({
                icon: '<?php echo $alertType; ?>',
                title: '<?php echo htmlspecialchars($message); ?>',
                showConfirmButton: false,
                timer: 2000
            }).then(function() {
                window.location.href = "manage_reservations.php";
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
</body>
</html>
