<?php include('userheader.php'); ?>
<?php
include_once('../controller/connect.php');

$dbs = new database();
$db = $dbs->connection();

$user_id = $_SESSION['User']['EmployeeId'];

// Fetch notifications for the logged-in user
$query = "SELECT * FROM notifications WHERE user_id='$user_id' ORDER BY created_at DESC";
$result = mysqli_query($db, $query);

// Mark notifications as read
if (isset($_GET['mark_read'])) {
    $notification_id = $_GET['mark_read'];
    $update_query = "UPDATE notifications SET read_status='read' WHERE id='$notification_id' AND user_id='$user_id'";
    mysqli_query($db, $update_query);
    echo "<script>window.location='notifications.php';</script>";
}
?>

<div class="s-12 l-10">
    <h1>Your Notifications</h1>
    <hr>
    <div class="clearfix"></div>
</div>

<div class="s-12 l-8">
    <table class="table-style">
        <tr>
            <th>Title</th>
            <th>Content</th>
            <th>Received At</th>
            <th>Actions</th>
        </tr>
        <?php while ($notification = mysqli_fetch_assoc($result)) { ?>
        <tr class="<?php echo $notification['read_status'] == 'unread' ? 'unread' : ''; ?>">
            <td><?php echo htmlspecialchars($notification['title']); ?></td>
            <td><?php echo htmlspecialchars($notification['content']); ?></td>
            <td><?php echo date('d/m/Y H:i', strtotime($notification['created_at'])); ?></td>
            <td>
                <?php if ($notification['read_status'] == 'unread') { ?>
                <a href="notifications.php?mark_read=<?php echo $notification['id']; ?>" class="btn-read">Mark as Read</a>
                <?php } ?>
            </td>
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

.unread {
    background-color: #ffdddd;
}

.btn-read {
    color: #007BFF;
    text-decoration: none;
}

.btn-read:hover {
    text-decoration: underline;
}
</style>
