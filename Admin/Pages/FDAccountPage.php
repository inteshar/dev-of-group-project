<?php
require_once '../../dbConnect.php';

// Fetch data from MySQL
$stmt = $conn->prepare("SELECT * FROM members ORDER BY date_created desc");
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Convert data to JSON
$json_data = json_encode($result);

// Write JSON data to a file
file_put_contents('../DataFiles/members_data.json', $json_data);
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
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Add your new Member wisely!</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="row g-3 needs-validation" method="POST" action="../../Admin/AdminComponents/AddMember.php" enctype="multipart/form-data">
                        <div class="col-sm-12">
                            <label for="formFile" class="form-label">Profile Picture<span class="text-danger fw-bold">*</span></label>
                            <input class="form-control" type="file" id="photo" name="photo">
                            <p class="text-danger">Only JPEG, JPG, and PNG files are accepted.</p>
                        </div>
                        <div class="col-sm-12">
                            <label for="validationCustom01" class="form-label">Member Name<span class="text-danger fw-bold">*</span></label>
                            <input type="text" class="form-control" id="validationCustom01" name="name" required>
                            <div class="valid-feedback">
                                Looks good!
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label for="validationCustom02" class="form-label">Member Mobile Number<span class="text-danger fw-bold">*</span></label>
                            <input type="number" class="form-control" id="validationCustom02" name="mobile" maxlength="10" required>
                            <div class="valid-feedback">
                                Looks good!
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label for="validationCustomUsername" class="form-label">Member Email Address<span class="text-danger fw-bold">*</span></label>
                            <div class="input-group has-validation">
                                <input type="text" class="form-control" id="validationCustomUsername" aria-describedby="inputGroupPrepend" name="email" required>
                                <div class="invalid-feedback">
                                    Please choose a username.
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <label for="validationCustomUsername" class="form-label">Date of Birth<span class="text-danger fw-bold">*</span></label>
                            <div class="input-group has-validation">
                                <input type="date" class="form-control" id="validationCustomUsername" aria-describedby="inputGroupPrepend" name="dob" required>
                                <div class="invalid-feedback">
                                    Please choose a username.
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label for="floatingTextarea">Permanent Address<span class="text-danger fw-bold">*</span></label>
                            <textarea class="form-control" placeholder="Your hometown address" id="floatingTextarea" name="address_perm"></textarea>
                        </div>
                        <div class="col-md-12">
                            <label for="floatingTextarea">Temporary Address<span class="text-danger fw-bold">*</span></label>
                            <textarea class="form-control" placeholder="Current address where you are residing" id="floatingTextarea" name="address_temp"></textarea>
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="invalidCheck" required>
                                <label class="form-check-label" for="invalidCheck">
                                    Are you sure you want to add this Member?
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
        <h1 class="pb-0 p-2">Fixed Deposit Accounts</h1>
        <div class="manage-member shadow">
            <div class="bg-primary-subtle rounded p-1">
                <button type="button" class="btn btn-warning ms-3 mt-2 shadow-sm fw-bold" data-bs-toggle="modal" data-bs-target="#staticBackdrop">Open New Account</button>
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
                        <input type="search" class="form-control shadow-sm" id="searchByName" placeholder="Search by Name">
                    </div>

                </div>
                <div class="table-container mt-3">
                    <table id="myTable" class="table table-hover text-center">
                        <thead class="table-warning fw-bold">
                            <tr>
                                <td>SN</td>
                                <td>Name</td>
                                <td>Photo</td>
                                <td>Mobile</td>
                                <td>Email</td>
                                <td>DOB</td>
                                <td>KYC Status</td>
                                <td>Address Permanent</td>
                                <td>Address Temporary</td>
                                <td>Date Created</td>
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
                        Are you sure you want to delete this record? This will also delete all the documents and records of <span class="fw-bold text-dark" id="memberName"></span>.
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
            let count = 1;
            fetch('../DataFiles/members_data.json')
                .then(response => response.json())
                .then(data => {
                    const tableBody = document.querySelector('#myTable tbody');
                    data.forEach(row => {
                        const tr = document.createElement('tr');
                        const kycStatus = row.kyc_status === '1' ? `<svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="text-success bi bi-check-circle-fill" viewBox="0 0 16 16">
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                        </svg>` : `<svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="text-danger bi bi-x-circle-fill" viewBox="0 0 16 16">
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293z"/>
                        </svg>`;
                        tr.innerHTML = `
                        <td>${count++}</td>
                        <td class="table-row">${row.name}</td>
                        <td class="table-row"><img class="user-img shadow" src="../../Admin/MembersFiles/${row.name}/${row.photo}" /></td>
                <td class="table-row">${row.mobile}</td>
                <td class="table-row">${row.email}</td>
                <td class="table-row">${row.dob}</td>
                <td class="table-row">${kycStatus}</td>
                <td class="table-row">${row.address_perm}</td>
                <td class="table-row">${row.address_temp}</td>
                <td class="table-row">${row.date_created}</td>
                <td>
                    <a href="../../Admin/Pages/EditMemberPage.php?memberId=${row.id}" class="btn btn-warning shadow-sm m-1"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
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
            window.location.href = `../../Admin/AdminComponents/DeleteMember.php?id=${id}`;
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