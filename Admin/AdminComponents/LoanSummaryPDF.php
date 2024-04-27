<?php
session_start();
error_reporting(E_ALL & ~E_NOTICE);

require_once '../../dbConnect.php';

$memberId = $_GET['memberId']; // Assuming you're passing the memberId through the URL

$stmt = $conn->prepare("SELECT members.*, loan_account.* 
                        FROM members 
                        INNER JOIN loan_account 
                        ON members.id = loan_account.member_id 
                        WHERE members.id = :id");
$stmt->bindParam(':id', $memberId); // Bind the memberId parameter
$stmt->execute();

// Fetch the result as an associative array
$member = $stmt->fetch(PDO::FETCH_ASSOC);

require_once '../../Admin/dompdf/autoload.inc.php'; // Include the autoloader
use Dompdf\Dompdf;

$count = 1;
$PAGE_NUM = 1;
$currentDateTime = date("Y-m-d H:i:s");

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
  margin-top: -37%;
  margin-left: 50%;
}
.table-custom {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 60px;
    font-size: 14px;
    color: #A3A3A3;
}
.table-custom thead {
    background-color: #fff;
    color: #000;
    font-size: 16px;
}
.table-custom th, .table-custom td {
    padding: 8px;
    text-align: center;
}
.party{
    border: 1px solid #000;
    height: 200px;
    width: 150px;
    margin: 10px;
}
tbody tr td{
    
}
.footer-text{
    color: #5c6b73;
}
.company-name{
    font-weight: bold;
}
";
$html = "
<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>" . $member['name'] . " Loan Account Summary " . $currentDateTime . "</title>
    <style>
        $customCss
    </style>
</head>
<body>
    <h1>Account Summary</h1>
    <p style='color: #5c6b73'>Loan Account</p>
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
                <h4>Loan Amount</h4>
                <p>Rs. {$member['loan_amount']}</p>
            </div>
            <div class='col'>
                <h4>EMI</h4>
                <p>Rs. {$member['emi']}/day</p>
            </div>
        </div>
        <div class='right'>
            <div class='col'>
                <h4>Loan ID</h4>
                <p>{$member['account_number']}</p>
            </div>
            <div class='col'>
                <h4>Approved Amount</h4>
                <p>Rs. {$member['approval_amount']}</p>
            </div>
            <div class='col'>
                <h4>Plan</h4>
                <p>{$member['plan']} Days</p>
            </div>
            <div class='col'>
                <h4>Account Opened on</h4>
                <p>{$member['account_opened_on']}</p>
            </div>
        </div>
    
    </div>
    <table class='table-custom'>
        <tbody>
        <tr>
        <td class='party'>Photo</td>
        <td class=''></td>
        <td class='party'>Company Seal/Stamp</td>
        </tr>
        </tbody>
        <thead>
            <tr>
                <td>Name .................................................................</td>
                <td></td>
                <td>Name .................................................................</td>
            </tr>
            <tr>
                <td>Signature .................................................................</td>
                <td></td>
                <td>Signature .................................................................</td>
            </tr>
        </thead>
        </table>
        <p class='footer-text'>Generated On: " . $currentDateTime . "</p>
        <p class='footer-text'>Approved By: " . $_SESSION['login_user'] . "</p>
        <p class='company-name'>Development of Group</p>
        </body>
        </html>
";



// Create a new instance of Dompdf
$dompdf = new Dompdf();

// Load HTML content into Dompdf
$dompdf->loadHtml($html);

// (Optional) Set paper size and orientation
$dompdf->setPaper('A4', 'portrait');

// Render the HTML as PDF
$dompdf->render();

// Output PDF to the browser for download
$dompdf->stream($member['name'] . " Loan Account Summary " . $currentDateTime . '.pdf', ['Attachment' => false]);
