<?php
session_start();
if (!isset($_SESSION['role'])) {
    header("Location: ../../php/error.php?welcome=Please login to access this page");
    exit();
}
ob_start();
require_once "../../configuration/config.php";

// LEAVE REQUESTS PER MONTH
$leaveRequests = mysqli_query($con, "SELECT DATE_FORMAT(date_request, '%M') as month, COUNT(*) as count FROM tbl_leave_requests GROUP BY month ORDER BY STR_TO_DATE(month, '%M')");
$leaveMonths = $leaveCounts = [];
while ($row = mysqli_fetch_assoc($leaveRequests)) {
    $leaveMonths[] = $row['month'];
    $leaveCounts[] = $row['count'];
}

// LEAVE REQUESTS PER FACULTY
$facultyRequests = mysqli_query($con, "SELECT faculty, COUNT(*) as count FROM tbl_leave_requests GROUP BY faculty");
$facultyNames = $facultyCounts = [];
while ($row = mysqli_fetch_assoc($facultyRequests)) {
    $facultyNames[] = $row['faculty'];
    $facultyCounts[] = $row['count'];
}

// LEAVE REQUEST TYPES
$leaveTypes = mysqli_query($con, "SELECT request_type, COUNT(*) as count FROM tbl_leave_requests GROUP BY request_type");
$leaveTypeNames = $leaveTypeCounts = [];
while ($row = mysqli_fetch_assoc($leaveTypes)) {
    $leaveTypeNames[] = $row['request_type'];
    $leaveTypeCounts[] = $row['count'];
}

// SERVICE REQUESTS PER FACULTY
$serviceData = mysqli_query($con, "SELECT faculty, COUNT(*) as count FROM tbl_service_requests GROUP BY faculty");
$serviceFaculties = $serviceCounts = [];
while ($row = mysqli_fetch_assoc($serviceData)) {
    $serviceFaculties[] = $row['faculty'];
    $serviceCounts[] = $row['count'];
}

// CERTIFICATION REQUEST STATUS
$certificationData = mysqli_query($con, "SELECT request_status, COUNT(*) as count FROM tbl_certification_requests GROUP BY request_status");
$certificationStatuses = $certificationCounts = [];
while ($row = mysqli_fetch_assoc($certificationData)) {
    $certificationStatuses[] = $row['request_status'];
    $certificationCounts[] = $row['count'];
}

