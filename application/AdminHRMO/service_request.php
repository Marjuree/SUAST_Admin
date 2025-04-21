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

    <!-- External CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="shortcut icon" href="../../img/favicon.png" />

    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>

<body class="skin-blue">
    <?php 
    require_once "../../configuration/config.php";
    require_once('../../includes/header.php');
    require_once('../../includes/head_css.php'); 
    ?>

    <div class="wrapper row-offcanvas row-offcanvas-left">
        <?php require_once('../../includes/sidebar.php'); ?>

        <aside class="right-side">
            <section class="content-header">
                <p>Welcome,
                    <strong><?= isset($_SESSION['role']) ? htmlspecialchars($_SESSION['role']) : 'User'; ?></strong>
                </p>
            </section>

            <section class="content">
                <div class="row">
                    <div class="box">
                        <section class="content">
                            <div class="box box-primary">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Service Requests</h3>
                                </div>
                                <br>
                                <div class="box-body">
                                    <?php
                                    $query = "SELECT id, request_type, date_request, name, faculty, reason, request_status, created_at 
                                              FROM tbl_service_requests ORDER BY created_at DESC";
                                    $stmt = $con->prepare($query);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    ?>
                                    <h3>Service Record Requests</h3>
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
                                                        <span
                                                            class="badge <?= ($row['request_status'] == 'Approved') ? 'bg-success' : (($row['request_status'] == 'Pending') ? 'bg-warning' : 'bg-danger') ?>">
                                                            <?= htmlspecialchars($row['request_status']) ?>
                                                        </span>
                                                    </td>
                                                    <td><?= htmlspecialchars($row['created_at']) ?></td>
                                                    <td>
                                                        <form method="POST" action="update_service.php" class="action-form">
                                                            <input type="hidden" name="request_id" value="<?= htmlspecialchars($row['id']) ?>">
                                                            <button type="button" class="btn btn-success btn-sm approve-btn">Approve</button>
                                                            <button type="button" class="btn btn-danger btn-sm disapprove-btn">Disapprove</button>
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

    <?php require_once "../../includes/footer.php"; ?>

    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../../js/off-canvas.js"></script>
    <script src="../../js/hoverable-collapse.js"></script>
    <script src="../../js/chart.js"></script>

    <script>
        document.querySelectorAll('.approve-btn').forEach(button => {
            button.addEventListener('click', function () {
                const form = this.closest('form');

                Swal.fire({
                    title: 'Approve Request?',
                    text: "This action will approve the service request.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Approve'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'approve';
                        form.appendChild(input);
                        form.submit();
                    }
                });
            });
        });

        document.querySelectorAll('.disapprove-btn').forEach(button => {
            button.addEventListener('click', function () {
                const form = this.closest('form');

                Swal.fire({
                    title: 'Disapprove Request?',
                    text: "This action will disapprove the service request.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, Disapprove'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'disapprove';
                        form.appendChild(input);
                        form.submit();
                    }
                });
            });
        });
    </script>
</body>

</html>
