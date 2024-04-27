<?php
require_once '../../dbConnect.php';

$memberId = $_GET['memberId'];

$stmt = $conn->prepare("SELECT members.*, members_kyc.* FROM members INNER JOIN members_kyc ON members.id=members_kyc.member_id WHERE members.id = :id");
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
            <h1 class="m-3 mb-5">Member Information and Documents</br>
            </h1>
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
                            <p class="member-info-heading">Relative Name</p>
                            <h5><?php echo $member['relative']; ?></h5>
                        </div>
                        <div class="member-info">
                            <p class="member-info-heading">Relation</p>
                            <h5><?php echo $member['relation']; ?></h5>
                        </div>
                        <div class="member-info">
                            <p class="member-info-heading">Reference</p>
                            <h5><?php echo $member['ref_name']; ?></h5>
                        </div>
                        <div class="member-info">
                            <p class="member-info-heading">Temporary Address</p>
                            <h5><?php echo $member['address_temp']; ?></h5>
                        </div>
                        <div class="member-info">
                            <p class="member-info-heading">KYC Date</p>
                            <h5><?php echo $member['date_created']; ?></h5>
                        </div>
                    </div>
                </div>
                <div class="mb-4">
                    <h4 class="fw-bold">Aadhaar Card</h4>
                    <div class="ratio ratio-1x1">
                        <object class="shadow" data='<?php echo $member['aadhaar_card']; ?>'>
                            <p><a href="<?php echo $member['aadhaar_card']; ?>">Download</a></p>
                        </object>
                    </div>
                </div>
                <div class="mb-4">
                    <h4 class="fw-bold">PAN Card</h4>
                    <div class="ratio ratio-1x1">
                        <object class="shadow" data='<?php echo $member['pan_card']; ?>'>
                            <p><a href="<?php echo $member['pan_card']; ?>">Download</a></p>
                        </object>
                    </div>
                </div>
                <div class="mb-4">
                    <h4 class="fw-bold">Signature</h4>
                    <div class="ratio ratio-1x1">
                        <object class="shadow sign-doc" data='<?php echo $member['signature']; ?>'>
                            <p><a href="<?php echo $member['signature']; ?>">Download</a></p>
                        </object>
                    </div>
                </div>
                <div class="mb-4">
                    <h4 class="fw-bold">Reference Aadhaar Card</h4>
                    <div class="ratio ratio-1x1">
                        <object class="shadow" data='<?php echo $member['ref_aadhaar']; ?>'>
                            <p><a href="<?php echo $member['aadhaar_card']; ?>">Download</a></p>
                        </object>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js" integrity="sha256-gOQJIa9+K/XdfAuBkg2ONAdw5EnQbokw/s2b8BqsRFg=" crossorigin="anonymous"></script>
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