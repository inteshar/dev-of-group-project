<?php
require_once '../../dbConnect.php'; // Include your database connection file

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $memberId = $_GET['id'];
    $next_payment = date("Y-m-d", strtotime("+1 day"));
    $stmt = $conn->prepare("UPDATE `loan_account` SET `status` = '1', `account_opened_on` = CURDATE(), `next_payment` = '$next_payment'  WHERE `loan_account`.`member_id` = :id");

    $stmt->bindParam(':id', $memberId);

    if ($stmt->execute()) {
        header("Location: ./LoanSummaryPDF.php?memberId=" . $memberId);
    } else {
        header("Location: ../../Admin/Pages/AccountRequest.php?msg=<p class='bg-danger-subtle text-danger p-2 rounded text-center'>Something went wrong! Please try again later.</p>");
    }
}
