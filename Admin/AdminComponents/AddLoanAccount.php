<?php
require_once '../../dbConnect.php'; // Include your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $memberId = $_POST['memberId'];
    $loanAmount = $_POST['loan_amount'];
    $approvalAmount = $_POST['approval_amount'];
    $plan = $_POST['plan'];

    $emi = round(($loanAmount - $approvalAmount) / $plan, 3);

    $stmt1 = $conn->prepare("SELECT members.name FROM members WHERE id = :id");
    $stmt1->bindParam(':id', $memberId);
    $stmt1->execute();
    $name = $stmt1->fetch(PDO::FETCH_ASSOC);

    $no = sprintf("%08d", rand(0, 49999999));
    $acc_no = "LN" . $no;

    //echo $memberId . '<br>' . $loanAmount . '<br>' . $approvalAmount . '<br>' . $plan . '<br>' . $emi . '<br>' . $name['name'] . '<br>' . $acc_no;

    // Execute the statement
    if ($stmt1->execute()) {
        try {
            $stmt = $conn->prepare("INSERT INTO `loan_account`(`member_id`, `account_number`, `loan_amount`, `approval_amount`, `plan`, `emi`, `status`) 
        VALUES (:id, :acc_no, :loanAmount, :approvalAmount, :plan, :emi, 0)");
            $stmt->bindParam(':id', $memberId);
            $stmt->bindParam(':acc_no', $acc_no);
            $stmt->bindParam(':loanAmount', $loanAmount);
            $stmt->bindParam(':approvalAmount', $approvalAmount);
            $stmt->bindParam(':plan', $plan);
            $stmt->bindParam(':emi', $emi);
            $stmt->execute();
            header("Location: ../../Admin/Pages/LoanAccountPage.php?msg=<p class='bg-success-subtle text-success p-2 rounded text-center'>New Loan Account for <span class='fw-bold'>" . $name['name'] . "</span> with Account Number: <span class='fw-bold'>" . $acc_no . "</span> requested Successfully</p>");
        } catch (Exception $e) {
            header("Location: ../../Admin/Pages/LoanAccountPage.php?msg=<p class='bg-danger-subtle text-danger p-2 rounded text-center'>Account for the selected member already exists!</p>");
        }
    } else {
        header("Location: ../../Admin/Pages/LoanAccountPage.php?msg=<p class='bg-danger-subtle text-danger p-2 rounded text-center'>Something went wrong! Please try again later.</p>");
    }
}
