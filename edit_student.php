<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

include "db.php";

// 🔥 GET STUDENT
$id = $_GET['id'];
$result = mysqli_query($conn, "SELECT * FROM students WHERE id=$id");
$row = mysqli_fetch_assoc($result);

// 🔥 UPDATE
if (isset($_POST['update'])) {
    $name = $_POST['name'];
    $class = $_POST['class'];
    $section = $_POST['section'];
    $roll_no = $_POST['roll_no'];

    $query = "UPDATE students SET 
              name='$name', class='$class', section='$section', roll_no='$roll_no'
              WHERE id=$id";

    mysqli_query($conn, $query);

    echo "<script>alert('Student Updated Successfully ✅'); window.location.href='view_students.php';</script>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Student | EduTrack Pro</title>

    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include "navbar.php"; ?>

<div class="container mt-5">

    <h2 class="mb-4 text-center">✏️ Edit Student</h2>

    <form method="POST" class="border p-4 rounded shadow">

        <div class="mb-3">
            <label>Name:</label>
            <input type="text" name="name" class="form-control" value="<?php echo $row['name']; ?>" required>
        </div>

        <!-- 💀 FIXED CLASS -->
        <div class="mb-3">
            <label>Class:</label>
            <select name="class" class="form-control" required>
                <option value="11" <?php if($row['class']=='11') echo 'selected'; ?>>11</option>
                <option value="12" <?php if($row['class']=='12') echo 'selected'; ?>>12</option>
            </select>
        </div>

        <!-- 💀 FIXED SECTION -->
        <div class="mb-3">
            <label>Section:</label>
            <select name="section" class="form-control" required>
                <option value="PCM" <?php if($row['section']=='PCM') echo 'selected'; ?>>PCM</option>
                <option value="PCB" <?php if($row['section']=='PCB') echo 'selected'; ?>>PCB</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Roll No:</label>
            <input type="text" name="roll_no" class="form-control" value="<?php echo $row['roll_no']; ?>" required>
        </div>

        <button type="submit" name="update" class="btn btn-success w-100">
            Update Student
        </button>

    </form>

</div>

<?php include "footer.php"; ?>

</body>
</html>