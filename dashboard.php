<?php
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
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

            /* 🔥 TEXT VISIBILITY FIX */
            text-shadow: 0 0 6px rgba(0,0,0,0.6);
        }

        .card:hover {
            transform: scale(1.05);
        }

        .btn {
            border-radius: 10px;
            font-weight: bold;
        }

        small {
            font-size: 12px;
            opacity: 0.95;
            display: block;
            margin-top: 5px;
            text-shadow: 0 0 4px rgba(0,0,0,0.5);
        }

        /* 🔥 HEADINGS CLEAR */
        h2, h3, h4, h5 {
            text-shadow: 0 0 8px rgba(0,0,0,0.7);
        }

        /* 🔥 TIMETABLE TEXT */
        .timetable-box {
            text-shadow: 0 0 5px rgba(0,0,0,0.6);
        }
    </style>
</head>
<body>

<?php include "navbar.php"; ?>

<div class="container mt-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>📊 Dashboard</h2>
        <a href="logout.php" class="btn btn-danger">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </div>

    <div class="row text-center">

<?php
$students = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM students"));
$classes = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM classes"));
$sections = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM sections"));

$date = date("Y-m-d");
$total_attendance = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM attendance WHERE date='$date'"));
$present = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM attendance WHERE status='Present' AND date='$date'"));

$attendance_percent = ($total_attendance > 0) ? round(($present / $total_attendance) * 100) : 0;
?>

        <!-- Students -->
        <div class="col-md-3">
            <a href="view_students.php" style="text-decoration:none;">
                <div class="card p-3">
                    <h4><i class="fas fa-users"></i></h4>
                    <h5>Total Students</h5>
                    <h3><?php echo $students; ?></h3>
                </div>
            </a>
        </div>

        <!-- Classes -->
        <div class="col-md-3">
            <a href="manage_classes.php" style="text-decoration:none;">
                <div class="card p-3">
                    <h4><i class="fas fa-school"></i></h4>
                    <h5>Total Classes</h5>
                    <h3><?php echo $classes; ?></h3>

                    <small>
                        <?php
                        $c = mysqli_query($conn, "SELECT * FROM classes LIMIT 3");
                        if(mysqli_num_rows($c)==0){
                            echo "No classes added";
                        } else {
                            while($row = mysqli_fetch_assoc($c)){
                                echo "<div>• Class ".$row['class_name']."</div>";
                            }
                        }
                        ?>
                    </small>
                </div>
            </a>
        </div>

        <!-- Sections -->
        <div class="col-md-3">
            <a href="manage_classes.php" style="text-decoration:none;">
                <div class="card p-3">
                    <h4><i class="fas fa-layer-group"></i></h4>
                    <h5>Total Sections</h5>
                    <h3><?php echo $sections; ?></h3>

                    <small>
                        <?php
                        $s = mysqli_query($conn, "SELECT * FROM sections LIMIT 3");
                        if(mysqli_num_rows($s)==0){
                            echo "No sections added";
                        } else {
                            while($row = mysqli_fetch_assoc($s)){
                                echo "<div>• Section ".$row['section_name']."</div>";
                            }
                        }
                        ?>
                    </small>
                </div>
            </a>
        </div>

        <!-- Attendance -->
        <div class="col-md-3">
            <a href="view_attendance.php" style="text-decoration:none;">
                <div class="card p-3">
                    <h4><i class="fas fa-chart-line"></i></h4>
                    <h5>Today's Attendance</h5>
                    <h3><?php echo $attendance_percent; ?>%</h3>
                </div>
            </a>
        </div>

    </div>

    <!-- 💀 TODAY TIMETABLE -->
    <div class="mt-5 timetable-box">

        <h4>📅 Today’s Timetable</h4>

        <?php
        $today = date("Y-m-d");

        $query = "SELECT * FROM timetable 
                  WHERE date='$today' 
                  ORDER BY class, section, time";

        $result = mysqli_query($conn, $query);

        $current_group = "";

        if (mysqli_num_rows($result) == 0) {
            echo "<p>No classes scheduled today 😔</p>";
        } else {

            while ($row = mysqli_fetch_assoc($result)) {

                $group = $row['class'] . "-" . $row['section'];

                if ($group != $current_group) {

                    if ($current_group != "") {
                        echo "</div>";
                    }

                    echo "<div class='mb-3'>";
                    echo "<strong>Class " . $row['class'] . " - Section " . $row['section'] . "</strong><br>";

                    $current_group = $group;
                }

                echo "• " . $row['subject'] . " - " . date("h:i A", strtotime($row['time'])) . "<br>";
            }

            echo "</div>";
        }
        ?>

    </div>

</div>
<?php include "footer.php"; ?>
</body>
</html>