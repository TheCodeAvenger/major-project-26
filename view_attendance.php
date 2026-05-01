<?php  
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

include "db.php";

// 🔥 BULK DELETE LOGIC
if (isset($_POST['delete_selected'])) {
    if (!empty($_POST['selected'])) {
        $ids = implode(",", $_POST['selected']);
        mysqli_query($conn, "DELETE FROM attendance WHERE id IN ($ids)");
        echo "<script>alert('Selected records deleted successfully 💀'); window.location.href='view_attendance.php';</script>";
    }
}
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

    <!-- 🔍 FILTER -->
    <form method="GET" class="mb-4 row">
        <div class="col-md-4">
            <label>Select Date:</label>
            <input type="date" name="date" class="form-control" 
                   value="<?php echo $_GET['date'] ?? ''; ?>">
        </div>

        <div class="col-md-4">
            <label>Select Month:</label>
            <input type="month" name="month" class="form-control" 
                   value="<?php echo $_GET['month'] ?? ''; ?>">
        </div>

        <div class="col-md-4 d-flex align-items-end">
            <button class="btn btn-primary w-100">Filter</button>
        </div>
    </form>

    <!-- 🔥 BULK DELETE FORM START -->
    <form method="POST">

    <div class="mb-3">
        <button type="submit" name="delete_selected" class="btn btn-danger"
                onclick="return confirm('Delete selected records?')">
            🗑 Delete Selected
        </button>
    </div>

    <table class="table table-bordered table-striped text-center">
        <tr class="table-dark">
            <th>
                <input type="checkbox" onclick="toggleAll(this)">
            </th>
            <th>Student Name</th>
            <th>Class</th>
            <th>Section</th>
            <th>Status</th>
            <th>Date</th>
            <th>Action</th>
        </tr>

<?php
$where = "";

if (!empty($_GET['date'])) {
    $date = $_GET['date'];
    $where = "WHERE attendance.date = '$date'";
}
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

if (mysqli_num_rows($result) == 0) {
    echo "<tr><td colspan='7'>No attendance found 😔</td></tr>";
} else {
    while ($row = mysqli_fetch_assoc($result)) {
?>
        <tr>
            <!-- 🔥 CHECKBOX -->
            <td>
                <input type="checkbox" name="selected[]" value="<?php echo $row['id']; ?>">
            </td>

            <td><?php echo $row['name']; ?></td>
            <td><?php echo $row['class']; ?></td>
            <td><?php echo $row['section']; ?></td>
            <td><?php echo $row['status']; ?></td>
            <td><?php echo $row['date']; ?></td>

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
    </form>

</div>

<!-- 💀 SELECT ALL SCRIPT -->
<script>
function toggleAll(source) {
    let checkboxes = document.querySelectorAll('input[name="selected[]"]');
    checkboxes.forEach(cb => cb.checked = source.checked);
}
</script>

<?php include "footer.php"; ?>
</body>
</html>