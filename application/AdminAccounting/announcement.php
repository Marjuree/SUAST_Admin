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
    <title>Announcement | Dash</title>
    <link rel="shortcut icon" href="../../img/favicon.png" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
    <!-- Google Fonts: Poppins -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,500,600&display=swap" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #eef2f3, #ffffff);
            font-family: 'Poppins', Arial, sans-serif; /* Use Poppins */
        }

        .box,
        .container,
        h2.text-center,
        .btn,
        .announcement-card,
        .announcement-header,
        .announcement-meta,
        .announcement-message,
        .announcement-actions,
        .modal-content,
        .modal-header,
        .modal-title,
        .form-group label,
        .form-control {
            font-family: 'Poppins', Arial, sans-serif !important;
        }

        .box {
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 4px 24px rgba(51,102,255,0.07), 0 1.5px 6px rgba(0,0,0,0.04);
            padding: 32px 24px 24px 24px;
            margin-bottom: 32px;
            border: none;
        }

        .container {
            max-width: 700px;
        }

        h2.text-center {
            color: #3366ff;
            font-weight: 700;
            letter-spacing: 1px;
            margin-bottom: 18px;
        }

        .btn-primary {
            background: #3366ff !important;
            color: #fff !important;
            border: none !important;
            border-radius: 8px !important;
            font-weight: 600;
            padding: 10px 28px;
            box-shadow: 0 2px 8px rgba(51,102,255,0.07);
            transition: background 0.2s;
        }

        .btn-primary:hover {
            background: #254edb !important;
        }

        .announcement-card {
            background: #f8fafc;
            border-radius: 14px;
            box-shadow: 0 2px 8px rgba(51,102,255,0.04);
            padding: 18px 20px 14px 20px;
            margin-bottom: 18px;
            text-align: left;
            position: relative;
            transition: box-shadow 0.2s;
        }

        .announcement-card:hover {
            box-shadow: 0 4px 16px rgba(51,102,255,0.11);
        }

        .announcement-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 8px;
        }

        .announcement-header .avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: #3366ff;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.2rem;
            box-shadow: 0 1px 4px rgba(51,102,255,0.09);
        }

        .announcement-meta {
            font-size: 0.95rem;
            color: #888;
            margin-bottom: 6px;
        }

        .announcement-message {
            font-size: 1.08rem;
            color: #222;
            margin-bottom: 8px;
            white-space: pre-line;
        }

        .announcement-actions {
            position: absolute;
            top: 16px;
            right: 18px;
        }

        .announcement-actions .btn {
            padding: 4px 12px;
            font-size: 0.95rem;
            border-radius: 6px;
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

        .btn-success {
            background: #28a745 !important;
            color: #fff !important;
            border: none !important;
            border-radius: 8px !important;
            font-weight: 600;
            padding: 8px 22px;
        }

        .btn-outline-danger {
            border-radius: 8px !important;
            font-weight: 600;
            padding: 8px 22px;
        }

        @media (max-width: 900px) {
            .box, .container {
                padding: 16px 4px 16px 4px;
            }
        }

        @media (max-width: 600px) {
            .box, .container {
                padding: 8px 0 8px 0;
            }

            .announcement-card {
                padding: 12px 6px 10px 10px;
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
                <h1>Announcement</h1>
            </section>
            
            <section class="content">
                <div class="row">
                    <div class="box">
                        <div class="container mt-4">
                            <h2 class="text-center">Announcements</h2>
                            
                            <button class="btn btn-primary" data-toggle="modal" data-target="#announcementModal">
                                New Announcement
                            </button>
                            <hr>
                            
                            
                            <div class="chat-container mt-3" id="announcementList">
                            <!-- Messages will be loaded here -->
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </aside>
    </div>
    
  <!-- Enhanced Announcement Modal -->
<div id="announcementModal" class="modal fade" tabindex="-1" role="dialog">
    <form method="post">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" style="color: white;">📢 Post Announcement</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="adminName">Name From:</label>
                        <input type="text" id="adminName" class="form-control" placeholder="Enter your name" required>
                    </div>
                    <div class="form-group">
                        <label for="messageInput">Announcement:</label>
                        <textarea id="messageInput" class="form-control" rows="4" placeholder="Type your message here..." required></textarea>
                    </div>
                    <!-- <div class="form-group">
                        <label for="statusInput">Status:</label>
                        <select id="statusInput" class="form-control">
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                    </div> -->
                    <div class="form-group">
                        <label for="roleInput">Office of:</label>
                        <select id="roleInput" class="form-control">
                            <option value="Accounting">Accounting</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-danger px-4 py-2" data-dismiss="modal">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    <button type="button" class="btn btn-success px-4 py-2" onclick="sendMessage()">
                        <i class="fas fa-paper-plane"></i> Post Announcement
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>


    
    <script>
        document.addEventListener("DOMContentLoaded", fetchAnnouncements);
        
        function fetchAnnouncements() {
            fetch("announcement_fetch.php")
                .then(response => response.text())
                .then(data => {
                    document.getElementById("announcementList").innerHTML = data;
                })
                .catch(error => console.error("Error fetching announcements:", error));
        }
        
        function sendMessage() {
        let adminName = document.getElementById("adminName").value.trim();
        let messageText = document.getElementById("messageInput").value.trim();
        // let status = document.getElementById("statusInput").value;
        let role = document.getElementById("roleInput").value;

        if (adminName !== "" && messageText !== "") {
            let formData = new URLSearchParams();
            formData.append("admin_name", adminName);
            formData.append("message", messageText);
            // formData.append("status", status);
            formData.append("role", role);

            fetch("announcement_post.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: formData.toString()
            })
            .then(response => response.text())
            .then(data => {
                if (data.includes("success")) {
                    // Clear fields
                    document.getElementById("adminName").value = "";
                    document.getElementById("messageInput").value = "";

                    // Refresh announcements
                    fetchAnnouncements();

                    // Close the modal (for Bootstrap 4)
                    $('#announcementModal').modal('hide');
                } else {
                    alert("Failed to send: " + data);
                }
            })
            .catch(error => console.error("Error:", error));
        } else {
            alert("Please fill in all fields.");
        }
    }

        </script>
        
        <?php include "../../includes/footer.php"; ?>
        
    
        
        <script type="text/javascript">
            $(function() {
                $("#table").dataTable({
                    "aoColumnDefs": [{ "bSortable": false, "aTargets": [0, 5] }],
                    "aaSorting": []
                });
            });
        </script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function deleteAnnouncement(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "This announcement will be permanently deleted.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e74c3c',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch("announcement_delete.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: new URLSearchParams({ id: id })
                })
                .then(res => res.text())
                .then(response => {
                    if (response.trim() === "success") {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: 'Announcement has been deleted.',
                            timer: 1500,
                            showConfirmButton: false
                        });

                        // Remove the deleted announcement from the DOM
                        const deletedDiv = document.getElementById(`announcement-${id}`);
                        if (deletedDiv) deletedDiv.remove();
                    } else {
                        Swal.fire('Error!', 'Something went wrong.', 'error');
                    }
                });
            }
        });
    }
</script>

    </body>
    </html>
