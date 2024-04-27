<?php
require_once '../../dbConnect.php';

// Fetch data from MySQL
$stmt = $conn->prepare("SELECT members.id, savings_account.*, members.name, members.photo FROM members INNER JOIN savings_account ON members.id=savings_account.member_id ORDER BY account_opened_on DESC");
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Convert data to JSON
$json_data = json_encode($result);

// Write JSON data to a file
file_put_contents('../DataFiles/savings_account_data.json', $json_data);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="icon" href="./Assets/favicon.ico" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" integrity="sha256-2TnSHycBDAm2wpZmgdi0z81kykGPJAkiUY+Wf97RbvY=" crossorigin="anonymous" />
    <title>Admin</title>
    <!-- Include html2pdf.js library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js"></script>

</head>

<body>
    <?php include '../../Admin/AdminComponents/SideBar.php' ?>
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Open New Savings Account!</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="row g-3 needs-validation" method="POST" action="../../Admin/AdminComponents/AddSavingsAccount.php">
                        <div class="col-sm-12">
                            <label for="validationCustom01" class="form-label">Choose a member<span class="text-danger fw-bold">*</span></label>
                            <select class="form-select shadow-sm" id="itemNumber" name="memberId" required>
                                <option selected disabled>---</option>
                                <?php
                                $stmt1 = $conn->prepare("SELECT members.id, members.name FROM members WHERE kyc_status=1");
                                $stmt1->execute();
                                $members = $stmt1->fetchAll(PDO::FETCH_ASSOC);
                                foreach ($members as $member) {
                                    $found = false;
                                    foreach ($result as $r) {
                                        if ($r['name'] === $member['name']) {
                                            $found = true;
                                            break;
                                        }
                                    }
                                    if (!$found) {
                                        echo '<option value=' . $member['id'] . '>' . $member['name'] . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-sm-12">
                            <label for="validationCustom01" class="form-label">Interest Rate (%)<span class="text-danger fw-bold">*</span></label>
                            <input type="text" class="form-control" id="validationCustom01" name="int_rate" required>
                            <div class="valid-feedback">
                                Looks good!
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="invalidCheck" required>
                                <label class="form-check-label" for="invalidCheck">
                                    Are you sure you want to open savings account for this member?
                                </label>
                                <div class="invalid-feedback">
                                    You must agree before submitting.
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <button class="btn btn-primary" type="submit">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="mt-3">
            <?php
            if (isset($_GET['msg'])) {
                $message = $_GET['msg'];
                echo $message;
            }
            ?>
        </div>
        <h1 class="pb-0 p-2">Savings Accounts</h1>
        <div class="manage-member shadow">
            <div class="bg-primary-subtle rounded p-1">
                <button type="button" class="btn btn-warning ms-3 mt-2 shadow-sm fw-bold" data-bs-toggle="modal" data-bs-target="#staticBackdrop">Open New Account</button>
                <div class="container mt-3">
                    <input type="search" class="form-control shadow-sm" id="searchByName" placeholder="Search by Name, Account Number or Account Open Date">
                </div>
                <div class="table-container mt-3">
                    <table id="myTable" class="table table-hover text-center">
                        <thead class="table-warning fw-bold">
                            <tr>
                                <td>Member ID</td>
                                <td>Name</td>
                                <td>Photo</td>
                                <td>Account No.</td>
                                <td>Interest Rate</td>
                                <td>Balance</td>
                                <td>Last Trans. Amount</td>
                                <td>Last Trans. Type</td>
                                <td>Last Trans. Date</td>
                                <td>Account Opened On</td>
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
        <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm Deletion</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-danger" id="confirmationText">
                        Are you sure you want to close/delete this savings account of <span class="fw-bold text-dark" id="memberName"></span>? This will also delete all the transaction history of this particular account.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger" onclick="proceedDelete()">Delete</button>
                    </div>
                </div>
            </div>
        </div>

        <input type="hidden" id="deleteId" value="">
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js" integrity="sha256-gOQJIa9+K/XdfAuBkg2ONAdw5EnQbokw/s2b8BqsRFg=" crossorigin="anonymous"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            fetch('../DataFiles/savings_account_data.json')
                .then(response => response.json())
                .then(data => {
                    const tableBody = document.querySelector('#myTable tbody');
                    data.forEach(row => {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                        <td class="table-row">${row.member_id}</td>
                        <td class="table-row">${row.name}</td>
                        <td class="table-row"><img class="user-img shadow" src="../../Admin/MembersFiles/${row.name}/${row.photo}" /></td>
                <td class="table-row">${row.account_number}</td>
                <td class="table-row">${row.interest_rate}</td>
                <td class="table-row">${row.account_bal}</td>
                <td class="table-row">${row.last_transaction_amount}</td>
                <td class="table-row">${row.last_transaction_type}</td>
                <td class="table-row">${row.last_transaction_date}</td>
                <td class="table-row">${row.account_opened_on}</td>
                <td>
                    <a href="../../Admin/Pages/SavingsAccountSummaryPage.php?memberId=${row.member_id}" class="btn btn-warning shadow-sm m-1"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                    <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                    <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"/>
                    </svg></a>
                    <button onclick="confirmDelete(${row.id}, '${row.name}')" class="btn btn-danger shadow-sm m-1"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                    <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
                    <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
                    </svg></button>
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
                    let found = false;

                    // Check each cell in the row for a match
                    row.querySelectorAll('.table-row').forEach(cell => {
                        const cellText = cell.textContent.toLowerCase();
                        if (cellText.includes(searchText)) {
                            found = true;
                        }
                    });

                    row.style.display = found ? '' : 'none';
                });
            });

        });
    </script>
    <script>
        function confirmDelete(id, name) {
            // Show the Bootstrap modal
            var myModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
            myModal.show();

            // Store the ID for deletion when confirmed
            document.getElementById('deleteId').value = id;

            // Display member's name in the modal
            document.getElementById('memberName').innerText = name;
        }

        function proceedDelete() {
            var id = document.getElementById('deleteId').value;
            window.location.href = `../../Admin/AdminComponents/DeleteSavingsAccount.php?id=${id}`;
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