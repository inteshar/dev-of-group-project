<?php
require_once '../../dbConnect.php'; // Include your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $mobile = $_POST['mobile'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // You may need to hash the password before storing it in the database for security

    $stmt = $conn->prepare("INSERT INTO users (name, mobile, email, address, password, role) 
    VALUES (:name, :mobile, :email, :address, :password, :role)");


    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':mobile', $mobile);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':address', $address);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':role', $role);

    if ($stmt->execute()) {
        header("Location: ../../Admin/Pages/ManageStaffPage.php?msg=<p class='bg-success-subtle text-success p-2 rounded text-center'>Staff '$name' Added Successfully</p>");
    } else {
        header("Location: ../../Admin/Pages/ManageStaffPage.php?msg=<p class='bg-danger-subtle text-danger p-2 rounded text-center'>Something went wrong! Please try again later.</p>");
    }
}
