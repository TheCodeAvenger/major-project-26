<?php
session_start();
include "db.php";

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'student') {
    header("Location: login.php");
    exit();
}

$name = $_SESSION['user']['name'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Attendance</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include "navbar.php"; ?>

<div class="container mt-5">
    <h3>📊 My Attendance</h3>

    <table class="table table-bordered">
        <tr>
            <th>Date</th>
            <th>Status</th>
        </tr>

<?php
$query = "SELECT attendance.date, attendance.status 
          FROM attendance
          JOIN students ON attendance.student_id = students.id
          WHERE students.name='$name'";

$result = mysqli_query($conn, $query);

while($row = mysqli_fetch_assoc($result)){
?>
        <tr>
            <td><?php echo $row['date']; ?></td>
            <td><?php echo $row['status']; ?></td>
        </tr>
<?php } ?>

    </table>
</div>

</body>
</html>