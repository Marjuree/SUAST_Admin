<?php
require_once "../../configuration/config.php";

if (!isset($_GET['id'])) {
    die("No ID provided.");
}

$id = intval($_GET['id']);
$query = mysqli_query($con, "SELECT image_blob FROM tbl_applicants WHERE id = $id");

if (!$query || mysqli_num_rows($query) == 0) {
    die("Image not found.");
}

$row = mysqli_fetch_assoc($query);

header("Content-Type: image/jpeg"); // adjust if using PNG
echo $row['image_blob'];
?>
