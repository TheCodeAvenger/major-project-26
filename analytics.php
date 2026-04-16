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
    <title>Analytics</title>

    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
/* Force white text */
.container {
    color: #ffffff !important;
}

/* Headings clear */
h2, h4 {
    color: #ffffff !important;
    text-shadow: 0 0 8px rgba(0,0,0,0.7);
}

/* Data text */
.container div, .container p {
    color: #ffffff !important;
    text-shadow: 0 0 5px rgba(0,0,0,0.6);
}

/* Strong labels */
strong {
    color: #ffffff !important;
}
</style>

</head>
<body>

<?php include "navbar.php"; ?>

<div class="container mt-5">

    <h2 class="text-center mb-4">📊 Analytics Dashboard</h2>

    <!-- 🎓 STUDENTS PER CLASS -->
    <div class="mb-5">
        <h4>🎓 Students per Class</h4>

        <?php
        $query = "SELECT class, COUNT(*) as total 
                  FROM students 
                  GROUP BY class";

        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) == 0) {
            echo "<p>No student data available 😔</p>";
        } else {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<div>• Class " . $row['class'] . " → " . $row['total'] . " students</div>";
            }
        }
        ?>
    </div>

    <!-- 💀 TABLE ANALYTICS -->
    <div>
        <h4>📝 Today’s Attendance (Class & Section Wise)</h4>

        <table class="table table-bordered table-striped text-center">
            <tr class="table-dark">
                <th>Class</th>
                <th>Section</th>
                <th>Total Students</th>
                <th>Present</th>
                <th>Absent</th>
            </tr>

<?php
$date = date("Y-m-d");

$query = "SELECT students.class, students.section,
          COUNT(students.id) as total_students,
          SUM(CASE WHEN attendance.status='Present' THEN 1 ELSE 0 END) as present,
          SUM(CASE WHEN attendance.status='Absent' THEN 1 ELSE 0 END) as absent
          FROM students
          LEFT JOIN attendance 
          ON students.id = attendance.student_id AND attendance.date='$date'
          GROUP BY students.class, students.section
          ORDER BY students.class, students.section";

$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    echo "<tr><td colspan='5'>No data available 😔</td></tr>";
} else {

    while ($row = mysqli_fetch_assoc($result)) {
?>
        <tr>
            <td><?php echo $row['class']; ?></td>
            <td><?php echo $row['section']; ?></td>
            <td><?php echo $row['total_students']; ?></td>
            <td><?php echo $row['present'] ?? 0; ?></td>
            <td><?php echo $row['absent'] ?? 0; ?></td>
        </tr>
<?php 
    }
}
?>

        </table>
    </div>

</div>
<?php include "footer.php"; ?>
</body>
</html>