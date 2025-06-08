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
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<style>
    body {
        background: linear-gradient(120deg, #f4f7fa 60%, #e6eaf0 100%);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .content-header h1 {
        color: #3366ff;
        font-weight: 700;
        letter-spacing: 1px;
        margin-bottom: 10px;
    }
    .box {
        background: #fff;
        border-radius: 18px;
        box-shadow: 0 4px 24px rgba(51,102,255,0.07), 0 1.5px 6px rgba(0,0,0,0.04);
        padding: 32px 24px 24px 24px;
        margin-bottom: 32px;
        border: none;
    }
    .box h3 {
        color: #3366ff;
        font-weight: 600;
        margin-bottom: 18px;
    }
    .table-responsive {
        width: 100%;
        overflow-x: auto;
    }
    .table {
        background: #fff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(51,102,255,0.04);
        margin-bottom: 0;
    }
    .table th, .table td {
        text-align: center;
        vertical-align: middle;
        border: none !important;
    }
    .table th {
        background:rgb(18, 18, 18) !important;
        color: #fff;
        font-weight: 600;
        font-size: 1.05rem;
        border-bottom: 2px solid #d1d8e6 !important;
    }
    .table-striped tbody tr:nth-of-type(odd) {
        background: #f8fafc;
    }
    .table-striped tbody tr:hover {
        background: #e6eaf0;
        transition: background 0.2s;
    }
    .label {
        border-radius: 8px;
        padding: 4px 12px;
        font-size: 0.97rem;
        font-weight: 500;
    }
    .label-success {
        background: #28a745;
        color: #fff;
    }
    .label-danger {
        background: #cc0000;
        color: #fff;
    }
    .label-primary {
        background: #3366ff;
        color: #fff;
    }
    .label-info {
        background: #6c757d;
        color: #fff;
    }
    .label-default {
        background: #bfc9d1;
        color: #fff;
    }
    .btn-xs, .btn-sm {
        border-radius: 6px !important;
        font-size: 0.98rem !important;
        padding: 6px 16px !important;
        font-weight: 500;
        margin: 2px 0;
        box-shadow: 0 2px 8px rgba(51,102,255,0.07);
        transition: background 0.2s, color 0.2s;
        border: none !important;
    }
    .btn-success {
        background: #28a745 !important;
        color: #fff !important;
    }
    .btn-danger {
        background: #cc0000 !important;
        color: #fff !important;
    }
    .btn-primary {
        background: #3366ff !important;
        color: #fff !important;
    }
    .btn-warning {
        background: #ffc107 !important;
        color: #222 !important;
    }
    .btn-default {
        background: #e6eaf0 !important;
        color: #222 !important;
    }
    .modal-content {
        border-radius: 16px;
        box-shadow: 0 2px 16px rgba(0,0,0,0.09);
    }
    .modal-header {
        background: #f4f7fa;
        border-bottom: 1px solid #e6eaf0;
        border-radius: 16px 16px 0 0;
    }
    .modal-title {
        font-weight: 700;
        color: #3366ff;
    }
    .form-group label {
        font-weight: 500;
    }
    .form-control {
        border-radius: 7px;
        border: 1px solid #e0e6ed;
        font-size: 1rem;
    }
    .close {
        font-size: 1.5rem;
        color: #222;
    }
    @media (max-width: 900px) {
        .box {
            padding: 16px 4px 16px 4px;
        }
        .table th, .table td {
            font-size: 0.97rem;
            padding: 8px 2px;
        }
    }
    @media (max-width: 600px) {
        .box {
            padding: 8px 0 8px 0;
        }
        .table th, .table td {
            font-size: 0.95rem;
            padding: 6px 2px;
        }
        .btn-xs, .btn-sm {
            font-size: 0.93rem !important;
            padding: 5px 8px !important;
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
    <h1>Request Clearance | Dashboard</h1>
</section>

<section class="content">
    <div class="row">
        <div class="box">
            <div class="box-body">
                <hr>
                <h3>Clearance Requests</h3>

                <form method="POST" action="bulk_toggle.php" id="bulkForm">
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
                                    if ($status === 'For Signature') {
                                        $labelClass = 'label-success';
                                    } elseif ($status === 'For Payment') {
                                        $labelClass = 'label-danger';
                                    } elseif ($status === 'Cleared') {
                                        $labelClass = 'label-primary';
                                    } else {
                                        $labelClass = 'label-info';
                                    }
                                ?>
                                <tr>
                                    <td><input type="checkbox" name="ids[]" value="<?= htmlspecialchars($row['id']) ?>"></td>
                                    <td><?= htmlspecialchars($row['student_id']) ?></td>
                                    <td><?= htmlspecialchars($row['full_name']) ?></td>
                                    <td><?= htmlspecialchars($row['balance']) ?></td>
                                    <td><span class="label <?= $labelClass ?>"><?= $status ?></span></td>
                                    <td><?= htmlspecialchars($row['date_requested']) ?></td>
                                    <td>
                                        <button class="btn btn-success btn-xs update-status" title="For Signature" data-id="<?= $row['id'] ?>" data-status="For Signature">
                                            <span class="glyphicon glyphicon-pencil"></span>
                                        </button>
                                        <button class="btn btn-danger btn-xs update-status" title="For Payment" data-id="<?= $row['id'] ?>" data-status="For Payment">
                                            <span class="glyphicon glyphicon-usd"></span>
                                        </button>
                                        <button class="btn btn-primary btn-xs update-status" title="Cleared" data-id="<?= $row['id'] ?>" data-status="Cleared">
                                            <span class="glyphicon glyphicon-ok"></span>
                                        </button>
                                        <button type="button" class="btn btn-warning btn-xs open-balance-modal"
                                            title="Edit Balance"
                                            data-toggle="modal" data-target="#balanceModal"
                                            data-id="<?= htmlspecialchars($row['id']) ?>"
                                            data-name="<?= htmlspecialchars($row['full_name']) ?>"
                                            data-balance="<?= htmlspecialchars($row['balance']) ?>">
                                            <span class="glyphicon glyphicon-edit"></span>
                                        </button>
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

                        <div style="margin-top: 10px;">
                            <button type="button" class="btn btn-success btn-sm" id="enableBtn">Enable Selected</button>
                            <button type="button" class="btn btn-danger btn-sm" id="disableBtn">Disable Selected</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
</aside>
</div>

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
                    <input type="text" class="form-control" id="studentName" disabled>
                </div>
                <div class="form-group">
                    <label for="currentBalance">Current Balance</label>
                    <input type="text" class="form-control" id="currentBalance" disabled>
                </div>
                <div class="form-group">
                    <label for="balance">New Balance</label>
                    <input type="number" class="form-control" name="balance" id="balance" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Update Balance</button>
            </div>
        </div>
    </form>
</div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function () {
    $('#selectAll').click(function () {
        $('input[name="ids[]"]').prop('checked', this.checked);
    });

    // Open balance modal and fill fields
    $('.open-balance-modal').on('click', function () {
        $('#modalClearanceId').val($(this).data('id'));
        $('#studentName').val($(this).data('name'));
        $('#currentBalance').val($(this).data('balance'));
    });

    // Status update with SweetAlert and auto reload
    $('.update-status').on('click', function () {
        const id = $(this).data('id');
        const status = $(this).data('status');
        Swal.fire({
            title: `Are you sure?`,
            text: `You are about to update the status to "${status}"`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, update it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'process_clearance.php',
                    type: 'POST',
                    data: { id: id, status: status },
                    success: function (response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Status Updated',
                            text: 'The status has been updated successfully.',
                            timer: 1200,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                            setTimeout(function() {
                                location.reload();
                            }, 1500); // reload again after 1.5 seconds
                        });
                    },
                    error: function (xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'An error occurred: ' + error
                        });
                    }
                });
            }
        });
    });

    // Bulk enable/disable with SweetAlert and auto reload
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
                const form = $('#bulkForm');
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

    $('#enableBtn').on('click', function () { handleBulkAction('enable'); });
    $('#disableBtn').on('click', function () { handleBulkAction('disable'); });
});
</script>
</body>
</html>
