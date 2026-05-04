<?php
require "auth_admin.php";
include "db.php";

// 📅 Date
$date = $_REQUEST['date'] ?? date("Y-m-d");

// 🔍 Filters
$filter_class = $_GET['class'] ?? '';
$filter_section = $_GET['section'] ?? '';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Attendance | EduTrack Pro</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include "navbar.php"; ?>

<div class="container mt-5">
    <h2 class="mb-4 text-center">📝 Mark Attendance</h2>

    <!-- 🔍 FILTER -->
    <form method="GET" class="row mb-3">

        <div class="col-md-4">
            <label>Date:</label>
            <input type="date" name="date" class="form-control" value="<?php echo $date; ?>">
        </div>

        <div class="col-md-3">
            <label>Class:</label>
            <select name="class" class="form-control">
                <option value="">All</option>
                <option value="11" <?php if($filter_class=='11') echo 'selected'; ?>>11</option>
                <option value="12" <?php if($filter_class=='12') echo 'selected'; ?>>12</option>
            </select>
        </div>

        <div class="col-md-3">
            <label>Section:</label>
            <select name="section" class="form-control">
                <option value="">All</option>
                <option value="PCM" <?php if($filter_section=='PCM') echo 'selected'; ?>>PCM</option>
                <option value="PCB" <?php if($filter_section=='PCB') echo 'selected'; ?>>PCB</option>
            </select>
        </div>

        <div class="col-md-2 d-flex align-items-end">
            <button class="btn btn-primary w-100">Load</button>
        </div>

    </form>

    <!-- FORM -->
    <form method="POST">
        <input type="hidden" name="date" value="<?php echo $date; ?>">

        <table class="table table-bordered text-center">
            <tr class="table-dark">
                <th>Name</th>
                <th>Class</th>
                <th>Section</th>
                <th>Attendance</th>
            </tr>

<?php
// 🔥 Query with filter
$where = [];

if($filter_class) $where[] = "class='$filter_class'";
if($filter_section) $where[] = "section='$filter_section'";

$whereSQL = $where ? "WHERE " . implode(" AND ", $where) : "";

$result = mysqli_query($conn, "SELECT * FROM students $whereSQL ORDER BY class, section, roll_no");

if(mysqli_num_rows($result) == 0){
    echo "<tr><td colspan='4'>No students found 😔</td></tr>";
}

while ($row = mysqli_fetch_assoc($result)) {

    $check = mysqli_query($conn, "SELECT * FROM attendance 
                                 WHERE student_id='{$row['id']}' AND date='$date'");
    $existing = mysqli_fetch_assoc($check);
?>
        <tr>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo $row['class']; ?></td>
            <td><?php echo $row['section']; ?></td>
            <td>
                <input type="radio" 
                       name="attendance[<?php echo $row['id']; ?>]" 
                       value="Present"
                       <?php if ($existing && $existing['status']=='Present') echo 'checked'; ?>
                       required> Present

                <input type="radio" 
                       name="attendance[<?php echo $row['id']; ?>]" 
                       value="Absent"
                       <?php if ($existing && $existing['status']=='Absent') echo 'checked'; ?>>
                Absent
            </td>
        </tr>
<?php } ?>

        </table>

        <button type="submit" name="submit" class="btn btn-success w-100">
            Save Attendance
        </button>

    </form>
</div>

<?php include "footer.php"; ?>

</body>
</html>

<?php
if (isset($_POST['submit'])) {

    $date = $_POST['date'];

    foreach ($_POST['attendance'] as $student_id => $status) {

        $check = mysqli_query($conn, "SELECT * FROM attendance 
                                     WHERE student_id='$student_id' AND date='$date'");

        if (mysqli_num_rows($check) > 0) {
            $query = "UPDATE attendance 
                      SET status='$status' 
                      WHERE student_id='$student_id' AND date='$date'";
        } else {
            $query = "INSERT INTO attendance (student_id, status, date)
                      VALUES ('$student_id', '$status', '$date')";
        }

        mysqli_query($conn, $query);
    }

    echo "<script>alert('Attendance Saved Successfully! ✅'); 
          window.location.href='attendance.php?date=$date';</script>";
}
?>