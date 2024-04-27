<?php
require_once '../../dbConnect.php';

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $id_to_delete = $_GET['id'];

    $stmt1 = $conn->prepare("SELECT * FROM users WHERE id = :id");
    $stmt1->bindParam(':id', $id_to_delete);
    $stmt1->execute();
    $result = $stmt1->fetch(PDO::FETCH_ASSOC); // Using fetch() to get a single row

    if ($result) {
        $name = $result['name'];
        $stmt = $conn->prepare("DELETE FROM users WHERE id = :id");
        $stmt->bindParam(':id', $id_to_delete);

        if ($stmt->execute()) {
            header("Location: ../../Admin/Pages/ManageStaffPage.php?msg=<p class='bg-success-subtle text-success p-2 rounded text-center'>User '$name' Deleted Successfully</p>");
        } else {
            $errors = $stmt->errorInfo();
            header("Location: ../../Admin/Pages/ManageStaffPage.php?msg=<p class='bg-danger-subtle text-danger p-2 rounded text-center'>$errors[2]</p>");
        }
    } else {
        header("Location: ../../Admin/Pages/ManageStaffPage.php?msg=<p class='bg-danger-subtle text-danger p-2 rounded text-center'>No user found with this ID</p>");
    }
}
