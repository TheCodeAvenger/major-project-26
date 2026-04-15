<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

include "db.php";
?>

<?php


$id = $_GET['id'];
$result = mysqli_query($conn, "SELECT * FROM students WHERE id=$id");
$row = mysqli_fetch_assoc($result);

if (isset($_POST['update'])) {
    $name = $_POST['name'];
    $class = $_POST['class'];
    $section = $_POST['section'];
    $roll_no = $_POST['roll_no'];

    $query = "UPDATE students SET 
              name='$name', class='$class', section='$section', roll_no='$roll_no'
              WHERE id=$id";

    mysqli_query($conn, $query);
    header("Location: view_students.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Student</title>
<link rel="stylesheet" href="style.css">
    <!-- Bootstrap -->
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

        <div class="mb-3">
            <label>Class:</label>
            <select name="class" class="form-control" required>
                <?php
                $class_query = mysqli_query($conn, "SELECT * FROM classes");
                while ($c = mysqli_fetch_assoc($class_query)) {
                    $selected = ($c['class_name'] == $row['class']) ? "selected" : "";
                    echo "<option value='{$c['class_name']}' $selected>{$c['class_name']}</option>";
                }
                ?>
            </select>
        </div>

        <div class="mb-3">
            <label>Section:</label>
            <select name="section" class="form-control" required>
                <?php
                $sec_query = mysqli_query($conn, "SELECT * FROM sections");
                while ($s = mysqli_fetch_assoc($sec_query)) {
                    $selected = ($s['section_name'] == $row['section']) ? "selected" : "";
                    echo "<option value='{$s['section_name']}' $selected>{$s['section_name']}</option>";
                }
                ?>
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

</body>
</html>