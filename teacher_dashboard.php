<?php
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'teacher') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Teacher Dashboard | EduTrack Pro</title>

    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(to right, #373b44, #4286f4);
            color: white;
        }

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
    </style>
</head>

<body>

<?php include "navbar.php"; ?>

<div class="container mt-5">

    <h2 class="text-center mb-4">👨‍🏫 Teacher Dashboard</h2>

    <div class="row text-center">

        <div class="col-md-4">
            <a href="view_students.php" style="text-decoration:none;">
                <div class="card p-3">
                    <h5>👥 View Students</h5>
                </div>
            </a>
        </div>

        <div class="col-md-4">
            <a href="attendance.php" style="text-decoration:none;">
                <div class="card p-3">
                    <h5>📝 Mark Attendance</h5>
                </div>
            </a>
        </div>

        <div class="col-md-4">
            <a href="view_timetable.php" style="text-decoration:none;">
                <div class="card p-3">
                    <h5>📅 View Timetable</h5>
                </div>
            </a>
        </div>

    </div>

</div>

<?php include "footer.php"; ?>

</body>
</html>