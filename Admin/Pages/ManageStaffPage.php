<?php
require_once '../../dbConnect.php';

// Fetch data from MySQL
$stmt = $conn->prepare("SELECT * FROM users ORDER BY date_joined desc");
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Convert data to JSON
$json_data = json_encode($result);

// Write JSON data to a file
file_put_contents('../DataFiles/staff_data.json', $json_data);
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
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Add your new staff wisely!</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="row g-3 needs-validation" method="POST" action="../../Admin/AdminComponents/AddStaff.php">
                        <div class="col-sm-12">
                            <label for="validationCustom01" class="form-label">Staff Name<span class="text-danger fw-bold">*</span></label>
                            <input type="text" class="form-control" id="validationCustom01" name="name" required>
                            <div class="valid-feedback">
                                Looks good!
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label for="validationCustom02" class="form-label">Staff Mobile Number<span class="text-danger fw-bold">*</span></label>
                            <input type="number" class="form-control" id="validationCustom02" name="mobile" required>
                            <div class="valid-feedback">
                                Looks good!
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label for="validationCustomUsername" class="form-label">Staff Email Address<span class="text-danger fw-bold">*</span></label>
                            <div class="input-group has-validation">
                                <input type="text" class="form-control" id="validationCustomUsername" aria-describedby="inputGroupPrepend" name="email" required>
                                <div class="invalid-feedback">
                                    Please choose a username.
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="validationCustom03" class="form-label">Address<span class="text-danger fw-bold">*</span></label>
                            <input type="text" class="form-control" id="validationCustom03" name="address" required>
                            <div class="invalid-feedback">
                                Please provide a valid Address.
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label for="validationCustom05" class="form-label">Password<span class="text-danger fw-bold">*</span></label>
                            <input type="text" class="form-control" id="validationCustom05" name="password" required>
                            <div class="invalid-feedback">
                                Please provide a valid password.
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <label for="validationCustom05" class="form-label">Staff Role<span class="text-danger fw-bold">*</span></label>
                            <select class="form-select" aria-label="Default select example" name="role">
                                <option selected disabled>Select a role</option>
                                <option value="admin">Admin</option>
                                <option value="staff">Staff</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="invalidCheck" required>
                                <label class="form-check-label" for="invalidCheck">
                                    Are you sure you want to add this staff?
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
        <h1 class="pb-0 p-2">Manage Staffs</h1>
        <div class="manage-staff shadow">
            <div class="bg-primary-subtle rounded p-1">
                <button type="button" class="btn btn-warning ms-3 mt-2 shadow-sm fw-bold" data-bs-toggle="modal" data-bs-target="#staticBackdrop">Add Staff</button>
                <!-- <div class="p-3 d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <select class="form-select shadow-sm" id="itemNumber">
                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="20">20</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>

                    <div>
                        <input type="search" class="form-control shadow-sm" placeholder="Search by Name">
                    </div>
                </div> -->
                <div class="table-container mt-3">
                    <table id="myTable" class="table table-hover">
                        <thead class="fw-bold">
                            <tr>
                                <td>SN</td>
                                <td>Name</td>
                                <td>Mobile</td>
                                <td>Email</td>
                                <td>Password</td>
                                <td>Address</td>
                                <td>Date Created</td>
                                <td>Actions</td>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js" integrity="sha256-gOQJIa9+K/XdfAuBkg2ONAdw5EnQbokw/s2b8BqsRFg=" crossorigin="anonymous"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {

            fetch('../DataFiles/staff_data.json')
                .then(response => response.json())
                .then(data => {
                    const tableBody = document.querySelector('#myTable tbody');
                    let count = 1;
                    data.forEach(row => {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                <td>${count++}</td>
                <td class="table-row">${row.name}</td>
                <td class="table-row">${row.mobile}</td>
                <td class="table-row">${row.email}</td>
                <td class="table-row"><span class="blurred password-col" onclick="toggleBlur(this)">${row.password}</span></td>
                <td class="table-row">${row.address}</td>
                <td class="table-row">${row.date_joined}</td>
                <td class="table-row">
                    <a href="../../Admin/Pages/EditStaffPage.php?staffId=${row.id}" class="btn btn-warning shadow-sm"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                    </svg></a>
                    <button onclick="confirmDelete(${row.id})" class="btn btn-danger shadow-sm"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                    <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
                    <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
                    </svg></button>
                </td>
            `;
                        tableBody.appendChild(tr);
                    });
                })
                .catch(error => console.error('Error fetching data:', error));
        });
    </script>
    <script>
        function confirmDelete(id) {
            if (confirm("Are you sure you want to delete this record?")) {
                window.location.href = `../../Admin/AdminComponents/DeleteStaff.php?id=${id}`;
            } else {
                // Do nothing or handle cancellation
            }
        }
    </script>
    <script>
        function toggleBlur(element) {
            // Toggle the 'blurred' class on the clicked element
            element.classList.toggle('blurred');
        }
    </script>
</body>

</html>

<style>
    .manage-staff {
        margin: 20px 0;
    }

    .manage-staff .table-container {
        overflow-x: auto;
    }

    .manage-staff .table-container table .table-row {
        min-width: 180px;
    }

    .manage-staff .password-col {
        cursor: pointer;
    }

    .blurred {
        filter: blur(5px);
    }
</style>