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
    
    <style>
        body {
            background: linear-gradient(to right, #eef2f3, #ffffff);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .announcement-container {
            max-width: 600px;
            margin: 40px auto;
            padding: 30px;
            border-radius: 20px;
            background: #fff;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .announcement-container img {
            width: 80px;
            margin-bottom: 15px;
        }

        .announcement-container h1 {
            font-size: 36px;
            color: #1a1a1a;
            margin-bottom: 10px;
        }

        .announcement-container p {
            font-size: 16px;
            color: #444;
            margin: 10px 0 20px;
        }

        .notice-btn {
            display: inline-block;
            margin-top: 10px;
            padding: 10px 20px;
            background: #2196f3;
            color: #fff;
            border-radius: 25px;
            text-decoration: none;
            font-weight: bold;
            transition: background 0.3s ease;
        }

        .notice-btn:hover {
            background: #1769aa;
        }

        .announcement-footer {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
            font-size: 13px;
            color: #777;
        }

        .announcement-footer strong {
            display: block;
            color: #111;
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
                <h1>Dashboard</h1>
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
                    <h5 class="modal-title">📢 Post Announcement</h5>
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
                    <div class="form-group">
                        <label for="statusInput">Status:</label>
                        <select id="statusInput" class="form-control">
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="roleInput">Office of:</label>
                        <select id="roleInput" class="form-control">
                            <option value="Accounting">Suast</option>
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
        let status = document.getElementById("statusInput").value;
        let role = document.getElementById("roleInput").value;

        if (adminName !== "" && messageText !== "") {
            let formData = new URLSearchParams();
            formData.append("admin_name", adminName);
            formData.append("message", messageText);
            formData.append("status", status);
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
