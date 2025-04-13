<?php
header('Content-Type: application/json');

$uploadDir = "../../uploads/";
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
    $filename = basename($_FILES['file']['name']);
    $target = $uploadDir . $filename;

    if (move_uploaded_file($_FILES['file']['tmp_name'], $target)) {
        echo json_encode(["status" => "success", "message" => "File received successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to move file."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "No file uploaded or error occurred."]);
}
