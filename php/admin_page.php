<?php
// Start output buffering
ob_start();

// Include necessary PHP files
require_once "../configuration/config.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Welcome Applicants</title>
    <link rel="shortcut icon" href="../img/favicon.png">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

    <!-- Stylesheets -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/font-awesome.min.css" rel="stylesheet">
    <link href="../css/ionicons.min.css" rel="stylesheet">
    <link href="../js/morris/morris-0.4.3.min.css" rel="stylesheet">
    <link href="../css/AdminLTE.css" rel="stylesheet">
    <link href="../css/select2.css" rel="stylesheet">
    <link href="../css/landing_page.css" rel="stylesheet">
    <link href="../css/button.css" rel="stylesheet">

    <script src="../js/jquery-1.12.3.js"></script>
</head>

<style>
.button {
    font-size: 1.1em;
    background-color: #00bcd4;
    color: white;
    padding: 14px 28px;
    border: none;
    border-radius: 50px;
    transition: all 0.3s ease-in-out;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    margin: 10px;
    width: 250px;
    max-width: 90%;
    text-align: center;
    display: inline-block;
}

.button:hover {
    background-color: #0097a7;
    transform: translateY(-2px);
    box-shadow: 0 6px 8px rgba(0, 0, 0, 0.2);
}

.buttons-container {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 20px;
}

@media (max-width: 767px) {
    .button {
        width: 100%;
    }

    .buttons-container {
        flex-direction: column;
        align-items: center;
    }
}
</style>

<body>



    <!-- Welcome Section -->
    <div class="welcome-container">
        <img src="../img/ken.png" alt="SUAST Logo" class="logo">
        <p><strong>WELCOME TO THE ADMIN OFFICE SITE</strong></p>
    </div>
    <div class="buttons-container">
        <button class="button" data-toggle="modal" data-target="#administrator">Sign Up Now!</button>
    </div>



    <!-- Modal for Administrator Login -->
    <div id="administrator" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title"><strong>WELCOME TO ADMIN OFFICE</strong></h4>
                    <p class="text-danger"><strong>Note:</strong> This Login is for Official Use only.</p>
                    <p class="text-muted"><strong>Republic Act No. 10173:</strong> Data Privacy Act of 2012 -
                        Unauthorized access is strictly prohibited.</p>
                    <img src="../img/ken.png" style="height:100px;" />

                </div>
                <div class="modal-body">
                    <form role="form" method="post">
                        <div class="form-group">
                            <label for="txt_username">Username</label>
                            <input type="text" class="form-control" name="txt_username" placeholder="Enter Username"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="txt_password">Password</label>
                            <input type="password" class="form-control" name="txt_password" placeholder="Enter Password"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="select_role">Office</label>
                            <select class="form-control" name="select_role" required>
                                <option value="" disabled selected>Select Office</option>
                                <option value="SUAST">SUAST</option>
                                <option value="HRMO">HRMO</option>
                                <option value="Accounting">Accounting</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-info" name="btn_login">Log in</button>
                        <button type="button" class="btn btn-info" data-toggle="modal"
                            data-target="#regadministrator">Signup</button>
                        <div id="error" class="text-danger text-center">
                            <?php echo isset($login_error) ? $login_error : ''; ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Registration Modal for Administrator -->
    <div id="regadministrator" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title text-center"><strong>PLEASE REGISTER</strong></h4>
                </div>
                <div class="modal-body">
                    <form method="post">
                        <div class="form-group">
                            <label>Full Name</label>
                            <input type="text" class="form-control" name="reg_name" required>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" class="form-control" name="reg_email" required>
                        </div>
                       
                        <div class="form-group">
                            <label>Username</label>
                            <input type="text" class="form-control" name="reg_username" required>
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" class="form-control" name="reg_password" required>
                        </div>
                        <div class="form-group">
                            <label>Confirm Password</label>
                            <input type="password" class="form-control" name="confirm_password" required>
                        </div>
                        <div class="form-group">
                            <label>Office</label>
                            <select class="form-control" name="reg_role" required>
                                <option value="" disabled selected>Select Office</option>
                                <option value="SUAST">SUAST</option>
                                <option value="HRMO">HRMO</option>
                                <option value="Accounting">Accounting</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-info" name="btn_register">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>



    <!--------------------Registration Modal for Administrator End ---------------->


    <?php include "../controller/controller.php"; ?>

    <?php include "../includes/foot.php"; ?>


    <!-- Bootstrap JS and other necessary scripts -->
    <script src="../js/bootstrap.min.js" type="text/javascript"></script>
    <script src="../js/morris/raphael-2.1.0.min.js" type="text/javascript"></script>
    <script src="../js/morris/morris.js" type="text/javascript"></script>
    <script src="../js/select2.full.js" type="text/javascript"></script>

    <!-- DataTables & AdminLTE App -->
    <script src="../js/jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="../js/dataTables.buttons.min.js" type="text/javascript"></script>
    <script src="../js/buttons.print.min.js" type="text/javascript"></script>
    <script src="../js/plugins/datatables/dataTables.bootstrap.js" type="text/javascript"></script>
    <script src="../js/AdminLTE/app.js" type="text/javascript"></script>

    <script type="text/javascript">
    $(function() {
        $("#table").dataTable({
            "aoColumnDefs": [{
                "bSortable": false,
                "aTargets": [0, 5]
            }],
            "aaSorting": [],
            "dom": '<"search"f><"top"l>rt<"bottom"ip><"clear">'
        });
    });
    </script>
</body>

</html>

<?php
// End output buffering and flush content
ob_end_flush();
?>
