<?php
require_once '../../dbConnect.php'; // Include your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $memberId = $_POST['id'];
    $name = $_POST['name'];
    $mobile = $_POST['mobile'];
    $email = $_POST['email'];
    $dob = $_POST['dob'];
    $address_perm = $_POST['address_perm'];
    $address_temp = $_POST['address_temp'];
    $rel_name = $_POST['rel_name'];
    $relation = $_POST['relation'];
    $ref_name = $_POST['ref_name'];

    $stmt = $conn->prepare("UPDATE `members` SET `name` = :name, `mobile` = :mobile, `email` = :email, `dob` = :dob, `address_perm` = :address_perm, `address_temp` = :address_temp, `relative` = :rel_name, `relation` = :relation, `ref_name` = :ref_name WHERE `members`.`id` = :id");

    $stmt->bindParam(':id', $memberId);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':mobile', $mobile);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':dob', $dob);
    $stmt->bindParam(':address_perm', $address_perm);
    $stmt->bindParam(':address_temp', $address_temp);
    $stmt->bindParam(':rel_name', $rel_name);
    $stmt->bindParam(':relation', $relation);
    $stmt->bindParam(':ref_name', $ref_name);

    if ($stmt->execute()) {
        header("Location: ../../Admin/Pages/ManageMemberPage.php?msg=<p class='bg-success-subtle text-success p-2 rounded text-center'>Member '$name' Updated Successfully</p>");
    } else {
        header("Location: ../../Admin/Pages/ManageMemberPage.php?msg=<p class='bg-danger-subtle text-danger p-2 rounded text-center'>Something went wrong! Please try again later.</p>");
    }
}
