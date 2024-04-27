<?php
require_once '../../dbConnect.php';
$memberId = $_GET['memberId'];
$acc_no = $_POST['acc_no'];
$amount = $_POST['amount'];
$purpose = $_POST['purpose'];
$today = date("Y-m-d");

$codeLength = 12;
$characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'; // Characters to use for the code
$tran_id = '';

for ($i = 0; $i < $codeLength; $i++) {
    $tran_id .= $characters[rand(0, strlen($characters) - 1)];
}

$stmt2 = $conn->prepare("SELECT account_bal from savings_account WHERE account_number='$acc_no'");
$stmt2->execute();
$account_bal = $stmt2->fetch(PDO::FETCH_ASSOC);


if ($account_bal['account_bal'] >= $amount) {
    $stmt1 = $conn->prepare("INSERT INTO `savings_statement`(`account_number`, `purpose`, `trans_id`, withdrawal, `deposit`, `transaction_date`) 
                        VALUES (:acc_no, :purpose, :tran_id, :amount, '--', :today)");
    $stmt1->bindParam(':acc_no', $acc_no);
    $stmt1->bindParam(':purpose', $purpose);
    $stmt1->bindParam(':tran_id', $tran_id);
    $stmt1->bindParam(':amount', $amount);
    $stmt1->bindParam(':today', $today);
    if ($stmt1->execute()) {
        $new_bal = $account_bal['account_bal'] - $amount;
        $stmt3 = $conn->prepare("UPDATE `savings_account` SET `account_bal`=$new_bal,`last_transaction_amount`=$amount,`last_transaction_type`='Withdrawal',`last_transaction_date`='$today' WHERE account_number='$acc_no'");
        $stmt3->execute();
        header("Location: ../../Admin/Pages/SavingsAccountSummaryPage.php?memberId=$memberId&msg=<p class='bg-success-subtle text-success p-2 rounded text-center'>Amount of Rs. <span class='fw-bold'>" . $amount . "</span> withdrawn from Account <span class='fw-bold'>" . $acc_no . "</span> Successfully.</p>");
    }
} else {
    header("Location: ../../Admin/Pages/SavingsAccountSummaryPage.php?memberId=$memberId&msg=<p class='bg-danger-subtle text-danger p-2 rounded text-center fw-bold'>Insufficient Balance!</p>");
}
