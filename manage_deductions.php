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
    <link rel="stylesheet" href="styles.css"> <!-- Link to your stylesheet -->
</head>
<body>
<div class="container">
    <h1>Manage Advanced Salary Requests</h1>

    <h2>Pending Requests</h2>
    <table class="table-style">
        <tr>
            <th>Employee ID</th>
            <th>Employee Name</th>
            <th>Requested Amount</th>
            <th>Actions</th>
        </tr>
        <?php while ($request = mysqli_fetch_assoc($pending_requests)) { 
            $employee_query = mysqli_query($db, "SELECT FirstName FROM employee WHERE EmpId='".$request['employee_id']."'");
            $employee = mysqli_fetch_assoc($employee_query);
        ?>
        <tr>
            <td><?php echo $request['employee_id']; ?></td>
            <td><?php echo $employee['EmpName']; ?></td>
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
</div>
<?php include('footer.php'); ?>
</body>
</html>
