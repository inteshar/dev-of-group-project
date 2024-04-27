<?php
require_once '../../dbConnect.php'; // Include your database connection file
// var_dump($_POST);
// var_dump($_FILES);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $memberId = $_POST['memberId'];

    $stmt1 = $conn->prepare("SELECT members.name FROM members WHERE id = :id AND kyc_status = 0");
    $stmt1->bindParam(':id', $memberId);
    $stmt1->execute();
    $name = $stmt1->fetch(PDO::FETCH_ASSOC);

    // Handle file upload for the photo
    $aadhaar = $_FILES['aadhaar_card']; // Assuming 'name' is being submitted in the form
    $pan = $_FILES['pan_card']; // Assuming 'name' is being submitted in the form
    $signature = $_FILES['signature']; // Assuming 'name' is being submitted in the form
    $ref_aadhaar = $_FILES['ref_aadhaar']; // Assuming 'name' is being submitted in the form

    // Check if the file is an image
    $allowedExtensions = array('pdf', 'PDF');

    $targetDir = '../../Admin/MembersFiles/';

    // Create a directory if it doesn't exist, named after the member's name
    $memberDir = $targetDir . $name['name'] . '/';

    if (!file_exists($memberDir)) {
        mkdir($memberDir, 0777, true);
    }

    // Function to handle file uploads
    function handleFileUpload($file, $memberDir, $allowedExtensions)
    {
        $targetFile = $memberDir . basename($file['name']);
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        if (!in_array($imageFileType, $allowedExtensions)) {
            // Handle invalid file types
            header("Location: ../../Admin/Pages/MemberKYCPage.php?msg=<p class='bg-danger-subtle text-danger p-2 rounded text-center'>Unsupported file types. Please upload only PDFs.</p>");
            return false;
        }

        return move_uploaded_file($file['tmp_name'], $targetFile);
    }

    // Handle each file upload individually
    $uploaded1 = handleFileUpload($aadhaar, $memberDir, $allowedExtensions);
    $uploaded2 = handleFileUpload($pan, $memberDir, $allowedExtensions);
    $uploaded3 = handleFileUpload($signature, $memberDir, $allowedExtensions);
    $uploaded4 = handleFileUpload($ref_aadhaar, $memberDir, $allowedExtensions);

    if (!($uploaded1 && $uploaded2 && $uploaded3 && $uploaded4)) {
        // Handle file upload failure
        header("Location: ../../Admin/Pages/MemberKYCPage.php?msg=<p class='bg-danger-subtle text-danger p-2 rounded text-center'>File upload failed. Please try again.</p>");
        exit();
    }

    // Prepare SQL statement for insertion
    $stmt = $conn->prepare("INSERT INTO members_kyc (member_id, aadhaar_card, pan_card, signature, ref_aadhaar) 
    VALUES (:memberId, :aadhaar_card, :pan_card, :signature, :ref_aadhaar)");

    // Bind parameters to the prepared statement
    $stmt->bindParam(':memberId', $memberId);
    $targetFile1 = $memberDir . basename($aadhaar['name']);
    $targetFile2 = $memberDir . basename($pan['name']);
    $targetFile3 = $memberDir . basename($signature['name']);
    $targetFile4 = $memberDir . basename($ref_aadhaar['name']);
    $stmt->bindParam(':aadhaar_card', $targetFile1);
    $stmt->bindParam(':pan_card', $targetFile2);
    $stmt->bindParam(':signature', $targetFile3);
    $stmt->bindParam(':ref_aadhaar', $targetFile4);

    // Execute the statement
    if ($stmt->execute()) {
        $stmt1 = $conn->prepare("UPDATE `members` SET `kyc_status` = 1 WHERE `members`.`id` = :id");
        $stmt1->bindParam(':id', $memberId);
        $stmt1->execute();
        header("Location: ../../Admin/Pages/MemberKYCPage.php?msg=<p class='bg-success-subtle text-success p-2 rounded text-center'>Member '" . $name['name'] . "' Added Successfully</p>");
    } else {
        header("Location: ../../Admin/Pages/MemberKYCPage.php?msg=<p class='bg-danger-subtle text-danger p-2 rounded text-center'>Something went wrong! Please try again later.</p>");
    }
}
