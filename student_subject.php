<?php 
require "auth_student.php";
include "db.php";

if (isset($_SESSION['admin'])) {
    header("Location: dashboard.php");
    exit();
}

$class = $_SESSION['class'];
$section = $_SESSION['section'];
$name = $_SESSION['student_name'];

$subject = $_GET['subject'] ?? '';

if ($subject == '') {
    header("Location: student_dashboard.php");
    exit();
}

/* ================= SUBMIT / REUPLOAD ================= */
if(isset($_POST['submit_answer'])){

    $exam_id = $_POST['exam_id'];

    $file = $_FILES['answer']['name'];
    $tmp = $_FILES['answer']['tmp_name'];
    $path = "uploads/answers/" . time() . "_" . $file;

    // get deadline
    $exam_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT deadline FROM exams WHERE id='$exam_id'"));
    $today = date("Y-m-d");

    if($exam_data['deadline'] && $today > $exam_data['deadline']){
        echo "<script>alert('Deadline Over ❌');</script>";
    } else {

        // check existing
        $check = mysqli_query($conn, "SELECT * FROM submissions 
                                     WHERE exam_id='$exam_id' AND student_name='$name'");

        if(mysqli_num_rows($check) > 0){
            // 🔁 REPLACE FILE
            $old = mysqli_fetch_assoc($check);
            @unlink($old['answer_file']);

            move_uploaded_file($tmp, $path);

            mysqli_query($conn, "UPDATE submissions 
                                 SET answer_file='$path'
                                 WHERE exam_id='$exam_id' AND student_name='$name'");

            echo "<script>alert('Re-uploaded ✅'); window.location.href='student_subject.php?subject=$subject';</script>";
        } else {

            move_uploaded_file($tmp, $path);

            mysqli_query($conn, "INSERT INTO submissions (exam_id, student_name, answer_file)
                                 VALUES ('$exam_id','$name','$path')");

            echo "<script>alert('Submitted ✅'); window.location.href='student_subject.php?subject=$subject';</script>";
        }
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $subject; ?></title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include "student_navbar.php"; ?>

<div class="container mt-5">

<h3>📘 <?php echo $subject; ?></h3>
<p>Class <?php echo $class; ?> - <?php echo $section; ?></p>

<hr>

<?php
$exams = mysqli_query($conn, "SELECT * FROM exams 
                             WHERE class='$class' AND section='$section' AND subject='$subject'");

while($exam = mysqli_fetch_assoc($exams)){

    $submitted = mysqli_query($conn, "SELECT * FROM submissions 
                                     WHERE exam_id='{$exam['id']}' AND student_name='$name'");
    $subData = mysqli_fetch_assoc($submitted);

    $today = date("Y-m-d");
?>

<div class="card p-3 mb-3">

<strong><?php echo $exam['title']; ?></strong><br>

<?php if($exam['deadline']){ ?>
<span class="badge bg-danger">Deadline: <?php echo $exam['deadline']; ?></span>
<?php } ?>

<br>

<a href="<?php echo $exam['question_file']; ?>" target="_blank" class="btn btn-primary btn-sm mt-2">
    View Question
</a>

<!-- MARKS -->
<?php if($subData && $subData['marks'] !== null){ ?>
    <div class="mt-2 alert alert-info">
        Marks: <?php echo $subData['marks']; ?> 🧠
    </div>
<?php } ?>

<?php if($exam['deadline'] && $today > $exam['deadline']){ ?>

    <button class="btn btn-danger w-100 mt-2" disabled>Deadline Over ❌</button>

<?php } else { ?>

    <form method="POST" enctype="multipart/form-data" class="mt-2">
        <input type="hidden" name="exam_id" value="<?php echo $exam['id']; ?>">
        <input type="file" name="answer" class="form-control mb-2" required>

        <?php if($subData){ ?>
            <button name="submit_answer" class="btn btn-warning w-100">Re-upload Answer 🔁</button>
        <?php } else { ?>
            <button name="submit_answer" class="btn btn-success w-100">Submit Answer</button>
        <?php } ?>
    </form>

<?php } ?>

</div>

<?php } ?>

</div>

</body>
</html>