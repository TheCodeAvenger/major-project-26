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
    <title>Dashboard</title>

    <link rel="stylesheet" href="style.css">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Hover Effect -->
    <style>
        .card {
             cursor: pointer;
    transition: 0.3s;
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(10px);
    border-radius: 15px;
    color: white;
        }
        .card:hover {
            transform: scale(1.05);
        }
        body {
background: linear-gradient(to right, #373b44, #4286f4);    color: white;
}
.card {
    border-radius: 15px;
}
.btn {
    border-radius: 10px;
    font-weight: bold;
}

    </style>
</head>
<body>
<?php include "navbar.php"; ?>

<div class="container mt-5">

    <!-- Header with Logout -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>📊 Dashboard</h2>
        <a href="logout.php" class="btn btn-danger">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </div>

    <div class="row text-center">

<?php
// Total Students
$students = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM students"));

// Total Classes
$classes = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM classes"));

// Total Sections
$sections = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM sections"));

// Attendance % (today)
$date = date("Y-m-d");

$total_attendance = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM attendance WHERE date='$date'"));
$present = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM attendance WHERE status='Present' AND date='$date'"));

$attendance_percent = ($total_attendance > 0) ? round(($present / $total_attendance) * 100) : 0;
?>

        <!-- Students -->
        <div class="col-md-3">
            <a href="view_students.php" style="text-decoration:none;">
                <div class="card bg-primary text-white p-3">
                    <h4><i class="fas fa-users"></i></h4>
                    <h5>Total Students</h5>
                    <h3><?php echo $students; ?></h3>
                </div>
            </a>
        </div>

        <!-- Classes -->
        <div class="col-md-3">
            <div class="card bg-success text-white p-3">
                <h4><i class="fas fa-school"></i></h4>
                <h5>Total Classes</h5>
                <h3><?php echo $classes; ?></h3>
            </div>
        </div>

        <!-- Sections -->
        <div class="col-md-3">
            <div class="card bg-warning text-dark p-3">
                <h4><i class="fas fa-layer-group"></i></h4>
                <h5>Total Sections</h5>
                <h3><?php echo $sections; ?></h3>
            </div>
        </div>

        <!-- Attendance -->
        <div class="col-md-3">
            <a href="view_attendance.php" style="text-decoration:none;">
                <div class="card bg-danger text-white p-3">
                    <h4><i class="fas fa-chart-line"></i></h4>
                    <h5>Today's Attendance</h5>
                    <h3><?php echo $attendance_percent; ?>%</h3>
                </div>
            </a>
        </div>

    </div>

</div>

</body>
</html>