<?php
require_once "../../configuration/config.php";

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid Request ID.");
}

$request_id = (int) $_GET['id']; // Ensure it's an integer to avoid SQL injection

// SQL query to fetch the BLOB file data from the database
$sql = "SELECT leave_form, file_name FROM tbl_leave_requests WHERE id = ?";
$stmt = $con->prepare($sql);

if (!$stmt) {
    die("Prepare failed: " . $con->error);
}

$stmt->bind_param("i", $request_id);
$stmt->execute();

// Use get_result() for better handling
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("File not found in the database.");
}

$row = $result->fetch_assoc();

$file_data = $row['leave_form'];
$file_name = $row['file_name'];

// Ensure file data is not empty
if (empty($file_data)) {
    die("File data is empty.");
}

// Determine MIME type based on file extension
$file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION)); // Make extension lowercase
$mime_types = [
    'pdf' => 'application/pdf',
    'doc' => 'application/msword',
    'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    'jpg' => 'image/jpeg',
    'jpeg' => 'image/jpeg',
    'png' => 'image/png',
    'gif' => 'image/gif',
    'txt' => 'text/plain'
];
$mime_type = $mime_types[$file_extension] ?? 'application/octet-stream';

// Set the headers
header('Content-Description: File Transfer');
header('Content-Type: ' . $mime_type);
header('Content-Disposition: attachment; filename="' . basename($file_name) . '"');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . strlen($file_data));

// Clean output buffering
if (ob_get_length()) {
    ob_end_clean();
}

// Output the file content (BLOB data) safely
echo $file_data;
exit;

$stmt->close();
$con->close();
?>
