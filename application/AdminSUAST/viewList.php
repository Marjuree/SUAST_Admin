<?php
session_start();
if (!isset($_SESSION['role'])) {
    header("Location: ../../php/error.php?welcome=Please login to access this page");
    exit();
}
ob_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Applicant | Dashboard</title>
    <link rel="shortcut icon" href="../../img/favicon.png" />

    <!-- Bootstrap 3 -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet">

    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

    <style>
        .horizontal-scroll {
            overflow-x: auto;
            white-space: nowrap;
        }

        .scroll-box {
            display: inline-block;
            min-width: 600px;
            vertical-align: top;
            margin-right: 20px;
            background: #fff;
            padding: 20px;
            border: 1px solid #000;
            border-radius: 10px;
        }

        .responsive-divider {
            border-bottom: 6px solid #003399;
            width: 80%;
            max-width: 325px;
        }

        @media (max-width: 767px) {
            .scroll-box .row {
                display: flex;
                flex-direction: row !important;
                flex-wrap: nowrap;
            }

            .scroll-box .col-sm-7,
            .scroll-box .col-sm-5 {
                width: 50% !important;
                float: none;
            }

            /* Optional: Add scroll if the box overflows */
            .scroll-box {
                overflow-x: auto;
            }
        }

        @media (max-width: 480px) {
            .responsive-divider span {
                font-size: 0.875rem;
            }

            .text-right img {
                margin-left: -50px !important;
            }
        }

        @media print {

            /* Hide everything by default */
            body * {
                visibility: hidden !important;
            }

            /* Show only the print area */
            aside.right-side,
            aside.right-side * {
                visibility: visible !important;
            }

            /* Full width print layout */
            aside.right-side {
                position: absolute;
                left: 0;
                top: 0;
                width: 60%;
            }

            .modal-content {
                border: none;
            }

            /* Hide print button */
            .btn-primary {
                display: none !important;
            }

            .scroll-box table {
                width: 100% !important;
                /* or any desired value like 80% */
            }

            /* Fix scroll-box layout 11*/
            .scroll-box {
                display: block !important;
                width: 60% !important;
                page-break-inside: avoid;
                margin-top: -800px !important;
            }

            .horizontal-scroll {
                overflow: visible !important;
                white-space: normal !important;
                margin-top: -120px
            }

            .red {
                color: red !important;
            }
        }



        /* Fixed A4-width scroll box */
        .scroll-box {
            display: inline-block;
            width: 794px;
            vertical-align: top;
            margin-right: 20px;
            background: #fff;
            padding: 20px;
            border: 1px solid #000;
            border-radius: 10px;
            box-sizing: border-box;
        }

        /* Disable responsive behavior for small screens */
        @media (max-width: 1024px) {

            body,
            .container-fluid,
            .modal-dialog,
            .scroll-box {
                width: 794px !important;
                min-width: 794px !important;
            }

            .scroll-box,
            .horizontal-scroll {
                overflow-x: visible !important;
            }

            .col-xs-9,
            .col-xs-3,
            .col-sm-7,
            .col-sm-5 {
                float: none;
                width: auto;
            }

            .text-right img {
                margin-left: 0 !important;
            }
        }
    </style>
</head>

