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

    <!-- Google Fonts: Poppins -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,500,600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', Arial, sans-serif !important;
        }

        .scroll-box,
        .horizontal-scroll,
        .container-fluid,
        .modal-content,
        .modal-header,
        .modal-title,
        .form-group label,
        .form-control,
        .table,
        .table th,
        .table td,
        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        label,
        .btn,
        .responsive-divider,
        .print-only {
            font-family: 'Poppins', Arial, sans-serif !important;
        }

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

        .print-only {
            display: none;
        }


        @media print {
            /* Hide everything by default */
            body * {
                visibility: hidden !important;
            }

            .no-print,
            .room-box {
                display: none !important;
            }

            .print-only {
                display: block !important;
            }

            /* Show only the selected room-box */
            .room-box.print-visible,
            .room-box.print-visible * {
                display: block !important;
                visibility: visible !important;
                position: static !important;
                overflow: visible !important;
                height: auto !important;
            }

            /* Keep structure clean */
            .horizontal-scroll {
                overflow: visible !important;
                white-space: normal !important;
                margin-top: 0 !important;
            }

            .scroll-box {
                width: 100% !important;
                margin: 0 auto !important;
                display: block !important;
                page-break-inside: avoid;
                break-inside: avoid;
            }

            .scroll-box table {
                width: 100% !important;
                visibility: visible !important;
            }

            aside.right-side,
            aside.right-side * {
                visibility: visible !important;
            }

            aside.right-side {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }

            .btn-primary,
            .form-group,
            #roomFilter {
                display: none !important;
            }

            .red {
                color: red !important;
            }

            /* Optional: force page break between rooms when printing "all" */
            .room-box.print-visible + .room-box.print-visible {
                page-break-before: always;
                break-before: page;
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
                    <div class="text-right no-print" style="margin-bottom: 10px;">
                        <button onclick="window.print()" class="btn btn-primary">
                            <span class="glyphicon glyphicon-print"></span> Print
                        </button>
                    </div>

                    <!-- Header Layout -->
                    <div class="container-fluid print-only"
                        style="padding-bottom: 15px; border-bottom: 1px solid #ccc;">
                        <div class="row">
                            <div class="col-xs-9">
                                <h6>Republic of the Philippines</h6>
                                <div class="responsive-divider"></div>
                                <h3 style="color: #003399; font-weight: bold;">DAVAO ORIENTAL <br> STATE UNIVERSITY
                                </h3>
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
                            // Skip this room if room_disabled is 1
                            if (isset($row['room_disabled']) && $row['room_disabled'] == 1) {
                                continue;
                            }
                            if (!isset($rooms[$room])) {
                                $rooms[$room] = [];
                            }
                            $rooms[$room][] = $row;
                        }
                        ?>

                        <div class="horizontal-scroll" style="padding: 15px 0; border: none;">
                            <?php foreach ($rooms as $room => $students): ?>
                                <?php
                                $approvedStudents = array_filter($students, function ($s) {
                                    return $s['status'] === 'approved';
                                });
                                if (empty($approvedStudents))
                                    continue;
                                ?>
                                <div class="scroll-box room-box" data-room="<?= htmlspecialchars($room) ?>">
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
                                                    <?php
                                                    $counter = 1;
                                                    foreach ($students as $student):
                                                        if ($student['status'] === 'approved'):
                                                            ?>
                                                            <tr>
                                                                <td><?= $counter++ ?></td>
                                                                <td><?= htmlspecialchars($student['name']) ?></td>
                                                            </tr>
                                                            <?php
                                                        endif;
                                                    endforeach;
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>

                                        <!-- Right side: room/date + reminders -->
                                        <div class="col-sm-5">
                                            <!-- Room Display -->
                                            <div class="text-center"
                                                style="border: 1px solid #000; padding: 10px; margin-bottom: 10px; background: #f8f9fa;">
                                                <h4 style="margin:0; font-weight:bold;"> <?= htmlspecialchars($room) ?></h4>
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
                                            <div class="print-only" style="border: 1px solid #000; padding: 10px;">
                                                <strong class="red"
                                                    style="color: red; font-size: 20px; display: block; text-align: center;">Reminders:</strong>
                                                <ul style="padding-left: 20px; margin: 10px 0 0;">
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
