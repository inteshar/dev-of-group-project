<?php
require_once '../../dbConnect.php';

// Fetch data from MySQL
$stmt = $conn->prepare("SELECT members.id, loan_account.*, members.name, members.photo
FROM members
INNER JOIN loan_account ON members.id = loan_account.member_id
WHERE loan_account.status = '0'
ORDER BY account_opened_on DESC;");
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Convert data to JSON
$json_data = json_encode($result);

// Write JSON data to a file
file_put_contents('../DataFiles/loan_account_data.json', $json_data);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="icon" href="./Assets/favicon.ico" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="refresh" content="10">
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
        <h1 class="pb-0 p-2">Loan Account Requests</h1>

        <div class="contianer ps-2">
            <p>This page will automatically refresh in <span style="max-width: 25px;" class="p-1 fw-bold text-center bg-danger-subtle text-danger rounded" id="progressIndicator"></span>
            </p>
        </div>

        <script>
            const progressBar = document.getElementById('progressIndicator');
            let timeLeft = 10;

            function updateProgressBar() {
                const interval = 1000; // Update every second
                const progressIncrement = (100 / timeLeft) * (interval / 1000);

                const timer = setInterval(() => {
                    timeLeft--;

                    if (timeLeft >= 0) {
                        progressBar.style.width = (100 - (progressIncrement * timeLeft)) + '%';
                        progressBar.innerText = timeLeft;
                    } else {
                        clearInterval(timer);
                    }
                }, interval);
            }

            updateProgressBar();
        </script>

        <div class="manage-member shadow">
            <div class="bg-primary-subtle rounded p-1">
                <div class="p-3 d-flex justify-content-between align-items-center mb-3">
                    <!-- <div>
                        <select class="form-select shadow-sm" id="itemNumber">
                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="20">20</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div> -->

                    <div>
                        <input type="search" class="form-control shadow-sm" id="searchByName" placeholder="Search by Loan ID">
                    </div>

                </div>
                <div class="table-container mt-3">
                    <table id="myTable" class="table table-hover text-center">
                        <thead class="table-warning fw-bold">
                            <tr>
                                <td>SN</td>
                                <td>Loan ID</td>
                                <!-- <td>Name</td> -->
                                <td>Photo</td>
                                <td>Loan Amount</td>
                                <td>Approved Amount</td>
                                <td>Plan(Days)</td>
                                <td>EMI/Day</td>
                                <td>Active</td>
                                <td>Requested On</td>
                                <td>Actions</td>
                            </tr>
                        </thead>
                        <tbody class="align-middle">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div>
        <div class="modal fade" id="confirmApproveModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm Approval</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="confirmationText">
                        Are you sure you want to approve the following loan request?
                        <table class="table table-bordered mt-2">
                            <tr>
                                <td>Loan ID</td>
                                <td class="fw-bold text-success" id="loanId"></td>
                            </tr>
                            <tr>
                                <td>Customer Name</td>
                                <td class="fw-bold text-success" id="memberName"></td>
                            </tr>
                            <tr>
                                <td>Loan Amount</td>
                                <td class="fw-bold text-success" id="loanAmount"></td>
                            </tr>
                            <tr>
                                <td>Approved Amount</td>
                                <td class="fw-bold text-success" id="approvedAmount"></td>
                            </tr>
                            <tr>
                                <td>Plan</td>
                                <td class="fw-bold text-success" id="plan"></td>
                            </tr>
                            <tr>
                                <td>EMI/day</td>
                                <td class="fw-bold text-success" id="emi">Rs.</td>
                            </tr>
                        </table>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-warning" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-success" onclick="proceedApprove()">Approve</button>
                    </div>
                </div>
            </div>
        </div>

        <input type="hidden" id="approveId" value="">
    </div>
    <div>
        <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm Denial</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="confirmationText">
                        Are you sure you want to deny the following loan request?
                        <table class="table table-bordered mt-2">
                            <tr>
                                <td>Loan ID</td>
                                <td class="fw-bold text-danger" id="loanId1"></td>
                            </tr>
                            <tr>
                                <td>Customer Name</td>
                                <td class="fw-bold text-danger" id="memberName1"></td>
                            </tr>
                            <tr>
                                <td>Loan Amount</td>
                                <td class="fw-bold text-danger" id="loanAmount1"></td>
                            </tr>
                            <tr>
                                <td>Approved Amount</td>
                                <td class="fw-bold text-danger" id="approvedAmount1"></td>
                            </tr>
                            <tr>
                                <td>Plan</td>
                                <td class="fw-bold text-danger" id="plan1"></td>
                            </tr>
                            <tr>
                                <td>EMI/day</td>
                                <td class="fw-bold text-danger" id="emi1">Rs.</td>
                            </tr>
                        </table>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-warning" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger" onclick="proceedDeny()">Deny</button>
                    </div>
                </div>
            </div>
        </div>

        <input type="hidden" id="denyId" value="">
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js" integrity="sha256-gOQJIa9+K/XdfAuBkg2ONAdw5EnQbokw/s2b8BqsRFg=" crossorigin="anonymous"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            let count = 1;
            fetch('../DataFiles/loan_account_data.json')
                .then(response => response.json())
                .then(data => {
                    const tableBody = document.querySelector('#myTable tbody');
                    data.forEach(row => {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                        <td>${count++}</td>
                        <td class="table-row fw-bold">${row.account_number}</td>
                        <td class="table-row"><img class="user-img shadow" src="../../Admin/MembersFiles/${row.name}/${row.photo}" /></td>
                <td class="table-row">${row.loan_amount}</td>
                <td class="table-row">${row.approval_amount}</td>
                <td class="table-row">${row.plan}</td>
                <td class="table-row">${row.emi}</td>
                <td class="table-row">${row.status === '0' ? `<svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="text-danger bi bi-x-circle-fill" viewBox="0 0 16 16">
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293z"/>
                        </svg>`:`<svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="text-success bi bi-check-circle-fill" viewBox="0 0 16 16">
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                        </svg>`}</td>
                <td class="table-row">${row.request_date}</td>
                <td>
                    <button onclick="confirmApprove('${row.account_number}', '${row.name}', '${row.loan_amount}', '${row.approval_amount}', '${row.plan}', '${row.emi}', '${row.member_id}')" class="btn btn-success fw-bold shadow-sm m-1">Approve</button>
                    <button onclick="confirmDeny('${row.account_number}', '${row.name}', '${row.loan_amount}', '${row.approval_amount}', '${row.plan}', '${row.emi}', '${row.member_id}')" class="btn btn-danger fw-bold shadow-sm m-1">Deny</button>
                </td>
            `;
                        tableBody.appendChild(tr);
                    });
                })
                .catch(error => console.error('Error fetching data:', error));

            // Search functionality
            const searchInput = document.getElementById('searchByName');
            searchInput.addEventListener('input', () => {
                const searchText = searchInput.value.toLowerCase().trim();
                const tableRows = document.querySelectorAll('#myTable tbody tr');

                tableRows.forEach(row => {
                    const nameCell = row.querySelector('.table-row');
                    if (nameCell) {
                        const name = nameCell.textContent.toLowerCase();
                        row.style.display = name.includes(searchText) ? '' : 'none';
                    }
                });
            });
        });
    </script>
    <script>
        function confirmApprove(id, name, loan_amount, approved_amount, plan, emi, memberId) {
            // Show the Bootstrap modal
            var myModal = new bootstrap.Modal(document.getElementById('confirmApproveModal'));
            myModal.show();

            // Store the ID for deletion when confirmed
            document.getElementById('approveId').value = memberId;

            // Display member's name in the modal
            document.getElementById('memberName').innerText = name;
            document.getElementById('loanId').innerText = id;
            document.getElementById('loanAmount').innerText = loan_amount;
            document.getElementById('approvedAmount').innerText = approved_amount;
            document.getElementById('plan').innerText = plan;
            document.getElementById('emi').innerText = emi;
        }

        function proceedApprove() {
            var id = document.getElementById('approveId').value;
            window.location.href = `../../Admin/AdminComponents/LoanApprove.php?id=${id}`;
        }
    </script>
    <script>
        function confirmDeny(id, name, loan_amount, approved_amount, plan, emi, memberId) {
            // Show the Bootstrap modal
            var myModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
            myModal.show();

            // Store the ID for deletion when confirmed
            document.getElementById('denyId').value = memberId;

            // Display member's name in the modal
            document.getElementById('memberName1').innerText = name;
            document.getElementById('loanId1').innerText = id;
            document.getElementById('loanAmount1').innerText = loan_amount;
            document.getElementById('approvedAmount1').innerText = approved_amount;
            document.getElementById('plan1').innerText = plan;
            document.getElementById('emi1').innerText = emi;
        }

        function proceedDeny() {
            var id = document.getElementById('denyId').value;
            window.location.href = `../../Admin/AdminComponents/LoanDeny.php?id=${id}`;
        }
    </script>
</body>

</html>

<style>
    .manage-member {
        margin: 20px 0;
    }

    .manage-member .table-container {
        overflow-x: auto;
    }

    .manage-member .table-container table .table-row {
        border-right: 1px solid black;
        border-left: 1px solid black;
    }

    .manage-member .user-img {
        height: 80px;
        border-radius: 30px;
    }
</style>