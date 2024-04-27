<?php
session_start();
error_reporting(E_ALL & ~E_NOTICE);

require_once '../../dbConnect.php';

$from = $_GET['from'];
$to = $_GET['to'];

$fromFull = date('F j, Y', strtotime($from));
$toFull = date('F j, Y', strtotime($to));

$memberId = $_GET['memberId']; // Assuming you're passing the memberId through the URL

$stmt = $conn->prepare("SELECT members.*, savings_account.* 
                        FROM members 
                        INNER JOIN savings_account 
                        ON members.id = savings_account.member_id 
                        WHERE members.id = :id");
$stmt->bindParam(':id', $memberId); // Bind the memberId parameter
$stmt->execute();

// Fetch the result as an associative array
$member = $stmt->fetch(PDO::FETCH_ASSOC);

if ($from != "" && $to != "") {
    $stmt2 = $conn->prepare("SELECT * 
                        FROM savings_statement WHERE account_number = :accno AND transaction_date BETWEEN '$from' AND '$to' ORDER BY transaction_date desc");
    $stmt2->bindParam(':accno', $member['account_number']); // Bind the account number parameter
    $stmt2->execute();

    // Fetch the result as an associative array
    $statements = $stmt2->fetchAll(PDO::FETCH_ASSOC);
} else {
    $stmt2 = $conn->prepare("SELECT * 
                        FROM savings_statement WHERE account_number = :accno ORDER BY transaction_date desc");
    $stmt2->bindParam(':accno', $member['account_number']); // Bind the account number parameter
    $stmt2->execute();

    // Fetch the result as an associative array
    $statements = $stmt2->fetchAll(PDO::FETCH_ASSOC);
}

require_once '../../Admin/dompdf/autoload.inc.php'; // Include the autoloader
use Dompdf\Dompdf;

$count = 1;
$PAGE_NUM = 1;
$currentDateTime = date("Y-m-d H:i:s");

$imgPath = '../MembersFiles/' . $member['name'] . '/' . $member['photo'];

// HTML content to be converted to PDF
$customCss = "
@import url('https://fonts.googleapis.com/css2?family=Poppins&display=swap');
*{
    font-family: 'Poppins', sans-serif;
}
thead{
    background: #5d879d;
}
thead td{
    padding: 12px;
}
.memberId{
    color: #5c6b73;
    font-size: 12px;
    margin-top: -20px;
    margin-bottom: 10px;
}
.account-holder {
    display: block;
    margin-bottom: 20px;
    border-top: 1px solid black;
    padding-top: 12px;
    border-bottom: 1px solid black;
    padding-bottom: 12px;
}
.account-holder .col {
    display: inline-block;
    width: 50%;
    margin-right: 5%;
    vertical-align: top;
}
.account-holder h4 {
    font-size: 12px;
    margin-bottom: -15px;
    color: #444857;
}
.account-holder p {
    font-weight: bold;
    font-size: 18px;
    margin-bottom: 10px;
    width: fit-content;
}
.right{
  margin-top: -30%;
  margin-left: 50%;
}
.table-custom {
    width: 100%;
    border-collapse: collapse;
    margin-top: -10px;
    font-size: 14px;
}
.table-custom thead {
    background-color: #5d879d;
    color: white;
}
.table-custom th, .table-custom td {
    border: 0.2px solid black;
    padding: 8px;
    text-align: left;
}
.statement-header{
    font-weight: bold;
    text-align: center;
    background: #5d879d;
    color: white;
    padding: 8px;
}
.statement-header span{
    font-weight: none;
    font-size: 12px;
}
.footer-text{
    color: #5c6b73;
}
.company-name{
    font-weight: bold;
}
";
if ($from === "") {
    $html = "
<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>" . $member['name'] . " Savings Account Statement " . $currentDateTime . "</title>
    <style>
        $customCss
    </style>
</head>
<body>
    <h1>Account Summary</h1>
    <p style='color: #5c6b73'>Savings Account</p>
    <div class='memberId'>
        <span>Member ID: {$member['member_id']}</span>
    </div>
    <div class='account-holder'>
        <div class='left'>
            <div class='col'>
                <h4>Account Holder</h4>
                <p>{$member['name']}</p>
            </div>
            <div class='col'>
                <h4>Permanent Address</h4>
                <p>{$member['address_perm']}</p>
            </div>
            <div class='col'>
                <h4>Account Balance</h4>
                <p>Rs. {$member['account_bal']}</p>
            </div>
        </div>
        <div class='right'>
            <div class='col'>
                <h4>Account Number</h4>
                <p>{$member['account_number']}</p>
            </div>
            <div class='col'>
                <h4>Interest Rate</h4>
                <p>{$member['interest_rate']}%</p>
            </div>
            <div class='col'>
                <h4>Account Opened on</h4>
                <p>{$member['account_opened_on']}</p>
            </div>
        </div>
    
    </div>
    <p class='statement-header'>Account Statement<br/></p>
    <table class='table-custom'>
        <thead>
            <tr>
                <td>SN</td>
                <td>Description/Trans. ID</td>
                <td>Deposit</td>
                <td>Withdrawal</td>
                <td>Date</td>
            </tr>
        </thead>
        <tbody>
";
} else {
    $html = "
<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>" . $member['name'] . " Savings Account Statement " . $currentDateTime . "</title>
    <style>
        $customCss
    </style>
</head>
<body>
    <h1>Account Summary</h1>
    <p style='color: #5c6b73'>Savings Account</p>
    <div class='account-holder'>
        <div class='left'>
            <div class='col'>
                <h4>Account Holder</h4>
                <p>{$member['name']}</p>
            </div>
            <div class='col'>
                <h4>Permanent Address</h4>
                <p>{$member['address_perm']}</p>
            </div>
            <div class='col'>
                <h4>Account Balance</h4>
                <p>Rs. {$member['account_bal']}</p>
            </div>
        </div>
        <div class='right'>
            <div class='col'>
                <h4>Account Number</h4>
                <p>{$member['account_number']}</p>
            </div>
            <div class='col'>
                <h4>Interest Rate</h4>
                <p>{$member['interest_rate']}%</p>
            </div>
            <div class='col'>
                <h4>Account Opened on</h4>
                <p>{$member['account_opened_on']}</p>
            </div>
        </div>
    
    </div>
    <p class='statement-header'>Account Statement<br/><span>Displaying the statement for the period between {$fromFull} and {$toFull}</span></p>
    <table class='table-custom'>
        <thead>
            <tr>
                <td>SN</td>
                <td>Description/Trans. ID</td>
                <td>Deposit</td>
                <td>Withdrawal</td>
                <td>Date</td>
            </tr>
        </thead>
        <tbody>
";
}

foreach ($statements as $row) {
    $deposit = $row['deposit'] === '--' ? "{$row['deposit']}" : "Rs. {$row['deposit']}";
    $withdrawal = $row['withdrawal'] === '--' ? "{$row['withdrawal']}" : "Rs. {$row['withdrawal']}";
    $html .= "<tr>
                <td>" . $count++ . "</td>
                <td>{$row['purpose']}<br/>{$row['trans_id']}</td>
                <td>{$deposit}</td>
                <td>{$withdrawal}</td>
                <td>{$row['transaction_date']}</td>
            </tr>";
};

$html .= "</tbody></table>
<p class='footer-text'>Statement Issued By: " . $_SESSION['login_user'] . "</p>
<p class='company-name'>Development of Group</p>
</body>
</html>";


// Create a new instance of Dompdf
$dompdf = new Dompdf();

// Load HTML content into Dompdf
$dompdf->loadHtml($html);

// (Optional) Set paper size and orientation
$dompdf->setPaper('A4', 'portrait');

// Render the HTML as PDF
$dompdf->render();

// Output PDF to the browser for download
$dompdf->stream($member['name'] . " Savings Account Statement " . $currentDateTime . '.pdf', ['Attachment' => false]);
