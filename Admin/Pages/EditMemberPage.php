<?php
require_once '../../dbConnect.php';

$memberId = $_GET['memberId'];

$stmt = $conn->prepare("SELECT * FROM members WHERE id = :id");
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
    <div class="container">
        <div class="edit-member">
            <h1 class="m-3 mb-5">Edit your member details</br>
                <p class="fs-6 mt-2">You are editing details of '<?php echo $member['name'] ?>'</p>
            </h1>
            <form class="row g-3 needs-validation shadow p-5 rounded" method="POST" action="../../Admin/AdminComponents/Editmember.php">
                <div class="d-flex justify-content-center mb-3">
                    <img class="member-img shadow" src="../MembersFiles/<?php echo $member['name'] ?>/<?php echo $member['photo'] ?>" alt="">
                </div>
                <input type="hidden" name="id" value="<?php echo $member['id']; ?>">
                <div class="col-sm-12">
                    <label for="validationCustom01" class="form-label fw-bold">Member Name</label>
                    <input type="text" class="form-control shadow-sm" id="validationCustom01" name="name" value="<?php echo $member['name']; ?>" required>
                    <div class="valid-feedback">
                        Looks good!
                    </div>
                </div>
                <div class="col-sm-6">
                    <label for="validationCustom02" class="form-label fw-bold">Member Mobile Number</label>
                    <input type="number" class="form-control shadow-sm" id="validationCustom02" name="mobile" value="<?php echo $member['mobile']; ?>" maxlength="10" required>
                    <div class="valid-feedback">
                        Looks good!
                    </div>
                </div>
                <div class="col-sm-6">
                    <label for="validationCustomUsername" class="form-label fw-bold">Member Email Address</label>
                    <div class="input-group has-validation">
                        <input type="text" class="form-control shadow-sm" id="validationCustomUsername" aria-describedby="inputGroupPrepend" name="email" value="<?php echo $member['email']; ?>" required>
                        <div class="invalid-feedback">
                            Please choose a username.
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <label for="validationCustomUsername" class="form-label fw-bold">Date of Birth</label>
                    <div class="input-group has-validation">
                        <input type="date" class="form-control shadow-sm" id="validationCustomUsername" aria-describedby="inputGroupPrepend" name="dob" value="<?php echo $member['dob']; ?>" required>
                        <div class="invalid-feedback">
                            Please choose a username.
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <label for="address_perm" class="fw-bold">Permanent Address</label>
                    <textarea class="form-control shadow-sm" placeholder="Your hometown address" id="address_perm" name="address_perm"><?php echo $member['address_perm']; ?></textarea>
                </div>
                <div class="col-md-12">
                    <label for="address_temp" class="fw-bold">Temporary Address</label>
                    <textarea class="form-control shadow-sm" placeholder="Current address where you are residing" id="address_temp" name="address_temp"><?php echo $member['address_temp']; ?></textarea>
                </div>
                <div class="col-sm-6">
                    <label for="validationCustom01" class="form-label fw-bold">Relative Name<span class="text-danger fw-bold">*</span></label>
                    <input type="text" class="form-control shadow-sm" id="validationCustom01" name="rel_name" value="<?php echo $member['relative']; ?>" required>
                    <div class="valid-feedback">
                        Looks good!
                    </div>
                </div>
                <div class="col-sm-6">
                    <label for="validationCustom01" class="form-label fw-bold">Relationship<span class="text-danger fw-bold">*</span></label>
                    <select class="form-select shadow-sm" id="itemNumber" name="relation" required>
                        <?php
                        echo "<option value='" . $member['relation'] . "' selected>" . $member['relation'] . "</option>"
                        ?>
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
                <div class="col-sm-12">
                    <label for="validationCustom01" class="form-label fw-bold">Reference Name<span class="text-danger fw-bold">*</span></label>
                    <input type="text" class="form-control shadow-sm" id="validationCustom01" name="ref_name" value="<?php echo $member['ref_name']; ?>" required>
                    <div class="valid-feedback">
                        Looks good!
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="invalidCheck" required>
                        <label class="form-check-label fw-bold" for="invalidCheck">
                            Are you sure you want to update this Member?
                        </label>
                        <div class="invalid-feedback">
                            You must agree before submitting.
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <button class="btn btn-primary fw-bold shadow" type="submit">Submit</button>
                    <a href="../../Admin/Pages/ManageMemberPage.php" class="btn btn-warning shadow fw-bold" type="submit">Cancel</a>
                </div>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js" integrity="sha256-gOQJIa9+K/XdfAuBkg2ONAdw5EnQbokw/s2b8BqsRFg=" crossorigin="anonymous"></script>
</body>

</html>

<style>
    .edit-member {
        margin: 20px 0;
    }

    .member-img {
        height: 200px;
        border-radius: 100px;
    }
</style>