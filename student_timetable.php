<?php
session_start();
include "db.php";

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'student') {
    header("Location: login.php");
    exit();
}

// future me class/section link karenge
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Timetable</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include "navbar.php"; ?>

<div class="container mt-5">
    <h3>📅 My Timetable</h3>

    <table class="table table-bordered">
        <tr>
            <th>Class</th>
            <th>Section</th>
            <th>Subject</th>
            <th>Time</th>
        </tr>

<?php
$result = mysqli_query($conn, "SELECT * FROM timetable");

while($row = mysqli_fetch_assoc($result)){
?>
        <tr>
            <td><?php echo $row['class']; ?></td>
            <td><?php echo $row['section']; ?></td>
            <td><?php echo $row['subject']; ?></td>
            <td><?php echo $row['time']; ?></td>
        </tr>
<?php } ?>

    </table>
</div>

</body>
</html>