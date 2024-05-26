<?php
require_once '../../dbConnect.php'; // Include your database connection file

session_start(); // Ensure session is started before using $_SESSION

if ($_SERVER["REQUEST_METHOD"] == "POST") {


    $staff = $conn->prepare("SELECT name FROM `users` WHERE email = '".$_SESSION['login_user']."';");
    $staff->execute();
    $staffName = $staff->fetch(PDO::FETCH_ASSOC);

    // Check if the POST variables are set
    $memberId = $_POST['memberId'];
    $name = $_POST['name'];
    $payment = $_POST['payment'];
    
    // Check if the session variable is set
    $pay_receivedBy = $staffName['name'];
    
    $date = date("Y-m-d");

    $stmt = $conn->prepare("INSERT INTO `payments` (`member_id`, `amount`, `staff`, `date`) VALUES ('$memberId', '$payment', '$pay_receivedBy', '$date')");

    // $next_payment = date("Y-m-d", strtotime("+1 day"));
    // $stmt = $conn->prepare("UPDATE `loan_account` SET `status` = '1', `account_opened_on` = CURDATE(), `next_payment` = '$next_payment', `remaining_payment` = '$loan_amount'  WHERE `loan_account`.`member_id` = :id");
    // $stmt->bindParam(':id', $memberId);

    if ($stmt->execute()) {
        $stmt2 = $conn->prepare("SELECT remaining_payment, total_paid, next_payment FROM loan_account WHERE member_id = '$memberId'");
        $stmt2->execute();
        $remaining_payment = $stmt2->fetch(PDO::FETCH_ASSOC);
        
        $new_amount = $remaining_payment['remaining_payment'] - $payment;
        $new_totalPaid = $remaining_payment['total_paid'] + $payment;
        $new_nextPayDate = date('Y-m-d', strtotime($remaining_payment['next_payment'] . ' +1 day'));

        echo $new_nextPayDate;

        $stmt3 = $conn->prepare("UPDATE `loan_account` SET `last_payment` = '$date', `last_paid_amount` = '$payment', `total_paid` = '$new_totalPaid', `next_payment` = '$new_nextPayDate', `remaining_payment` = '$new_amount'  WHERE `member_id` =  '$memberId'");
        $stmt3->execute();

        header("Location: ../../Admin/Pages/Payments.php?msg=<p class='bg-success-subtle text-success p-2 rounded text-center fw-bold'>Payment of Rs. ".$payment." for ".$name." has been received.</p>");
    } else {
        header("Location: ../../Admin/Pages/Payments.php?msg=<p class='bg-danger-subtle text-danger p-2 rounded text-center fw-bold'>Something went wrong! Please try again later.</p>");
    }
}
