<?php
require_once '../../dbConnect.php';

$stmt = $conn->prepare("SELECT members.id, members_kyc.*, members.name, members.photo FROM members INNER JOIN members_kyc ON members.id=members_kyc.member_id ORDER BY date_created DESC");
$stmt->execute();

// Fetch all the results as an associative array
$member_kyc = $stmt->fetchAll(PDO::FETCH_ASSOC);

$json_data = json_encode($member_kyc);

// Write JSON data to a file
file_put_contents('../DataFiles/members_kyc_data.json', $json_data);

$stmt2 = $conn->prepare("SELECT members.id, members.name from members WHERE kyc_status='0'");
$stmt2->execute();

// Fetch all the results as an associative array
$member = $stmt2->fetchAll(PDO::FETCH_ASSOC);
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
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Add new Member KYC</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="row g-3 needs-validation" method="POST" action="../../Admin/AdminComponents/AddMemberKYC.php" enctype="multipart/form-data" onsubmit="return validateFiles()">
                        <p class="text-danger pb-0">Only PDF files are accepted.</p>
                        <div class="col-sm-12">
                            <label for="validationCustom01" class="form-label">Choose a member<span class="text-danger fw-bold">*</span></label>
                            <select class="form-select shadow-sm" id="itemNumber" name="memberId" required>
                                <option selected disabled>---</option>
                                <?php
                                foreach ($member as $members) {

                                    echo '<option value=' . $members['id'] . '>' . $members['name'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-sm-12">
                            <label for="formFile" class="form-label">Aadhaar Card<span class="text-danger fw-bold">*</span></label>
                            <input class="form-control" type="file" id="photo" accept=".pdf" name="aadhaar_card" required>
                        </div>
                        <div class="col-sm-12">
                            <label for="formFile" class="form-label">PAN Card<span class="text-danger fw-bold">*</span></label>
                            <input class="form-control" type="file" id="photo" accept=".pdf" name="pan_card" required>
                        </div>
                        <div class="col-sm-12">
                            <label for="formFile" class="form-label">Signature<span class="text-danger fw-bold">*</span></label>
                            <input class="form-control" type="file" id="photo" accept=".pdf" name="signature" required>
                        </div>
                        <!-- <div class="col-sm-6">
                            <label for="validationCustom01" class="form-label">Relative Name<span class="text-danger fw-bold">*</span></label>
                            <input type="text" class="form-control" id="validationCustom01" name="rel_name" required>
                            <div class="valid-feedback">
                                Looks good!
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label for="validationCustom01" class="form-label">Relationship<span class="text-danger fw-bold">*</span></label>
                            <select class="form-select shadow-sm" id="itemNumber" name="relation" required>
                                <option selected disabled>---</option>
                                <option value="Father">Father</option>
                                <option value="Mother">Mother</option>
                                <option value="Brother">Brother</option>
                                <option value="Sister">Sister</option>
                                <option value="Husband">Husband</option>
                                <option value="Wife">Wife</option>
                                <option value="Uncle">Uncle</option>
                                <option value="Aunty">Aunty</option>
                                <option value="Others">Others</option>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label for="validationCustom01" class="form-label">Reference Name<span class="text-danger fw-bold">*</span></label>
                            <input type="text" class="form-control" id="validationCustom01" name="ref_name" required>
                            <div class="valid-feedback">
                                Looks good!
                            </div>
                        </div> -->
                        <div class="col-sm-12">
                            <label for="formFile" class="form-label">Reference Aadhaar Card<span class="text-danger fw-bold">*</span></label>
                            <input class="form-control" type="file" id="photo" accept=".pdf" name="ref_aadhaar" required>
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="invalidCheck" required>
                                <label class="form-check-label" for="invalidCheck">
                                    Are you sure you want to KYC for this member?
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
        <h1 class="pb-0 p-2">Manage Members KYC</h1>
        <div class="manage-member shadow">
            <div class="bg-primary-subtle rounded p-1">
                <button type="button" class="btn btn-warning ms-3 mt-2 shadow-sm fw-bold" data-bs-toggle="modal" data-bs-target="#staticBackdrop">Add Member KYC</button>
                <div class="container mt-3">
                    <input type="search" class="form-control shadow-sm" id="searchByName" placeholder="Search by Member ID, Name, Email or Mobile">
                </div>
                <div class="table-container mt-3">
                    <table id="myTable" class="table table-hover text-center">
                        <thead class="table-warning fw-bold">
                            <tr>
                                <td>Member ID</td>
                                <td>Name</td>
                                <td>Photo</td>
                                <td>Aadhaar Card</td>
                                <td>PAN Card</td>
                                <td>Signature</td>
                                <td>Reference Aadhaar</td>
                                <td>KYC Date</td>
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
            fetch('../DataFiles/members_kyc_data.json')
                .then(response => response.json())
                .then(data => {
                    const tableBody = document.querySelector('#myTable tbody');
                    data.forEach(row => {
                        const tr = document.createElement('tr');

                        const aadhaar_card = row.aadhaar_card ? `<svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="text-success bi bi-check-circle-fill" viewBox="0 0 16 16">
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                        </svg>` : `<svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="text-danger bi bi-x-circle-fill" viewBox="0 0 16 16">
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293z"/>
                        </svg>`;
                        const pan_card = row.pan_card ? `<svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="text-success bi bi-check-circle-fill" viewBox="0 0 16 16">
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                        </svg>` : `<svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="text-danger bi bi-x-circle-fill" viewBox="0 0 16 16">
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293z"/>
                        </svg>`;
                        const signature = row.signature ? `<svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="text-success bi bi-check-circle-fill" viewBox="0 0 16 16">
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                        </svg>` : `<svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="text-danger bi bi-x-circle-fill" viewBox="0 0 16 16">
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293z"/>
                        </svg>`;
                        const ref_aadhaar = row.ref_aadhaar ? `<svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="text-success bi bi-check-circle-fill" viewBox="0 0 16 16">
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                        </svg>` : `<svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="text-danger bi bi-x-circle-fill" viewBox="0 0 16 16">
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293z"/>
                        </svg>`;

                        tr.innerHTML = `
                        <td class="table-row">${row.member_id}</td>
                        <td class="table-row">${row.name}</td>
                        <td class="table-row"><img class="user-img shadow" src="../../Admin/MembersFiles/${row.name}/${row.photo}" /></td>
                <td class="table-row">${aadhaar_card}</td>
                <td class="table-row">${pan_card}</td>
                <td class="table-row">${signature}</td>
                <td class="table-row">${ref_aadhaar}</td>
                <td class="table-row">${row.date_created}</td>
                <td>
                    <a href="../../Admin/Pages/ViewMemberDetails.php?memberId=${row.member_id}" class="btn btn-warning shadow-sm m-1"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                    <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                    <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"/>
                    </svg></a>
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
        function validateFiles() {
            var allowedExtensions = /(\.pdf)$/i;
            var fileInputs = document.querySelectorAll('input[type="file"]');

            for (var i = 0; i < fileInputs.length; i++) {
                var fileInput = fileInputs[i];
                var fileName = fileInput.value;

                if (!allowedExtensions.exec(fileName)) {
                    alert('Please select only PDF files.');
                    return false;
                }
            }

            return true;
        }
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
            window.location.href = `../../Admin/AdminComponents/DeleteMemberKYC.php?id=${id}`;
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