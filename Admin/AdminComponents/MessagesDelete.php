<?php
error_reporting(E_ALL & ~E_NOTICE);
require_once '../../dbConnect.php';
$msgId = $_GET['msgId'];
try {
    $stmt = $conn->prepare("DELETE FROM messages where id=$msgId");
    if ($stmt->execute()) {
        header('Location: ../../Admin/');
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
