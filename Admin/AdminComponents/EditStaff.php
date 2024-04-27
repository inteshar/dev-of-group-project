<?php
require_once '../../dbConnect.php'; // Include your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $staffId = $_POST['id'];
    $name = $_POST['name'];
    $mobile = $_POST['mobile'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    $stmt = $conn->prepare("UPDATE `users` SET `name` = :name, `mobile` = :mobile, `email` = :email, `address` = :address, `password` = :password, `role` = :role WHERE `users`.`id` = :id");

    $stmt->bindParam(':id', $staffId);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':mobile', $mobile);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':address', $address);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':role', $role);

    if ($stmt->execute()) {
        header("Location: ../../Admin/Pages/ManageStaffPage.php?msg=<p class='bg-success-subtle text-success p-2 rounded text-center'>Staff '$name' Updated Successfully</p>");
    } else {
        header("Location: ../../Admin/Pages/ManageStaffPage.php?msg=<p class='bg-danger-subtle text-danger p-2 rounded text-center'>Something went wrong! Please try again later.</p>");
    }
}
