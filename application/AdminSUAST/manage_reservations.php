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
    <title>Exam Reservation</title>
    <link rel="stylesheet" href="../../css/exam_schedule.css">
    <link rel="shortcut icon" href="../../img/favicon.png" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Ensure jQuery is loaded -->
</head>
<style>
    /* Default: Hide header */
    .school-header {
        display: none;
        text-align: center;
        margin-bottom: 20px;
    }

    .school-header img {
        width: 120px;
        height: 120px;
    }

    .school-header h2 {
        color: #002f6c;
        font-size: 24px;
        font-weight: bold;
        margin: 5px 0;
    }

    .school-header h4 {
        color: #333;
        font-size: 14px;
        font-style: italic;
        margin: 5px 0;
    }

    .line-break {
        width: 100%;
        height: 3px;
        background: #002f6c;
        margin: 10px 0;
    }

    .header-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    @media print {
        body * {
            visibility: hidden;
        }

        #printSection,
        #printSection * {
            visibility: visible;
        }

        #printSection {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            text-align: center;
        }

        .school-header {
            display: flex !important;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
        }

        .header-left {
            text-align: left;
        }

        .header-right img {
            width: 120px;
            height: 120px;
        }

        /* Hide the Action column */
        #printSection th:last-child,
        #printSection td:last-child {
            display: none;
        }
    }
    
