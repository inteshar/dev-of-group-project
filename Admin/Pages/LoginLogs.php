<?php
require_once '../../dbConnect.php';

// Fetch data from MySQL
$stmt = $conn->prepare("SELECT * FROM login_logs ORDER BY datetime desc");
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Convert data to JSON
$json_data = json_encode($result);

// Write JSON data to a file
file_put_contents('../DataFiles/login_logs_data.json', $json_data);
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
    <div>
        <div class="modal fade" id="confirmApproveModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm History Clear</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body bg-warning-subtle text-danger" id="confirmationText">
                        Are you sure you want to clear all the login history?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" onclick="proceedClear()">Yes</button>
                        <button type="button" class="btn btn-warning" data-bs-dismiss="modal">No</button>
                    </div>
                </div>
            </div>
        </div>

        <input type="hidden" id="approveId" value="">
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
        <h1 class="pb-0 p-2 pt-3">Login Logs</h1>
        <div class="manage-member shadow">
            <div class="bg-primary-subtle rounded p-1">
                <div class="p-3 d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <input type="search" class="form-control shadow-sm" id="searchByName" placeholder="Search by Email">
                    </div>
                    <button class="btn btn-outline-danger shadow" onclick="confirmClear()">Clear History</button>
                </div>
                <div class="table-container mt-3">
                    <table id="myTable" class="table table-hover text-center">
                        <thead class="table-warning fw-bold">
                            <tr>
                                <td>SN</td>
                                <td>Email</td>
                                <td>Date & Time</td>
                                <td>IP Address</td>
                                <td>Device</td>
                            </tr>
                        </thead>
                        <tbody class="align-middle">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js" integrity="sha256-gOQJIa9+K/XdfAuBkg2ONAdw5EnQbokw/s2b8BqsRFg=" crossorigin="anonymous"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            let count = 1;
            fetch('../DataFiles/login_logs_data.json')
                .then(response => response.json())
                .then(data => {
                    const tableBody = document.querySelector('#myTable tbody');
                    data.forEach(row => {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                        <td>${count++}</td>
                        <td class="table-row">${row.email}</td>
                <td class="table-row">${row.datetime}</td>
                <td class="table-row">${row.ip_address}</td>
                <td class="table-row">${row.device}</td>
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
        function confirmClear() {
            // Show the Bootstrap modal
            var myModal = new bootstrap.Modal(document.getElementById('confirmApproveModal'));
            myModal.show();

            // Store the ID for deletion when confirmed
            document.getElementById('approveId').value = memberId;
        }

        function proceedClear() {
            window.location.href = `../../Admin/AdminComponents/ClearLoginLogs.php`;
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