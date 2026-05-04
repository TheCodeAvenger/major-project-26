<?php
require "auth_admin.php";
include "db.php";
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard | EduTrack Pro</title>

    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

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
            text-shadow: 0 0 6px rgba(0,0,0,0.6);
        }

        .card:hover {
            transform: scale(1.05);
        }

        small {
            font-size: 12px;
            opacity: 0.95;
        }
    </style>
</head>
<body>

<?php include "navbar.php"; ?>

<div class="container mt-5">

    <div class="d-flex justify-content-between mb-4">
        <h2>📊 Dashboard</h2>
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>

<?php
$students = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM students"));

// 💀 HARD FIXED VALUES
$classes = 2;   // 11,12
$sections = 2;  // PCM,PCB

$date = date("Y-m-d");
$total = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM attendance WHERE date='$date'"));
$present = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM attendance WHERE status='Present' AND date='$date'"));

$percent = ($total>0) ? round(($present/$total)*100) : 0;
?>

    <div class="row text-center">

        <div class="col-md-3">
            <div class="card p-3">
                <h5>👥 Students</h5>
                <h3><?php echo $students; ?></h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card p-3">
                <h5>🏫 Classes</h5>
                <h3><?php echo $classes; ?></h3>
                <small>11, 12</small>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card p-3">
                <h5>📚 Sections</h5>
                <h3><?php echo $sections; ?></h3>
                <small>PCM, PCB</small>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card p-3">
                <h5>📊 Attendance</h5>
                <h3><?php echo $percent; ?>%</h3>
            </div>
        </div>

    </div>

    <!-- TIMETABLE -->
    <div class="mt-5">
        <h4>📅 Today’s Timetable</h4>

<?php
$result = mysqli_query($conn, "SELECT * FROM timetable WHERE date='$date' ORDER BY class,section,time");

$current = "";

while($row = mysqli_fetch_assoc($result)){

    $group = $row['class']."-".$row['section'];

    if($group != $current){
        if($current!="") echo "</div>";

        echo "<div class='mb-3'>";
        echo "<strong>Class ".$row['class']." - ".$row['section']."</strong><br>";

        $current = $group;
    }

    echo "• ".$row['subject']." - ".date("h:i A",strtotime($row['time']))."<br>";
}

echo "</div>";
?>

    </div>

</div>

<?php include "footer.php"; ?>
</body>
</html>