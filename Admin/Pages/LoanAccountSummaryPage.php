<?php
error_reporting(E_ALL & ~E_NOTICE);
require_once '../../dbConnect.php';

$memberId = $_GET['memberId'];

$stmt = $conn->prepare("SELECT members.*, loan_account.* FROM members INNER JOIN loan_account ON members.id=loan_account.member_id WHERE member_id=:id");
$stmt->bindParam(':id', $memberId);
$stmt->execute();

// Fetch the result as an associative array
$member = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="icon" href="./Assets/favicon.ico" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" integrity="sha256-2TnSHycBDAm2wpZmgdi0z81kykGPJAkiUY+Wf97RbvY=" crossorigin="anonymous" />
    <title>Admin</title>
</head>

<body>
    <?php include '../../Admin/AdminComponents/SideBar.php' ?>
    <div class="container">
        <div class="mt-3">
            <?php
            if (isset($_GET['msg'])) {
                $message = $_GET['msg'];
                echo $message;
            }
            ?>
        </div>
        <div class="edit-member">
            <h1 class="m-3 mb-4">Loan Account Summary</h1>
            <?php
            if ($member['status'] === '0') {
                echo '<p class="text-danger p-2 alert alert-danger text-center m-5">This loan account is not approved by the admin.</p>';
            } else {
            ?>
                <div class="card-group mb-4">
                    <div class="card border-0 p-4 ps-5 pe-5">
                        <a class="btn btn-success shadow fw-bold" href="../AdminComponents/LoanSummaryPDF.php?memberId=<?php echo $member['member_id']; ?>" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-file-earmark-arrow-down" viewBox="0 0 16 16">
                                <path d="M8.5 6.5a.5.5 0 0 0-1 0v3.793L6.354 9.146a.5.5 0 1 0-.708.708l2 2a.5.5 0 0 0 .708 0l2-2a.5.5 0 0 0-.708-.708L8.5 10.293z" />
                                <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2M9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5z" />
                            </svg> Download PDF</a>
                    </div>
                </div>
            <?php
            } ?>
            <div class="row g-3 needs-validation shadow p-5 rounded">
                <div class="row mb-3">
                    <div class="col-sm-4 mb-3">
                        <img class="member-img shadow" src="../MembersFiles/<?php echo $member['name'] ?>/<?php echo $member['photo'] ?>" alt="">
                    </div>
                    <div class="col-sm-4">
                        <div class="member-info">
                            <p class="member-info-heading">Name</p>
                            <h5><?php echo $member['name']; ?></h5>
                        </div>
                        <div class="member-info">
                            <p class="member-info-heading">Mobile Number</p>
                            <h5><?php echo $member['mobile']; ?></h5>
                        </div>
                        <div class="member-info">
                            <p class="member-info-heading">Email Address</p>
                            <h5><?php echo $member['email']; ?></h5>
                        </div>
                        <div class="member-info">
                            <p class="member-info-heading">Permanent Address</p>
                            <h5><?php echo $member['address_perm']; ?></h5>
                        </div>
                        <div class="member-info">
                            <p class="member-info-heading">Temporary Address</p>
                            <h5><?php echo $member['address_temp']; ?></h5>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="member-info">
                            <p class="member-info-heading">Loan ID</p>
                            <h5><?php echo $member['account_number']; ?></h5>
                        </div>
                        <div class="member-info">
                            <p class="member-info-heading">Loan Amount</p>
                            <h5>Rs. <?php echo $member['loan_amount']; ?></h5>
                        </div>
                        <div class="member-info">
                            <p class="member-info-heading">Approved Amount</p>
                            <h5>Rs. <?php echo $member['approval_amount']; ?></h5>
                        </div>
                        <div class="member-info">
                            <p class="member-info-heading">EMI</p>
                            <h5>Rs. <?php echo $member['emi']; ?>/day</h5>
                        </div>
                        <div class="member-info">
                            <p class="member-info-heading">Plan</p>
                            <h5><?php echo $member['plan']; ?> Days</h5>
                        </div>
                        <div class="member-info">
                            <p class="member-info-heading">Account Opened on</p>
                            <h5><?php echo $member['account_opened_on']; ?></h5>
                        </div>
                    </div>
                </div>
                <div class="container">
                    <h4 class="fw-bold border-1 border-bottom border-success pb-3">Payment Summary<br><span class="fs-6 fw-light"><?php echo $member['name']; ?></span></h4>
                    <div class="row mt-3 justify-content-between">
                        <div class="col-sm-auto m-1 pt-3 pb-3 ps-auto pe-auto shadow-sm bg-info-subtle rounded member-info">
                            <p class="alert alert-light text-dark fw-bold rounded p-2">Previous Payment</p>
                            <h5>Rs. <?php echo $member['last_paid_amount']; ?></h5>
                            <h6>Date: <?php echo $member['last_payment']; ?></h6>
                        </div>
                        <div class="col-sm-auto m-1 pt-3 pb-3 ps-auto pe-auto shadow-sm bg-warning-subtle rounded member-info">
                            <p class="alert alert-light text-dark fw-bold rounded p-2">Next Payment</p>
                            <h5>Rs. <?php echo $member['emi']; ?></h5>
                            <h6>Date: <?php echo $member['next_payment']; ?></h6>
                        </div>
                        <div class="col-sm-auto m-1 pt-3 pb-3 ps-auto pe-auto shadow-sm bg-success-subtle text-success rounded member-info">
                            <p class="alert alert-light text-dark fw-bold rounded p-2">Total Paid</p>
                            <h5>Rs. <?php echo $member['total_paid']; ?></h5>
                        </div>
                        <div class="col-sm-auto m-1 pt-3 pb-3 ps-auto pe-auto shadow-sm bg-danger-subtle text-danger rounded member-info">
                            <p class="alert alert-light text-dark fw-bold rounded p-2">Payment Remaining</p>
                            <h5>Rs.
                                <?php
                                if ($member['remaining_payment'] == "null") {
                                    echo $member['loan_amount'];
                                } else {
                                    echo $member['remaining_payment'];
                                }
                                ?>
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js" integrity="sha256-gOQJIa9+K/XdfAuBkg2ONAdw5EnQbokw/s2b8BqsRFg=" crossorigin="anonymous"></script>
</body>

</html>

<style>
    .edit-member {
        margin: 20px 0;
        width: 100%;
    }

    .member-img {
        height: 250px;
        border-radius: 10px;
    }

    .member-info-heading {
        margin: 0;
        font-size: 11px;
    }

    .member-info h5 {
        font-weight: bold;
    }

    .sign-doc {
        min-height: fit-content;
        min-width: fit-content;
    }
</style>