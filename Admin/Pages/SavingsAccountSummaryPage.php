<?php
error_reporting(E_ALL & ~E_NOTICE);
require_once '../../dbConnect.php';

$memberId = $_GET['memberId'];
$from = isset($_POST['from']) ? $_POST['from'] : '';
$to = isset($_POST['to']) ? $_POST['to'] : '';

$stmt = $conn->prepare("SELECT members.*, savings_account.* FROM members INNER JOIN savings_account ON members.id=savings_account.member_id WHERE member_id=:id");
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
    <!-- Withdraw money modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Withdraw Money from Account No. <span class="text-danger"><?php echo $member['account_number']; ?></span></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="row g-3 needs-validation" method="POST" action="../../Admin/AdminComponents/WithdrawSavingsMoney.php?memberId=<?php echo $memberId; ?>">
                        <div class="col-sm-12">
                            <input type="text" class="form-control bg-danger-subtle text-danger fw-bold shadow-sm" hidden name="acc_no" value="<?php echo $member['account_number'] ?>">
                        </div>
                        <div class="col-sm-12">
                            <label for="validationCustom02" class="form-label">Withdraw Amount<span class="text-danger fw-bold">*</span></label>
                            <input type="number" class="form-control shadow-sm" id="validationCustom02" name="amount" required>
                            <div class="valid-feedback">
                                Looks good!
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <label for="validationCustom01" class="form-label">Purpose<span class="text-danger fw-bold">*</span></label>
                            <input type="text" class="form-control shadow-sm" id="validationCustom01" name="purpose" required>
                            <div class="valid-feedback">
                                Looks good!
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input shadow-sm" type="checkbox" value="" id="invalidCheck" required>
                                <label class="form-check-label" for="invalidCheck">
                                    Are you sure you want to withdraw from the account number shown above?
                                </label>
                                <div class="invalid-feedback">
                                    You must agree before submitting.
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <button class="btn btn-primary shadow" type="submit">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Withdraw money modal -->

    <!-- Deposit money modal -->
    <div class="modal fade" id="staticBackdrop1" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Deposit Money to Account No. <span class="text-danger"><?php echo $member['account_number']; ?></span></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="row g-3 needs-validation" method="POST" action="../../Admin/AdminComponents/DepositSavingsMoney.php?memberId=<?php echo $memberId; ?>">
                        <div class="col-sm-12">
                            <input type="text" class="form-control bg-danger-subtle text-danger fw-bold shadow-sm" hidden name="acc_no" value="<?php echo $member['account_number'] ?>">
                        </div>
                        <div class="col-sm-12">
                            <label for="validationCustom02" class="form-label">Deposit Amount<span class="text-danger fw-bold">*</span></label>
                            <input type="number" class="form-control shadow-sm" id="validationCustom02" name="amount" required>
                            <div class="valid-feedback">
                                Looks good!
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <label for="validationCustom01" class="form-label">Purpose<span class="text-danger fw-bold">*</span></label>
                            <input type="text" class="form-control shadow-sm" id="validationCustom01" name="purpose" required>
                            <div class="valid-feedback">
                                Looks good!
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input shadow-sm" type="checkbox" value="" id="invalidCheck" required>
                                <label class="form-check-label" for="invalidCheck">
                                    Are you sure you want to deposit to the account number shown above?
                                </label>
                                <div class="invalid-feedback">
                                    You must agree before submitting.
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <button class="btn btn-primary shadow" type="submit">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Deposit money modal -->
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
            <h1 class="m-3 mb-4">Savings Account Summary</h1>
            <div class="card-group mb-4">
                <div class="card border-0 p-4 ps-5 pe-5">
                    <a class="btn btn-success shadow fw-bold" href="./SavingsStatementDownload.php?memberId=<?php echo $member['member_id']; ?>&from=<?php echo $from; ?>&to=<?php echo $to; ?>" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-file-earmark-arrow-down" viewBox="0 0 16 16">
                            <path d="M8.5 6.5a.5.5 0 0 0-1 0v3.793L6.354 9.146a.5.5 0 1 0-.708.708l2 2a.5.5 0 0 0 .708 0l2-2a.5.5 0 0 0-.708-.708L8.5 10.293z" />
                            <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2M9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5z" />
                        </svg> Download Statement</a>
                </div>
                <div class="card border-0 p-4 ps-5 pe-5">
                    <button type="button" class="btn btn-warning shadow fw-bold" data-bs-toggle="modal" data-bs-target="#staticBackdrop"><img class="me-3" width="25" height="25" src="https://img.icons8.com/ios/50/initiate-money-transfer.png" alt="initiate-money-transfer" /> Withdraw</button>
                </div>
                <div class="card border-0 p-4 ps-5 pe-5">
                    <button type="button" class="btn btn-info shadow fw-bold" data-bs-toggle="modal" data-bs-target="#staticBackdrop1"><img class="me-3" width="25" height="25" src="https://img.icons8.com/ios/50/request-money.png" alt="request-money" /> Deposit</button>
                </div>
            </div>
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
                            <p class="member-info-heading">Date of Birth</p>
                            <h5><?php echo $member['dob']; ?></h5>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="member-info">
                            <p class="member-info-heading">Account Number</p>
                            <h5><?php echo $member['account_number']; ?></h5>
                        </div>
                        <div class="member-info">
                            <p class="member-info-heading">Interest Rate</p>
                            <h5><?php echo $member['interest_rate']; ?>%</h5>
                        </div>
                        <div class="member-info">
                            <p class="member-info-heading">Account Balance</p>
                            <h5>Rs. <?php echo $member['account_bal']; ?></h5>
                        </div>
                        <div class="member-info">
                            <p class="member-info-heading">Temporary Address</p>
                            <h5><?php echo $member['address_temp']; ?></h5>
                        </div>
                        <div class="member-info">
                            <p class="member-info-heading">Account Opened on</p>
                            <h5><?php echo $member['account_opened_on']; ?></h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="pt-5">
                <?php
                // add a snippet to fetch statement based on the date range chosen by user
                if ($from != "" && $to != "") {
                    $stmt = $conn->prepare("SELECT * FROM savings_statement WHERE account_number = '" . $member['account_number'] . "' AND transaction_date BETWEEN '$from' AND '$to' ORDER BY transaction_date DESC");
                    $stmt->execute();
                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    // Convert data to JSON
                    $json_data = json_encode($result);

                    // Write JSON data to a file
                    file_put_contents('../DataFiles/savings_statement_data.json', $json_data);

                    echo "<p class='fw-bold bg-success-subtle p-3 text-success text-center rounded'>Displaying the statement for the period between " . date('F j, Y', strtotime($from)) . " and " . date('F j, Y', strtotime($to)) . "</p>";
                } else {
                    $stmt = $conn->prepare("SELECT * FROM savings_statement WHERE account_number = '" . $member['account_number'] . "' ORDER BY transaction_date DESC");
                    $stmt->execute();
                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    // Convert data to JSON
                    $json_data = json_encode($result);

                    // Write JSON data to a file
                    file_put_contents('../DataFiles/savings_statement_data.json', $json_data);
                }
                // snippet here //
                ?>
                <form class="d-flex justify-content-center align-items-center" action="#" method="POST">

                    <label for="startDate" class="p-3">From:</label>
                    <input class="form-control shadow" type="date" id="startDate" name="from">

                    <label for="endDate" class="p-3">To:</label>
                    <input class="form-control shadow" type="date" id="endDate" name="to">

                    <button class="btn btn-warning ms-3 shadow" type="submit"><svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-filter fw-bold" viewBox="0 0 16 16">
                            <path d="M6 10.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5m-2-3a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5m-2-3a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5" />
                        </svg></button>
                </form>
                <p class="text-center fw-bold mt-2">Account Statement</p>
                <div>
                    <input type="search" class="form-control shadow" id="searchInput" placeholder="Search by Tran. ID">
                </div>
                <table id="statement" class="mt-3 table table-striped table-bordered">
                    <thead class="table-warning fw-bold">
                        <tr>
                            <td>SN</td>
                            <td>Description</td>
                            <td>Withdrawal</td>
                            <td>Deposit</td>
                            <td>Date</td>
                        </tr>
                    </thead>
                    <tbody class="align-middle">

                    </tbody>
                </table>

            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js" integrity="sha256-gOQJIa9+K/XdfAuBkg2ONAdw5EnQbokw/s2b8BqsRFg=" crossorigin="anonymous"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            let count = 1;
            fetch('../DataFiles/savings_statement_data.json')
                .then(response => response.json())
                .then(data => {
                    const tableBody = document.querySelector('#statement tbody');
                    data.forEach(row => {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                    <td>${count++}</td>
                    <td class="table-row">${row.purpose+"</br>"+row.trans_id}</td>
                    <td class="table-row">${row.withdrawal}</td>
                    <td class="table-row">${row.deposit}</td>
                    <td class="table-row">${row.transaction_date}</td>
                `;
                        tableBody.appendChild(tr);
                    });
                })
                .catch(error => console.error('Error fetching data:', error));

            // Search functionality
            const searchInput = document.getElementById('searchInput');
            searchInput.addEventListener('input', () => {
                const searchText = searchInput.value.toLowerCase().trim();
                const tableRows = document.querySelectorAll('#statement tbody tr');

                tableRows.forEach(row => {
                    const descriptionCell = row.querySelector('.table-row:nth-child(2)'); // Adjust index if needed
                    if (descriptionCell) {
                        const description = descriptionCell.textContent.toLowerCase();
                        row.style.display = description.includes(searchText) ? '' : 'none';
                    }
                });
            });
        });
    </script>
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