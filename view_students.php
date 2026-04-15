<?php include "db.php"; ?>

<!DOCTYPE html>
<html>
<head>
    <title>View Students</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<div class="container mt-5">

    <h2 class="mb-4 text-center">📚 All Students</h2>

    <div class="text-end mb-3">
        <a href="add_student.php" class="btn btn-primary">
            <i class="fas fa-user-plus"></i> Add Student
        </a>
    </div>

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
        $query = "SELECT * FROM students";
        $result = mysqli_query($conn, $query);

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
        <?php } ?>
        </tbody>

    </table>

</div>

</body>
</html>