<?php
require_once '../../dbConnect.php'; // Include your database connection file

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $memberId = $_GET['id'];

    $stmt = $conn->prepare("DELETE from loan_account where member_id=:id");

    $stmt->bindParam(':id', $memberId);

    if ($stmt->execute()) {
        header("Location: ../../Admin/Pages/AccountRequest.php?msg=<p class='bg-danger-subtle text-danger p-2 rounded text-center'>Loan request has been denied.</p>");
    } else {
        header("Location: ../../Admin/Pages/AccountRequest.php?msg=<p class='bg-danger-subtle text-danger p-2 rounded text-center'>Something went wrong! Please try again later.</p>");
    }
}
