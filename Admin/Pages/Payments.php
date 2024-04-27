<?php
require_once '../../dbConnect.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="icon" href="./Assets/favicon.ico" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" integrity="sha256-2TnSHycBDAm2wpZmgdi0z81kykGPJAkiUY+Wf97RbvY=" crossorigin="anonymous" />
    <title>Admin</title>
    <!-- Include html2pdf.js library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js"></script>

</head>

<body>
    <?php
    include '../../Admin/AdminComponents/SideBar.php';
    // require_once '../dbConnect.php';

    $stmt = $conn->prepare("SELECT members.name AS member_name, members.mobile AS member_mobile, members.address_perm AS member_add, combined_accounts.* FROM members INNER JOIN ( SELECT member_id, account_number, account_opened_on FROM loan_account UNION SELECT member_id, account_number, account_opened_on FROM savings_account ) AS combined_accounts ON members.id = combined_accounts.member_id ORDER BY COALESCE(combined_accounts.account_opened_on) DESC LIMIT 10");
    $stmt->execute();
    $recent_acc = $stmt->fetchAll(PDO::FETCH_ASSOC);


    ?>
    <div class="container">
        <div class="recent-customers shadow">
            <div class="bg-primary-subtle rounded p-1">
                <h5 class="pb-0 p-2 fw-bold">Recent Accounts</h5>
                <div class="table-container">
                    <table class="table table-hover text-center">
                        <thead>
                            <tr>
                                <th class="fs-6 fw-bold bg-primary-subtle">SN</th>
                                <th class="fs-6 fw-bold bg-primary-subtle">Name</th>
                                <th class="fs-6 fw-bold bg-primary-subtle">Mobile</th>
                                <th class="fs-6 fw-bold bg-primary-subtle">Address</th>
                                <th class="fs-6 fw-bold bg-primary-subtle">Account No.</th>
                                <th class="fs-6 fw-bold bg-primary-subtle">Reg. Date</th>
                            </tr>
                        </thead>
                        <tbody class="fs-6">
                            <?php
                            $count = 1;
                            foreach ($recent_acc as $rec) {
                                echo "
                        <tr>
                        <td>" . $count++ . "</td>
                        <td class='table-row'>" . $rec['member_name'] . "</td>
                        <td class='table-row'>" . $rec['member_mobile'] . "</td>
                        <td class='table-row'>" . $rec['member_add'] . "</td>
                        <td class='table-row'>" . $rec['account_number'] . "</td>
                        <td class='table-row'>" . $rec['account_opened_on'] . "</td>
                    </tr>
                        ";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js" integrity="sha256-gOQJIa9+K/XdfAuBkg2ONAdw5EnQbokw/s2b8BqsRFg=" crossorigin="anonymous"></script>

</body>

</html>

<style>
    .recent-customers {
        margin: 20px 0;
    }

    .recent-customers .table-container {
        overflow-x: auto;
    }

    .recent-customers .table-container table .table-row {
        min-width: 180px;
    }
</style>