$username = $_SESSION['first_name'] ?? 'User';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>HRMO | Dash</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="../../img/favicon.png" />
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <style>
        .card {
            background: #f4faff;
            border-radius: 1.5rem;
            box-shadow: 0 6px 20px rgba(0, 123, 255, 0.1);
            padding: 1rem;
        }

        .card-title {
            font-weight: 600;
            color: #333;
            margin-bottom: 1rem;
        }

        .info-box {
            border-radius: 1.5rem;
            background: #e3f2fd;
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.15);
        }

        .info-box-icon {
            border-radius: 1.5rem 0 0 1.5rem;
            background: #00bcd4 !important;
        }

        .info-box-content {
            padding: 0.5rem 1rem;
        }

        .chart-container {
            max-height: 250px;
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
        <aside class="right-side">
            <section class="content-header">
                <h1>Dashboard</h1>
                <p>Welcome, <strong><?php echo htmlspecialchars($username); ?></strong></p>
            </section>

            <section class="content">
                <?php
                $serviceCount = mysqli_num_rows(mysqli_query($con, "SELECT * FROM tbl_service_requests"));
                $leaveCount = mysqli_num_rows(mysqli_query($con, "SELECT * FROM tbl_leave_requests"));
                $certificationCount = mysqli_num_rows(mysqli_query($con, "SELECT * FROM tbl_certification_requests"));
                ?>
                <div class="row">
                    <div class="col-md-3 col-sm-6 col-xs-12"><br>
                        <div class="info-box shadow-sm rounded"
                            style="background: #e6eaf0; border-left: 5px solid #002B5B; transition: box-shadow 0.3s ease;">
                            <span class="info-box-icon"
                                style="background-color: #002B5B; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 28px; width: 60px; height: 60px; border-radius: 0.5rem;">
                                <i class="fa fa-tasks"></i>
                            </span>
                            <div class="info-box-content" style="padding-left: 15px;">
                                <span class="info-box-text"
                                    style="font-weight: 600; font-size: 1.1rem; color: #002B5B;">Service Requests</span>
                                <span class="info-box-number"
                                    style="font-weight: bold; font-size: 1.8rem; color: #001f40;"><?= $serviceCount; ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6 col-xs-12"><br>
                        <div class="info-box shadow-sm rounded"
                            style="background: #e6eaf0; border-left: 5px solid #002B5B; transition: box-shadow 0.3s ease;">
                            <span class="info-box-icon"
                                style="background-color: #002B5B; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 28px; width: 60px; height: 60px; border-radius: 0.5rem;">
                                <i class="fa fa-file-alt"></i>
                            </span>
                            <div class="info-box-content" style="padding-left: 15px;">
                                <span class="info-box-text"
                                    style="font-weight: 600; font-size: 1.1rem; color: #002B5B;">Leave Requests</span>
                                <span class="info-box-number"
                                    style="font-weight: bold; font-size: 1.8rem; color: #001f40;"><?= $leaveCount; ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6 col-xs-12"><br>
                        <div class="info-box shadow-sm rounded"
                            style="background: #e6eaf0; border-left: 5px solid #002B5B; transition: box-shadow 0.3s ease;">
                            <span class="info-box-icon"
                                style="background-color: #002B5B; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 28px; width: 60px; height: 60px; border-radius: 0.5rem;">
                                <i class="fa fa-check-circle"></i>
                            </span>
                            <div class="info-box-content" style="padding-left: 15px;">
                                <span class="info-box-text"
                                    style="font-weight: 600; font-size: 1.1rem; color: #002B5B;">Certification
                                    Requests</span>
                                <span class="info-box-number"
                                    style="font-weight: bold; font-size: 1.8rem; color: #001f40;"><?= $certificationCount; ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <style>
                    .info-box:hover {
                        box-shadow: 0 8px 20px rgba(0, 43, 91, 0.4);
                    }
                </style>


                <!-- CHARTS -->
                <div class="row">
                    <div class="col-md-4 col-sm-12"><br>
                        <div class="card">
                            <h4 class="card-title">Leave Requests Over Time</h4>
                            <div id="leaveLineChart" class="chart-container"></div>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-12"><br>
                        <div class="card">
                            <h4 class="card-title">Leave Requests by Faculty</h4>
                            <div id="leaveBarChart" class="chart-container"></div>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-12"><br>
                        <div class="card">
                            <h4 class="card-title">Leave Request Types</h4>
                            <div id="leavePieChart" class="chart-container"></div>
                        </div>
                    </div>

                    <div class="col-md-6 col-sm-12"><br>
                        <div class="card">
                            <h4 class="card-title">Service Record by Faculty</h4>
                            <div id="serviceBarChart" class="chart-container"></div>
                        </div>
                    </div>

                    <div class="col-md-6 col-sm-12"><br>
                        <div class="card">
                            <h4 class="card-title">Certification Request Status</h4>
                            <div id="certificationPieChart" class="chart-container"></div>
                        </div>
                    </div>
                </div>
            </section>
        </aside>
    </div>

    <!-- ApexCharts Initialization -->
    <script>
        new ApexCharts(document.querySelector("#leaveLineChart"), {
            chart: { type: 'line', height: 250 },
            series: [{
                name: 'Leave Requests',
                data: <?= json_encode($leaveCounts); ?>
            }],
            xaxis: { categories: <?= json_encode($leaveMonths); ?> },
            colors: ['#ff5733']
        }).render();

        new ApexCharts(document.querySelector("#leaveBarChart"), {
            chart: { type: 'bar', height: 250 },
            series: [{
                name: 'Requests',
                data: <?= json_encode($facultyCounts); ?>
            }],
            xaxis: { categories: <?= json_encode($facultyNames); ?> },
            colors: ['#f39c12', '#8e44ad', '#3498db', '#2ecc71', '#e74c3c', '#95a5a6'],
            plotOptions: {
                bar: {
                    distributed: true  // This enables different color per bar
                }
            }
        }).render();


        new ApexCharts(document.querySelector("#leavePieChart"), {
            chart: { type: 'pie', height: 250 },
            series: <?= json_encode($leaveTypeCounts); ?>,
            labels: <?= json_encode($leaveTypeNames); ?>,
            colors: ['#1abc9c', '#9b59b6', '#34495e', '#e67e22']
        }).render();

        new ApexCharts(document.querySelector("#serviceBarChart"), {
            chart: { type: 'bar', height: 250 },
            series: [{
                name: 'Service Requests',
                data: <?= json_encode($serviceCounts); ?>
            }],
            xaxis: { categories: <?= json_encode($serviceFaculties); ?> },
            colors: ['#f39c12', '#8e44ad', '#3498db', '#2ecc71', '#e74c3c'],
            plotOptions: {
                bar: {
                    distributed: true
                }
            }
        }).render();

        new ApexCharts(document.querySelector("#certificationPieChart"), {
            chart: { type: 'donut', height: 250 },
            series: <?= json_encode($certificationCounts); ?>,
            labels: <?= json_encode($certificationStatuses); ?>,
            colors: ['#ff6347', '#00bcd4', '#ffa500', '#28a745']
        }).render();
    </script>

    <?php require_once "../../includes/footer.php"; ?>
</body>

</html>
