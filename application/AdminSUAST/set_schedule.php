<?php
require_once "../../configuration/config.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id']) && isset($_POST['examTime'])) {
    $id = intval($_POST['id']); // Ensure the ID is an integer
    $examTime = $_POST['examTime']; // The exam time being passed from the form

    // Sanitize input for safety
    $examTime = mysqli_real_escape_string($con, $examTime);

    // Ensure the exam time is in the correct format (as a string)
    if (empty($examTime)) {
        echo "❌ Exam time cannot be empty.";
        exit();
    }

    // Debug: Check the values
    echo "Attempting to update exam_time for id: $id with exam time: $examTime<br>";

    // Prepare the SQL statement to update only the exam_time
    $stmt = $con->prepare("UPDATE tbl_reservation SET exam_time = ? WHERE id = ?");
    
    // Check if the statement was prepared correctly
    if ($stmt === false) {
        echo "❌ Error preparing the SQL statement.";
        exit();
    }

    // Bind parameters: "s" for string (examTime), "i" for integer (id)
    $stmt->bind_param("si", $examTime, $id);

    // Debug: Check the query execution
    if ($stmt->execute()) {
        echo "✅ Exam time updated successfully for ID: $id";
    } else {
        echo "❌ Error updating exam time: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
} else {
    echo "❌ Invalid request. Please ensure all required fields are submitted.";
}
?>
