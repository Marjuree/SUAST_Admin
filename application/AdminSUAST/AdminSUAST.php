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

    <!-- Google Fonts: Poppins -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,500,600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', Arial, sans-serif !important;
        }

        .scrollable-container,
        .chart-container,
        .chart-wrapper,
        .card,
        .card h4,
        .info-box,
        .info-box-content,
        .info-box-text,
        .info-box-number,
        .btn,
        .modal-content,
        .modal-header,
        .modal-title,
        .form-group label,
        .form-control {
            font-family: 'Poppins', Arial, sans-serif !important;
        }

        .scrollable-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            margin-bottom: 30px;
        }

        /* ...existing styles... */
        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        label,
        .info-box-text,
        .info-box-number,
        .card h4,
        .content-header h1 {
            font-family: 'Poppins', Arial, sans-serif !important;
        }



        .chart-container {
            max-width: 900px;
        }

        .chart-wrapper {
            display: flex;
            gap: 20px;
            flex-wrap: nowrap;
        }

        .card {
            flex: 1 1 0;
            min-width: 400px;
            background: #fff;
            padding: 15px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .card h4 {
            margin-bottom: 10px;
            color: #333;
        }

        /* Set fixed height for each chart */
        .apexcharts-canvas {
            max-height: 250px !important;
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
                        <div class="info-box shadow-sm rounded"
                            style="background: #e6eaf0; border-left: 5px solid #0056b3; transition: box-shadow 0.3s ease;">
                            <a href="#" style="text-decoration: none; color: inherit;">
                                <span class="info-box-icon"
                                    style="background-color: #0056b3; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 28px; width: 60px; height: 60px; border-radius: 0.5rem;">
                                    <i class="fa fa-tasks"></i>
                                </span>
                            </a>
                            <div class="info-box-content" style="padding-left: 15px;">
                                <span class="info-box-text"
                                    style="font-weight: 600; font-size: 1.1rem; color: #000;">Applicants</span>
                                <span class="info-box-number"
                                    style="font-weight: bold; font-size: 1.8rem; color: #000;"><?= $takers; ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6 col-xs-12"><br>
                        <div class="info-box shadow-sm rounded"
                            style="background: #e6eaf0; border-left: 5px solid #28a745; transition: box-shadow 0.3s ease;">
                            <a href="#" style="text-decoration: none; color: inherit;">
                                <span class="info-box-icon"
                                    style="background-color: #28a745; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 28px; width: 60px; height: 60px; border-radius: 0.5rem;">
                                    <i class="fa fa-file-alt"></i>
                                </span>
                            </a>
                            <div class="info-box-content" style="padding-left: 15px;">
                                <span class="info-box-text"
                                    style="font-weight: 600; font-size: 1.1rem; color: #000;">Available Slot</span>
                                <span class="info-box-number"
                                    style="font-weight: bold; font-size: 1.8rem; color: #000;"><?= $availableSlots; ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6 col-xs-12"><br>
                        <div class="info-box shadow-sm rounded"
                            style="background: #e6eaf0; border-left: 5px solid #cc0000; transition: box-shadow 0.3s ease;">
                            <a href="#" style="text-decoration: none; color: inherit;">
                                <span class="info-box-icon"
                                    style="background-color: #cc0000; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 28px; width: 60px; height: 60px; border-radius: 0.5rem;">
                                    <i class="fa fa-check-circle"></i>
                                </span>
                            </a>
                            <div class="info-box-content" style="padding-left: 15px;">
                                <span class="info-box-text"
                                    style="font-weight: 600; font-size: 1.1rem; color: #000;">Registered Takers
                                    Requests</span>
                                <span class="info-box-number"
                                    style="font-weight: bold; font-size: 1.8rem; color: #000;"><?= $registeredTakers; ?></span>
                            </div>
                        </div>
                    </div>

                    <style>
                        .info-box:hover {
                            box-shadow: 0 8px 20px rgba(0, 43, 91, 0.4);
                        }
                    </style>
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
                toolbar: { show: false },
                fontFamily: 'Poppins, Arial, sans-serif'

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
                    columnWidth: '45%',
                    distributed: true  // Enables different colors per bar
                }
            },
            dataLabels: {
                enabled: true,
                style: { fontSize: '12px' }
            },
            tooltip: {
                theme: 'light'
            },
            legend: {
                show: true,
                position: 'right',
                verticalAlign: 'middle',
                floating: false,
                labels: { colors: '#333' },
                markers: { radius: 12 }
            }
        };

        var chartBar = new ApexCharts(document.querySelector("#barChart"), optionsBar);
        chartBar.render();


        // Line Chart
        var optionsLine = {
            chart: {
                type: 'line',
                height: 300,
                toolbar: { show: false },
                fontFamily: 'Poppins, Arial, sans-serif'

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
                fontFamily: 'Poppins, Arial, sans-serif',

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
