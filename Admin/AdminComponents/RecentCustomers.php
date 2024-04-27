<?php
// require_once '../dbConnect.php';

$stmt = $conn->prepare("SELECT members.name AS member_name, members.mobile AS member_mobile, members.address_perm AS member_add, combined_accounts.* FROM members INNER JOIN ( SELECT member_id, account_number, account_opened_on FROM loan_account UNION SELECT member_id, account_number, account_opened_on FROM savings_account ) AS combined_accounts ON members.id = combined_accounts.member_id ORDER BY COALESCE(combined_accounts.account_opened_on) DESC LIMIT 10");
$stmt->execute();
$recent_acc = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>
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