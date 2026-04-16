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

    <!-- 🔍 FILTER SECTION -->
    <form method="GET" class="mb-4 row">

        <div class="col-md-4">
            <label>Select Date:</label>
            <input type="date" name="date" class="form-control" 
                   value="<?php echo isset($_GET['date']) ? $_GET['date'] : ''; ?>">
        </div>

        <div class="col-md-4">
            <label>Select Month:</label>
            <input type="month" name="month" class="form-control" 
                   value="<?php echo isset($_GET['month']) ? $_GET['month'] : ''; ?>">
        </div>

        <div class="col-md-4 d-flex align-items-end">
            <button class="btn btn-primary w-100">Filter</button>
        </div>

    </form>

    <!-- 🔥 DELETE OPTIONS -->
    <div class="mb-3">

        <?php if (!empty($_GET['date'])) { ?>
            <a href="delete_attendance.php?date=<?php echo $_GET['date']; ?>" 
               class="btn btn-danger me-2"
               onclick="return confirm('Delete all attendance for this date?')">
               🗑 Delete This Date
            </a>
        <?php } ?>

        <?php if (!empty($_GET['month'])) { ?>
            <a href="delete_attendance.php?month=<?php echo $_GET['month']; ?>" 
               class="btn btn-danger"
               onclick="return confirm('Delete full month attendance?')">
               🗑 Delete This Month
            </a>
        <?php } ?>

    </div>

    <table class="table table-bordered table-striped">
        <tr class="table-dark">
            <th>Student Name</th>
            <th>Class</th>
            <th>Section</th>
            <th>Status</th>
            <th>Date</th>
            <th>Action</th>
        </tr>

<?php
$where = "";

// 📅 Date filter
if (!empty($_GET['date'])) {
    $date = $_GET['date'];
    $where = "WHERE attendance.date = '$date'";
}

// 📆 Month filter
elseif (!empty($_GET['month'])) {
    $month = $_GET['month'];
    $where = "WHERE DATE_FORMAT(attendance.date, '%Y-%m') = '$month'";
}

$query = "SELECT attendance.id, students.name, students.class, students.section, 
                 attendance.status, attendance.date
          FROM attendance
          JOIN students ON attendance.student_id = students.id
          $where
          ORDER BY attendance.date DESC";

$result = mysqli_query($conn, $query);

// ❌ No data message
if (mysqli_num_rows($result) == 0) {
    echo "<tr><td colspan='6' class='text-center'>No attendance found 😔</td></tr>";
} else {
    while ($row = mysqli_fetch_assoc($result)) {
?>
        <tr>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo $row['class']; ?></td>
            <td><?php echo $row['section']; ?></td>
            <td><?php echo $row['status']; ?></td>
            <td><?php echo $row['date']; ?></td>

            <!-- 🔥 SINGLE DELETE -->
            <td>
                <a href="delete_attendance.php?id=<?php echo $row['id']; ?>" 
                   class="btn btn-danger btn-sm"
                   onclick="return confirm('Delete this record?')">
                   Delete
                </a>
            </td>
        </tr>
<?php 
    }
}
?>

    </table>
</div>

</body>
</html>