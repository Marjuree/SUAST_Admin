<?php
require_once "../../configuration/config.php";

$query = "SELECT exam_time FROM tbl_exam_schedule ORDER BY exam_time ASC";
$result = mysqli_query($con, $query);

$examTimes = [];

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $examTimes[] = $row['exam_time'];
    }
}

echo json_encode($examTimes); // Return the exam times as a JSON array
?>
