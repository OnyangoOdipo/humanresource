<?php include('userheader.php'); ?>
<?php
include_once('../controller/connect.php');

$dbs = new database();
$db = $dbs->connection();

// Fetch announcements
$query = "SELECT * FROM announcements ORDER BY created_at DESC";
$result = mysqli_query($db, $query);
?>

<div class="s-12 l-10">
    <h1>Company Announcements</h1>
    <hr>
    <div class="clearfix"></div>
</div>

<div class="s-12 l-8">
    <table class="table-style">
        <tr>
            <th>Title</th>
            <th>Content</th>
            <th>Posted On</th>
        </tr>
        <?php while ($announcement = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?php echo htmlspecialchars($announcement['title']); ?></td>
            <td><?php echo htmlspecialchars($announcement['content']); ?></td>
            <td><?php echo date('d/m/Y H:i', strtotime($announcement['created_at'])); ?></td>
        </tr>
        <?php } ?>
    </table>
</div>

<?php include('userfooter.php'); ?>

<style>
.table-style {
    width: 100%;
    border-collapse: collapse;
}

.table-style th, .table-style td {
    border: 1px solid #ddd;
    padding: 8px;
}

.table-style tr:nth-child(even) {
    background-color: #f2f2f2;
}

.table-style th {
    background-color: #4CAF50;
    color: white;
    text-align: left;
}
</style>
