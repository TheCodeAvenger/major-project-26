<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

include "db.php";

// 📅 Selected date logic (GET + POST support)
$date = isset($_REQUEST['date']) ? $_REQUEST['date'] : date("Y-m-d");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Attendance</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include "navbar.php"; ?>

<div class="container mt-5">
    <h2 class="mb-4 text-center">📝 Mark Attendance</h2>

    <!-- FORM -->
    <form method="POST">

        <!-- 📅 DATE INPUT (AUTO SUBMIT) -->
        <div class="mb-3">
            <label>Select Date:</label>
            <input type="date" name="date" class="form-control" 
                   value="<?php echo $date; ?>" 
                   onchange="this.form.submit()">
        </div>

        <table class="table table-bordered">
            <tr>
                <th>Name</th>
                <th>Class</th>
                <th>Section</th>
                <th>Attendance</th>
            </tr>

<?php
$result = mysqli_query($conn, "SELECT * FROM students");

while ($row = mysqli_fetch_assoc($result)) {

    // Check attendance for selected date
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
                           <?php if ($existing && $existing['status'] == 'Present') echo 'checked'; ?>
                           required> Present

                    <input type="radio" 
                           name="attendance[<?php echo $row['id']; ?>]" 
                           value="Absent"
                           <?php if ($existing && $existing['status'] == 'Absent') echo 'checked'; ?>>
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

</body>
</html>

<?php
if (isset($_POST['submit'])) {

    $date = $_POST['date'];

    foreach ($_POST['attendance'] as $student_id => $status) {

        // Check if already exists
        $check = mysqli_query($conn, "SELECT * FROM attendance 
                                     WHERE student_id='$student_id' AND date='$date'");

        if (mysqli_num_rows($check) > 0) {
            // Update existing
            $query = "UPDATE attendance 
                      SET status='$status' 
                      WHERE student_id='$student_id' AND date='$date'";
        } else {
            // Insert new
            $query = "INSERT INTO attendance (student_id, status, date)
                      VALUES ('$student_id', '$status', '$date')";
        }

        mysqli_query($conn, $query);
    }

    echo "<script>alert('Attendance Saved Successfully!'); 
          window.location.href='attendance.php?date=$date';</script>";
}
?>