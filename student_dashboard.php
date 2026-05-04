<?php
require "auth_student.php";   // 💀 security check
include "db.php";            // 💀 database connection

// 💀 extra safety (admin ko yaha aane se rokne ke liye)
if (isset($_SESSION['admin'])) {
    header("Location: dashboard.php");
    exit();
}

$class = $_SESSION['class'];
$section = $_SESSION['section'];
$name = $_SESSION['student_name'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Student Dashboard</title>

    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .card {
            cursor: pointer;
            transition: 0.3s;
            border-radius: 15px;
        }
        .card:hover {
            transform: scale(1.05);
        }
    </style>
</head>
<body>

<?php include "student_navbar.php"; ?>

<div class="container mt-5 text-center">

    <h2>👋 <?php echo $name; ?></h2>
    <p>Class <?php echo $class; ?> - <?php echo $section; ?></p>

    <hr>

    <h4>Select Subject</h4>

    <div class="row mt-4">

<?php
$subjects = ($section == "PCM") 
    ? ["Physics","Chemistry","Mathematics"] 
    : ["Physics","Chemistry","Biology"];

foreach($subjects as $sub){
?>

    <div class="col-md-4">
        <div class="card p-3"
             onclick="openSubject('<?php echo $sub ?>')">
            <?php echo $sub ?>
        </div>
    </div>

<?php } ?>

    </div>

</div>

<script>
function openSubject(subject){
    window.location.href = "student_subject.php?subject=" + subject;
}
</script>

</body>
</html>