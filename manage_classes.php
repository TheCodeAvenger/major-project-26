<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

include "db.php";

// Add class
if (isset($_POST['add_class'])) {
    $class = $_POST['class_name'];
    mysqli_query($conn, "INSERT INTO classes (class_name) VALUES ('$class')");
}

// Add section
if (isset($_POST['add_section'])) {
    $section = $_POST['section_name'];
    mysqli_query($conn, "INSERT INTO sections (section_name) VALUES ('$section')");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Classes & Sections</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include "navbar.php"; ?>

<div class="container mt-5">

    <h2 class="text-center mb-4">📚 Manage Classes & Sections</h2>

    <div class="row">

        <!-- ADD CLASS -->
        <div class="col-md-6">
            <h4>Add Class</h4>
            <form method="POST">
                <input type="text" name="class_name" class="form-control mb-2" placeholder="Enter Class (e.g. 10)" required>
                <button name="add_class" class="btn btn-success w-100">Add Class</button>
            </form>

            <hr>

            <h5>All Classes</h5>
            <ul class="list-group">
                <?php
                $c = mysqli_query($conn, "SELECT * FROM classes");
                if(mysqli_num_rows($c)==0){
                    echo "<li class='list-group-item'>No classes added</li>";
                } else {
                    while($row = mysqli_fetch_assoc($c)){
                        echo "<li class='list-group-item'>Class ".$row['class_name']."</li>";
                    }
                }
                ?>
            </ul>
        </div>

        <!-- ADD SECTION -->
        <div class="col-md-6">
            <h4>Add Section</h4>
            <form method="POST">
                <input type="text" name="section_name" class="form-control mb-2" placeholder="Enter Section (e.g. A)" required>
                <button name="add_section" class="btn btn-warning w-100">Add Section</button>
            </form>

            <hr>

            <h5>All Sections</h5>
            <ul class="list-group">
                <?php
                $s = mysqli_query($conn, "SELECT * FROM sections");
                if(mysqli_num_rows($s)==0){
                    echo "<li class='list-group-item'>No sections added</li>";
                } else {
                    while($row = mysqli_fetch_assoc($s)){
                        echo "<li class='list-group-item'>Section ".$row['section_name']."</li>";
                    }
                }
                ?>
            </ul>
        </div>

    </div>

</div>

</body>
</html>