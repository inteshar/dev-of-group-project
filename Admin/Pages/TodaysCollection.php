<?php
require_once '../../dbConnect.php';

// Fetch data from MySQL
$stmt = $conn->prepare("SELECT * FROM members JOIN payments ON members.id = payments.member_id WHERE payments.date = CURDATE();");
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Convert data to JSON
$json_data = json_encode($result);

// Write JSON data to a file
file_put_contents('../DataFiles/todayscollection.json', $json_data);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="./Assets/favicon.ico" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" integrity="sha256-2TnSHycBDAm2wpZmgdi0z81kykGPJAkiUY+Wf97RbvY=" crossorigin="anonymous" />
    <title>Today's Collection</title>
</head>
<body>
<?php include '../../Admin/AdminComponents/SideBar.php' ?>
<div class="container">
        
        <h1 class="pb-0 p-2 fw-bold">Today's Total Collection List</h1>
        <div class="manage-member shadow">
            <div class="bg-primary-subtle rounded p-1">
                <div class="container mt-3">
                    <input type="search" class="form-control shadow-sm" id="searchByName" placeholder="Search by Member ID, Name, Email or Mobile">
                </div>
                <div class="table-container mt-3">
                    <table id="myTable" class="table table-hover text-center">
                        <thead class="table-warning fw-bold">
                            <tr>
                                <td>Photo</td>
                                <td>Name</td>
                                <td>Mobile</td>
                                <td>Amount</td>
                                <td>Date</td>
                                <td>Collected By</td>
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
            fetch('../DataFiles/todayscollection.json')
                .then(response => response.json())
                .then(data => {
                    const tableBody = document.querySelector('#myTable tbody');
                    if (data.length === 0) {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `<td colspan="6" class="text-center p-3 rounded fw-bold text-success">NO PAYMENT COLLECTED TODAY</td>`;
                        tableBody.appendChild(tr);
                    } else {
                        data.forEach(row => {
                            const tr = document.createElement('tr');
                            tr.innerHTML = `
                            <td class="table-row"><img class="user-img shadow" src="../../Admin/MembersFiles/${row.name}/${row.photo}" /></td>
                            <td class="table-row">${row.name}</td>
                            <td class="table-row">${row.mobile}</td>
                            <td class="table-row">${new Intl.NumberFormat('en-US', { style: 'currency', currency: 'INR' }).format(row.amount)}</td>
                            <td class="table-row">${row.date}</td>
                            <td class="table-row">${row.staff}</td>
                            `;
                            tableBody.appendChild(tr);
                        });
                    }
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
</style>
</style>
</style>