<?php
require_once '../../dbConnect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt1 = $conn->prepare("SELECT members.name FROM members WHERE id = :id");
    $stmt1->bindParam(':id', $id);
    $stmt1->execute();
    $result = $stmt1->fetch(PDO::FETCH_ASSOC);
    $name = $result['name'];

    // Fetch the photo to retain
    $stmt = $conn->prepare("SELECT photo FROM members WHERE id = ?");
    $stmt->execute([$id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Delete KYC data except photo
    $stmtDelete = $conn->prepare("DELETE FROM members_kyc WHERE member_id = ?");
    $stmtDelete->execute([$id]);


    $stmtUpdate = $conn->prepare("UPDATE `members` SET `kyc_status` = 0 WHERE `members`.`id` = :id");
    $stmtUpdate->bindParam(':id', $id);
    $stmtUpdate->execute();


    // Delete other files except photo
    $memberDirectory = "../../Admin/MembersFiles/{$name}/{$result['photo']}";
    $files = glob($memberDirectory . "/*");

    foreach ($files as $file) {
        if (is_file($file)) {
            unlink($file); // Delete the file
        }
    }

    // Redirect to a page or send a success message
    header("Location: ../../Admin/Pages/MemberKYCPage.php?msg=<p class='bg-success-subtle text-success p-2 rounded text-center'>KYC data of '$name' deleted successfully.</p>");
    exit();
} else {
    // Handle if no ID is provided
    header("Location: ../../Admin/Pages/MemberKYCPage.php?msg=<p class='bg-success-subtle text-success p-2 rounded text-center'>Invalid ID provided.</p>");
    exit();
}
