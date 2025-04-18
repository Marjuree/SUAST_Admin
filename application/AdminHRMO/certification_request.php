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
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>HRMO | Dash</title>

    <!-- External CSS Libraries -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../../vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="../../vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="../../css/style.css">

    <!-- Favicon -->
    <link rel="shortcut icon" href="../../img/favicon.png" />
</head>

<body class="skin-blue">
    <?php 
    require_once "../../configuration/config.php";
    require_once('../../includes/header.php');
    require_once('../../includes/head_css.php'); 
    ?>

    <div class="wrapper row-offcanvas row-offcanvas-left">
        <?php include('../../includes/sidebar.php'); ?>

        <aside class="right-side">
            <section class="content-header">
                <p>Welcome, <strong><?= isset($_SESSION['role']) ? htmlspecialchars($_SESSION['role']) : 'User'; ?></strong></p>
            </section>

            <section class="content">
                <div class="row">
                    <div class="box">
                        <section class="content">
                            <div class="box box-primary">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Certification Requests</h3>
                                </div>

                                <div class="box-body">
                                    <?php
                                    // Fetch all certification requests using a prepared statement
                                    $query = "SELECT id, request_type, date_request, name, faculty, reason, request_status, created_at 
                                              FROM tbl_certification_requests ORDER BY created_at DESC";

                                    if ($stmt = $con->prepare($query)) {
                                        $stmt->execute();
                                        $result = $stmt->get_result();
                                    } else {
                                        echo "<div class='alert alert-danger'>Query failed: " . htmlspecialchars($con->error) . "</div>";
                                        exit();
                                    }
                                    ?>
                                    <h3>Certification Requests</h3>
                                    <!-- Make the table responsive by wrapping it in a div -->
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Date of Request</th>
                                                    <th>Full Name</th>
                                                    <th>Faculty/Institute</th>
                                                    <th>Request Type</th>
                                                    <th>Reason</th>
                                                    <th>Status</th>
                                                    <th>Created At</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php while ($row = $result->fetch_assoc()): ?>
                                                    <tr>
                                                        <td><?= htmlspecialchars($row['date_request']) ?></td>
                                                        <td><?= htmlspecialchars($row['name']) ?></td>
                                                        <td><?= htmlspecialchars($row['faculty']) ?></td>
                                                        <td><?= htmlspecialchars($row['request_type']) ?></td>
                                                        <td><?= htmlspecialchars($row['reason']) ?></td>
                                                        <td>
                                                            <span class="badge <?= ($row['request_status'] == 'Approved') ? 'bg-success' : (($row['request_status'] == 'Pending') ? 'bg-warning' : 'bg-danger') ?>">
                                                                <?= htmlspecialchars($row['request_status']) ?>
                                                            </span>
                                                        </td>
                                                        <td><?= htmlspecialchars($row['created_at']) ?></td>
                                                        <td>
                                                            <form action="update_cert.php" method="POST">
                                                                <input type="hidden" name="request_id" value="<?= $row['id'] ?>">
                                                                <button type="submit" name="approve" class="btn btn-success btn-sm">Approve</button>
                                                                <button type="submit" name="disapprove" class="btn btn-danger btn-sm">Disapprove</button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                <?php endwhile; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </section>
        </aside>
    </div>

    <?php include "../../includes/footer.php"; ?>

    <!-- JavaScript Libraries -->
    <script src="../../vendors/js/vendor.bundle.base.js"></script>
    <script src="../../js/off-canvas.js"></script>
    <script src="../../js/hoverable-collapse.js"></script>
    <script src="../../vendors/chart.js/Chart.min.js"></script>
    <script src="../../js/chart.js"></script>

    <script type="text/javascript">
        $(function() {
            $("#table").dataTable({
                "aoColumnDefs": [{
                    "bSortable": false,
                    "aTargets": [0, 5]
                }],
                "aaSorting": []
            });
        });
    </script>
</body>

</html>
