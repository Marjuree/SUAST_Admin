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
    
.table thead th {
    background-color: #343a40 !important;
    color: #fff;
    text-align: center;
}

.table tbody tr td {
    text-align: center;
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
                <h1>Manage Applicants</h1>
            </section>

            <section class="content">
                <div class="box">
                    <div class="box-header d-flex justify-content-between align-items-center">
                        <h3 class="box-title">Applicant List</h3>
                    </div>

                    <div class="box-body table-responsive">
                        <table id="examTable" class="table table-bordered table-striped">
                            <thead class="thead-dark">
                                <tr class="text-center">
                                    <th>ID</th>
                                    <th>Full Name</th>
                                    <th>Birthdate</th>
                                    <th>Age</th>
                                    <th>Gender</th>
                                    <th>Contact</th>
                                    <th>Email</th>
                                    <th>Options</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query = mysqli_query($con, "SELECT * FROM tbl_applicants ORDER BY id DESC");
                                while ($row = mysqli_fetch_assoc($query)) {
                                    $fullName = "{$row['lname']}, {$row['fname']} {$row['mname']}";
                                    echo "<tr class='text-center'>
                                            <td>{$row['id']}</td>
                                            <td>{$fullName}</td>
                                            <td>{$row['bdate']}</td>
                                            <td>{$row['age']}</td>
                                            <td>{$row['gender']}</td>
                                            <td>{$row['contact']}</td>
                                            <td>{$row['email']}</td>
                                            <td>
                                                <a href='view_applicant.php?id={$row['id']}' class='btn btn-info btn-sm'>View</a>
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

    <?php require_once "../../includes/footer.php"; ?>

    <script type="text/javascript">
    $(function() {
        $("#examTable").dataTable({
            "aoColumnDefs": [{
                "bSortable": false,
                "aTargets": [0, 7]
            }],
            "aaSorting": []
        });
    });
    </script>
</body>
</html>