</style>

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
                <h1>Reservation List</h1>
            </section>

            <section class="content">
                <div class="box">
                    <div class="box-header d-flex justify-content-between align-items-center">
                        <form action="" method="GET">
                            <label for="nameFilter">Find by Name:</label>
                            <input type="text" name="nameFilter" id="nameFilter" class="form-control"
                                style="width: 200px; display: inline-block;" placeholder="Enter Name"
                                value="<?php echo isset($_GET['nameFilter']) ? htmlspecialchars($_GET['nameFilter']) : ''; ?>">

                            <label for="roomFilter">Find by Room:</label>
                            <select name="roomFilter" id="roomFilter" class="form-control"
                                style="width: 200px; display: inline-block;">
                                <option value="">All Rooms</option>
                                <?php
                                $roomQuery = mysqli_query($con, "SELECT DISTINCT room FROM tbl_reservation ORDER BY room ASC");
                                while ($roomRow = mysqli_fetch_assoc($roomQuery)) {
                                    $selected = (isset($_GET['roomFilter']) && $_GET['roomFilter'] == $roomRow['room']) ? "selected" : "";
                                    echo "<option value='{$roomRow['room']}' $selected>{$roomRow['room']}</option>";
                                }
                                ?>
                            </select>
                            <button type="submit" class="btn btn-primary">Search</button>
                            <!-- <button class="btn btn-success" onclick="printReservations()"><i
                                    class="fa fa-chart-bar"></i> Print Reports</button> -->
                        </form>
                        <!-- Room Disable/Enable Section -->
                        <div class="mb-3" style="margin-bottom: 15px; margin-top: 30px;">
                            <label for="roomStatusSelect"><strong>Room:</strong></label>
                            <select id="roomStatusSelect" class="form-control"
                                style="width: 200px; display: inline-block; margin: 0 10px;">
                                <option value="">Select Room</option>
                                <?php
                                $roomQuery2 = mysqli_query($con, "SELECT DISTINCT room FROM tbl_reservation ORDER BY room ASC");
                                while ($roomRow2 = mysqli_fetch_assoc($roomQuery2)) {
                                    echo "<option value='{$roomRow2['room']}'>{$roomRow2['room']}</option>";
                                }
                                ?>
                            </select>
                            <button type="button" class="btn btn-danger" id="disableRoomBtn">Disable Room</button>
                            <button type="button" class="btn btn-success" id="enableRoomBtn">Enable Room</button>
                        </div>

                        <!-- DELETE SELECTED BUTTON -->
                        <div class="mb-3">
                            <button type="button" id="deleteSelectedBtn" class="btn btn-danger">Delete Selected</button>
                        </div>
                    </div>



                    <?php
                    if (isset($_GET['msg'])) {
                        echo "<div class='alert alert-success text-center'>{$_GET['msg']}</div>";
                    }
                    ?>

                    <div class="box-body table-responsive">
                        <div id="printSection">
                            <div class="school-header">
                                <div class="header-left">
                                    <h4><strong>Republic of the Philippines</strong></h4>
                                    <div class="line-break"></div>
                                    <h2>DAVAO ORIENTAL STATE UNIVERSITY</h2>
                                    <h4>A university of excellence, innovation, and inclusion</h4>
                                </div>
                                <div class="header-right">
                                    <img src="images/ken.png" alt="School Logo">
                                </div>
                            </div>

                            <div class="line-break"></div>
                            <h3><u>EXAM RESERVATION LIST</u></h3>

                            <table id="examTable" class="table table-bordered table-striped">
                                <thead class="thead-dark">
                                    <tr class="text-center">
                                        <!-- Checkbox column -->
                                        <th><input type="checkbox" id="selectAll"></th>
                                        <th>Full Name</th>
                                        <th>Exam Date</th>
                                        <th>Exam Time</th>
                                        <th>Room</th>
                                        <th>Venue</th>
                                        <th>Status</th>
                                        <th>User Update</th>
                                        <th>Details</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $query = "SELECT * FROM tbl_reservation WHERE 1";
                                    if (isset($_GET['nameFilter']) && !empty($_GET['nameFilter'])) {
                                        $nameFilter = mysqli_real_escape_string($con, $_GET['nameFilter']);
                                        $query .= " AND name LIKE '%$nameFilter%'";
                                    }
                                    if (isset($_GET['roomFilter']) && !empty($_GET['roomFilter'])) {
                                        $roomFilter = mysqli_real_escape_string($con, $_GET['roomFilter']);
                                        $query .= " AND room = '$roomFilter'";
                                    }
                                    $query .= " ORDER BY exam_time ASC";

                                    $result = mysqli_query($con, $query);
                                    if ($result && mysqli_num_rows($result) > 0) {
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            $userUpdateLabel = $row['user_requested_update'] == 1 ? 'Updated' : 'Pending';
                                            $userUpdateClass = $row['user_requested_update'] == 1 ? 'label label-success' : 'label label-warning';

                                            echo "<tr>
                                                <td class='text-center'>
                                                    <input type='checkbox' class='selectItem' value='{$row['id']}'>
                                                </td>
                                                <td>" . htmlspecialchars($row['name'] ?? '') . "</td>
                                                <td>" . htmlspecialchars($row['exam_date'] ?? '') . "</td>
                                                <td>" . htmlspecialchars($row['exam_time'] ?? '') . "</td>
                                                <td>" . htmlspecialchars($row['room']) . "</td>
                                                <td>" . htmlspecialchars($row['venue']) . "</td>
                                                <td class='text-center'>
                                                    <form action='update_status.php' method='POST' style='display:inline-block; margin-bottom: 5px;'>
                                                        <input type='hidden' name='id' value='{$row['id']}'>
                                                        <input type='hidden' name='status' value='approved'>
                                                        <button type='submit' class='btn btn-success btn-sm'>Approve</button>
                                                    </form>
                                                    <button type='button' class='btn btn-warning btn-sm' onclick='openRejectModal({$row['id']})'>Reject</button>
                                                    <div style='margin-top:5px;'><small><strong>Current:</strong> " . htmlspecialchars($row['status'] ?? '') . "</small></div>
                                                </td>
                                                <td class='text-center'>
                                                    <span class='$userUpdateClass'>$userUpdateLabel</span>
                                                </td>
                                                <td class='text-center'>
                                                    <a href='view_applicant.php?id={$row['applicant_id']}' class='btn btn-info btn-sm'>View</a>
                                                </td>
                                                <td class='text-center'>
                                                    <button class='btn btn-danger btn-sm' onclick='deleteReservation({$row['id']})'>Delete</button>
                                                </td>
                                            </tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='10' class='text-center'>No reservations found</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php require_once "../../includes/footer.php"; ?>
                </div>
            </section>
        </aside>
    </div>

    <!-- Reject Reason Modal (kept unchanged) -->
    <div class="modal fade" id="rejectReasonModal" tabindex="-1" role="dialog" aria-labelledby="rejectReasonModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="rejectReasonForm" method="POST" action="update_status.php">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="rejectReasonModalLabel">Reject Reservation</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="rejectReservationId">
                        <input type="hidden" name="status" value="rejected">
                        <div class="form-group">
                            <label for="rejectReason">Reason for Rejection</label>
                            <textarea class="form-control" name="reason" id="rejectReason" rows="4" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-warning">Submit Rejection</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>


        // Select/Deselect All checkboxes
        $('#selectAll').click(function () {
            $('.selectItem').prop('checked', this.checked);
        });

        // Delete Selected Reservations with SweetAlert2
        $('#deleteSelectedBtn').click(function () {
            var selected = $('.selectItem:checked').map(function () {
                return this.value;
            }).get();

            if (selected.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'No selection',
                    text: 'Please select at least one reservation to delete.'
                });
                return;
            }

            Swal.fire({
                title: 'Are you sure?',
                text: `You are about to delete ${selected.length} reservation(s). This action cannot be undone.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Yes, delete!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'ajax_delete_reservation.php',
                        type: 'POST',
                        data: { ids: selected },
                        success: function (response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: response
                            }).then(() => location.reload());
                        },
                        error: function () {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'An error occurred while deleting reservations.'
                            });
                        }
                    });
                }
            });
        });

        // Delete single reservation with SweetAlert2
        function deleteReservation(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You are about to delete this reservation. This action cannot be undone.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Yes, delete!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'ajax_delete_reservation.php',
                        type: 'POST',
                        data: { ids: [id] },
                        success: function (response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: response
                            }).then(() => location.reload());
                        },
                        error: function () {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'Error deleting reservation.'
                            });
                        }
                    });
                }
            });
        }
    </script>

    <script>
        function openRejectModal(id) {
            $('#rejectReservationId').val(id);
            $('#rejectReason').val('');
            $('#rejectReasonModal').modal('show');
        }

        function printReservations() {
            window.print();
        }

        // Open Modal and Fill Data Dynamically
        $('#setScheduleModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var reservationId = button.data('id');

            // Set the modal fields with the selected reservation's data
            $('#reservationId').val(reservationId);

            // Fetch available exam times from the server
            $.ajax({
                url: 'get_exam_times.php',
                type: 'GET',
                success: function (response) {
                    var examTimes = JSON.parse(response);
                    $('#examTime').empty();
                    examTimes.forEach(function (examTime) {
                        $('#examTime').append('<option value="' + examTime + '">' + examTime +
                            '</option>');
                    });
                }
            });
        });

        // Handle the Form Submission via AJAX
        $(document).ready(function () {
            $('#setScheduleForm').on('submit', function (e) {
                e.preventDefault();

                var formData = $(this).serialize();

                $.ajax({
                    url: 'set_schedule.php',
                    type: 'POST',
                    data: formData,
                    success: function (response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: response,
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function () {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Error updating the schedule.',
                        });
                    }
                });
            });
        });

        // Disable/Enable Room buttons functionality
        $(document).ready(function () {
            $('#disableRoomBtn').click(function () {
                var selectedRoom = $('#roomStatusSelect').val();
                if (!selectedRoom) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'No room selected',
                        text: 'Please select a room to disable.'
                    });
                    return;
                }

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You are about to disable the selected room. This action cannot be undone.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Yes, disable!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: 'ajax_update_room_status.php',
                            type: 'POST',
                            data: {
                                room: selectedRoom,
                                status: 'disabled'
                            },
                            success: function (response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Room disabled',
                                    text: response
                                }).then(() => location.reload());
                            },
                            error: function () {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: 'Error disabling the room.'
                                });
                            }
                        });
                    }
                });
            });

            $('#enableRoomBtn').click(function () {
                var selectedRoom = $('#roomStatusSelect').val();
                if (!selectedRoom) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'No room selected',
                        text: 'Please select a room to enable.'
                    });
                    return;
                }

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You are about to enable the selected room.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    confirmButtonText: 'Yes, enable!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: 'ajax_update_room_status.php',
                            type: 'POST',
                            data: {
                                room: selectedRoom,
                                status: 'enabled'
                            },
                            success: function (response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Room enabled',
                                    text: response
                                }).then(() => location.reload());
                            },
                            error: function () {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: 'Error enabling the room.'
                                });
                            }
                        });
                    }
                });
            });
        });

        $('#disableRoomBtn, #enableRoomBtn').click(function () {
            var room = $('#roomStatusSelect').val();
            if (!room) {
                Swal.fire('Please select a room.');
                return;
            }
            var action = $(this).attr('id') === 'disableRoomBtn' ? 'disable' : 'enable';
            var confirmText = action === 'disable'
                ? 'You are about to disable all reservations for this room. They will not be shown in lists.'
                : 'You are about to enable all reservations for this room. They will be shown in lists.';
            Swal.fire({
                title: 'Are you sure?',
                text: confirmText,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, proceed!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'ajax_toggle_room_status.php',
                        type: 'POST',
                        data: { room: room, action: action },
                        success: function (response) {
                            Swal.fire('Success', response, 'success').then(() => location.reload());
                        },
                        error: function () {
                            Swal.fire('Error', 'Could not update room status.', 'error');
                        }
                    });
                }
            });
        });
    </script>
</body>

</html
