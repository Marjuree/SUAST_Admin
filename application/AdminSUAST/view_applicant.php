<?php
session_start();
if (!isset($_SESSION['role'])) {
    header("Location: ../../php/error.php?welcome=Please login to access this page");
    exit();
}
require_once "../../configuration/config.php";

// Get applicant_id from query string
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid applicant ID.");
}

$applicant_id = intval($_GET['id']);

// Use applicant_id (foreign key), not id (primary key)
$stmt = mysqli_prepare($con, "SELECT * FROM tbl_applicants WHERE applicant_id = ?");
mysqli_stmt_bind_param($stmt, "i", $applicant_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {
    die("Applicant not found.");
}

$app = mysqli_fetch_assoc($result);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>View Applicant</title>
    <link rel="stylesheet" href="../../css/exam_schedule.css">
    <link rel="shortcut icon" href="../../img/favicon.png" />

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f4f4f4;
        color: #333;
    }

    .container {
        max-width: 1000px;
        margin: 30px auto;
        padding: 30px;
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 0 10px #ccc;
    }

    h2 {
        text-align: center;
        margin-bottom: 30px;
        font-size: 2rem;
        color: #007bff;
    }

    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        column-gap: 30px;
        row-gap: 15px;
    }

    .info-grid div {
        padding: 15px;
        background: #f9f9f9;
        border-left: 5px solid #007bff;
        border-radius: 5px;
        font-size: 1rem;
    }

    label {
        font-weight: bold;
        color: #333;
    }

    .image {
        text-align: center;
        margin: 30px 0;
    }

    .image img {
        max-width: 200px;
        height: auto;
        border-radius: 10px;
        border: 2px solid #007bff;
    }

    .back-btn {
        text-align: center;
        margin-top: 30px;
    }

    .back-btn a {
        padding: 12px 25px;
        background: #007bff;
        color: white;
        text-decoration: none;
        border-radius: 6px;
        font-size: 1rem;
    }

    .back-btn a:hover {
        background: #0056b3;
    }
    </style>
</head>

<body class="skin-blue">

    <div class="container">
        <h2>Applicant Details</h2>

        <div class="image">
            <?php if (!empty($app['image_blob'])): ?>
            <img src="view_image.php?id=<?= $app['id'] ?>" alt="Applicant Image">
            <?php elseif (!empty($app['image']) && file_exists("../../uploads/" . $app['image'])): ?>
            <img src="../../uploads/<?= $app['image'] ?>" alt="Applicant Image">
            <?php else: ?>
            <p>No image uploaded or the file is missing.</p>
            <?php endif; ?>
        </div>

        <div class="info-grid">
            <div><label>Full Name:</label> <?= "{$app['lname']}, {$app['fname']} {$app['mname']}" ?></div>
            <div><label>Birthdate:</label> <?= $app['bdate'] ?></div>
            <div><label>Age:</label> <?= $app['age'] ?></div>
            <div><label>Gender:</label> <?= $app['gender'] ?></div>
            <div><label>Civil Status:</label> <?= $app['civilstatus'] ?></div>
            <div><label>Religion:</label> <?= $app['religion'] ?></div>
            <div><label>Nationality:</label> <?= $app['nationality'] ?></div>
            <div><label>Ethnicity:</label> <?= $app['ethnicity'] ?></div>
            <div><label>Indigenous:</label> <?= $app['indigenous'] ?></div>
            <div><label>Basic Sector:</label> <?= $app['basic_sector'] ?></div>
            <div><label>Contact:</label> <?= $app['contact'] ?></div>
            <div><label>Email:</label> <?= $app['email'] ?></div>
            <div><label>First Option:</label> <?= $app['first_option'] ?></div>
            <div><label>Second Option:</label> <?= $app['second_option'] ?></div>
            <div><label>Third Option:</label> <?= $app['third_option'] ?></div>
            <div><label>Campus:</label> <?= $app['campus'] ?></div>
            <div><label>Purok:</label> <?= $app['purok'] ?></div>
            <div><label>Barangay:</label> <?= $app['barangay'] ?></div>
            <div><label>Municipality:</label> <?= $app['municipality'] ?></div>
            <div><label>Province:</label> <?= $app['province'] ?></div>
            <div><label>Mother's Name:</label> <?= $app['n_mother'] ?></div>
            <div><label>Father's Name:</label> <?= $app['n_father'] ?></div>
            <div><label>Mother's Contact:</label> <?= $app['c_mother'] ?></div>
            <div><label>Father's Contact:</label> <?= $app['c_father'] ?></div>
            <div><label>Mother's Occupation:</label> <?= $app['m_occupation'] ?></div>
            <div><label>Father's Occupation:</label> <?= $app['f_occupation'] ?></div>
            <div><label>Mother's Address:</label> <?= $app['m_address'] ?></div>
            <div><label>Father's Address:</label> <?= $app['f_address'] ?></div>
            <div><label>Living Status:</label> <?= $app['living_status'] ?></div>
            <div><label>Number of Siblings:</label> <?= $app['siblings'] ?></div>
            <div><label>Birth Order:</label> <?= $app['birth_order'] ?></div>
            <div><label>Monthly Income:</label> <?= $app['monthly_income'] ?></div>
            <div><label>Date Applied:</label> <?= $app['date_applied'] ?></div>
            <div>
                <label>Uploaded Document:</label>
                <?php if (!empty($app['document_blob'])): ?>
                <a href="view_document.php?id=<?= $app['id'] ?>" download>Download Document</a>
                <?php elseif (!empty($app['document']) && file_exists("../../uploads/" . $app['document'])): ?>
                <a href="../../uploads/<?= $app['document'] ?>" download>Download Document</a>
                <?php else: ?>
                No document uploaded.
                <?php endif; ?>
            </div>

        </div>

        <div class="back-btn">
            <a href="manage_reservations.php">← Back to Applicant List</a>
        </div>
    </div>

    <?php require_once "../../includes/footer.php"; ?>

</body>

</html>
