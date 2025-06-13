<?php
require_once "../../configuration/config.php";

// Fetch only announcements where role is 'SUAST'
$sql = "SELECT admin_name, message, role, created_at, id
        FROM tbl_announcement 
        WHERE role = 'SUAST' 
        ORDER BY created_at DESC";

$result = $con->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $admin = htmlspecialchars($row["admin_name"]);
        $message = nl2br(htmlspecialchars($row["message"]));
        $role = htmlspecialchars($row["role"]);
        $created_at = date("F d, Y h:i A", strtotime($row["created_at"]));
        $announcement_id = $row["id"];

        echo '
        <div id="announcement-' . $announcement_id . '" style="background: #ffffff; border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 10px 20px rgba(0,0,0,0.07); max-width: 700px; margin-left: auto; margin-right: auto; text-align: center;">
            <img src="https://cdn-icons-png.flaticon.com/512/3039/3039396.png" alt="icon" width="60" style="margin-bottom: 15px;">
            <p style="font-size: 16px; color: #333; margin-bottom: 20px; text-align: left;">' . $message . '</p>

            <div style="display: flex; justify-content: space-around; align-items: center; font-size: 14px; color: #666; flex-wrap: wrap;">
                <div><strong>From:</strong> ' . $admin . '</div>
                <div><strong>Date:</strong> ' . $created_at . '</div>
            </div>

            <button onclick="deleteAnnouncement(' . $announcement_id . ')" 
                    style="margin-top: 20px; padding: 8px 15px; background: #e74c3c; color: white; border: none; border-radius: 5px;">
                Delete
            </button>
        </div>';
    }
} else {
    echo '<p style="text-align: center; color: #999;">No announcements yet.</p>';
}
?>
