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
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Admin | Dashboard</title>
    <link rel="shortcut icon" href="../../img/favicon.png" />
    <link rel="stylesheet" href="../../vendors/mdi/css/materialdesignicons.min.css" />
    <link rel="stylesheet" href="../../vendors/css/vendor.bundle.base.css" />

    <style>
        .scrollable-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            margin-bottom: 30px;
        }



        .chart-container {
            max-width: 900px;
            margin: 0 auto;
        }

        .chart-wrapper {
            display: flex;
            flex-direction: column;
            gap: 40px;
        }

        .card {
            width: 100%;
            margin: 0 auto;
            padding: 10px;
            box-shadow: 0 2px 6px rgb(0 0 0 / 0.1);
            border-radius: 8px;
            background: #fff;
        }

        .card h4 {
            margin-bottom: 10px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
        }

        /* Set fixed height for each chart */
        .apexcharts-canvas {
            max-height: 300px !important;
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

            <section class="content" style="max-height: 85vh; overflow-y: auto; padding-right: 10px;">
                <!-- Info boxes -->
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
                        <div class="card">
                            <h4>Bar Chart</h4>
                            <div id="barChart"></div>
                        </div>

                        <div class="card">
                            <h4>Line Chart</h4>
                            <div id="lineChart"></div>
                        </div>

                        <div class="card">
                            <h4>Pie Chart</h4>
                            <div id="pieChart"></div>
                        </div>
                    </div>
                </div>
            </section>
        </aside>
    </div>

    <!-- ApexCharts Library -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <script>
        const labels = ['Takers', 'Available Slots', 'Registered Takers'];
        const data = [<?= $takers ?>, <?= $availableSlots ?>, <?= $registeredTakers ?>];

        // Bar Chart
        var optionsBar = {
            chart: {
                type: 'bar',
                height: 300,
                toolbar: { show: false }
            },
            series: [{
                name: 'Count',
                data: data
            }],
            xaxis: {
                categories: labels,
                labels: { rotate: -45 }
            },
            colors: ['#3366ff', '#00cc66', '#cc0000'],
            plotOptions: {
                bar: {
                    borderRadius: 6,
                    columnWidth: '45%'
                }
            },
            dataLabels: {
                enabled: true,
                style: { fontSize: '12px' }
            },
            tooltip: {
                theme: 'light'
            }
        };
        var chartBar = new ApexCharts(document.querySelector("#barChart"), optionsBar);
        chartBar.render();

        // Line Chart
        var optionsLine = {
            chart: {
                type: 'line',
                height: 300,
                toolbar: { show: false }
            },
            series: [{
                name: 'Count',
                data: data
            }],
            xaxis: {
                categories: labels
            },
            stroke: {
                curve: 'smooth',
                width: 3
            },
            markers: {
                size: 6,
                colors: ['#3366ff'],
                strokeColors: '#fff',
                strokeWidth: 2,
                hover: { size: 8 }
            },
            colors: ['#3366ff'],
            tooltip: {
                theme: 'light'
            }
        };
        var chartLine = new ApexCharts(document.querySelector("#lineChart"), optionsLine);
        chartLine.render();

        // Pie Chart
        var optionsPie = {
            chart: {
                type: 'pie',
                height: 300,
            },
            series: data,
            labels: labels,
            colors: ['#3366ff', '#00cc66', '#cc0000'],
            legend: {
                position: 'bottom'
            },
            tooltip: {
                theme: 'light'
            }
        };
        var chartPie = new ApexCharts(document.querySelector("#pieChart"), optionsPie);
        chartPie.render();
    </script>

    <?php require_once "../../includes/footer.php"; ?>

    <script src="../../vendors/js/vendor.bundle.base.js"></script>
    <script src="../../js/off-canvas.js"></script>
    <script src="../../js/hoverable-collapse.js"></script>
    <script src="../../js/template.js"></script>
</body>

</html>
