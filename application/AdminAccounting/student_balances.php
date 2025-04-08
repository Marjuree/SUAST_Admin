<?php
session_start();
if (!isset($_SESSION['role'])) {
    header("Location: ../../php/error.php?welcome=Please login to access this page");
    exit();
}

require_once "../../configuration/config.php"; // Ensure database connection

ob_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Clearance Requests | Dashboard</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../../vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="../../vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="../../css/style.css">
    <link href="../../css/landing.css" rel="stylesheet" type="text/css" />
    <link rel="shortcut icon" href="../../img/favicon.png" />

    <style>
    .table th,
    .table td {
        text-align: center;
        /* Center table content */
        vertical-align: middle;
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
                <h1>Student Balance | Dashboard</h1>
            </section>

            <section class="content">
                <div class="row">
                    <div class="box">
                        <div class="box-body">
                            <hr>
                            <h3>Student Records</h3>

                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Student ID</th>
                                        <th>Student Name</th>
                                        <th>Student Balance</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Modify your query to retrieve the balance.
                                    $query = "
                                        SELECT cr.id, cr.student_id, 
                                               COALESCE(su.full_name, 'Unknown Student') AS full_name, 
                                               cr.balance  -- Now we are using 'cr.balance' since it's in the 'tbl_clearance_requests' table
                                        FROM tbl_clearance_requests cr
                                        LEFT JOIN tbl_student_users su ON cr.student_id = su.school_id
                                        ORDER BY cr.balance DESC";  // Order by balance in 'tbl_clearance_requests'

                                    $result = $con->query($query);

                                    if (!$result) {
                                        die("Query failed: " . $con->error);  // Output the error message from MySQL
                                    }

                                    while ($row = $result->fetch_assoc()): 
                                    ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['id']) ?></td>
                                        <td><?= htmlspecialchars($row['student_id']) ?></td>
                                        <td><?= htmlspecialchars($row['full_name']) ?></td>
                                        <td><?= htmlspecialchars($row['balance']) ?></td> <!-- Display balance -->
                                        <td>
                                            <form method="POST" action="add_balance.php">
                                                <input type="hidden" name="clearance_id"
                                                    value="<?= htmlspecialchars($row['id']) ?>">
                                                <input type="number" name="balance" class="form-control mb-2"
                                                    placeholder="Enter Balance" required>
                                                <button type="submit" name="add_balance" class="btn btn-warning">Add
                                                    Balance</button>
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
        </aside>
    </div>

    <?php require_once "modal.php"; ?>
    <?php require_once "../../includes/footer.php"; ?>

    <script src="../../vendors/js/vendor.bundle.base.js"></script>
    <script src="../../js/off-canvas.js"></script>
    <script src="../../js/hoverable-collapse.js"></script>
</body>

</html>
