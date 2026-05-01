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

// 💀 COPY
if (isset($_POST['copy'])) {

    $new_date = $_POST['copy_date'];
    $prev_date = date('Y-m-d', strtotime($new_date . ' -1 day'));

    mysqli_query($conn, "INSERT INTO timetable (class, section, subject, date, time)
              SELECT class, section, subject, '$new_date', time
              FROM timetable
              WHERE date = '$prev_date'");

    echo "<script>alert('Copied successfully ✅');</script>";
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

    <!-- COPY -->
    <form method="POST" class="mb-4">
        <label>Copy Previous Day:</label>
        <input type="date" name="copy_date" class="form-control mb-2" required>
        <button name="copy" class="btn btn-warning w-100">Copy</button>
    </form>

    <hr>

    <form method="POST">

        <!-- 💀 FIXED CLASS -->
        <label>Class:</label>
        <select name="class" class="form-control mb-2" required>
            <option value="">Select</option>
            <option value="11">11</option>
            <option value="12">12</option>
        </select>

        <!-- 💀 FIXED SECTION -->
        <label>Section:</label>
        <select name="section" class="form-control mb-2" required>
            <option value="">Select</option>
            <option value="PCM">PCM</option>
            <option value="PCB">PCB</option>
        </select>

        <!-- SUBJECT -->
        <label>Subject:</label>
        <select name="subject" class="form-control mb-2" required>
            <option value="">Select</option>
            <?php
            $sub = mysqli_query($conn, "SELECT * FROM subjects");
            while($row = mysqli_fetch_assoc($sub)){
                echo "<option>".$row['subject_name']."</option>";
            }
            ?>
        </select>

        <label>Date:</label>
        <input type="date" name="date" class="form-control mb-2" required>

        <label>Time:</label>
        <input type="time" name="time" class="form-control mb-3" required>

        <button name="add" class="btn btn-success w-100">Add Timetable</button>

    </form>
</div>

<?php include "footer.php"; ?>

</body>
</html>