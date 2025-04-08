<?php
// Start session only if it is not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "../configuration/config.php"; // No whitespace above this line
require_once "../application/SystemLog.php"; // Logging utility

// ---------------------------
// 🔐 REGISTRATION HANDLER
// ---------------------------
if (isset($_POST['btn_register'])) {
    $reg_name = mysqli_real_escape_string($con, $_POST['reg_name'] ?? '');
    $reg_email = mysqli_real_escape_string($con, $_POST['reg_email'] ?? '');
    $reg_username = mysqli_real_escape_string($con, $_POST['reg_username'] ?? '');
    $reg_password = $_POST['reg_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $reg_role = mysqli_real_escape_string($con, $_POST['reg_role'] ?? '');

    // Check if passwords match
    if ($reg_password === $confirm_password) {
        // Check if username already exists
        $check_query = "SELECT id FROM tbl_users_management WHERE username = '$reg_username'";
        $check_result = mysqli_query($con, $check_query);

        if (mysqli_num_rows($check_result) > 0) {
            echo "<script>alert('Username already exists. Please choose another.'); window.history.back();</script>";
        } else {
            $hashed_password = password_hash($reg_password, PASSWORD_DEFAULT);

            $query = "INSERT INTO tbl_users_management (name, email, username, password, role) 
                      VALUES ('$reg_name', '$reg_email', '$reg_username', '$hashed_password', '$reg_role')";

            if (mysqli_query($con, $query)) {
                echo "<script>alert('Registration successful!'); window.location.href='success.html';</script>";
            } else {
                echo "<script>alert('Error: Could not register user.');</script>";
            }
        }
    } else {
        echo "<script>alert('Passwords do not match.'); window.history.back();</script>";
    }
}

// ---------------------------
// 🔐 LOGIN HANDLER
// ---------------------------
if (isset($_POST['btn_login'])) { 
    $username = mysqli_real_escape_string($con, $_POST['txt_username'] ?? '');
    $password = $_POST['txt_password'] ?? '';
    $role = mysqli_real_escape_string($con, $_POST['select_role'] ?? '');

    // Fetch user by username and role
    $query = "SELECT * FROM tbl_users_management WHERE username = '$username' AND role = '$role'";
    $result = mysqli_query($con, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        if (password_verify($password, $row['password'])) {
            session_regenerate_id(true); // Secure session

            $_SESSION['role'] = $row['role'];
            $_SESSION['userid'] = $row['id'];
            $_SESSION['username'] = $row['username'];

            // ✅ Log successful login
            logMessage("INFO", "Login Success", "User '$username' logged in successfully.");

            // Redirect by role
            switch ($row['role']) {
                case "SUAST":
                    header("Location: ../application/AdminSUAST/AdminSUAST.php?success=login");
                    break;
                case "Accounting":
                    header("Location: ../application/AdminAccounting/Accountingdashboard.php?success=login");
                    break;
                case "HRMO":
                    header("Location: ../application/AdminHRMO/HRMODashboard.php?success=login");
                    break;
                default:
                    header("Location: invalid.html?error=invalid_role");
                    break;
            }
            exit;
        } else {
            // ❌ Log wrong password
            logMessage("WARNING", "Login Failed", "Incorrect password for user '$username'.");
            echo "<script>alert('Invalid Password!'); window.location.href='invalid.html';</script>";
        }
    } else {
        // ❌ Log failed login (role or user not found)
        logMessage("WARNING", "Login Failed", "Invalid role or user '$username' not found.");
        echo "<script>alert('Invalid Role or Username.'); window.location.href='invalid.html';</script>";
    }
}
?>
