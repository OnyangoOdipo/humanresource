<?php
require_once('../vendor/autoload.php'); // Ensure this path is correct for your setup

// Create a new TCPDF instance
$pdf = new TCPDF('P', 'mm', 'A6', true, 'UTF-8', false);

// Set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Your Company Name');
$pdf->SetTitle('Payslip');
$pdf->SetSubject('Payslip for Employee');
$pdf->SetKeywords('TCPDF, PDF, payslip, salary');

// Set default header data
$pdf->SetHeaderData('', 0, 'Payslip', 'Employee Salary Details');

// Set margins
$pdf->SetMargins(10, 10, 10);
$pdf->SetHeaderMargin(5);
$pdf->SetFooterMargin(10);

// Set auto page breaks
$pdf->SetAutoPageBreak(TRUE, 10);

// Set font
$pdf->SetFont('helvetica', '', 10);

// Add a page
$pdf->AddPage();

// Fetch employee salary and details from the database
include_once('../controller/connect.php');
session_start(); // Make sure to start the session
$dbs = new database();
$db = $dbs->connection();

$employeeId = $_SESSION['User']['EmployeeId']; // Get employee ID from session

$sql = "SELECT FirstName, LastName, BasicSalary FROM employee WHERE EmployeeId = '$employeeId'";
$result = mysqli_query($db, $sql);
$row = mysqli_fetch_assoc($result);

$firstName = $row['FirstName'];
$lastName = $row['LastName'];
$basicSalary = $row['BasicSalary'];

// Define fixed and variable allowances
$allowance1 = 5000; // Fixed allowance amount
$allowance2 = 3000;
$allowance3 = 2000;
$allowance4 = 1500;
$allowance5 = 1000;

// Ensure basicSalary is a numeric value for calculations
$basicSalary = floatval($basicSalary);

// Calculate total salary
$totalAllowances = $allowance1 + $allowance2 + $allowance3 + $allowance4 + $allowance5;
$totalSalary = $basicSalary + $totalAllowances;

// Define HTML content for the payslip
$html = '
<h1>Payslip</h1>
<table border="1" cellpadding="4">
    <tr>
        <td><strong>Employee ID</strong></td>
        <td>' . htmlspecialchars($employeeId) . '</td>
    </tr>
    <tr>
        <td><strong>Name</strong></td>
        <td>' . htmlspecialchars($firstName . ' ' . $lastName) . '</td>
    </tr>
    <tr>
        <td><strong>Basic Salary</strong></td>
        <td>Ksh.' . number_format($basicSalary, 2) . '</td>
    </tr>
    <tr>
        <td><strong>Allowance 1</strong></td>
        <td>Ksh.' . number_format($allowance1, 2) . '</td>
    </tr>
    <tr>
        <td><strong>Allowance 2</strong></td>
        <td>Ksh.' . number_format($allowance2, 2) . '</td>
    </tr>
    <tr>
        <td><strong>Allowance 3</strong></td>
        <td>Ksh.' . number_format($allowance3, 2) . '</td>
    </tr>
    <tr>
        <td><strong>Allowance 4</strong></td>
        <td>Ksh.' . number_format($allowance4, 2) . '</td>
    </tr>
    <tr>
        <td><strong>Allowance 5</strong></td>
        <td>Ksh.' . number_format($allowance5, 2) . '</td>
    </tr>
    <tr>
        <td><strong>Total Allowances</strong></td>
        <td>Ksh.' . number_format($totalAllowances, 2) . '</td>
    </tr>
    <tr>
        <td><strong>Total Salary</strong></td>
        <td>Ksh.' . number_format($totalSalary, 2) . '</td>
    </tr>
</table>
';

// Output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');

// Close and output PDF document
$pdf->Output('payslip.pdf', 'I');
?>
