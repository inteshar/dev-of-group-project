<?php
require_once "../dbConnect.php";
$stmt = $conn->query("SELECT COUNT(*) AS totalMembers FROM members");
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$totalMembers = $result['totalMembers'];
$stmt1 = $conn->query("SELECT COUNT(*) AS totalApplications FROM loan_account where status = 0");
$result1 = $stmt1->fetch(PDO::FETCH_ASSOC);
$totalApplications = $result1['totalApplications'];

$stmt3 = $conn->prepare("SELECT amount FROM payments WHERE date = CURDATE()");
$stmt3->execute();
$payments = $stmt3->fetchAll(PDO::FETCH_ASSOC);

$totalPaymentsToday = 0;
foreach ($payments as $payment) {
    $totalPaymentsToday += $payment['amount'];
}

$stmt4 = $conn->prepare("SELECT remaining_payment FROM loan_account");
$stmt4->execute();
$pending = $stmt4->fetchAll(PDO::FETCH_ASSOC);

$totalPending = 0;
foreach ($pending as $pen) {
    $totalPending += $pen['remaining_payment'];
}
?>
<div class="overview">
    <div class="card-group">
        <div class="card m-3 p-3 border-0 bg-primary-subtle rounded shadow">
            <div class="d-flex justify-content-between">
                <svg xmlns="http://www.w3.org/2000/svg" width="70" height="70" fill="currentColor" class="bi bi-people-fill text-success" viewBox="0 0 16 16">
                    <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5.784 6A2.238 2.238 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.325 6.325 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1zM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5" />
                </svg>
                <p class="fs-6 text-end">
                    Total Members
                    <br />
                    <span class="fs-2 fw-bold"><?php echo $totalMembers; ?></span>
                </p>
            </div>
        </div>
        <div class="card m-3 p-3 border-0 bg-primary-subtle rounded shadow">
            <div class="d-flex justify-content-between">
                <svg xmlns="http://www.w3.org/2000/svg" width="70" height="70" fill="currentColor" class="bi bi-file-earmark-text-fill text-danger" viewBox="0 0 16 16">
                    <path d="M9.293 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.707A1 1 0 0 0 13.707 4L10 .293A1 1 0 0 0 9.293 0M9.5 3.5v-2l3 3h-2a1 1 0 0 1-1-1M4.5 9a.5.5 0 0 1 0-1h7a.5.5 0 0 1 0 1zM4 10.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5m.5 2.5a.5.5 0 0 1 0-1h4a.5.5 0 0 1 0 1z" />
                </svg>
                <p class="fs-6 text-end">
                    New Loan Requests
                    <br />
                    <span class="fs-2 fw-bold"><?php echo $totalApplications; ?></span>
                </p>
            </div>
        </div>
        <div class="card m-3 p-3 border-0 bg-primary-subtle rounded shadow">
            <div class="d-flex justify-content-between">
                <svg xmlns="http://www.w3.org/2000/svg" width="70" height="70" fill="currentColor" class="bi bi-wallet-fill text-primary" viewBox="0 0 16 16">
                    <path d="M1.5 2A1.5 1.5 0 0 0 0 3.5v2h6a.5.5 0 0 1 .5.5c0 .253.08.644.306.958.207.288.557.542 1.194.542.637 0 .987-.254 1.194-.542.226-.314.306-.705.306-.958a.5.5 0 0 1 .5-.5h6v-2A1.5 1.5 0 0 0 14.5 2z" />
                    <path d="M16 6.5h-5.551a2.678 2.678 0 0 1-.443 1.042C9.613 8.088 8.963 8.5 8 8.5c-.963 0-1.613-.412-2.006-.958A2.679 2.679 0 0 1 5.551 6.5H0v6A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5z" />
                </svg>
                <p class="fs-6 text-end">
                    Today's Total Collection
                    <br />
                    <span class="fs-2 fw-bold">Rs. <?php echo number_format($totalPaymentsToday, 2)?></span>
                </p>
            </div>
        </div>
        <div class="card m-3 p-3 border-0 bg-primary-subtle rounded shadow">
            <div class="d-flex justify-content-between">
                <svg xmlns="http://www.w3.org/2000/svg" width="70" height="70" fill="currentColor" class="bi bi-cash-stack text-danger-emphasis" viewBox="0 0 16 16">
                    <path d="M1 3a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1zm7 8a2 2 0 1 0 0-4 2 2 0 0 0 0 4" />
                    <path d="M0 5a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1zm3 0a2 2 0 0 1-2 2v4a2 2 0 0 1 2 2h10a2 2 0 0 1 2-2V7a2 2 0 0 1-2-2z" />
                </svg>
                <p class="fs-6 text-end">
                    Total Payments Pending
                    <br />
                    <span class="fs-2 fw-bold">Rs. <?php echo number_format($totalPending, 2) ?></span>
                </p>
            </div>
        </div>
    </div>
</div>
<style>
    .overview .card-group div {
        min-height: 100px;
        padding: 0 12px;
    }
</style>