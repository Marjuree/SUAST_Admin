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
    } else {
        $message = "❌ Error updating status.";
    }

    // Close the statement
    $stmt->close();
} else {
    $message = "❌ Invalid request.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Processing...</title>
    <script>
        setTimeout(function () {
            window.location.href = "manage_reservations.php"; 
        }, 2000); // Delay for 2 seconds
    </script>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin-top: 100px;
        }
        .message-box {
            display: inline-block;
            padding: 20px;
            border: 2px solid #ccc;
            border-radius: 10px;
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <div class="message-box">
        <h2><?php echo htmlspecialchars($message); ?></h2>
        <p>Redirecting to reservation list...</p>
    </div>
</body>
</html>
