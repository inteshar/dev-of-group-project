<?php
error_reporting(E_ALL & ~E_NOTICE);
require_once '../dbConnect.php';

if (isset($_GET['memberId'])) {
    $memberId = $_GET['memberId'];
} else {
    $memberId = "";
    $msg = "Member ID is required.";
}

$stmt = $conn->prepare("SELECT members.*, loan_account.* FROM members INNER JOIN loan_account ON members.id=loan_account.member_id WHERE member_id=:id");
$stmt->bindParam(':id', $memberId);
if ($stmt->execute()) {
    // Fetch the result as an associative array
    $member = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
<div class="container">
    <div class="d-flex justify-content-center align-items-center">
        <div class="status-check rounded p-3" id="loan-status-download">
            <div>
                <form action="#" class="row">
                    <div class="col-sm-9">
                        <input type="text" name="memberId" class="form-control shadow" placeholder="Type your Member ID. e.g. 454215">
                    </div>
                    <div class="col-sm-3">
                        <input type="submit" class="btn btn-primary w-100 shadow" value="Check">
                    </div>
                </form>


                <div class="p-3">
                </div>

                <?php
                if ($member >= 1) {
                ?>
                    <div class="container bg-light pt-3 pb-3 rounded shadow" id="loanDetailsContainer">
                        <div class="download-loan-status-btn">
                            <svg onclick="downloadLoanDetails()" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-download" viewBox="0 0 16 16">
                                <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5" />
                                <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z" />
                            </svg>
                        </div>
                        <h4 class="customer-name text-center fw-bold text-muted pb-3">
                            <?php echo $member['name']; ?>
                            <br />
                            <i class="customer-address fw-normal">
                                <?php echo $member['address_perm']; ?>
                            </i>
                        </h4>
                        <div class="loan-details">
                            <p class="track-left">
                                Loan Amount <br />
                                <strong>Rs. <?php echo number_format($member['loan_amount'], 2); ?></strong>
                            </p>
                            <p class="track-right">
                                Loan ID <br />
                                <strong><?php echo $member['account_number']; ?></strong>
                            </p>
                        </div>
                        <div class="loan-details">
                            <p class="track-left">
                                Remaining Loan Amount <br />
                                <strong>Rs. <?php echo number_format($member['remaining_payment'], 2); ?></strong>
                            </p>
                            <p class="track-right">
                                Plan <br />
                                <strong><?php echo $member['plan']; ?> Days</strong>
                            </p>
                        </div>
                        <div class="loan-details">
                            <p class="track-left">
                                EMI <br />
                                <strong>Rs. <?php echo number_format($member['emi'], 2); ?>/day</strong>
                            </p>
                            <p class="track-right">
                                Loan Status <br />
                                <?php if ($member['status'] === '0') {
                                    echo "<strong class='text-danger'>Pending</strong>";
                                } else {
                                    echo "<strong class='text-success'>Approved</strong>";
                                } ?>
                            </p>
                        </div>
                        <p class="track-footer text-center text-muted pt-3">
                            <?php if ($member['status'] === '0') {
                                echo "
                                Our team is handling your loan request. You'll receive an update
                            soon on your email and phone about the status of your
                            application.
                            <br />
                            <br />
                            For any further query, please contact us on
                            <br />
                            <strong>
                                +91-9102531527
                                <br />
                                developmentofgroup@gmail.com
                            </strong>
                                ";
                            } else {
                                echo "
                                <strong>Congratulations! Your loan request has been successfully approved by our team.</strong> 
                            <br />
                            <br />
                            For any further query, please contact us on
                            <br />
                            <strong>
                                +91-9102531527
                                <br />
                                developmentofgroup@gmail.com
                            </strong>
                                ";
                            } ?>
                        </p>
                    </div>
                <?php
                } else {
                ?>
                    <div class="mt-3">
                        <?php
                        $msg = "<p class='alert alert-info text-success shadow fw-bold text-center'>Use your Member ID to check your Loan Account Status.</p>";
                        echo $msg;
                        ?>
                    </div>
                <?php
                } ?>
            </div>
        </div>
    </div>
</div>
<script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
<!-- JavaScript to handle the download -->
<script>
    function downloadLoanDetails() {
        // Get the HTML element to capture
        const loanDetailsContainer = document.getElementById('loanDetailsContainer');

        // Use html2canvas to capture the content
        html2canvas(loanDetailsContainer).then(function(canvas) {
            // Convert the canvas to PNG data URL
            const dataUrl = canvas.toDataURL('image/png');

            // Create a temporary link element
            const a = document.createElement('a');
            a.href = dataUrl;
            a.download = 'loan_details.png';

            // Trigger a click on the link to start the download
            a.click();
        });
    }
</script>

<style>
    .status-check {
        background: #e3e3e3;
        width: 500px;
    }

    .customer-name {
        border-bottom: 1px dashed rgb(189, 189, 189);
    }

    .customer-address {
        font-size: 11px;
        padding: 0%;
        margin: 0%;
    }

    .loan-details {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        padding-top: 12px;
        padding-left: 16px;
        padding-right: 16px;
    }

    .loan-details p {
        font-size: 12px;
    }

    .loan-details strong {
        font-size: 1rem;
    }

    .track-left {
        text-align: left;
    }

    .track-right {
        text-align: right;
    }

    .track-footer {
        border-top: 1px dashed rgb(189, 189, 189);
        font-size: 11px;
    }

    .download-loan-status-btn {
        display: flex;
        justify-content: space-between;
        margin: 0 12px 12px 12px;
        cursor: pointer;
    }

    .token-number {
        font-size: 10px;
    }
</style>