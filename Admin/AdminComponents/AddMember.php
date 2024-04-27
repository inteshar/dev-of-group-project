<?php
require_once '../../dbConnect.php'; // Include your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $mobile = $_POST['mobile'];
    $email = $_POST['email'];
    $dob = $_POST['dob'];
    $kyc_status = 0;
    $address_perm = $_POST['address_perm'];
    $address_temp = $_POST['address_temp'];
    $rel_name = $_POST['rel_name'];
    $relation = $_POST['relation'];
    $ref_name = $_POST['ref_name'];

    $member_id = sprintf("%06d", rand(0, 999999));

    // Handle file upload for the photo
    $photo = $_FILES['photo']; // Assuming 'name' is being submitted in the form

    $targetDir = '../../Admin/MembersFiles/';

    // Create a directory if it doesn't exist, named after the member's name
    $memberDir = $targetDir . $name . '/';

    if (!file_exists($memberDir)) {
        mkdir($memberDir, 0777, true);
    }

    $targetFile = $memberDir . basename($photo['name']);

    // Check if the file is an image
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    $allowedExtensions = array('jpg', 'jpeg', 'png', 'JPG', 'JPEG', 'PNG');

    if (!in_array($imageFileType, $allowedExtensions)) {
        // Handle invalid file types
        header("Location: ../../Admin/Pages/ManageMemberPage.php?msg=<p class='bg-danger-subtle text-danger p-2 rounded text-center'>Invalid file type. Upload only JPG, JPEG & PNG images.</p>");
        exit();
    }

    // Move uploaded file to the appropriate folder
    $uploaded = move_uploaded_file($photo['tmp_name'], $targetFile);

    if (!$uploaded) {
        // Handle file upload failure
        header("Location: ../../Admin/Pages/ManageMemberPage.php?msg=<p class='bg-danger-subtle text-danger p-2 rounded text-center'>File upload failed. Please try again.</p>");
        exit();
    }

    // Prepare SQL statement for insertion
    $stmt = $conn->prepare("INSERT INTO members (id, name, photo, mobile, email, dob, kyc_status, address_perm, address_temp, relative, relation, ref_name) 
    VALUES (:member_id, :name, :photo, :mobile, :email, :dob, :kyc_status, :address_perm, :address_temp, :rel_name, :relation, :ref_name)");

    // Bind parameters to the prepared statement
    $stmt->bindParam(':photo', $photo['name']);
    $stmt->bindParam(':member_id', $member_id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':mobile', $mobile);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':dob', $dob);
    $stmt->bindParam(':kyc_status', $kyc_status);
    $stmt->bindParam(':address_perm', $address_perm);
    $stmt->bindParam(':address_temp', $address_temp);
    $stmt->bindParam(':rel_name', $rel_name);
    $stmt->bindParam(':relation', $relation);
    $stmt->bindParam(':ref_name', $ref_name);

    // Execute the statement
    if ($stmt->execute()) {
        header("Location: ../../Admin/Pages/ManageMemberPage.php?msg=<p class='bg-success-subtle text-success p-2 rounded text-center'>Member '$name' Added Successfully</p>");
    } else {
        header("Location: ../../Admin/Pages/ManageMemberPage.php?msg=<p class='bg-danger-subtle text-danger p-2 rounded text-center'>Something went wrong! Please try again later.</p>");
    }
}
