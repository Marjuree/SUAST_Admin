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
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Google Fonts: Poppins -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,500,600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', Arial, sans-serif !important;
            background: #f4f7fa;
        }

        .box,
        .box h2,
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
        h6,
        label {
            font-family: 'Poppins', Arial, sans-serif !important;
        }

        .box {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 2px 16px rgba(0, 0, 0, 0.07);
            padding: 24px 18px 18px 18px;
            margin-bottom: 32px;
        }

        .box h2 {
            color: #3366ff;
            font-weight: 600;
            margin-bottom: 18px;
        }

        .btn-primary,
        .btn-success,
        .btn-danger {
            border-radius: 6px !important;
            font-weight: 500;
            box-shadow: 0 2px 8px rgba(51, 102, 255, 0.07);
            transition: background 0.2s, color 0.2s;
        }

        .btn-primary {
            background: #3366ff !important;
            color: #fff !important;
            border: none !important;
        }

        .btn-danger {
            background: #cc0000 !important;
            color: #fff !important;
            border: none !important;
        }

        .btn-primary:hover,
        .btn-danger:hover {
            opacity: 0.9;
        }

        .table {
            background: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        .table th {
            background: #e6eaf0;
            color: #222;
            font-weight: 600;
            font-size: 1.05rem;
            border: none;
        }

        .table td {
            vertical-align: middle !important;
            text-align: center;
            border: none;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background: #f8fafc;
        }

        .table-striped tbody tr:hover {
            background: #e6eaf0;
            transition: background 0.2s;
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

        @media (max-width: 600px) {
            .box {
                padding: 10px 2px 10px 2px;
            }

            .table th,
            .table td {
                font-size: 0.95rem;
                padding: 6px 2px;
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
                <h1>Contact Management</h1>
                <div style="height:3px;width:100px;background:#3366ff;border-radius:2px;margin:8px 0 16px 0;"></div>
            </section>

            <section class="content container-fluid">
                <div class="row justify-content-center">
                    <div class="col-12">
                        <div class="box p-3 bg-white shadow-sm rounded">
                            <div class="container-fluid mt-3 px-0" style="max-width:100%;">
                                <h2 class="text-center">Contact Details</h2>
                                <div class="d-flex justify-content-end">
                                    <button class="btn btn-primary mb-3" data-toggle="modal"
                                        data-target="#addContactModal">Add Contact</button>
                                </div>
                                <div class="contact-container mt-3 table-responsive p-0" id="contactList">
                                    <?php
                                    // Fetch the contacts from the database
                                    $query = "SELECT * FROM tbl_contact";
                                    $result = mysqli_query($con, $query);
                                    if (mysqli_num_rows($result) > 0) {
                                        echo "<table class='table table-bordered table-striped m-0'>";
                                        echo "<thead class='thead-dark'><tr><th>Name</th><th>Email</th><th>Phone</th><th>Action</th></tr></thead><tbody>";
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            echo "<tr>
                                                    <td>{$row['name']}</td>
                                                    <td>{$row['email']}</td>
                                                    <td>{$row['phone']}</td>
                                                    <td><button class='btn btn-danger delete-btn' data-id='{$row['id']}'>Delete</button></td>
                                                  </tr>";
                                        }
                                        echo "</tbody></table>";
                                    } else {
                                        echo "<p class='text-muted'>No contact details found.</p>";
                                    }
                                    ?>
                                </div>

                                <!-- Add Contact Modal -->
                                <div class="modal fade" id="addContactModal" tabindex="-1" role="dialog"
                                    aria-labelledby="addContactModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="addContactModalLabel">Add Contact</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form method="POST" action="add_contact.php">
                                                    <div class="form-group">
                                                        <label for="name">Name</label>
                                                        <input type="text" name="name" class="form-control"
                                                            placeholder="Name" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="email">Email</label>
                                                        <input type="email" name="email" class="form-control"
                                                            placeholder="Email" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="phone">Phone</label>
                                                        <input type="text" name="phone" class="form-control"
                                                            placeholder="Phone" required>
                                                    </div>
                                                    <button type="submit" class="btn btn-primary">Add Contact</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div> <!-- End Modal -->

                            </div> <!-- End container -->
                        </div> <!-- End box -->
                    </div> <!-- End column -->
                </div> <!-- End row -->
            </section>

        </aside>
    </div>

    <?php require_once "../../includes/footer.php"; ?>

    <!-- Script to handle delete action -->
    <script>
        // Add event listener for all delete buttons
        const deleteButtons = document.querySelectorAll('.delete-btn');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function () {
                const contactId = this.getAttribute('data-id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Send the delete request to PHP via AJAX
                        window.location.href = `delete_contact.php?id=${contactId}`;
                    }
                });
            });
        });
    </script>
</body>

</html>