<body class="skin-blue">

    <?php
    require_once('../../includes/header.php');
    require_once('../../includes/head_css.php');
    ?>

    <div class="wrapper row-offcanvas row-offcanvas-left">
        <?php require_once('../../includes/sidebar.php'); ?>

        <aside style="font-style: italic;" class="right-side">
            <div class="modal-dialog" style="width: 70%;">
                <div class="modal-content"
                    style="padding: 20px; border-radius: 15px; font-family: Arial, sans-serif; border: none;">

                    <!-- Print Button -->
                    <div class="text-right" style="margin-bottom: 10px;">
                        <button onclick="window.print()" class="btn btn-primary">
                            <span class="glyphicon glyphicon-print"></span> Print
                        </button>
                    </div>

                    <!-- Header Layout -->
                    <div class="container-fluid" style="padding-bottom: 15px; border-bottom: 1px solid #ccc;">
                        <div class="row">
                            <div class="col-xs-9">
                                <h6>Republic of the Philippines</h6>
                                <div class="responsive-divider"></div>
                                <h3 style="color: #003399; font-weight: bold;">DAVAO ORIENTAL <br> STATE UNIVERSITY</h3>
                                <p style="font-style: italic; font-size: 14px;">"A university of excellence, innovation,
                                    and inclusion"</p>
                                <div class="responsive-divider"></div>
                            </div>
                            <div class="col-xs-3 text-right">
                                <img src="../../img/logo1.png" alt="University Seal" style="height: 120px;">
                            </div>
                        </div>

                        <div class="text-center" style="margin-top: 10px;">
                            <h6 style="font-weight: bold; text-decoration: underline;">OFFICE OF STUDENT COUNSELING AND
                                DEVELOPMENT</h6>
                            <p style="font-weight: bold; text-decoration: underline; font-size: 14px;">State University
                                Aptitude and Scholarship Test</p>
                        </div>
                    </div>

                    <!-- Room and Schedule Info -->
                    <div class="modal-body">
                        <?php
                        $query = "SELECT * FROM tbl_reservation ORDER BY room, name ASC";
                        $result = $con->query($query);

                        $rooms = [];
                        while ($row = $result->fetch_assoc()) {
                            $room = $row['room'];
                            if (!isset($rooms[$room])) {
                                $rooms[$room] = [];
                            }
                            $rooms[$room][] = $row;
                        }
                        ?>

                        <div class="horizontal-scroll" style="padding: 15px 0; border: none;">
                            <?php foreach ($rooms as $room => $students): ?>
                                <div class="scroll-box">
                                    <div class="row">
                                        <!-- Left side: student table -->
                                        <div class="col-sm-7">
                                            <h5 style="font-weight: bold;"><?= htmlspecialchars($students[0]['venue']) ?>
                                            </h5>
                                            <h5>Date and Venue</h5>

                                            <table class="table table-bordered table-striped mb-0">
                                                <thead class="thead-dark">
                                                    <tr>
                                                        <th style="width: 50px;">#</th>
                                                        <th>NAME</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($students as $index => $student): ?>
                                                        <?php if ($student['status'] === 'approved'): ?>
                                                            <tr>
                                                                <td><?= $index + 1 ?></td>
                                                                <td><?= htmlspecialchars($student['name']) ?></td>
                                                            </tr>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>

                                        <!-- Right side: room/date + reminders -->
                                        <div class="col-sm-5">
                                            <!-- Room -->
                                            <div class="text-center"
                                                style="border: 1px solid #000; padding: 10px; margin-bottom: 15px;">
                                                <h5><strong><?= htmlspecialchars($room) ?></strong></h5>
                                            </div>

                                            <!-- Date & Time -->
                                            <div class="text-center"
                                                style="border: 1px solid #000; padding: 10px; margin-bottom: 15px;">
                                                <?php
                                                $rawDate = $students[0]['exam_date'];
                                                $rawTime = $students[0]['exam_time'];

                                                $formattedDate = !empty($rawDate) ? date('F d, Y', strtotime($rawDate)) : 'Date not set';

                                                if (!empty($rawTime)) {
                                                    $timeParts = explode('-', $rawTime);
                                                    $startTime = isset($timeParts[0]) ? date('g:i A', strtotime(trim($timeParts[0]))) : '';
                                                    $endTime = isset($timeParts[1]) ? date('g:i A', strtotime(trim($timeParts[1]))) : '';
                                                    $formattedTime = $startTime && $endTime ? "$startTime - $endTime" : 'Time not set';
                                                } else {
                                                    $formattedTime = 'Time not set';
                                                }
                                                ?>
                                                <h5><strong><?= $formattedDate ?></strong></h5>
                                                <h5>Time: <?= $formattedTime ?></h5>
                                            </div>

                                            <!-- Reminders -->
                                            <div style="border: 1px solid #000; padding: 10px;">
                                                <strong class="red"
                                                    style="color: red; font-size: 20px; display: block; text-align: center;">Reminders:</strong>
                                                <ul style="padding-left: 20px; margin: 10px 0 0; ">
                                                    <li>Arrive 30 minutes before schedule.</li>
                                                    <li>Bring valid school/government ID.</li>
                                                    <li>No electronic gadgets inside.</li>
                                                    <li>Follow proctors' instructions.</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                </div>
            </div>
        </aside>

    </div>

    <?php require_once "../../includes/footer.php"; ?>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

</body>

</html>
