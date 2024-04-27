<?php
require_once '../../dbConnect.php';

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $id_to_delete = $_GET['id'];

    $stmt1 = $conn->prepare("SELECT * FROM members WHERE id = :id");
    $stmt1->bindParam(':id', $id_to_delete);
    $stmt1->execute();
    $result = $stmt1->fetch(PDO::FETCH_ASSOC); // Using fetch() to get a single row

    if ($result) {
        $name = $result['name'];
        $stmt = $conn->prepare("DELETE FROM members WHERE id = :id");
        $stmt->bindParam(':id', $id_to_delete);

        if ($stmt->execute()) {
            $stmt = $conn->prepare("DELETE FROM members_kyc WHERE member_id = :id");
            $stmt->bindParam(':id', $id_to_delete);
            $stmt->execute();

            function deleteDirectory($dirPath)
            {
                if (!is_dir($dirPath)) {
                    // Check if the path provided is a directory
                    return;
                }

                // Open the directory
                $dirHandle = opendir($dirPath);

                // Iterate through each file and subdirectory
                while (($file = readdir($dirHandle)) !== false) {
                    if ($file != '.' && $file != '..') {
                        $filePath = $dirPath . DIRECTORY_SEPARATOR . $file;

                        // If it's a directory, delete its contents recursively
                        if (is_dir($filePath)) {
                            deleteDirectory($filePath);
                        } else {
                            // Delete the file
                            unlink($filePath);
                        }
                    }
                }

                // Close the directory handle
                closedir($dirHandle);

                // Delete the directory itself
                rmdir($dirPath);
            }

            // Usage: Provide the directory path you want to delete
            $directoryPath = '../../Admin/MembersFiles/' . $name;
            deleteDirectory($directoryPath);


            header("Location: ../../Admin/Pages/ManageMemberPage.php?msg=<p class='bg-success-subtle text-success p-2 rounded text-center'>Member '$name' Deleted Successfully</p>");
        } else {
            $errors = $stmt->errorInfo();
            header("Location: ../../Admin/Pages/ManageMemberPage.php?msg=<p class='bg-danger-subtle text-danger p-2 rounded text-center'>$errors[2]</p>");
        }
    } else {
        header("Location: ../../Admin/Pages/ManageMemberPage.php?msg=<p class='bg-danger-subtle text-danger p-2 rounded text-center'>No user found with this ID</p>");
    }
}
