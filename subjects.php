<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

include "db.php";

// Add subject
if (isset($_POST['add'])) {
    $name = $_POST['subject_name'];
    mysqli_query($conn, "INSERT INTO subjects (subject_name) VALUES ('$name')");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Subjects</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include "navbar.php"; ?>

<div class="container mt-5">
    <h2 class="text-center mb-4">📘 Manage Subjects</h2>

    <!-- Add Subject -->
    <form method="POST" class="mb-4">
        <input type="text" name="subject_name" class="form-control mb-2" placeholder="Enter Subject Name" required>
        <button name="add" class="btn btn-success w-100">Add Subject</button>
    </form>

    <!-- Show Subjects -->
    <table class="table table-bordered">
        <tr class="table-dark">
            <th>ID</th>
            <th>Subject Name</th>
        </tr>

<?php
$result = mysqli_query($conn, "SELECT * FROM subjects");

while ($row = mysqli_fetch_assoc($result)) {
?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['subject_name']; ?></td>
        </tr>
<?php } ?>

    </table>
</div>

</body>
</html>