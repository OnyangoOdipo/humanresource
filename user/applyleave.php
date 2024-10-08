<?php include('userheader.php'); ?>
<?php
include_once('../controller/connect.php');

$dbs = new database();
$db = $dbs->connection();

$empid = $_SESSION['User']['EmployeeId'];
$sql = mysqli_query($db, "SELECT * FROM type_of_leave");
if (isset($_POST['Apply'])) {
    $leave = $_POST['leavestatus'];
    $reason = $_POST['reason'];
    $startdate = $_POST['startdate'];
    $enddate = $_POST['enddate'];
    $leavestatus = "Pending";
    
    /* Convert date format */
    $date = str_replace('/', '-', $startdate);
    $startd = date('Y-m-d', strtotime($date));
    $datee = str_replace('/', '-', $enddate);
    $endd = date('Y-m-d', strtotime($datee));

    /* Insert the leave details without specifying the Detail_Id */
    mysqli_query($db, "INSERT INTO leavedetails (EmpId, TypesLeaveId, Reason, StateDate, EndDate, LeaveStatus) VALUES ('$empid', '$leave', '$reason', '$startd', '$endd', '$leavestatus')");
    
    echo "<script>window.location='applyleave.php';</script>";
}
?>
<div class="s-12 l-10">
    <h1>Apply Leave</h1>
    <hr>
    <div class="clearfix"></div>
</div>
<div class="s-12 l-6">
    <form action="" method="post">
        <label>Type Leave</label>
        <select id="country" name="leavestatus" title="Select Leave" required="">
            <option value="">-- Select Leave --</option>
            <?php while ($row = mysqli_fetch_assoc($sql)) { ?>
                <option value="<?php echo $row['LeaveId']; ?>"><?php echo ucfirst($row['Type_of_Name']); ?></option>
            <?php } ?>
        </select>
        <label for="lname">Reason</label>
        <input type="text" id="lname" name="reason" placeholder="Reason" title="Reason" required="" autocomplete="off">
        <label for="lname">Start Date</label>
        <input type="text" id="StartDate" name="startdate" autocomplete="off" placeholder="Start Date" title="Start Date" required="">
        <label for="lname">End Date</label>
        <input type="text" id="EndDate" name="enddate" autocomplete="off" placeholder="End Date" title="End Date" required="">
        
        <input type="submit" name="Apply" title="Submit" value="Submit">
    </form>
</div>

<?php include('userfooter.php'); ?>

<script type="text/javascript">
    $('#EndDate').datetimepicker({
        yearOffset: 0,
        lang: 'ch',
        timepicker: false,
        format: 'd/m/Y',
        formatDate: 'Y/m/d',
        minDate: '2015/01/01',
        maxDate: '2030/01/01'
    });

    $('#StartDate').datetimepicker({
        yearOffset: 0,
        lang: 'ch',
        timepicker: false,
        format: 'd/m/Y',
        formatDate: 'Y/m/d',
        minDate: '2015/01/01',
        maxDate: '2030/01/01'
    });
</script>
