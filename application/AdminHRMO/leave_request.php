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

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../../vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="../../vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="shortcut icon" href="../../img/favicon.png" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">


    <style>
    .table th {
        text-align: center;
        background-color: #343A40;
        color: white;
        vertical-align: middle;
    }

    .progress-bar.bg-light {
        background-color: #e0e0e0 !important;
    }

    .progress-bar {
        transition: none !important;
    }

    .modal-header {
        background-color: #343A40;
        color: white;
    }

    /* Style for the date input field */
    #submitDateInput,
    #receiveDateInput {
        font-size: 1rem;
        padding: 10px;
        border-radius: 5px;
        border: 1px solid #ccc;
        width: 100%;
        margin-bottom: 15px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        transition: border-color 0.3s ease;
    }

    #submitDateInput:focus,
    #receiveDateInput:focus {
        border-color: #5cb85c;
        box-shadow: 0 0 8px rgba(92, 184, 92, 0.5);
    }

    /* Style for the modal buttons */
    .modal-footer .btn {
        font-size: 1rem;
        padding: 10px 20px;
        border-radius: 5px;
        transition: background-color 0.3s ease, transform 0.2s ease;
    }

    /* Button styles for the 'Save' buttons */
    #submitDateSave,
    #receiveDateSave {
        background-color: #5cb85c;
        color: white;
        border: none;
    }

    #submitDateSave:hover,
    #receiveDateSave:hover {
        background-color: #4cae4c;
        transform: translateY(-2px);
    }

    /* Style for the 'Close' button */
    .modal-footer .btn-secondary {
        background-color: #d9534f;
        color: white;
        border: none;
    }

    .modal-footer .btn-secondary:hover {
        background-color: #c9302c;
        transform: translateY(-2px);
    }

    /* Add some padding to the modal body for better spacing */
    .modal-body {
        padding: 20px;
    }

    /* Enhance the progress bar appearance */
    .progress {
        height: 10px;
        border-radius: 5px;
        overflow: hidden;
    }

    .progress-bar {
        transition: none;
        height: 100%;
        border-radius: 5px;
    }

    /* Style for the table buttons to make them more intuitive */
    .btn-secondary {
        font-size: 1rem;
        padding: 8px 15px;
        border-radius: 5px;
        transition: background-color 0.3s ease, transform 0.2s ease;
    }

    .btn-secondary:hover {
        background-color: #5bc0de;
        transform: translateY(-2px);
    }

    .table thead th {
        background-color: #343a40 !important;
        color: #fff;
        text-align: center;
    }

    .table tbody tr td {
        text-align: center;
    }
    </style>
</head>

