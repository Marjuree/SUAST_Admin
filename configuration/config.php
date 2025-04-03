<?php
$host = "bwygtvcqnww3bwtcc8mw-mysql.services.clever-cloud.com";
$user = "uwfbf1jptm3pg6p0";  // Updated username (lowercase "y" at the end)
$password = "mjLQ9V30EsAOUNyr3u1G";  // Updated password (zero instead of 'O')
$dbname = "bwygtvcqnww3bwtcc8mw";
$port = 3306;

// Create MySQL connection
$conn = new mysqli($host, $user, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
