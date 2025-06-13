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

    <!-- Google Fonts: Poppins -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,500,600&display=swap" rel="stylesheet">

    <script src="../js/jquery-1.12.3.js"></script>

    <style>
        html,
        body {
            height: 100%;
            margin: 0;
            display: flex;
            flex-direction: column;
            font-family: 'Poppins', Arial, sans-serif;
            /* Use Poppins */
        }

        body {
            font-family: 'Poppins', Arial, sans-serif;
            /* Use Poppins */
            background: url('../img/logo4.jpg') no-repeat center center fixed;
            background-size: cover;
        }

        .modal-backdrop {
            z-index: 1049 !important;
        }

        .modal {
            z-index: 1050 !important;
            position: fixed !important;
        }

        /* Push background elements back */
        .blur-overlay {
            z-index: 1 !important;
            position: relative;
        }

        .blur-overlay {
            flex: 1;
            backdrop-filter: blur(2px);
            background-color: rgba(31, 30, 30, 0.5);
            padding: 20px;
            position: relative;
            z-index: 1;
        }

        .welcome-container {
            text-align: center;
            color: white;
            margin-top: 50px;
            text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.7) !important;
        }

        .logo {
            height: 150px;
            margin-bottom: 20px;
            filter: drop-shadow(1px 1px 4px rgba(0, 0, 0, 0.6));
        }

        .button {
            font-size: 1.1em;
            background-color: #002B5B !important;
            color: white;
            padding: 14px 28px;
            border: 2px solid white;
            border-radius: 50px;
            transition: all 0.3s ease-in-out;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin: 10px;
            width: 250px;
            max-width: 90%;
            text-align: center;
            display: inline-block;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5) !important;
        }

        .button:hover {
            background-color: #002B5B;
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

        .title {
            color: white !important;
            text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.7);
        }

        .modal-body input {
            border-radius: 30px !important;
        }

        .form-control {
            border-radius: 30px !important;
        }

        .btn {
            background-color: #002B5B !important;
        }
    </style>

</head>

<body>
    <div class="blur-overlay">

        <!-- Welcome Section -->
        <div class="welcome-container">
            <img src="../img/uni.png" alt="SUAST Logo" class="logo">
            <p class="title"><strong>WELCOME TO THE ADMIN OFFICE SITE</strong></p>
        </div>

        <div class="buttons-container">
            <button class="button" data-toggle="modal" data-target="#administrator">Sign Up Now!</button>
        </div>


        <?php include "../controller/controller.php"; ?>

    </div> <!-- /.blur-overlay -->
    <?php include "../includes/foot.php"; ?>


    <!-- Modal for Administrator Login -->
    <div id="administrator" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false"
        style="margin-top: 70px;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header flex-column align-items-center text-center"
                    style="outline: none !important; box-shadow: none !important; border: none;">

                    <img src="../img/uni.png" alt="Logo"
                        style="width: 200px; height: auto; margin-bottom: 10px; margin-top: -40px;"
                        class="mx-auto d-block">

                    <h4 class="modal-title font-weight-bold" style="margin-top: -50px;">WELCOME TO ADMIN OFFICE</h4>

                    <h4 class="modal-title" style="font-size: 10px; color: #dc3545;">
                        <strong>Note:</strong> This Login is for Official Use only.
                    </h4>

                    <p class="text-muted text-center" style="font-size: 10px;">
                        <strong>Republic Act No. 10173:</strong> Data Privacy Act of 2012 - Unauthorized access is
                        strictly prohibited.
                    </p>

                    <button type="button" class="close position-absolute" style="right: 10px; top: 10px;"
                        data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
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
                            <div class="d-flex align-items-center position-relative">
                                <input type="password" class="form-control pr-5" id="admin_password" name="txt_password"
                                    placeholder="Enter Password" required>
                                <span id="toggleAdminPassword"
                                    style="position: absolute; top: -30px; right: 25px; cursor: pointer; user-select: none; display: flex; align-items: center; height: 100%;">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="black"
                                        stroke-width="1.5" viewBox="0 0 24 24" width="22" height="22">
                                        <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z" />
                                        <circle cx="12" cy="12" r="3.5" />
                                    </svg>
                                </span>
                            </div>
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

                        <button type="submit" class="btn btn-block" style="background-color: #02457A; color: white;"
                            name="btn_login">Log in</button>



                        <p class="mt-2 text-center">
                            <span style="color: black;">New User?</span>
                            <a href="#" data-toggle="modal" data-target="#regadministrator" class="text-warning " style="color: orange;">
                                Register
                            </a>
                        </p>

                        <div id="error" class="text-danger text-center mt-2">
                            <?php echo isset($login_error) ? $login_error : ''; ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

   <script>
  const togglePassword = document.querySelector('#toggleAdminPassword');
  const passwordInput = document.querySelector('#admin_password');

  togglePassword.addEventListener('click', () => {
    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordInput.setAttribute('type', type);

    togglePassword.innerHTML = type === 'password'
      ? `<svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="black" stroke-width="1.5" viewBox="0 0 24 24" width="22" height="22">
            <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z"/>
            <circle cx="12" cy="12" r="3.5"/>
         </svg>`
      : `<svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="black" stroke-width="1.5" viewBox="0 0 24 24" width="22" height="22">
            <path d="M17.94 17.94C16.12 19.25 14.13 20 12 20c-7 0-11-8-11-8a21.77 21.77 0 0 1 5.06-6.06M22.54 6.42A21.77 21.77 0 0 1 23 12s-4 8-11 8a10.94 10.94 0 0 1-4.24-.88M1 1l22 22"/>
            <circle cx="12" cy="12" r="3.5"/>
         </svg>`;
  });
