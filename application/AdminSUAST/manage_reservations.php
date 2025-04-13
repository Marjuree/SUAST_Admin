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
    <link rel="stylesheet" href="../../css/exam_schedule.css">
    <link rel="shortcut icon" href="../../img/favicon.png" />
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
                            <!-- Changed POST to GET for filtering -->
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
                            <button class="btn btn-success" onclick="printReservations()"><i
                                    class="fa fa-chart-bar"></i> Print Reports</button>
                        </form>
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
                                        <th>Full Name</th>
                                        <th>Exam Date</th>
                                        <th>Exam Time</th>
                                        <th>Room</th>
                                        <th>Venue</th>
                                        <th>Status</th>
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
                                            echo "<tr>
                                                    <td>" . htmlspecialchars($row['name']) . "</td>
                                                    <td>" . htmlspecialchars($row['exam_date']) . "</td>
                                                    <td>" . htmlspecialchars($row['exam_time']) . "</td>
                                                    <td>" . htmlspecialchars($row['room']) . "</td>
                                                    <td>" . htmlspecialchars($row['venue']) . "</td>
                                                    <td class='text-center'>
                                                        <form action='update_status.php' method='POST' style='display:inline-block; margin-bottom: 5px;'>
                                                            <input type='hidden' name='id' value='{$row['id']}'>
                                                            <input type='hidden' name='status' value='approved'>
                                                            <button type='submit' class='btn btn-success btn-sm'>Approve</button>
                                                        </form>
                                                        <form action='update_status.php' method='POST' style='display:inline-block;'>
                                                            <input type='hidden' name='id' value='{$row['id']}'>
                                                            <input type='hidden' name='status' value='rejected'>
                                                            <button type='submit' class='btn btn-warning btn-sm'>Reject</button>
                                                        </form>
                                                        <div style='margin-top:5px;'><small><strong>Current:</strong> " . htmlspecialchars($row['status']) . "</small></div>
                                                    </td>
                                                    <td>
                                                        <button type='button' class='btn btn-primary btn-sm' data-toggle='modal' data-target='#setScheduleModal' data-id='{$row['id']}'>Set Schedule</button>
                                                    </td>
                                                  </tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='6' class='text-center'>No reservations found</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Modal -->
                    <div class="modal fade" id="setScheduleModal" tabindex="-1" role="dialog"
                        aria-labelledby="setScheduleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="setScheduleModalLabel">Set Exam Schedule</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form id="setScheduleForm">
                                        <input type="hidden" name="id" id="reservationId">

                                        <!-- Exam Date Dropdown -->
                                        <div class="form-group">
                                            <label for="examDate">Select Exam Date:</label>
                                            <select class="form-control" name="examDate" id="examDate" required>
                                                <option value="">Select Exam Date</option>
                                                <?php
                            // Populate exam dates from tbl_exam_schedule
                            $result_dates = mysqli_query($con, "SELECT DISTINCT exam_date FROM tbl_exam_schedule WHERE exam_date IS NOT NULL ORDER BY exam_date ASC");
                            while ($row = mysqli_fetch_assoc($result_dates)) {
                                $formatted = date('F j, Y', strtotime($row['exam_date']));
                                echo "<option value='{$row['exam_date']}'>{$formatted}</option>";
                            }
                            ?>
                                            </select>
                                        </div>

                                        <!-- Exam Time Dropdown -->
                                        <div class="form-group">
                                            <label for="examTime">Select Exam Time:</label>
                                            <select class="form-control" name="examTime" id="examTime" required>
                                                <option value="">Select Exam Time</option>
                                                <?php
                            // Populate exam times from tbl_exam_schedule
                            $result_times = mysqli_query($con, "SELECT DISTINCT exam_time FROM tbl_exam_schedule WHERE exam_time IS NOT NULL ORDER BY exam_time ASC");
                            while ($row = mysqli_fetch_assoc($result_times)) {
                                echo "<option value='{$row['exam_time']}'>{$row['exam_time']}</option>";
                            }
                            ?>
                                            </select>
                                        </div>

                                        <button type="submit" class="btn btn-primary">Set Exam Schedule</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>


                    <?php require_once "../../includes/footer.php"; ?>
                </div>
            </section>
        </aside>
    </div>

    <script>
    function printReservations() {
        window.print();
    }

    // Open Modal and Fill Data Dynamically
    $('#setScheduleModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var reservationId = button.data('id');

        // Set the modal fields with the selected reservation's data
        $('#reservationId').val(reservationId);

        // Fetch available exam times from the server
        $.ajax({
            url: 'get_exam_times.php',
            type: 'GET',
            success: function(response) {
                var examTimes = JSON.parse(response);
                $('#examTime').empty();
                examTimes.forEach(function(examTime) {
                    $('#examTime').append('<option value="' + examTime + '">' + examTime +
                        '</option>');
                });
            }
        });
    });

    // Handle the Form Submission via AJAX
    $(document).ready(function() {
        $('#setScheduleForm').on('submit', function(e) {
            e.preventDefault();

            var formData = $(this).serialize();

            $.ajax({
                url: 'set_schedule.php',
                type: 'POST',
                data: formData,
                success: function(response) {
                    alert(response);
                    location.reload();
                },
                error: function() {
                    alert("❌ Error updating the schedule.");
                }
            });

        });
    });
    </script>
</body>

</html>