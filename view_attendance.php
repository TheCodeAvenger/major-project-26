<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

include "db.php";
?>



<!DOCTYPE html>
<html>
<head>
    <title>View Attendance</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include "navbar.php"; ?>

<div class="container mt-5">
    <h2 class="mb-4 text-center">📊 Attendance Records</h2>

    <table class="table table-bordered table-striped">
        <tr class="table-dark">
            <th>Student Name</th>
            <th>Class</th>
            <th>Section</th>
            <th>Status</th>
            <th>Date</th>
        </tr>

<?php
$query = "SELECT students.name, students.class, students.section, attendance.status, attendance.date
          FROM attendance
          JOIN students ON attendance.student_id = students.id";

$result = mysqli_query($conn, $query);

while ($row = mysqli_fetch_assoc($result)) {
?>
        <tr>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo $row['class']; ?></td>
            <td><?php echo $row['section']; ?></td>
            <td><?php echo $row['status']; ?></td>
            <td><?php echo $row['date']; ?></td>
        </tr>
<?php } ?>

    </table>
</div>

</body>
</html>