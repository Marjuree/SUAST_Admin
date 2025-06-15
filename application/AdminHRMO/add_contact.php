<?php
session_start();
require_once "../../configuration/config.php"; // Ensure database connection

// Redirect if not logged in
if (!isset($_SESSION['role'])) {
    header("Location: ../../php/error.php?welcome=Please login to access this page");
    exit();
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $phone = mysqli_real_escape_string($con, $_POST['phone']);

    // Insert into the database
    $query = "INSERT INTO tbl_contact (name, email, phone) VALUES ('$name', '$email', '$phone')";
    if (mysqli_query($con, $query)) {
        $_SESSION['message'] = "Contact added successfully!";
        $alertMessage = "Contact added successfully!";
        $alertType = "success";
    } else {
        $_SESSION['error'] = "Error adding contact: " . mysqli_error($con);
        $alertMessage = "Error adding contact: " . mysqli_error($con);
        $alertType = "error";
    }
} else {
    $_SESSION['error'] = "Invalid request!";
    $alertMessage = "Invalid request!";
    $alertType = "error";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="../../css/exam_schedule.css">
    <link rel="shortcut icon" href="../../img/favicon.png" />
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Contact Add Status</title>
</head>
<body>
    <script>
        // Show SweetAlert2 message based on the PHP variables
        Swal.fire({
            title: '<?php echo $alertMessage; ?>',
            icon: '<?php echo $alertType; ?>',
            confirmButtonText: 'OK',
            allowOutsideClick: false
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "contact.php"; // Redirect after click
            }
        });
    </script>
</body>
</html>
