<?php
require_once '../../dbConnect.php';

$staffId = $_GET['staffId'];

$stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
$stmt->bindParam(':id', $staffId);
$stmt->execute();

// Fetch the result as an associative array
$user = $stmt->fetch(PDO::FETCH_ASSOC);
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
        <div class="edit-staff">
            <h1 class="m-3 mb-5">Edit your staff details</br>
                <p class="fs-6 mt-2">You are editing details of '<?php echo $user['name'] ?>'</p>
            </h1>
            <form class="row g-3 needs-validation shadow p-5 rounded" method="POST" action="../../Admin/AdminComponents/EditStaff.php">
                <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                <div class="col-sm-12">
                    <label for="validationCustom01" class="form-label fw-bold">Staff Name</label>
                    <input type="text" class="form-control shadow-sm" id="validationCustom01" name="name" value="<?php echo $user['name']; ?>" required>
                    <div class="valid-feedback">
                        Looks good!
                    </div>
                </div>
                <div class="col-sm-6">
                    <label for="validationCustom02" class="form-label fw-bold">Staff Mobile Number</label>
                    <input type="number" class="form-control shadow-sm" id="validationCustom02" value="<?php echo $user['mobile']; ?>" name="mobile" required>
                    <div class="valid-feedback">
                        Looks good!
                    </div>
                </div>
                <div class="col-sm-6">
                    <label for="validationCustomUsername" class="form-label fw-bold">Staff Email Address</label>
                    <div class="input-group has-validation">
                        <input type="text" class="form-control shadow-sm" id="validationCustomUsername" aria-describedby="inputGroupPrepend" value="<?php echo $user['email']; ?>" name="email" required>
                        <div class="invalid-feedback">
                            Please choose a username.
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="validationCustom03" class="form-label fw-bold">Address</label>
                    <input type="text" class="form-control shadow-sm" id="validationCustom03" value="<?php echo $user['address']; ?>" name="address" required>
                    <div class="invalid-feedback">
                        Please provide a valid Address.
                    </div>
                </div>
                <div class="col-sm-6">
                    <label for="validationCustom05" class="form-label fw-bold">Password</label>
                    <input type="text" class="form-control shadow-sm" id="validationCustom05" value="<?php echo $user['password']; ?>" name="password" required>
                    <div class="invalid-feedback">
                        Please provide a valid password.
                    </div>
                </div>
                <div class="col-sm-12">
                    <label for="validationCustom05" class="form-label">Staff Role<span class="text-danger fw-bold">*</span></label>
                    <select class="form-select" aria-label="Default select example" name="role">
                        <option <?php if ($user['role'] === 'admin') {
                                    echo 'selected';
                                } ?> value="admin">Admin</option>
                        <option <?php if ($user['role'] === 'staff') {
                                    echo 'selected';
                                } ?> value="staff">Staff</option>
                    </select>
                </div>
                <div class="col-12">
                    <div class="form-check">
                        <input class="form-check-input shadow-sm" type="checkbox" id="invalidCheck" required>
                        <label class="form-check-label fw-bold" for="invalidCheck">
                            Are you sure you want to edit this staff?
                        </label>
                        <div class="invalid-feedback">
                            You must agree before submitting.
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <button class="btn btn-primary shadow" type="submit">Submit</button>
                    <a href="../../Admin/Pages/ManageStaffPage.php" class="btn btn-warning shadow" type="submit">Cancel</a>
                </div>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js" integrity="sha256-gOQJIa9+K/XdfAuBkg2ONAdw5EnQbokw/s2b8BqsRFg=" crossorigin="anonymous"></script>
</body>

</html>

<style>
    .edit-staff {
        margin: 20px 0;
    }
</style>