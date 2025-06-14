<?php
session_start();
if (!isset($_SESSION['role'])) {
    header("Location: ../../php/error.php?welcome=Please login to access this page");
    exit();
}
ob_start();
include "../../configuration/config.php";

// Fetch Clearance Data
$clearancePending = mysqli_num_rows(mysqli_query($con, "SELECT * FROM tbl_clearance_requests WHERE status = 'Pending'"));
$clearanceForSignature = mysqli_num_rows(mysqli_query($con, "SELECT * FROM tbl_clearance_requests WHERE status = 'For Signature'"));
$clearanceForPayment = mysqli_num_rows(mysqli_query($con, "SELECT * FROM tbl_clearance_requests WHERE status = 'For Payment'"));
$clearanceCleared = mysqli_num_rows(mysqli_query($con, "SELECT * FROM tbl_clearance_requests WHERE status = 'Cleared'"));
$clearanceTotal = mysqli_num_rows(mysqli_query($con, "SELECT * FROM tbl_clearance_requests"));

$username = $_SESSION['first_name'] ?? 'User';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Accounting | Dashboard</title>
    <link rel="shortcut icon" href="../../img/favicon.png" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <!-- Google Fonts: Poppins -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,500,600&display=swap" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(120deg, #f4f7fa 60%, #e6eaf0 100%);
            font-family: 'Poppins', Arial, sans-serif;
        }

        .content-header h1 {
            color: #3366ff;
            font-weight: 700;
            letter-spacing: 1px;
            font-family: 'Poppins', Arial, sans-serif;
        }

        .content-header p {
            color: #222;
            font-size: 1.1rem;
            font-family: 'Poppins', Arial, sans-serif;
        }

        .info-box {
            background: #fff;
            border-radius: 1.2rem;
            box-shadow: 0 4px 18px rgba(51, 102, 255, 0.08);
            display: flex;
            align-items: center;
            padding: 1.2rem 1.2rem 1.2rem 0.8rem;
            margin-bottom: 1.5rem;
            transition: box-shadow 0.3s;
            border-left: 6px solid #3366ff;
        }

        .info-box:hover {
            box-shadow: 0 8px 24px rgba(51, 102, 255, 0.18);
        }

        .info-box-icon {
            background: #3366ff;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            width: 60px;
            height: 60px;
            border-radius: 1rem;
            margin-right: 1.2rem;
            box-shadow: 0 2px 8px rgba(51, 102, 255, 0.09);
        }

        .info-box-content {
            flex: 1;
        }

        .info-box-text {
            font-weight: 600;
            font-size: 1.08rem;
            color: #222;
        }

        .info-box-number {
            font-weight: bold;
            font-size: 2.1rem;
            color: #3366ff;
        }

        .card {
            background: #fff;
            border-radius: 1.5rem;
            box-shadow: 0 4px 18px rgba(51, 102, 255, 0.08);
            padding: 1.5rem 1.2rem 1.2rem 1.2rem;
            margin-bottom: 1.5rem;
        }

        .card-title {
            font-weight: bold;
            font-size: 1.2rem;
            color: #3366ff;
            margin-bottom: 1rem;
        }

        .chart-container {
            max-height: 250px;
        }

        @media (max-width: 900px) {
            .info-box,
            .card {
                padding: 1rem 0.7rem 1rem 0.7rem;
            }

            .info-box-icon {
                font-size: 1.5rem;
                width: 48px;
                height: 48px;
            }

            .info-box-number {
                font-size: 1.4rem;
            }
        }

        @media (max-width: 600px) {
            .info-box,
            .card {
                border-radius: 0.7rem;
                padding: 0.7rem 0.4rem 0.7rem 0.4rem;
            }

            .info-box-icon {
                font-size: 1.2rem;
                width: 38px;
                height: 38px;
                border-radius: 0.5rem;
            }

            .info-box-number {
                font-size: 1.1rem;
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

        <aside class="right-side">
            <section class="content-header">
                <h1>Dashboard</h1>
                <p>Welcome, <strong><?= htmlspecialchars($username) ?></strong></p>
            </section>

            <section class="content">
                <div class="row">
                    <div class="col-md-3 col-sm-6 col-xs-12"><br>
                        <div class="info-box shadow-sm rounded"
                            style="background: #e6eaf0; border-left: 5px solid #002B5B; transition: box-shadow 0.3s ease;">
                            <a href="#" style="text-decoration: none; color: inherit;">
                                <span class="info-box-icon"
                                    style="background-color: #002B5B; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 28px; width: 60px; height: 60px; border-radius: 0.5rem;">
                                    <i class="fa fa-file"></i>
                                </span>
                            </a>
                            <div class="info-box-content" style="padding-left: 15px;">
                                <span class="info-box-text"
                                    style="font-weight: 600; font-size: 1.1rem; color: #000;">Clearance</span>
                                <span class="info-box-number"
                                    style="font-weight: bold; font-size: 1.8rem; color: #000;"><?= $clearanceTotal ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <style>
                    .info-box:hover {
                        box-shadow: 0 8px 20px rgba(0, 43, 91, 0.4);
                    }
                </style>


                <div class="row">
                    <div class="col-md-6 col-sm-12"><br>
                        <div class="card">
                            <h4 class="card-title">Status Count</h4>
                            <div id="barChart1" class="chart-container"></div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12"><br>
                        <div class="card">
                            <h4 class="card-title">Request Overtime</h4>
                            <div id="barChart2" class="chart-container"></div>
                        </div>
                    </div>
                </div>
            </section>
        </aside>
    </div>

    <script>
        const clearanceData = {
            pending: <?= $clearancePending ?>,
            signature: <?= $clearanceForSignature ?>,
            payment: <?= $clearanceForPayment ?>,
            cleared: <?= $clearanceCleared ?>,
            total: <?= $clearanceTotal ?>
        };

        // Chart 1: Status Count
        new ApexCharts(document.querySelector("#barChart1"), {
            chart: {
                type: 'bar',
                height: 250,
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 1000
                }
            },
            series: [{
                name: 'Requests',
                data: [
                    clearanceData.pending,
                    clearanceData.signature,
                    clearanceData.payment,
                    clearanceData.cleared
                ]
            }],
            xaxis: {
                categories: ['Pending', 'For Signature', 'For Payment', 'Cleared'],
                labels: {
                    style: { colors: ['#333', '#333', '#333', '#333'] }
                }
            },
            colors: ['#3366ff', '#00cc66', '#cc0000', '#99ccff'],  // updated to solid, bright colors
            plotOptions: {
                bar: {
                    borderRadius: 8,
                    columnWidth: '50%',
                    distributed: true
                }
            },
            tooltip: {
                theme: 'light'
            },
            dataLabels: {
                enabled: true
            },
            legend: {
                show: true,
                position: 'right',
                verticalAlign: 'middle',
                floating: false,
                labels: { colors: '#333' },
                markers: { radius: 12 }
            }
        }).render();




        // Chart 2: Total Request
        new ApexCharts(document.querySelector("#barChart2"), {
            chart: {
                type: 'bar',
                height: 250,
                animations: {
                    enabled: true,
                    easing: 'easeInOutQuad',
                    speed: 1000
                }
            },
            series: [{
                name: 'Total Requests',
                data: [clearanceData.total]
            }],
            xaxis: {
                categories: ['Clearance'],
                labels: {
                    style: {
                        colors: ['#444']
                    }
                }
            },
            colors: ['#3366ff'],
            plotOptions: {
                bar: {
                    borderRadius: 12,
                    columnWidth: '50%'
                }
            },
            tooltip: {
                theme: 'light',
                y: {
                    formatter: val => Math.round(val)
                }
            },
            dataLabels: {
                enabled: true
            }
        }).render();
    </script>

    <?php require_once "../../includes/footer.php"; ?>
</body>

</html>
