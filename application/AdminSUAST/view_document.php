<?php
require_once "../../configuration/config.php";

if (!isset($_GET['id'])) {
    die("No ID provided.");
}

$id = intval($_GET['id']);
$query = mysqli_query($con, "SELECT document_blob, document FROM tbl_applicants WHERE id = $id");

if (!$query || mysqli_num_rows($query) == 0) {
    die("Document not found.");
}

$row = mysqli_fetch_assoc($query);
$filename = $row['document'];
$ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
$mime = "application/octet-stream";
if ($ext === 'pdf') $mime = "application/pdf";
elseif ($ext === 'docx') $mime = "application/vnd.openxmlformats-officedocument.wordprocessingml.document";

header("Content-Type: $mime");
header("Content-Disposition: inline; filename=\"$filename\"");
echo $row['document_blob'];
?>
