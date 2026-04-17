<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

include "db.php";

// ➕ Add timetable
if (isset($_POST['add'])) {
    $class = $_POST['class'];
    $section = $_POST['section'];
    $subject = $_POST['subject'];
    $date = $_POST['date'];
    $time = $_POST['time'];

    mysqli_query($conn, "INSERT INTO timetable 
        (class, section, subject, date, time)
        VALUES ('$class','$section','$subject','$date','$time')");
}

// 💀 AUTO COPY (MAIN FEATURE)
if (isset($_POST['copy'])) {

    $new_date = $_POST['copy_date'];

    // previous date
    $prev_date = date('Y-m-d', strtotime($new_date . ' -1 day'));

    // copy query
    $query = "INSERT INTO timetable (class, section, subject, date, time)
              SELECT class, section, subject, '$new_date', time
              FROM timetable
              WHERE date = '$prev_date'";

    mysqli_query($conn, $query);

    echo "<script>alert('Timetable copied from previous day ✅');</script>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Timetable | EduTrack Pro</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include "navbar.php"; ?>

<div class="container mt-5">
    <h2 class="text-center mb-4">📅 Manage Timetable</h2>

    <!-- 💀 AUTO COPY SECTION -->
    <form method="POST" class="mb-4">
        <label>Copy Previous Day Timetable:</label>
        <input type="date" name="copy_date" class="form-control mb-2" required>
        <button name="copy" class="btn btn-warning w-100">⚡ Copy From Previous Day</button>
    </form>

    <hr>

    <!-- ➕ ADD TIMETABLE -->
    <form method="POST">

        <!-- CLASS -->
        <label>Class:</label>
        <select name="class" class="form-control mb-2" required>
            <option value="">Select Class</option>
            <?php
            $c = mysqli_query($conn, "SELECT * FROM classes");
            while($row = mysqli_fetch_assoc($c)){
                echo "<option>".$row['class_name']."</option>";
            }
            ?>
        </select>

        <!-- SECTION -->
        <label>Section:</label>
        <select name="section" class="form-control mb-2" required>
            <option value="">Select Section</option>
            <?php
            $s = mysqli_query($conn, "SELECT * FROM sections");
            while($row = mysqli_fetch_assoc($s)){
                echo "<option>".$row['section_name']."</option>";
            }
            ?>
        </select>

        <!-- SUBJECT -->
        <label>Subject:</label>
        <select name="subject" class="form-control mb-2" required>
            <option value="">Select Subject</option>
            <?php
            $sub = mysqli_query($conn, "SELECT * FROM subjects");
            while($row = mysqli_fetch_assoc($sub)){
                echo "<option>".$row['subject_name']."</option>";
            }
            ?>
        </select>

        <!-- DATE -->
        <label>Date:</label>
        <input type="date" name="date" class="form-control mb-2" required>

        <!-- TIME -->
        <label>Time:</label>
        <input type="time" name="time" class="form-control mb-3" required>

        <button name="add" class="btn btn-success w-100">Add Timetable</button>

    </form>
</div>

<?php include "footer.php"; ?>

</body>
</html>