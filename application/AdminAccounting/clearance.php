<?php
session_start();
if (!isset($_SESSION['role'])) {
    header("Location: ../../php/error.php?welcome=Please login to access this page");
    exit();
}

require_once "../../configuration/config.php"; // DB connection
ob_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Clearance Requests | Dashboard</title>

    <!-- Bootstrap 3 -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <style>
        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }

        .table th,
        .table td {
            text-align: center;
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
                <h1>Request Clearance | Dashboard</h1>
            </section>

            <section class="content">
                <div class="row">
                    <div class="box">
                        <div class="box-body">
                            <hr>
                            <h3>Clearance Requests</h3>

                            <form method="POST" action="bulk_toggle.php">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th><input type="checkbox" id="selectAll"></th>
                                                <th>Student ID</th>
                                                <th>Student Name</th>
                                                <th>Balance</th>
                                                <th>Status</th>
                                                <th>Date Requested</th>
                                                <th>Actions</th>
                                                <th>Semester</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $query = "
                                                SELECT cr.id, cr.student_id, 
                                                       COALESCE(su.full_name, 'Unknown Student') AS full_name, 
                                                       cr.status, cr.date_requested, cr.balance, cr.enabled 
                                                FROM tbl_clearance_requests cr
                                                LEFT JOIN tbl_student_users su ON cr.student_id = su.school_id
                                                ORDER BY cr.date_requested DESC";

                                            $result = $con->query($query);
                                            while ($row = $result->fetch_assoc()): 
                                                $status = !empty($row['status']) ? htmlspecialchars($row['status']) : "Pending";
                                                $labelClass = ($status == 'Approved') ? 'label-success' : (($status == 'Rejected') ? 'label-danger' : 'label-info');
                                            ?>
                                            <tr>
                                                <td><input type="checkbox" name="ids[]"
                                                        value="<?= htmlspecialchars($row['id']) ?>"></td>
                                                <td><?= htmlspecialchars($row['student_id']) ?></td>
                                                <td><?= htmlspecialchars($row['full_name']) ?></td>
                                                <td><?= htmlspecialchars($row['balance']) ?></td>
                                                <td><span class="label <?= $labelClass ?>"><?= $status ?></span></td>
                                                <td><?= htmlspecialchars($row['date_requested']) ?></td>
                                                <td>
                                                    <form method="POST" action="process_clearance.php"
                                                        class="form-inline" style="display:inline-block;">
                                                        <input type="hidden" name="id"
                                                            value="<?= htmlspecialchars($row['id']) ?>">
                                                        <button type="submit" name="approve"
                                                            class="btn btn-success btn-xs">Approve</button>
                                                        <button type="submit" name="disapprove"
                                                            class="btn btn-danger btn-xs">Disapprove</button>
                                                    </form>
                                                    <button class="btn btn-warning btn-xs open-balance-modal"
                                                        data-toggle="modal" data-target="#balanceModal"
                                                        data-id="<?= htmlspecialchars($row['id']) ?>"
                                                        data-name="<?= htmlspecialchars($row['full_name']) ?>"
                                                        data-balance="<?= htmlspecialchars($row['balance']) ?>">Balance</button>
                                                </td>
                                                <td>
                                                    <?php if ($row['enabled'] == 1): ?>
                                                    <span class="label label-success">Enabled</span>
                                                    <?php else: ?>
                                                    <span class="label label-default">Disabled</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>

                                    <!-- Bulk Action Buttons -->
                                    <div style="margin-top: 10px;">
                                        <button type="button" class="btn btn-success btn-sm" id="enableBtn">Enable
                                            Selected</button>
                                        <button type="button" class="btn btn-danger btn-sm" id="disableBtn">Disable
                                            Selected</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
        </aside>
    </div>

    <!-- BALANCE MODAL -->
    <div id="balanceModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="balanceModalLabel">
        <div class="modal-dialog" role="document">
            <form method="POST" action="add_balance.php">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title" id="balanceModalLabel">Update Student Balance</h4>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="clearance_id" id="modalClearanceId">
                        <div class="form-group">
                            <label for="studentName">Student Name</label>
                            <input type="text" class="form-control" id="studentName" readonly>
                        </div>
                        <div class="form-group">
                            <label for="balanceAmount">New Balance</label>
                            <input type="number" name="balance" class="form-control" id="balanceAmount" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="add_balance" class="btn btn-primary">Save changes</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <?php require_once "modal.php"; ?>
    <?php require_once "../../includes/footer.php"; ?>

    <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            $('#selectAll').click(function () {
                $('input[name="ids[]"]').prop('checked', this.checked);
            });

            $('.open-balance-modal').on('click', function () {
                $('#modalClearanceId').val($(this).data('id'));
                $('#studentName').val($(this).data('name'));
                $('#balanceAmount').val($(this).data('balance'));
            });

            function handleBulkAction(actionName) {
                const selected = $('input[name="ids[]"]:checked');
                if (selected.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'No records selected',
                        text: 'Please select at least one record to proceed.',
                    });
                    return;
                }

                Swal.fire({
                    title: `Are you sure you want to ${actionName} selected?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, do it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = $('form[action="bulk_toggle.php"]');
                        const hidden = $('<input>').attr({
                            type: 'hidden',
                            name: actionName === 'enable' ? 'bulk_enable' : 'bulk_disable',
                            value: '1'
                        });
                        form.append(hidden);
                        form.submit();
                    }
                });
            }

            $('#enableBtn').on('click', function () {
                handleBulkAction('enable');
            });

            $('#disableBtn').on('click', function () {
                handleBulkAction('disable');
            });
        });
    </script>

    <?php if (isset($_SESSION['swal'])): ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: '<?= $_SESSION['swal']['type'] ?>',
            title: '<?= $_SESSION['swal']['message'] ?>',
            showConfirmButton: false,
            timer: 2000
        });
    });
    </script>
    <?php unset($_SESSION['swal']); endif; ?>

</body>

</html>
