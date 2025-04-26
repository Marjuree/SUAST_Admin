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
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>HRMO | Dash</title>
    <link rel="shortcut icon" href="../../img/favicon.png" />
    <script src="../../assets/chart.js"></script>
    <style>
        canvas { max-height: 250px !important; }
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
            <div class="row">
                <!-- Info Boxes -->
                <?php
                $serviceCount = mysqli_num_rows(mysqli_query($con, "SELECT * FROM tbl_service_requests"));
                $leaveCount = mysqli_num_rows(mysqli_query($con, "SELECT * FROM tbl_leave_requests"));
                $certificationCount = mysqli_num_rows(mysqli_query($con, "SELECT * FROM tbl_certification_requests"));
                ?>
                <!-- Info Boxes -->
                <div class="col-md-3 col-sm-6 col-xs-12"><br>
                    <div class="info-box">
                        <span class="info-box-icon bg-aqua"><i class="fa fa-tasks"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Service Requests</span>
                            <span
                                class="info-box-number"><?php echo $serviceCount; ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12"><br>
                    <div class="info-box">
                        <span class="info-box-icon bg-aqua"><i class="fa fa-file-alt"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Leave Requests</span>
                            <span
                                class="info-box-number"><?php echo $leaveCount; ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12"><br>
                    <div class="info-box">
                        <span class="info-box-icon bg-aqua"><i class="fa fa-check-circle"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Certification Requests</span>
                            <span
                                class="info-box-number"><?php echo $certificationCount; ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts -->
            <div class="row">
                <div class="col-md-4 col-sm-12"><br>
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Leave Requests Over Time</h4>
                            <canvas id="leaveLineChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 col-sm-12"><br>
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Leave Requests by Faculty</h4>
                            <canvas id="leaveBarChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 col-sm-12"><br>
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Leave Request Types</h4>
                            <canvas id="leavePieChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-sm-12"><br>
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Service Record by Faculty</h4>
                            <canvas id="serviceBarChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-sm-12"><br>
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Certification Request Status</h4>
                            <canvas id="certificationPieChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </aside>
    </div>
    
    <script>
    // Chart configs
    const options = {
        responsive: true,
        maintainAspectRatio: false
    };

    new Chart(document.getElementById('leaveLineChart'), {
        type: 'line',
        data: {
            labels: <?= json_encode($leaveMonths); ?>,
            datasets: [{
                label: 'Leave Requests',
                data: <?= json_encode($leaveCounts); ?>,
                borderColor: '#ff5733', // Change color for the line
                backgroundColor: 'rgba(255, 87, 51, 0.2)', // Change fill color
                fill: true,
                tension: 0.3
            }]
        },
        options
    });

    new Chart(document.getElementById('leaveBarChart'), {
        type: 'bar',
        data: {
            labels: <?= json_encode($facultyNames); ?>,
            datasets: [{
                label: 'Requests',
                data: <?= json_encode($facultyCounts); ?>,
                backgroundColor: [
                    '#f39c12', '#8e44ad', '#3498db', '#2ecc71', '#e74c3c', '#95a5a6' // Different colors for each bar
                ]
            }]
        },
        options
    });

    new Chart(document.getElementById('leavePieChart'), {
        type: 'pie',
        data: {
            labels: <?= json_encode($leaveTypeNames); ?>,
            datasets: [{
                data: <?= json_encode($leaveTypeCounts); ?>,
                backgroundColor: ['#1abc9c', '#9b59b6', '#34495e', '#e67e22'] // Unique colors for each pie slice
            }]
        },
        options
    });

    new Chart(document.getElementById('serviceBarChart'), {
        type: 'bar',
        data: {
            labels: <?= json_encode($serviceFaculties); ?>,
            datasets: [{
                label: 'Service Requests',
                data: <?= json_encode($serviceCounts); ?>,
                backgroundColor: [
                    '#f39c12', '#8e44ad', '#3498db', '#2ecc71', '#e74c3c' // Diverse colors for each bar
                ]
            }]
        },
        options
    });

    new Chart(document.getElementById('certificationPieChart'), {
        type: 'doughnut',
        data: {
            labels: <?= json_encode($certificationStatuses); ?>,
            datasets: [{
                data: <?= json_encode($certificationCounts); ?>,
                backgroundColor: ['#ff6347', '#00bcd4', '#ffa500', '#28a745'] // Different colors for doughnut slices
            }]
        },
        options
    });
</script>

    <?php require_once "../../includes/footer.php"; ?>
</body>
</html>
