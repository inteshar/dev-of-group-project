<?php
require_once '../../dbConnect.php'; // Include your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $memberId = $_POST['memberId'];
    $int_rate = $_POST['int_rate'];

    $stmt1 = $conn->prepare("SELECT members.name FROM members WHERE id = :id");
    $stmt1->bindParam(':id', $memberId);
    $stmt1->execute();
    $name = $stmt1->fetch(PDO::FETCH_ASSOC);

    $no = sprintf("%08d", rand(0, 49999999));
    $acc_no = "SV" . $no;

    // Execute the statement
    if ($stmt1->execute()) {
        try {
            $stmt = $conn->prepare("INSERT INTO `savings_account`(`member_id`, `account_number`, `interest_rate`, `account_bal`) 
        VALUES (:id, :acc_no, :int_rate, 0)");
            $stmt->bindParam(':id', $memberId);
            $stmt->bindParam(':acc_no', $acc_no);
            $stmt->bindParam(':int_rate', $int_rate);
            $stmt->execute();
            header("Location: ../../Admin/Pages/SavingsAccountPage.php?msg=<p class='bg-success-subtle text-success p-2 rounded text-center'>New Savings Account for <span class='fw-bold'>" . $name['name'] . "</span> with Account Number: <span class='fw-bold'>" . $acc_no . "</span> Opened Successfully</p>");
        } catch (Exception $e) {
            header("Location: ../../Admin/Pages/SavingsAccountPage.php?msg=<p class='bg-danger-subtle text-danger p-2 rounded text-center'>Account for the selected member already exists!</p>");
        }
    } else {
        header("Location: ../../Admin/Pages/SavingsAccountPage.php?msg=<p class='bg-danger-subtle text-danger p-2 rounded text-center'>Something went wrong! Please try again later.</p>");
    }
}
