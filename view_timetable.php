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
    <title>View Timetable</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include "navbar.php"; ?>

<div class="container mt-5">
    <h2 class="mb-4 text-center">📅 Timetable Records</h2>

    <!-- 🔍 FILTER -->
    <form method="GET" class="row mb-4">

        <div class="col-md-3">
            <label>Date:</label>
            <input type="date" name="date" class="form-control"
                   value="<?php echo isset($_GET['date']) ? $_GET['date'] : ''; ?>">
        </div>

        <div class="col-md-3">
            <label>Class:</label>
            <select name="class" class="form-control">
                <option value="">All</option>
                <?php
                $c = mysqli_query($conn, "SELECT * FROM classes");
                while($row = mysqli_fetch_assoc($c)){
                    echo "<option>".$row['class_name']."</option>";
                }
                ?>
            </select>
        </div>

        <div class="col-md-3">
            <label>Section:</label>
            <select name="section" class="form-control">
                <option value="">All</option>
                <?php
                $s = mysqli_query($conn, "SELECT * FROM sections");
                while($row = mysqli_fetch_assoc($s)){
                    echo "<option>".$row['section_name']."</option>";
                }
                ?>
            </select>
        </div>

        <div class="col-md-3 d-flex align-items-end">
            <button class="btn btn-primary w-100">Filter</button>
        </div>

    </form>

    <table class="table table-bordered table-striped">
        <tr class="table-dark">
            <th>Class</th>
            <th>Section</th>
            <th>Subject</th>
            <th>Date</th>
            <th>Time</th>
        </tr>

<?php
$where = [];

// Filters
if (!empty($_GET['date'])) {
    $where[] = "date = '".$_GET['date']."'";
}
if (!empty($_GET['class'])) {
    $where[] = "class = '".$_GET['class']."'";
}
if (!empty($_GET['section'])) {
    $where[] = "section = '".$_GET['section']."'";
}

// Build WHERE
$whereSQL = "";
if (!empty($where)) {
    $whereSQL = "WHERE " . implode(" AND ", $where);
}

// Query
$query = "SELECT * FROM timetable $whereSQL ORDER BY date DESC, time ASC";

$result = mysqli_query($conn, $query);

// No data
if (mysqli_num_rows($result) == 0) {
    echo "<tr><td colspan='5' class='text-center'>No timetable found 😔</td></tr>";
} else {
    while ($row = mysqli_fetch_assoc($result)) {
?>
        <tr>
            <td><?php echo $row['class']; ?></td>
            <td><?php echo $row['section']; ?></td>
            <td><?php echo $row['subject']; ?></td>
            <td><?php echo $row['date']; ?></td>
            <td><?php echo date("h:i A", strtotime($row['time'])); ?></td>
        </tr>
<?php 
    }
}
?>

    </table>
</div>
<?php include "footer.php"; ?>
</body>
</html>