<?php
require "auth_admin.php";
include "db.php";
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Timetable | EduTrack Pro</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include "navbar.php"; ?>

<div class="container mt-5">
    <h2 class="mb-4 text-center">📅 Timetable Records</h2>

    <form method="GET" class="row mb-4">

        <div class="col-md-3">
            <label>Date:</label>
            <input type="date" name="date" class="form-control"
                   value="<?php echo $_GET['date'] ?? date('Y-m-d'); ?>">
        </div>

        <!-- 💀 FIXED CLASS -->
        <div class="col-md-3">
            <label>Class:</label>
            <select name="class" class="form-control">
                <option value="">All</option>
                <option value="11" <?php if(($_GET['class'] ?? '')=='11') echo 'selected'; ?>>11</option>
                <option value="12" <?php if(($_GET['class'] ?? '')=='12') echo 'selected'; ?>>12</option>
            </select>
        </div>

        <!-- 💀 FIXED SECTION -->
        <div class="col-md-3">
            <label>Section:</label>
            <select name="section" class="form-control">
                <option value="">All</option>
                <option value="PCM" <?php if(($_GET['section'] ?? '')=='PCM') echo 'selected'; ?>>PCM</option>
                <option value="PCB" <?php if(($_GET['section'] ?? '')=='PCB') echo 'selected'; ?>>PCB</option>
            </select>
        </div>

        <div class="col-md-3 d-flex align-items-end">
            <button class="btn btn-primary w-100">Filter</button>
        </div>

    </form>

    <table class="table table-bordered text-center">
        <tr class="table-dark">
            <th>Class</th>
            <th>Section</th>
            <th>Subject</th>
            <th>Date</th>
            <th>Time</th>
        </tr>

<?php
$date = $_GET['date'] ?? date("Y-m-d");

$where = ["date='$date'"];

if (!empty($_GET['class'])) {
    $where[] = "class='".$_GET['class']."'";
}
if (!empty($_GET['section'])) {
    $where[] = "section='".$_GET['section']."'";
}

$whereSQL = "WHERE " . implode(" AND ", $where);

$result = mysqli_query($conn, "SELECT * FROM timetable $whereSQL ORDER BY time");

if(mysqli_num_rows($result)==0){
    echo "<tr><td colspan='5'>No timetable 😔</td></tr>";
} else {
    while($row = mysqli_fetch_assoc($result)){
?>
<tr>
    <td><?php echo $row['class']; ?></td>
    <td><?php echo $row['section']; ?></td>
    <td><?php echo $row['subject']; ?></td>
    <td><?php echo $row['date']; ?></td>
    <td><?php echo date("h:i A", strtotime($row['time'])); ?></td>
</tr>
<?php }} ?>

    </table>
</div>

<?php include "footer.php"; ?>

</body>
</html>