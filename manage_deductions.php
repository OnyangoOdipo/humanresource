<?php
include_once('controller/connect.php');
$dbs = new database();
$db = $dbs->connection();

if (isset($_POST['approve_request'])) {
    $request_id = $_POST['request_id'];
    $request_query = mysqli_query($db, "SELECT * FROM advanced_salary_requests WHERE id='$request_id'");
    $request = mysqli_fetch_assoc($request_query);

    if ($request) {
        $employee_id = $request['employee_id'];
        $amount = $request['request_amount']; // Correct field name

        $deduction_query = "INSERT INTO deductions (employee_id, deduction_amount, reason) VALUES ('$employee_id', '$amount', 'Advance Salary')";
        mysqli_query($db, $deduction_query);

        $update_query = "UPDATE advanced_salary_requests SET status='Approved' WHERE id='$request_id'";
        mysqli_query($db, $update_query);

        mysqli_query($db, "UPDATE employee SET CurrentSalary = CurrentSalary - $amount WHERE EmpId = $employee_id");

        $notification_query = "INSERT INTO notifications (user_id, title, content, read_status) VALUES ('$employee_id', 'Advance Salary Approved', 'Your request for an advance salary of $amount has been approved.', 'Unread')";
        mysqli_query($db, $notification_query);
    }
}

if (isset($_POST['deny_request'])) {
    $request_id = $_POST['request_id'];
    $update_query = "UPDATE advanced_salary_requests SET status='Denied' WHERE id='$request_id'";
    mysqli_query($db, $update_query);

    // Fetch employee_id for notification
    $request_query = mysqli_query($db, "SELECT employee_id FROM advanced_salary_requests WHERE id='$request_id'");
    $request = mysqli_fetch_assoc($request_query);
    $employee_id = $request['employee_id'];

    // Insert notification
    $notification_query = "INSERT INTO notifications (user_id, title, content, read_status) VALUES ('$employee_id', 'Advance Salary Denied', 'Your request for an advance salary has been denied.', 'Unread')";
    mysqli_query($db, $notification_query);
}

// Fetch pending salary requests
$pending_requests = mysqli_query($db, "SELECT * FROM advanced_salary_requests WHERE status='Pending'");

// Fetch existing deductions
$deductions = mysqli_query($db, "SELECT d.*, e.FirstName FROM deductions d JOIN employee e ON d.employee_id = e.EmpId");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Advanced Salary Requests</title>
    
    <link rel="stylesheet" href="styles.css"> 
    <style>
        
        /* General Page Styles */
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #e9ecef;
    color: #212529;
    margin: 0;
    padding: 20px;
}

h1 {
    color: #343a40;
    text-align: center;
    font-size: 32px;
    margin-bottom: 30px;
}

h2 {
    color: #495057;
    font-size: 26px;
    margin-top: 40px;
    border-bottom: 2px solid #6c757d;
    padding-bottom: 10px;
}

/* Table Styles */
.table-style {
    width: 100%;
    border-collapse: collapse;
    margin: 25px 0;
    font-size: 18px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    background-color: #fff;
}

.table-style th, .table-style td {
    padding: 12px 15px;
    border: 1px solid #dee2e6;
    text-align: left;
}

.table-style th {
    background-color: #007bff;
    color: #ffffff;
}

.table-style tr {
    transition: background-color 0.2s ease;
}

.table-style tr:nth-child(even) {
    background-color: #f8f9fa;
}

.table-style tr:hover {
    background-color: #e9ecef;
}

/* Button Styles */
.btn-submit {
    background-color: #007bff;
    color: white;
    border: none;
    padding: 10px 20px;
    font-size: 16px;
    cursor: pointer;
    border-radius: 5px;
    transition: background-color 0.3s ease;
    text-align: center;
    display: inline-block;
}

.btn-submit:hover {
    background-color: #0056b3;
}

/* Form Styles */
form {
    display: inline;
    margin: 0;
}

input[type="hidden"] {
    display: none;
}

/* Responsive Design */
@media (max-width: 768px) {
    .table-style, .table-style th, .table-style td {
        display: block;
        width: 100%;
    }

    .table-style th, .table-style td {
        text-align: right;
        padding-left: 50%;
        position: relative;
    }

    .table-style th::before, .table-style td::before {
        content: attr(data-label);
        position: absolute;
        left: 10px;
        padding-right: 10px;
        white-space: nowrap;
        font-weight: bold;
    }

    .table-style th {
        background-color: #495057;
    }
}


    </style>
    
</head>
<body>
    <h1>Manage Advanced Salary Requests</h1>

    <h2>Pending Requests</h2>
    <table class="table-style">
        <tr>
            <th>Employee ID</th>
            <th>Requested Amount</th>
            <th>Actions</th>
        </tr>
        <?php while ($request = mysqli_fetch_assoc($pending_requests)) { 
            $employee_query = mysqli_query($db, "SELECT FirstName FROM employee WHERE EmpId='".$request['employee_id']."'");
            $employee = mysqli_fetch_assoc($employee_query);
        ?>
        <tr>
            <td><?php echo $request['employee_id']; ?></td>
            <td><?php echo $request['request_amount']; ?></td>
            <td>
                <form method="post" style="display:inline;">
                    <input type="hidden" name="request_id" value="<?php echo $request['id']; ?>">
                    <button type="submit" name="approve_request" class="btn-submit">Approve</button>
                </form>
                <form method="post" style="display:inline;">
                    <input type="hidden" name="request_id" value="<?php echo $request['id']; ?>">
                    <button type="submit" name="deny_request" class="btn-submit">Deny</button>
                </form>
            </td>
        </tr>
        <?php } ?>
    </table>

    <h2>Existing Deductions</h2>
    <table class="table-style">
        <tr>
            <th>Employee Name</th>
            <th>Deduction Amount</th>
            <th>Reason</th>
        </tr>
        <?php while ($deduction = mysqli_fetch_assoc($deductions)) { ?>
        <tr>
            <td><?php echo $deduction['FirstName']; ?></td>
            <td><?php echo $deduction['deduction_amount']; ?></td>
            <td><?php echo $deduction['reason']; ?></td>
        </tr>
        <?php } ?>
    </table>

</body>
</html>
