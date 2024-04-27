<?php
require_once '../../dbConnect.php';

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $id_to_delete = $_GET['id'];

    $stmt1 = $conn->prepare("SELECT * FROM loan_account WHERE id = :id");
    $stmt1->bindParam(':id', $id_to_delete);
    $stmt1->execute();
    $result = $stmt1->fetch(PDO::FETCH_ASSOC); // Using fetch() to get a single row
    if ($result) {
        $acc_no = $result['account_number'];
        $stmt = $conn->prepare("DELETE FROM loan_account WHERE account_number = :acc_no");
        $stmt->bindParam(':acc_no', $acc_no);
        if ($stmt->execute()) {
            header("Location: ../../Admin/Pages/LoanAccountPage.php?msg=<p class='bg-success-subtle text-success p-2 rounded text-center fw-bold'>Loan Account '$acc_no' Deleted Successfully</p>");
        } else {
            header("Location: ../../Admin/Pages/LoanAccountPage.php?msg=<p class='bg-danger-subtle text-danger p-2 rounded text-center'>No Account found with this ID</p>");
        }
    } else {
        header("Location: ../../Admin/Pages/LoanAccountPage.php?msg=<p class='bg-danger-subtle text-danger p-2 rounded text-center'>No Account found with this ID</p>");
    }
}
