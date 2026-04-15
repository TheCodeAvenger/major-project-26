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
    <title>Add Student</title>

    <link rel="stylesheet" href="style.css">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include "navbar.php"; ?>

<div class="container mt-5">

    <h2 class="mb-4 text-center">➕ Add Student</h2>

    <form method="POST" class="border p-4 rounded shadow">

        <div class="mb-3">
            <label>Name:</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Class:</label>
            <select name="class" class="form-control" required>
                <option value="">Select Class</option>
                <?php
                $class_query = mysqli_query($conn, "SELECT * FROM classes");
                while ($c = mysqli_fetch_assoc($class_query)) {
                    echo "<option value='{$c['class_name']}'>{$c['class_name']}</option>";
                }
                ?>
            </select>
        </div>

        <div class="mb-3">
            <label>Section:</label>
            <select name="section" class="form-control" required>
                <option value="">Select Section</option>
                <?php
                $sec_query = mysqli_query($conn, "SELECT * FROM sections");
                while ($s = mysqli_fetch_assoc($sec_query)) {
                    echo "<option value='{$s['section_name']}'>{$s['section_name']}</option>";
                }
                ?>
            </select>
        </div>

        <div class="mb-3">
            <label>Roll No:</label>
            <input type="text" name="roll_no" class="form-control" required>
        </div>

        <button type="submit" name="submit" class="btn btn-success w-100">
            Add Student
        </button>

    </form>

</div>

</body>
</html>

<?php
if (isset($_POST['submit'])) {

    $name = $_POST['name'];
    $class = $_POST['class'];
    $section = $_POST['section'];
    $roll_no = $_POST['roll_no'];

    $query = "INSERT INTO students (name, class, section, roll_no) 
              VALUES ('$name', '$class', '$section', '$roll_no')";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Student Added Successfully!');</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>