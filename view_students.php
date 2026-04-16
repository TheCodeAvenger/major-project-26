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
    <title>View Students</title>

    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<?php include "navbar.php"; ?>

<div class="container mt-5">

    <h2 class="mb-4 text-center">📚 All Students</h2>

    <div class="text-end mb-3">
        <a href="add_student.php" class="btn btn-primary">
            <i class="fas fa-user-plus"></i> Add Student
        </a>
    </div>

    <!-- 🔍 SEARCH + FILTER -->
    <form method="GET" class="row mb-4">

        <div class="col-md-4">
            <input type="text" name="search" class="form-control"
                   placeholder="Search by name..."
                   value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
        </div>

        <div class="col-md-3">
            <select name="class" class="form-control">
                <option value="">All Classes</option>
                <?php
                $c = mysqli_query($conn, "SELECT * FROM classes");
                while($row = mysqli_fetch_assoc($c)){
                    echo "<option>". $row['class_name'] ."</option>";
                }
                ?>
            </select>
        </div>

        <div class="col-md-3">
            <select name="section" class="form-control">
                <option value="">All Sections</option>
                <?php
                $s = mysqli_query($conn, "SELECT * FROM sections");
                while($row = mysqli_fetch_assoc($s)){
                    echo "<option>". $row['section_name'] ."</option>";
                }
                ?>
            </select>
        </div>

        <div class="col-md-2">
            <button class="btn btn-primary w-100">Search</button>
        </div>

    </form>

    <table class="table table-bordered table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Class</th>
                <th>Section</th>
                <th>Roll No</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>

<?php
$where = [];

if (!empty($_GET['search'])) {
    $search = $_GET['search'];
    $where[] = "name LIKE '%$search%'";
}

if (!empty($_GET['class'])) {
    $class = $_GET['class'];
    $where[] = "class = '$class'";
}

if (!empty($_GET['section'])) {
    $section = $_GET['section'];
    $where[] = "section = '$section'";
}

$whereSQL = "";
if (!empty($where)) {
    $whereSQL = "WHERE " . implode(" AND ", $where);
}

$query = "SELECT * FROM students $whereSQL";
$result = mysqli_query($conn, $query);

if(mysqli_num_rows($result) == 0){
    echo "<tr><td colspan='6' class='text-center'>No students found 😔</td></tr>";
} else {

    while ($row = mysqli_fetch_assoc($result)) {
?>

            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['class']; ?></td>
                <td><?php echo $row['section']; ?></td>
                <td><?php echo $row['roll_no']; ?></td>
                <td>
                    <a href="edit_student.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i>
                    </a>

                    <a href="delete_student.php?id=<?php echo $row['id']; ?>" 
                       class="btn btn-danger btn-sm"
                       onclick="return confirm('Are you sure you want to delete this student?');">
                        <i class="fas fa-trash"></i>
                    </a>
                </td>
            </tr>

<?php 
    }
}
?>

        </tbody>

    </table>

</div>
<?php include "footer.php"; ?>
</body>
</html>