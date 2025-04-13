<?php
    session_start();
    if (!isset($_SESSION['role'])) {
        header("Location: ../../php/error.php?welcome=Please login to access this page");
        exit();
    }
    ob_start();
    include "../../configuration/config.php";

    // Fetch Clearance Data
    $clearanceApproved = mysqli_num_rows(mysqli_query($con, "SELECT * FROM tbl_clearance_requests WHERE status = 'Approved'"));
    $clearancePending = mysqli_num_rows(mysqli_query($con, "SELECT * FROM tbl_clearance_requests WHERE status = 'Pending'"));
    $clearanceRejected = mysqli_num_rows(mysqli_query($con, "SELECT * FROM tbl_clearance_requests WHERE status = 'Rejected'"));
    $clearanceCount = mysqli_num_rows(mysqli_query($con, "SELECT * FROM tbl_clearance_requests"));
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Accounting | Dashboard</title>
    <link rel="shortcut icon" href="../../img/favicon.png" />
    <script src="../../assets/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
    <style>
    canvas {
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
                <p>Welcome, <strong><?php echo htmlspecialchars($username); ?></strong></p>
            </section>

            <section class="content">
                <div class="row">
                    <div class="col-md-3 col-sm-6 col-xs-12"><br>
                        <div class="info-box">
                            <a href="#">
                                <span class="info-box-icon bg-aqua"><i class="fa fa-file"></i></span>
                            </a>
                            <div class="info-box-content">
                                <span class="info-box-text">Clearance</span>
                                <span class="info-box-number"><?= $clearanceCount ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <?php 
                    $charts = [
                        ["Status Count", "barChart1"],
                        ["Request Overtime", "barChart2"]
                    ];
                    foreach ($charts as $chart) { ?>
                    <div class="col-md-6 col-sm-12"><br>
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title"><?= $chart[0] ?></h4>
                                <canvas id="<?= $chart[1] ?>"></canvas>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </section>
        </aside>
    </div>

    <script>
    const clearanceApproved = <?= $clearanceApproved ?>;
    const clearancePending = <?= $clearancePending ?>;
    const clearanceRejected = <?= $clearanceRejected ?>;
    const clearanceTotal = <?= $clearanceCount ?>;

    // Get canvas context for gradient bars
    const ctx1 = document.getElementById('barChart1').getContext('2d');
    const gradientOrange = ctx1.createLinearGradient(0, 0, 0, 200);
    gradientOrange.addColorStop(0, '#ffb347');
    gradientOrange.addColorStop(1, '#ffcc33');

    const gradientGreen = ctx1.createLinearGradient(0, 0, 0, 200);
    gradientGreen.addColorStop(0, '#66ff99');
    gradientGreen.addColorStop(1, '#00cc66');

    const gradientRed = ctx1.createLinearGradient(0, 0, 0, 200);
    gradientRed.addColorStop(0, '#ff6666');
    gradientRed.addColorStop(1, '#cc0000');

    new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: ['Clearance'],
            datasets: [{
                    label: 'Pending',
                    data: [clearancePending],
                    backgroundColor: gradientOrange,
                    borderRadius: 10
                },
                {
                    label: 'Approved',
                    data: [clearanceApproved],
                    backgroundColor: gradientGreen,
                    borderRadius: 10
                },
                {
                    label: 'Rejected',
                    data: [clearanceRejected],
                    backgroundColor: gradientRed,
                    borderRadius: 10
                }
            ]
        },
        options: {
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
                        font: {
                            size: 14,
                            family: "'Segoe UI', Tahoma, Geneva, Verdana, sans-serif"
                        }
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
                    ticks: {
                        color: '#555',
                        stepSize: 1
                    },
                    grid: {
                        color: '#eee'
                    }
                },
                x: {
                    ticks: {
                        color: '#555'
                    },
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Chart 2 (Total Requests)
    const ctx2 = document.getElementById('barChart2').getContext('2d');
    const gradientBlue = ctx2.createLinearGradient(0, 0, 0, 200);
    gradientBlue.addColorStop(0, '#99ccff');
    gradientBlue.addColorStop(1, '#3366ff');

    new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: ['Clearance'],
            datasets: [{
                label: 'Total Requests',
                data: [clearanceTotal],
                backgroundColor: gradientBlue,
                borderRadius: 12
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: {
                duration: 1000,
                easing: 'easeInOutQuad'
            },
            plugins: {
                legend: {
                    labels: {
                        color: '#333',
                        font: {
                            size: 14
                        }
                    }
                },
                tooltip: {
                    backgroundColor: '#fff',
                    titleColor: '#333',
                    bodyColor: '#444',
                    borderColor: '#ccc',
                    borderWidth: 1,
                    callbacks: {
                        label: function(context) {
                            return `${context.dataset.label}: ${Math.round(context.raw)}`;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        color: '#444',
                        callback: function(value) {
                            return Number.isInteger(value) ? value : '';
                        },
                        stepSize: 1
                    },
                    grid: {
                        color: '#eee'
                    }
                },
                x: {
                    ticks: {
                        color: '#444'
                    },
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
    </script>


    <?php require_once "../../includes/footer.php"; ?>
</body>

</html>