</script>

    <!-- Registration Modal for Administrator -->
    <div id="regadministrator" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="text-align:center;">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <!-- Centered Logo -->
                    <img src="../img/uni.png" alt="SUAST Logo"
                        style="display:block; margin:0 auto 10px auto; height:150px;">
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
                            <div style="position:relative;">
                                <input type="password" class="form-control" id="reg_password" name="reg_password"
                                    required minlength="8" style="padding-right:40px;">
                                <span id="toggleRegPassword"
                                    style="position:absolute; top:50%; right:12px; transform:translateY(-50%); cursor:pointer;">
                                    <!-- Eye icon SVG -->
                                    <svg id="regPasswordIcon" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        stroke="black" stroke-width="1.5" viewBox="0 0 24 24" width="22" height="22">
                                        <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z" />
                                        <circle cx="12" cy="12" r="3.5" />
                                    </svg>
                                </span>
                            </div>
                            <small id="passwordHelp" class="text-muted"></small>

                        </div>
                        <div class="form-group">
                            <label>Confirm Password</label>
                            <div style="position:relative;">
                                <input type="password" class="form-control" id="confirm_password"
                                    name="confirm_password" required minlength="8" style="padding-right:40px;">
                                <span id="toggleConfirmPassword"
                                    style="position:absolute; top:50%; right:12px; transform:translateY(-50%); cursor:pointer;">
                                    <!-- Eye icon SVG -->
                                    <svg id="confirmPasswordIcon" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        stroke="black" stroke-width="1.5" viewBox="0 0 24 24" width="22" height="22">
                                        <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z" />
                                        <circle cx="12" cy="12" r="3.5" />
                                    </svg>
                                </span>
                            </div>
                            <small id="confirmPasswordHelp" class="text-muted"></small>
                        </div>
                        <div class="form-group">
                            <label>Office</label>
                            <select class="form-control" name="reg_role" required>
                                <option value="" disabled selected>Select Office</option>
                                <option value="SUAST">SUAST</option>
                                <option value="HRMO">HRMO</option>
                                <option value="Accounting">ACCOUNTING</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-info" name="btn_register">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const passwordInput = document.getElementById('reg_password');
            const confirmInput = document.getElementById('confirm_password');
            const passwordHelp = document.getElementById('passwordHelp');
            const confirmPasswordHelp = document.getElementById('confirmPasswordHelp');
            const toggleRegPassword = document.getElementById('toggleRegPassword');
            const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');

            const strongPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*(),.?":{}|<>]).{8,}$/;

            // Password strength check
            passwordInput.addEventListener('input', function () {
                const password = passwordInput.value;

                if (password.length === 0) {
                    passwordHelp.textContent = '';
                } else if (!strongPattern.test(password)) {
                    passwordHelp.textContent = 'Password must include uppercase, lowercase, number, special character, and be at least 8 characters.';
                    passwordHelp.style.color = 'red';
                } else {
                    passwordHelp.textContent = 'Strong password!';
                    passwordHelp.style.color = 'green';
                }

                // Also check confirm password match on password input change
                checkPasswordMatch();
            });

            // Confirm password match check
            confirmInput.addEventListener('input', checkPasswordMatch);

            function checkPasswordMatch() {
                if (confirmInput.value.length === 0) {
                    confirmPasswordHelp.textContent = '';
                    return;
                }

                if (passwordInput.value === confirmInput.value) {
                    confirmPasswordHelp.textContent = 'Passwords match.';
                    confirmPasswordHelp.style.color = 'green';
                } else {
                    confirmPasswordHelp.textContent = 'Passwords do not match.';
                    confirmPasswordHelp.style.color = 'red';
                }
            }

            // Eye icon SVGs
            const eyeOpen = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="black" stroke-width="1.5"
                    viewBox="0 0 24 24" width="22" height="22">
                    <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z"/>
                    <circle cx="12" cy="12" r="3.5"/>
                </svg>`;
            const eyeClosed = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="black" stroke-width="1.5"
                    viewBox="0 0 24 24" width="22" height="22">
                    <path d="M17.94 17.94C16.12 19.25 14.13 20 12 20c-7 0-11-8-11-8a21.77 21.77 0 0 1 5.06-6.06M22.54 6.42A21.77 21.77 0 0 1 23 12s-4 8-11 8a10.94 10.94 0 0 1-4.24-.88M1 1l22 22"/>
                    <circle cx="12" cy="12" r="3.5"/>
                </svg>`;

            // Eye icon toggle for password
            toggleRegPassword.addEventListener('click', function () {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                this.innerHTML = type === 'password' ? eyeOpen : eyeClosed;
            });

            toggleConfirmPassword.addEventListener('click', function () {
                const type = confirmInput.getAttribute('type') === 'password' ? 'text' : 'password';
                confirmInput.setAttribute('type', type);
                this.innerHTML = type === 'password' ? eyeOpen : eyeClosed;
            });
        });
    </script>


    <!-- Scripts -->
    <script src="../js/bootstrap.min.js" type="text/javascript"></script>
    <script src="../js/morris/raphael-2.1.0.min.js" type="text/javascript"></script>
    <script src="../js/morris/morris.js" type="text/javascript"></script>
    <script src="../js/select2.full.js" type="text/javascript"></script>
    <script src="../js/jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="../js/dataTables.buttons.min.js" type="text/javascript"></script>
    <script src="../js/buttons.print.min.js" type="text/javascript"></script>
    <script src="../js/plugins/datatables/dataTables.bootstrap.js" type="text/javascript"></script>
    <script src="../js/AdminLTE/app.js" type="text/javascript"></script>

    <script type="text/javascript">
        $(function () {
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
