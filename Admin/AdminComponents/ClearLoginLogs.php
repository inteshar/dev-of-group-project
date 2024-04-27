<?php
require_once '../../dbConnect.php'; // Include your database connection file

$stmt = $conn->prepare("DELETE from login_logs");

if ($stmt->execute()) {
    header("Location: ../../Admin/Pages/LoginLogs.php?msg=<p class='bg-warning-subtle text-danger p-2 rounded text-center'>Login logs has been cleared.</p>");
} else {
    header("Location: ../../Admin/Pages/LoginLogs.php?msg=<p class='bg-danger-subtle text-danger p-2 rounded text-center'>Something went wrong! Please try again later.</p>");
}
