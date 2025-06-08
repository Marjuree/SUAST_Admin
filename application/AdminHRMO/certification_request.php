<?php
session_start();
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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

    <style>
    body {
        background: linear-gradient(120deg, #f4f7fa 60%, #e6eaf0 100%);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .content-header p {
        color: #3366ff;
        font-size: 1.2rem;
        font-weight: 600;
        margin-bottom: 18px;
    }

    .box {
        background: #fff;
        border-radius: 18px;
        box-shadow: 0 4px 24px rgba(51, 102, 255, 0.07), 0 1.5px 6px rgba(0, 0, 0, 0.04);
        padding: 32px 24px 24px 24px;
        margin-bottom: 32px;
        border: none;
    }

    .box-title {
        color: #3366ff;
        font-weight: 700;
        letter-spacing: 1px;
        font-size: 1.4rem;
    }

    .table-responsive {
        width: 100%;
        overflow-x: auto;
    }

    .table {
        background: #fff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(51, 102, 255, 0.04);
        margin-bottom: 0;
    }

    .table th,
    .table td {
        text-align: center;
        vertical-align: middle;
        border: none !important;
    }

    .table th {
         background:rgb(34, 34, 34) !important;
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

    .btn-info,
    .btn-primary,
    .btn-success,
    .btn-warning,
    .btn-danger,
    .btn-secondary {
        border-radius: 7px !important;
        font-weight: 500;
        box-shadow: 0 2px 8px rgba(51, 102, 255, 0.07);
        transition: background 0.2s, color 0.2s;
        border: none !important;
    }

    .btn-info {
        background: #17a2b8 !important;
        color: #fff !important;
    }

    .btn-primary {
        background: #3366ff !important;
        color: #fff !important;
    }

    .btn-success {
        background: #28a745 !important;
        color: #fff !important;
    }

    .btn-warning {
        background: #ffc107 !important;
        color: #222 !important;
    }

    .btn-danger {
        background: #cc0000 !important;
        color: #fff !important;
    }

    .btn-secondary {
        background: #6c757d !important;
        color: #fff !important;
    }

    .progress {
        height: 16px;
        border-radius: 8px;
        overflow: hidden;
        background: #e6eaf0;
        margin-bottom: 6px;
    }

    .progress-bar.bg-success {
        background-color: #28a745 !important;
    }

    .progress-bar.bg-warning {
        background-color: #ffc107 !important;
    }

    .progress-bar.bg-light {
        background-color: #e0e6ed !important;
    }

    .modal-content {
        border-radius: 16px;
        box-shadow: 0 2px 16px rgba(0, 0, 0, 0.09);
    }

    .modal-header {
        background: #f4f7fa;
        border-bottom: 1px solid #e6eaf0;
        border-radius: 16px 16px 0 0;
        color: #3366ff;
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

    .file-name {
        display: inline-block;
        max-width: 200px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    @media (max-width: 900px) {
        .box {
            padding: 16px 4px 16px 4px;
        }

        .table th,
        .table td {
            font-size: 0.97rem;
            padding: 8px 2px;
        }
    }

    @media (max-width: 600px) {
        .box {
            padding: 8px 0 8px 0;
        }

        .table th,
        .table td {
            font-size: 0.95rem;
            padding: 6px 2px;
        }

        .btn {
            font-size: 0.93rem !important;
            padding: 5px 8px !important;
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
                                    <h3 class="box-title">Certification Request From Employee</h3>
                                </div>
                                <br>

                                <div class="box-body">
                                    <?php
                        $query = "SELECT * FROM tbl_certification_requests ORDER BY created_at DESC";
                        $result = $con->query($query);

                        if (!$result) {
                            die("Query failed: " . $con->error);
                        }
                        ?>
                                    <h3>Submitted Certification Requests</h3>

                                    <div class="table-responsive">
                                        <table id="certificationTable" class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Date of Request</th>
                                                    <th>Full Name</th>
                                                    <th>Position</th>
                                                    <th>Request Type</th>
                                                    <th>Certification Form</th>
                                                    <th>Status Tracker</th>
                                                    <th>Set Dates</th>
                                                    <th>Location</th>
                                                    <th>Approval</th>
                                                    <th>Completion</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php while ($row = $result->fetch_assoc()):
                                                $stages = [
                                                    'Present Request',
                                                    'Preparing Certification Record',
                                                    'For Releasing',
                                                ];
                                
                                    
                                                        $currentStage = $row['current_stage'];
                                                        $currentStageIndex = array_search($currentStage, $stages);
                                                        ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($row['date_request']) ?></td>
                                                    <td><?= htmlspecialchars($row['name']) ?></td>
                                                    <td><?= htmlspecialchars($row['faculty']) ?></td>
                                                    <td><?= htmlspecialchars($row['request_type']) ?></td>

                                                    <td>
                                                        <?php if (!empty($row['certification_file'])): ?>
                                                        <a href="download_certification_file.php?id=<?= htmlspecialchars($row['id']) ?>"
                                                            class="btn btn-sm btn-info" download>
                                                            <i class="fa fa-download"></i>
                                                            <?= htmlspecialchars($row['file_name']) ?>
                                                        </a>
                                                        <?php else: ?>
                                                        No File
                                                        <?php endif; ?>
                                                    </td>



                                                    <td>
                                                        <div class="progress" style="height: 20px;">
                                                            <?php 
                                                                   $stages = [
                                                                    'Present Request',
                                                                    'Preparing Certification Record',
                                                                    'For Releasing',
                                                                ];

                                                                // Get the current stage index
                                                                $currentStageIndex = array_search($currentStage, $stages);

                                                                // Calculate the progress width for each stage based on the number of stages
                                                                $progressWidth = (100 / count($stages));

                                                                // Loop through each stage and create the progress bars
                                                                foreach ($stages as $index => $label):
                                                                
                                                                    $progressClass = '';
                                                                    if ($index < $currentStageIndex) {
                                                                        $progressClass = 'bg-success'; // Completed
                                                                    } elseif ($index == $currentStageIndex) {
                                                                        $progressClass = 'bg-warning'; // Current stage
                                                                    } else {
                                                                        $progressClass = 'bg-light'; // Pending stage
                                                                    }
                                                                ?>
                                                            <div class="progress-bar <?= $progressClass ?>"
                                                                style="width: <?= $progressWidth ?>%;"
                                                                title="<?= $label ?>">
                                                            </div>
                                                            <?php endforeach; ?>
                                                        </div>
                                                        <small><strong><?= $currentStage ?></strong></small>
                                                    </td>


                                                    <td>
                                                        <button class="btn btn-secondary btn-sm" data-toggle="modal"
                                                            data-target="#setCertificationModal"
                                                            data-request-id="<?= $row['id'] ?>"
                                                            data-current-stage="<?= $currentStage ?>"
                                                            <?php foreach ($row as $key => $value): ?>
                                                            data-<?= str_replace('_', '-', $key) ?>="<?= htmlspecialchars($value) ?>"
                                                            <?php endforeach; ?>>Set Dates</button>
                                                    </td>

                                                    <td>
                                                        <form method="POST" action="certification_stage.php">
                                                            <input type="hidden" name="request_id"
                                                                value="<?= $row['id'] ?>">
                                                            <select name="current_stage" class="form-control mb-1">
                                                                <?php foreach ($stages as $stageOption): ?>
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

                                                    <td>
                                                        <form method="POST" action="cert_status.php">
                                                            <input type="hidden" name="request_id" value="<?= $row['id'] ?>">
                                                            <input type="hidden" name="request_status"
                                                                value="<?= empty($row['request_status']) ? 'Pending' : ($row['request_status'] === 'Approved' ? 'Disapproved' : 'Approved') ?>">

                                                            <button type="submit"
                                                                class="btn btn-sm <?= empty($row['request_status']) ? 'btn-info' : ($row['request_status'] === 'Approved' ? 'btn-success' : 'btn-danger') ?>"
                                                                title="<?= empty($row['request_status']) ? 'Pending' : ($row['request_status'] === 'Approved' ? 'Approved' : 'Disapproved') ?>">
                                                                <?php if (empty($row['request_status'])): ?>
                                                                    <i class="fa fa-clock-o"></i>
                                                                <?php elseif ($row['request_status'] === 'Approved'): ?>
                                                                    <i class="fa fa-check"></i>
                                                                <?php else: ?>
                                                                    <i class="fa fa-times"></i>
                                                                <?php endif; ?>
                                                            </button>
                                                        </form>
                                                    </td>



                                                    <td>
                                                        <form method="POST" action="update_completion_status.php">
                                                            <input type="hidden" name="request_id"
                                                                value="<?= $row['id'] ?>">
                                                            <input type="hidden" name="completion_status"
                                                                value="<?= $row['completion_status'] === 'done' ? 'pending' : 'done' ?>">
                                                            <button type="submit"
                                                                class="btn btn-sm <?= $row['completion_status'] === 'done' ? 'btn-success' : 'btn-warning' ?>">
                                                                <?= ucfirst($row['completion_status']) ?>
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
    <div class="modal fade" id="setCertificationModal" tabindex="-1" role="dialog"
        aria-labelledby="setCertificationModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Set Certification Request Details</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="modalRequestId">
                    <input type="hidden" id="modalFacultyType">

                    <div class="form-group">
                        <label for="currentStageSelect">Select Stage</label>
                        <select id="currentStageSelect" class="form-control">
                            <?php foreach ($stages as $stage): ?>
                            <option value="<?= $stage ?>"><?= $stage ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div id="dynamicFields"></div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-default" data-dismiss="modal">Close</button>
                    <button class="btn btn-primary" id="saveCertificationDetails">Save Changes</button>
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

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

    <script>
    $(document).ready(function() {
        $('#certificationTable').DataTable({
            "dom": 'lfrtip',
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

        $('#setCertificationModal').on('show.bs.modal', function(event) {
            const button = $(event.relatedTarget);
            const requestId = button.data('request-id');
            const currentStage = button.data('current-stage');
            $('#modalRequestId').val(requestId);

            const stageSelect = $('#currentStageSelect');
            stageSelect.val(currentStage);
            const fieldsMap = {

                'Present Request': ['present_request_submitted', 'present_request_received'],
                'Preparing Certification Record': ['prepare_service_record_submitted',
                    'prepare_service_record_received'
                ],

                'For Releasing': ['for_releasing_submitted', 'for_releasing_received'],
            };

            let fields = fieldsMap[currentStage] || [];
            let dynamicFieldsHtml = '';

            fields.forEach(field => {
                let label = field.replace(/_/g, ' ').replace(/\b\w/g, l => l
                    .toUpperCase());
                let value = button.data(field.replace(/_/g, '-'));
                dynamicFieldsHtml += `
                    <div class="form-group">
                        <label for="${field}">${label}</label>
                        <input type="date" class="form-control date-field" id="${field}" name="${field}" value="${value || ''}">
                    </div>`;
            });

            $('#dynamicFields').html(dynamicFieldsHtml);
        });

        $('#saveCertificationDetails').click(function() {
            const requestId = $('#modalRequestId').val();
            const stage = $('#currentStageSelect').val();

            const dateInputs = $('#dynamicFields .date-field');
            let requestsMade = 0;

            dateInputs.each(function() {
                const fieldId = $(this).attr(
                    'id'); // Example: "cashier_submitted" or "logbook_received"
                const newDate = $(this).val();

                if (newDate) {
                    const lastUnderscore = fieldId.lastIndexOf('_');
                    let stageName = fieldId.substring(0,
                        lastUnderscore); // stage = everything before last _
                    let type = fieldId.substring(lastUnderscore +
                        1); // type = everything after last _

                    requestsMade++;

                    $.post('cert_date.php', {
                        request_id: requestId,
                        new_date: newDate,
                        stage: stageName,
                        type: type
                    }, function(response) {
                        if (response === 'success') {
                            if (--requestsMade === 0) {
                                Swal.fire('Updated!',
                                        'Certification request updated successfully.',
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

    });
    </script>





</body>

</html>
