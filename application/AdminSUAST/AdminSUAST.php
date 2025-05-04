<?php
session_start();
if (!isset($_SESSION['role'])) {
    header("Location: ../../php/error.php?welcome=Please login to access this page");
    exit();
}
ob_start();

include "../../configuration/config.php";

// Fetch data from database
$takers = mysqli_num_rows(mysqli_query($con, "SELECT * FROM tbl_reservation"));
$availableSlots = mysqli_num_rows(mysqli_query($con, "SELECT * FROM tbl_exam_schedule WHERE slot_limit > 0"));
$registeredTakers = mysqli_num_rows(mysqli_query($con, "SELECT * FROM tbl_applicant_registration"));
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Admin | Dashboard</title>
    <link rel="shortcut icon" href="../../img/favicon.png" />
    <link rel="stylesheet" href="../../vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="../../vendors/css/vendor.bundle.base.css">
    <script src="../../assets/chart.js"></script>
    <style>
        canvas {
            max-height: 250px !important;
        }

        /* Make info boxes scrollable on small screens */
        .scrollable-container {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            display: flex;
            flex-wrap: nowrap;
        }

        .info-box {
            display: inline-block;
            width: 23%; /* Adjust box size to fit 4 boxes per row */
            margin: 10px;
        }

        /* Adjust for small screens */
        @media (max-width: 767px) {
            .info-box {
                width: 45%; /* 2 boxes per row on small screens */
            }
        }

        @media (max-width: 480px) {
            .info-box {
                width: 90%; /* 1 box per row on very small screens */
            }
        }

        /* Ensure that chart section takes full width and is scrollable on small screens */
        .chart-container {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .chart-wrapper {
            display: flex;
            flex-wrap: nowrap;
            justify-content: space-around;
            width: max-content;
        }

        .card {
            margin: 10px;
            flex: 1;
            max-width: 32%;
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
            </section>
            
            <section class="content">
                <!-- Wrapper for info boxes with scroll functionality on small screens -->
                <div class="scrollable-container">
                    <div class="col-md-3 col-sm-6 col-xs-12"><br>
                        <div class="info-box">
                            <a href="../applicant/applicant.php">
                                <span class="info-box-icon bg-aqua"><i class="fa fa-user"></i></span>
                            </a>
                            <div class="info-box-content">
                                <span class="info-box-text">Applicants</span>
                                <span class="info-box-number"><?php echo $takers; ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12"><br>
                        <div class="info-box">
                            <a href="#">
                                <span class="info-box-icon bg-aqua"><i class="fa fa-tasks"></i></span>
                            </a>
                            <div class="info-box-content">
                                <span class="info-box-text">Available Slots</span>
                                <span class="info-box-number"><?php echo $availableSlots; ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12"><br>
                        <div class="info-box">
                            <a href="#">
                                <span class="info-box-icon bg-aqua"><i class="fa fa-file-alt"></i></span>
                            </a>
                            <div class="info-box-content">
                                <span class="info-box-text">Registered Takers</span>
                                <span class="info-box-number"><?php echo $registeredTakers; ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts section -->
                <div class="chart-container">
                    <div class="chart-wrapper">
                        <?php 
                        $charts = [
                            ["Bar Chart", "barChart"],
                            ["Line Chart", "lineChart"],
                            ["Pie Chart", "pieChart"]
                        ];
                        foreach ($charts as $chart) { ?>
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title"><?= $chart[0] ?></h4>
                                    <canvas id="<?= $chart[1] ?>"></canvas>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </section>
        </aside>
    </div>

    <script>
    const labels = ['Takers', 'Available Slots', 'Registered Takers'];
    const data = [<?= $takers ?>, <?= $availableSlots ?>, <?= $registeredTakers ?>];

    // Create canvas contexts
    const ctxBar = document.getElementById('barChart').getContext('2d');
    const ctxLine = document.getElementById('lineChart').getContext('2d');
    const ctxPie = document.getElementById('pieChart').getContext('2d');

    // Gradient colors for bar/line
    const gradientBlue = ctxBar.createLinearGradient(0, 0, 0, 200);
    gradientBlue.addColorStop(0, '#99ccff');
    gradientBlue.addColorStop(1, '#3366ff');

    const gradientGreen = ctxBar.createLinearGradient(0, 0, 0, 200);
    gradientGreen.addColorStop(0, '#66ff99');
    gradientGreen.addColorStop(1, '#00cc66');

    const gradientRed = ctxBar.createLinearGradient(0, 0, 0, 200);
    gradientRed.addColorStop(0, '#ff6666');
    gradientRed.addColorStop(1, '#cc0000');

    const gradients = [gradientBlue, gradientGreen, gradientRed];

    const commonOptions = {
        responsive: true,
        maintainAspectRatio: false,
        animation: {
            duration: 1200,
            easing: 'easeOutBounce'
        },
        plugins: {
            legend: {
                labels: {
                    color: '#333',
                    font: { size: 14, family: "'Segoe UI', Tahoma, sans-serif" }
                }
            },
            tooltip: {
                backgroundColor: '#fff',
                titleColor: '#333',
                bodyColor: '#444',
                borderColor: '#ccc',
                borderWidth: 1
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { color: '#555', stepSize: 1 },
                grid: { color: '#eee' }
            },
            x: {
                ticks: { color: '#555' },
                grid: { display: false }
            }
        }
    };

    // Bar Chart
    new Chart(ctxBar, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Exam Stats',
                data: data,
                backgroundColor: gradients,
                borderRadius: 10
            }]
        },
        options: commonOptions
    });

    // Line Chart
    new Chart(ctxLine, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Exam Stats',
                data: data,
                borderColor: '#3366ff',
                backgroundColor: 'rgba(51,102,255,0.2)',
                fill: true,
                tension: 0.3,
                pointRadius: 5,
                pointBackgroundColor: '#3366ff'
            }]
        },
        options: commonOptions
    });

    // Pie Chart
    new Chart(ctxPie, {
        type: 'pie',
        data: {
            labels: labels,
            datasets: [{
                label: 'Exam Stats',
                data: data,
                backgroundColor: [gradientBlue, gradientGreen, gradientRed]
            }]
        },
        options: {
            ...commonOptions,
            scales: {} // Pie chart doesn't need x/y scales
        }
    });
</script>

    <?php require_once "../../includes/footer.php"; ?>

  <script src="../../vendors/js/vendor.bundle.base.js"></script> 
  <script src="../../js/off-canvas.js"></script>
  <script src="../../js/hoverable-collapse.js"></script>
  <script src="../../js/template.js"></script>
  <script src="../../vendors/chart.js/Chart.min.js"></script>
</body>
</html>
