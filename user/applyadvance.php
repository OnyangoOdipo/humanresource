<?php include('userheader.php'); ?>
<?php
include_once('../controller/connect.php');

$dbs = new database();
$db = $dbs->connection();

if(isset($_POST['Apply'])) {
    $empid = $_SESSION['User']['EmployeeId'];
    $request_amount = $_POST['amount'];
    $status = "Pending";

    $query = "INSERT INTO advanced_salary_requests (employee_id, request_amount, status) VALUES ('$empid', '$request_amount', '$status')";
    mysqli_query($db, $query);

    echo "<script>window.location='applyadvance.php';</script>";
}
?>

<div class="s-12 l-10">
    <h1>Apply for Advance Salary</h1><hr>
    <div class="clearfix"></div>
</div>
<div class="s-12 l-6">
    <form action="" method="post">
        <label>Request Amount</label>
        <input type="text" id="amount" name="amount" placeholder="Request Amount" title="Request Amount" required="" autocomplete="off">

        <input type="submit" name="Apply" title="Submit" value="Submit">
    </form>
</div>

<?php include('userfooter.php'); ?>


<script type="text/javascript">
  
$('#EndDate').datetimepicker({
  yearOffset:0,
  lang:'ch',
  timepicker:false,
  format:'d/m/Y',
  formatDate:'Y/m/d',
  minDate:'2015/01/01', // yesterday is minimum date
  maxDate:'2030/01/01' // and tommorow is maximum date calendar
});

$('#StartDate').datetimepicker({
  yearOffset:0,
  lang:'ch',
  timepicker:false,
  format:'d/m/Y',
  formatDate:'Y/m/d',
  minDate:'2015/01/01', // yesterday is minimum date
  maxDate:'2030/01/01' // and tommorow is maximum date calendar
});

</script>


