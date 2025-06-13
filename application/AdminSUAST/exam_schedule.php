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
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Exam Schedule</title>
    <link rel="shortcut icon" href="../../img/favicon.png" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Google Fonts: Poppins -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,500,600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', Arial, sans-serif !important;
            background: #f4f7fa;
        }

        .box,
        .box-header,
        .box-header label,
        .bulk-action-buttons,
        .btn,
        .modal-content,
        .modal-header,
        .modal-title,
        .form-group label,
        .form-control,
        .table,
        .table th,
        .table td,
        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: 'Poppins', Arial, sans-serif !important;
        }

        .box {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 2px 16px rgba(0, 0, 0, 0.07);
            padding: 24px 18px 18px 18px;
            margin-bottom: 32px;
        }

        .box-header {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 18px;
        }

        .box-header form {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .box-header label {
            font-weight: 600;
            margin-right: 6px;
        }

        .box-header .btn {
            border-radius: 6px;
            font-weight: 500;
            margin-left: 6px;
        }

        .bulk-action-buttons {
            margin-top: 0;
            padding: 10px 18px;
            background: linear-gradient(135deg, #f8fafc 80%, #e6eaf0 100%);
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.07);
            display: flex;
            gap: 10px;
        }

        .bulk-action-buttons .btn {
            min-width: 120px;
            font-size: 1rem;
        }

        .table {
            background: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        .table th,
        .table td {
            vertical-align: middle !important;
            text-align: center;
        }

        .table th {
            background: rgb(29, 29, 29);
            color: #fff;
            font-weight: 600;
            font-size: 1.05rem;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background: #f8fafc;
        }

        .table-striped tbody tr:hover {
            background: #e6eaf0;
            transition: background 0.2s;
        }

        .badge-success {
            background: #28a745;
            color: #fff;
            border-radius: 8px;
            padding: 4px 12px;
            font-size: 0.95rem;
        }

        .badge-danger {
            background: #cc0000;
            color: #fff;
            border-radius: 8px;
            padding: 4px 12px;
            font-size: 0.95rem;
        }

        .btn {
            border-radius: 6px !important;
            font-size: 0.98rem !important;
            padding: 6px 16px !important;
        }

        .btn-warning {
            background: #ffc107 !important;
            color: #222 !important;
            border: none !important;
        }

        .btn-danger {
            background: #cc0000 !important;
            color: #fff !important;
            border: none !important;
        }

        .btn-success {
            background: #28a745 !important;
            color: #fff !important;
            border: none !important;
        }

        .btn-secondary {
            background: #6c757d !important;
            color: #fff !important;
            border: none !important;
        }

        .btn-primary {
            background: #3366ff !important;
            color: #fff !important;
            border: none !important;
        }

        .modal-content {
            border-radius: 14px;
            box-shadow: 0 2px 16px rgba(0, 0, 0, 0.09);
        }

        .modal-header {
            background: #f4f7fa;
            border-bottom: 1px solid #e6eaf0;
            border-radius: 14px 14px 0 0;
        }

        .modal-title {
            font-weight: 600;
            color: #3366ff;
        }

        .form-group label {
            font-weight: 500;
        }

        .form-control {
            border-radius: 6px;
            border: 1px solid #e0e6ed;
            font-size: 1rem;
        }

        .close {
            font-size: 1.5rem;
            color: #222;
        }

        #date-warning {
            font-size: 0.95rem;
        }

        @media (max-width: 900px) {
            .box-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }

            .bulk-action-buttons {
                width: 100%;
                justify-content: flex-start;
            }
        }

        @media (max-width: 600px) {
            .box {
                padding: 10px 2px 10px 2px;
            }

            .table th,
            .table td {
                font-size: 0.95rem;
                padding: 6px 2px;
            }

            .bulk-action-buttons {
                flex-direction: column;
                gap: 8px;
            }
        }
    </style>
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
                <h1>Schedule List</h1>
            </section>

            <section class="content">
                <div class="box">
                    <div class="box-header d-flex justify-content-between align-items-center">
                        <form method="GET" action="">
                            <label for="roomFilter">Find by Slot:</label>
                            <select name="roomFilter" id="roomFilter" class="form-control"
                                style="width: 200px; display: inline-block;">
                                <option value="">All Slot</option>
                                <?php
                                $roomQuery = mysqli_query($con, "SELECT DISTINCT room FROM tbl_exam_schedule ORDER BY room ASC");
                                while ($roomRow = mysqli_fetch_assoc($roomQuery)) {
                                    $selected = (isset($_GET['roomFilter']) && $_GET['roomFilter'] == $roomRow['room']) ? "selected" : "";
                                    echo "<option value='{$roomRow['room']}' $selected>{$roomRow['room']}</option>";
                                }
                                ?>
                            </select>
                            <button type="submit" class="btn btn-primary">Search Slot</button>
                            <button class="btn btn-primary" data-toggle="modal" data-target="#addScheduleModal">+Add
                                Slot</button>
                        </form>

                        <div class="bulk-action-buttons">
                            <button id="disableSelected" class="btn btn-danger">Disable Selected</button>
                            <button id="enableSelected" class="btn btn-success">Enable Selected</button>
                        </div>

                        <style>
                            .bulk-action-buttons {
                                margin-top: 20px;
                                padding: 15px;
                                background-color: #f9f9f9;
                                border-radius: 8px;
                                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                                display: inline-block;
                            }

                            .bulk-action-buttons button {
                                margin-right: 10px;
                            }
                        </style>

                    </div>

                    <div class="box-body table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr class="text-center">
                                    <th><input type="checkbox" id="checkAll"></th>
                                    <th>SUAST</th>
                                    <th>Exam Date</th>
                                    <th>Exam Time</th>
                                    <th>EXAM VENUE</th>
                                    <th>Room</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $roomFilter = isset($_GET['roomFilter']) ? mysqli_real_escape_string($con, $_GET['roomFilter']) : '';
                                $sql = "SELECT * FROM tbl_exam_schedule";
                                if (!empty($roomFilter)) {
                                    $sql .= " WHERE room = '$roomFilter'";
                                }
                                $query = mysqli_query($con, $sql);

                                while ($row = mysqli_fetch_assoc($query)) {
                                    $status = $row['status'];
                                    $statusLabel = $status === 'active' ? 'Disable' : 'Enable';
                                    $statusBtnClass = $status === 'active' ? 'btn-secondary' : 'btn-success';
                                    $badgeClass = $status === 'active' ? 'badge-success' : 'badge-danger';

                                    echo "<tr class='text-center'>
    <td><input type='checkbox' class='rowCheckbox' value='{$row['id']}'></td>
    <td>{$row['exam_name']}</td>
    <td>{$row['exam_date']}</td>
    <td>{$row['exam_time']}</td>
    <td>{$row['venue']}</td>
    <td>{$row['room']}</td>
    <td><span class='badge $badgeClass'>$status</span></td>
    <td>
        <div class='action-buttons'>
            <button class='btn btn-warning btn-edit' 
                data-id='{$row['id']}'
                data-name='{$row['exam_name']}'
                data-date='{$row['exam_date']}'
                data-time='{$row['exam_time']}'
                data-venue='{$row['venue']}'
                data-room='{$row['room']}'
                data-toggle='modal'
                data-target='#editScheduleModal'
                title='Edit'>
                <i class='fa fa-edit'></i>
            </button>
            <button class='btn btn-danger btn-delete' data-id='{$row['id']}' title='Delete'>
                <i class='fa fa-trash'></i>
            </button>
            <button class='btn $statusBtnClass btn-toggle-status' 
                data-id='{$row['id']}' 
                data-status='$status'
                title='$statusLabel'>
                <i class='fa " . ($status === 'active' ? "fa-ban" : "fa-check") . "'></i>
            </button>
        </div>
    </td>
</tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </aside>

    </div>

    <!-- Add Schedule Modal -->
    <div class="modal fade" id="addScheduleModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="addScheduleForm">
                    <div class="modal-header">
                        <h4 class="modal-title">Add Exam Schedule</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Exam Name</label>
                            <input type="text" name="exam_name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Exam Date</label>
                            <input type="date" id="exam_date" name="exam_date" class="form-control" required>
                            <small class="text-danger" id="date-warning" style="display: none;">This date is not
                                available.</small>
                        </div>
                        <div class="form-group">
                            <label>Exam Time</label>
                            <input type="text" name="exam_time" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>EXAM VENUE</label>
                            <input type="text" name="venue" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Room</label>
                            <input type="text" name="room" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Schedule Modal -->
    <div class="modal fade" id="editScheduleModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="editScheduleForm">
                    <input type="hidden" name="id" id="edit_id">
                    <div class="modal-header">
                        <h4 class="modal-title">Edit Exam Schedule</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Exam Name</label>
                            <input type="text" name="exam_name" id="edit_name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Exam Date</label>
                            <input type="date" name="exam_date" id="edit_date" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Exam Time</label>
                            <input type="text" name="exam_time" id="edit_time" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>EXAM VENUE</label>
                            <input type="text" name="venue" id="edit_subject" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Room</label>
                            <input type="text" name="room" id="edit_room" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php require_once "../../includes/footer.php"; ?>

    <script>
        $(document).ready(function () {
            // Select/Deselect All
            $("#checkAll").click(function () {
                $(".rowCheckbox").prop('checked', this.checked);
            });

            function updateStatus(actionType) {
                let selected = $(".rowCheckbox:checked").map(function () {
                    return this.value;
                }).get();

                if (selected.length === 0) {
                    Swal.fire("No selection", "Please select at least one row.", "warning");
                    return;
                }

                Swal.fire({
                    title: "Are you sure?",
                    text: `You are about to ${actionType === 'bulk_disable' ? 'disable' : 'enable'} ${selected.length} schedule(s).`,
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonText: "Yes, proceed",
                    cancelButtonText: "Cancel"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.post("function.php", {
                            action: actionType,
                            ids: selected
                        }, function (response) {
                            Swal.fire("Success", response, "success").then(() => {
                                location.reload();
                            });
                        }).fail(function (xhr, status, error) {
                            Swal.fire("Error", "AJAX error: " + error, "error");
                        });
                    }
                });
            }

            $("#disableSelected").click(function () {
                updateStatus("bulk_disable");
            });

            $("#enableSelected").click(function () {
                updateStatus("bulk_enable");
            });


            // Toggle Schedule Status
            $(".btn-toggle-status").click(function () {
                const id = $(this).data("id");
                const currentStatus = $(this).data("status");
                const newStatus = currentStatus === "active" ? "disabled" : "active";

                $.post("function.php", {
                    action: "toggle_status",
                    id: id,
                    status: newStatus
                }, function (response) {
                    Swal.fire("Updated!", response, "success").then(() => {
                        location.reload();
                    });
                });
            });

            // Populate edit modal
            $(".btn-edit").click(function () {
                $("#edit_id").val($(this).data("id"));
                $("#edit_name").val($(this).data("name"));
                $("#edit_date").val($(this).data("date"));
                $("#edit_time").val($(this).data("time"));
                $("#edit_subject").val($(this).data("venue"));
                $("#edit_room").val($(this).data("room"));
            });

            // Delete with SweetAlert2
            $(".btn-delete").click(function () {
                let id = $(this).data("id");
                Swal.fire({
                    title: "Are you sure?",
                    text: "This will delete the schedule permanently.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Yes, delete it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.post("function.php", {
                            action: "delete",
                            id: id
                        }, function (response) {
                            Swal.fire("Deleted!", response, "success").then(() => {
                                location.reload();
                            });
                        });
                    }
                });
            });

            // Submit edit form
            $("#editScheduleForm").submit(function (e) {
                e.preventDefault();
                $.post("edit.php", $(this).serialize() + "&action=edit", function (response) {
                    Swal.fire("Updated!", response, "success").then(() => {
                        location.reload();
                    });
                }).fail(function (xhr, status, error) {
                    Swal.fire("Error", "AJAX Error: " + error, "error");
                });
            });

            // Date availability check
            let unavailableDates = [];
            $.ajax({
                url: "fetch_unavailable_dates.php",
                method: "GET",
                dataType: "json",
                success: function (data) {
                    unavailableDates = data;
                }
            });

            $("#exam_date").on("change", function () {
                let selectedDate = $(this).val();
                if (unavailableDates.includes(selectedDate)) {
                    $("#date-warning").show();
                    $(this).val("");
                } else {
                    $("#date-warning").hide();
                }
            });

            // Submit add schedule form
            $("#addScheduleForm").submit(function (e) {
                e.preventDefault();
                $.ajax({
                    type: "POST",
                    url: "add_exam.php",
                    data: $(this).serialize(),
                    dataType: "json",
                    success: function (response) {
                        Swal.fire({
                            icon: response.status,
                            title: response.status === "success" ? "Success!" : "Error",
                            text: response.message,
                            confirmButtonColor: "#3085d6"
                        }).then(() => {
                            if (response.status === "success") {
                                location.reload();
                            }
                        });
                    },
                    error: function (xhr, status, error) {
                        Swal.fire("Error", "AJAX Error: " + error, "error");
                    }
                });
            });

        });
    </script>
</body>

</html>