<body class="skin-blue">
    <?php 
    require_once "../../configuration/config.php";
    require_once('../../includes/header.php');
    require_once('../../includes/head_css.php'); 
    ?>

    <!-- Keep your HTML <head> and includes above this line -->

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
                                    <h3 class="box-title">Leave Request From Employee</h3>
                                </div>
                                <br>

                                <div class="box-body">
                                    <?php
                                $query = "SELECT * FROM tbl_leave_requests ORDER BY created_at DESC";
                                $result = $con->query($query);

                                if (!$result) {
                                    die("Query failed: " . $con->error);
                                }
                                ?>
                                    <h3>Submitted Leave Requests</h3>

                                    <div class="table-responsive">
                                        <table id="leaveTable" class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Date of Request</th>
                                                    <th>Full Name</th>
                                                    <th>Faculty/Institute</th>
                                                    <th>Request Type</th>
                                                    <th>Leave Date</th>
                                                    <th>End of Leave</th>
                                                    <th>Leave Form</th>
                                                    <th>Status Tracker</th>
                                                    <th>Set Dates</th>
                                                    <th>Location</th>
                                                    <th>Approval</th>
                                                    <th>Completion</th>


                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php while ($row = $result->fetch_assoc()):
                                                    $facultyVal = strtolower($row['faculty']);
                                                    $stages = ($facultyVal === 'faculty') ?
                                                        ['HR', 'VP ACAD', 'HR Received', 'For Releasing'] :
                                                        ['HR', 'VP Finance', 'HR Received', 'For Releasing'];

                                                    $currentStage = $row['current_stage'];
                                                    $currentStageIndex = array_search($currentStage, $stages);
                                                ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($row['date_request']) ?></td>
                                                    <td><?= htmlspecialchars($row['name']) ?></td>
                                                    <td><?= htmlspecialchars($row['faculty']) ?></td>
                                                    <td><?= htmlspecialchars($row['request_type']) ?></td>
                                                    <td><?= htmlspecialchars($row['date_request']) ?></td>
                                                    <td><?= htmlspecialchars($row['leave_end_date']) ?></td>

                                                    <!-- File Download -->
                                                    <td>
                                                        <?php if (!empty($row['leave_form'])): ?>
                                                        <a href="download_leave_form.php?id=<?= htmlspecialchars($row['id']) ?>"
                                                            class="btn btn-sm btn-info" download>
                                                            <i class="fa fa-download"></i>
                                                            <?= htmlspecialchars($row['file_name']) ?>
                                                        </a>
                                                        <?php else: ?>
                                                        No File
                                                        <?php endif; ?>
                                                    </td>


                                                    <!-- Progress Bar -->
                                                    <td>
                                                        <div class="progress" style="height: 20px;">
                                                            <?php foreach ($stages as $index => $label): 
                                                                $progressClass = ($index <= $currentStageIndex) ? 'bg-success' : 'bg-light'; ?>
                                                            <div class="progress-bar <?= $progressClass ?>"
                                                                style="width: 25%;" title="<?= $label ?>"></div>
                                                            <?php endforeach; ?>
                                                        </div>
                                                        <small><strong><?= $currentStage ?></strong></small>
                                                    </td>

                                                    <!-- Set Dates Button -->
                                                    <td>
                                                        <button class="btn btn-secondary btn-sm" data-toggle="modal"
                                                            data-target="#setLeaveModal"
                                                            data-request-id="<?= $row['id'] ?>"
                                                            data-current-stage="<?= $currentStage ?>"
                                                            <?php foreach ($row as $key => $value): ?>
                                                            data-<?= str_replace('_', '-', $key) ?>="<?= htmlspecialchars($value) ?>"
                                                            <?php endforeach; ?>>
                                                            Set Dates
                                                        </button>
                                                    </td>

                                                    <!-- Set Stage Form -->
                                                    <td>
                                                        <form method="POST" action="update_stage.php">
                                                            <input type="hidden" name="request_id"
                                                                value="<?= $row['id'] ?>">
                                                            <select name="current_stage" class="form-control mb-1">
                                                                <?php 
                                                                // Add "For Releasing" to both sets of stages
                                                                $stageOptions = ($facultyVal === 'faculty') ? 
                                                                    ['Select Option', 'HR', 'VP ACAD', 'HR Received', 'For Releasing'] : 
                                                                    ['Select Option', 'HR', 'VP Finance', 'HR Received', 'For Releasing'];

                                                                foreach ($stageOptions as $stageOption): ?>
                                                                <option value="<?= $stageOption ?>"
                                                                    <?= $stageOption === $currentStage ? 'selected' : '' ?>>
                                                                    <?= $stageOption ?>
                                                                </option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                            <button type="submit" class="btn btn-primary btn-sm">Set
                                                                Stage</button>
                                                        </form>
                                                    </td>

                                                    <!-- Approval Status Form -->
                                                    <td>
                                                        <form method="POST" action="update_status.php">
                                                            <input type="hidden" name="request_id"
                                                                value="<?= $row['id'] ?>">
                                                            <input type="hidden" name="approval_status"
                                                                value="<?= empty($row['approval_status']) ? 'Pending' : ($row['approval_status'] === 'Approved' ? 'Disapproved' : 'Approved') ?>">

                                                            <button type="submit"
                                                                class="btn btn-sm 
                                                               <?= empty($row['approval_status']) ? 'btn-warning' : ($row['approval_status'] === 'Approved' ? 'btn-success' : 'btn-danger') ?>">
                                                                <?= empty($row['approval_status']) ? 'Pending' : ucfirst($row['approval_status']) ?>
                                                            </button>
                                                        </form>
                                                    </td>

                                                    <!-- Completion Status Form -->
                                                    <td>
                                                        <form method="POST" action="update_completion_status.php">
                                                            <input type="hidden" name="request_id"
                                                                value="<?= $row['id'] ?>">
                                                            <input type="hidden" name="status"
                                                                value="<?= $row['status'] === 'done' ? 'pending' : 'done' ?>">
                                                            <button type="submit"
                                                                class="btn btn-sm 
                                                                 <?= $row['status'] === 'done' ? 'btn-success' : 'btn-warning' ?>">
                                                                <?= ucfirst($row['status']) ?>
                                                            </button>
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

    <!-- Modal -->
    <div class="modal fade" id="setLeaveModal" tabindex="-1" role="dialog" aria-labelledby="setLeaveModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Set Leave Request Details</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="modalRequestId">
                    <input type="hidden" id="modalFacultyType"> <!-- ✅ ADDED THIS LINE -->

                    <div class="form-group">
                        <label for="currentStageSelect">Select Stage</label>
                        <select id="currentStageSelect" class="form-control">
                            <option value="HR">HR</option>
                            <option value="VP ACAD">VP ACAD</option>
                            <option value="VP Finance">VP Finance</option>
                            <option value="HR Received">HR Received</option>
                            <option value="For Releasing">For Releasing</option>
                            <option value="Completed">Completed</option>
                        </select>
                    </div>
                    <div id="dynamicFields"></div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-default" data-dismiss="modal">Close</button>
                    <button class="btn btn-primary" id="saveLeaveDetails">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    <?php require_once "../../includes/footer.php"; ?>
    <script src="../../vendors/js/vendor.bundle.base.js"></script>
    <script src="../../js/off-canvas.js"></script>
    <script src="../../js/hoverable-collapse.js"></script>
    <script src="../../vendors/chart.js/Chart.min.js"></script>
    <script src="../../js/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <!-- ...footer and vendor scripts -->
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>


    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

    <script>
    $(document).ready(function() {
        $('.approve-btn').click(function() {
            const requestId = $(this).data('request-id');

            Swal.fire({
                title: 'Are you sure?',
                text: 'You are about to approve this leave request.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, approve it!',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#28a745'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href =
                        `update_status.php?request_id=${requestId}&approve=1`;
                }
            });
        });

        $('.reject-btn').click(function() {
            const requestId = $(this).data('request-id');

            Swal.fire({
                title: 'Are you sure?',
                text: 'You are about to reject this leave request.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, reject it!',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#d33'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href =
                        `update_status.php?request_id=${requestId}&disapprove=1`;
                }
            });
        });
    });
    $('#leaveTable').DataTable({
        "dom": 'lfrtip', // l = length menu, f = filter (search bar), r = processing, t = table, i = info, p = pagination
        "lengthMenu": [
            [5, 10, 25, -1],
            [5, 10, 25, "All"]
        ],
        "pageLength": 10,
        "order": [
            [0, "desc"]
        ],
        "language": {
            "search": "Search records:"
        }
    });
    $('#setLeaveModal').on('show.bs.modal', function(event) {
        const button = $(event.relatedTarget);
        const requestId = button.data('request-id');
        const currentStage = button.data('current-stage');
        const facultyType = button.data('faculty'); // ✅ GET Faculty type

        $('#modalRequestId').val(requestId);
        $('#modalFacultyType').val(facultyType); // ✅ Store in hidden field

        let stageOptions = (facultyType && facultyType.toLowerCase() === 'faculty') ? ['HR', 'VP ACAD',
            'HR Received', 'For Releasing', 'Completed'
        ] : ['HR', 'VP Finance', 'HR Received', 'For Releasing', 'Completed'];

        let stageSelect = $('#currentStageSelect');
        stageSelect.empty();

        stageOptions.forEach(stage => {
            stageSelect.append(new Option(stage, stage));
        });

        stageSelect.val(currentStage);

        const fieldsMap = {
            'HR': ['hr_submitted', 'hr_received'],
            'VP ACAD': ['vp_acad_submitted', 'vp_acad_received'],
            'VP Finance': ['vp_finance_submitted', 'vp_finance_received'],
            'HR Received': ['hr_received_submitted', 'hr_received_received'],
            'For Releasing': ['for_releasing_submitted', 'for_releasing_received'],
            'Completed': ['completed_date']
        };

        let fields = fieldsMap[currentStage] || [];
        let dynamicFieldsHtml = '';

        fields.forEach(field => {
            let label = field.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
            let value = button.data(field.replace(/_/g, '-'));
            dynamicFieldsHtml += `
                <div class="form-group">
                    <label for="${field}">${label}</label>
                    <input type="date" class="form-control date-field" id="${field}" name="${field}" value="${value || ''}">
                </div>`;
        });

        $('#dynamicFields').html(dynamicFieldsHtml);
    });

    $('#saveLeaveDetails').click(function() {
        const requestId = $('#modalRequestId').val();
        const stage = $('#currentStageSelect').val();
        const facultyType = $('#modalFacultyType').val();

        const dateInputs = $('#dynamicFields .date-field');
        let requestsMade = 0;

        dateInputs.each(function() {
            const fieldId = $(this).attr('id');
            const newDate = $(this).val();

            if (newDate) {
                let stageName = '';
                let type = '';

                if (fieldId === 'completed_date') {
                    stageName = 'completed';
                    type = '';
                } else {
                    const parts = fieldId.split('_');
                    type = parts.pop(); // last part
                    stageName = parts.join('_'); // rest
                }

                requestsMade++;

                $.post('update_date.php', {
                    request_id: requestId,
                    new_date: newDate,
                    stage: stageName,
                    type: type,
                    faculty: facultyType
                }, function(response) {
                    if (response === 'success') {
                        if (--requestsMade === 0) {
                            Swal.fire('Updated!', 'Leave request updated successfully.',
                                    'success')
                                .then(() => location.reload());
                        }
                    } else {
                        Swal.fire('Error', response, 'error');
                    }
                });
            }
        });

    });
    </script>



</body>

</html>
