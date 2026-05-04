<?php
require "auth_admin.php";
include "db.php";

$class = $_GET['class'] ?? '';
$section = $_GET['section'] ?? '';
$subject = $_GET['subject'] ?? '';

/* ================= EXAM UPLOAD ================= */
if(isset($_POST['upload_exam'])){
    $title = $_POST['title'];
    $deadline = $_POST['deadline']; // can be empty

    $file = $_FILES['question']['name'];
    $tmp = $_FILES['question']['tmp_name'];
    $path = "uploads/questions/" . time() . "_" . $file;

    move_uploaded_file($tmp, $path);

    mysqli_query($conn, "INSERT INTO exams (class, section, subject, title, question_file, deadline)
                         VALUES ('$class','$section','$subject','$title','$path','$deadline')");

    header("Location: subject_page.php?class=$class&section=$section&subject=$subject");
    exit();
}

/* ================= MARKS ================= */
if(isset($_POST['give_marks'])){
    $id = $_POST['submission_id'];
    $marks = $_POST['marks'];

    mysqli_query($conn, "UPDATE submissions SET marks='$marks' WHERE id='$id'");

    header("Location: subject_page.php?class=$class&section=$section&subject=$subject");
    exit();
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

<?php include "navbar.php"; ?>

<div class="container mt-5">

<h2 class="mb-3">📘 <?php echo $subject; ?></h2>

<!-- ================= UPLOAD FORM ================= -->
<div class="card p-3 mb-4">
    <h5>Add New Exam</h5>

    <form method="POST" enctype="multipart/form-data">
        <label>Exam Title:</label>
        <input type="text" name="title" class="form-control mb-2" required>

        <label>Deadline:</label>
        <input type="date" name="deadline" class="form-control mb-2" required>

        <label>Question File:</label>
        <input type="file" name="question" class="form-control mb-2" required>

        <button name="upload_exam" class="btn btn-success w-100">
            Upload Exam
        </button>
    </form>
</div>

<hr>

<?php
$exams = mysqli_query($conn, "SELECT * FROM exams 
                             WHERE class='$class' AND section='$section' AND subject='$subject'
                             ORDER BY id DESC");

if(mysqli_num_rows($exams)==0){
    echo "<p>No exams yet</p>";
}

while($exam = mysqli_fetch_assoc($exams)){
?>

<div class="card p-3 mb-4">

    <h5><?php echo $exam['title']; ?></h5>

    <!-- DEADLINE SHOW -->
    <?php if(!empty($exam['deadline'])){ ?>
        <span class="badge bg-danger mb-2">
            Deadline: <?php echo $exam['deadline']; ?>
        </span>
    <?php } ?>

    <br>

    <a href="<?php echo $exam['question_file']; ?>" target="_blank" class="btn btn-primary btn-sm mb-3">
        View Question
    </a>

    <hr>

    <h6>📥 Submissions</h6>

<?php
$subs = mysqli_query($conn, "SELECT * FROM submissions WHERE exam_id='{$exam['id']}'");

if(mysqli_num_rows($subs)==0){
    echo "<p>No submissions yet</p>";
}

while($s = mysqli_fetch_assoc($subs)){
?>

    <div class="border p-2 mb-2">

        <strong><?php echo $s['student_name']; ?></strong>

        <div class="mt-1 mb-2">
            <a href="<?php echo $s['answer_file']; ?>" target="_blank" class="btn btn-primary btn-sm">
                View Answer
            </a>
        </div>

        <!-- ================= MARKS INPUT ================= -->
        <form method="POST" class="d-flex gap-2">
            <input type="hidden" name="submission_id" value="<?php echo $s['id']; ?>">

            <input type="number" name="marks" class="form-control"
                   placeholder="Enter marks"
                   value="<?php echo ($s['marks'] !== null) ? $s['marks'] : ''; ?>">

            <button name="give_marks" class="btn btn-success btn-sm">
                Save
            </button>
        </form>

    </div>

<?php } ?>

</div>

<?php } ?>

</div>

</body>
</html>