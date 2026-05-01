<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

include "db.php";

$class = $_GET['class'] ?? '';
$section = $_GET['section'] ?? '';
$subject = $_GET['subject'] ?? '';

/* ================= EXAM UPLOAD ================= */
if(isset($_POST['upload_exam'])){
    $title = $_POST['title'];

    $file = $_FILES['question']['name'];
    $tmp = $_FILES['question']['tmp_name'];

    $path = "uploads/questions/" . time() . "_" . $file;

    move_uploaded_file($tmp, $path);

    mysqli_query($conn, "INSERT INTO exams (class, section, subject, title, question_file)
                         VALUES ('$class','$section','$subject','$title','$path')");

    header("Location: subject_page.php?class=$class&section=$section&subject=$subject");
}


/* ================= DELETE EXAM ================= */
if(isset($_GET['delete_exam'])){
    $id = $_GET['delete_exam'];

    // delete question file
    $q = mysqli_fetch_assoc(mysqli_query($conn, "SELECT question_file FROM exams WHERE id='$id'"));
    if($q) @unlink($q['question_file']);

    // delete answer files
    $ans = mysqli_query($conn, "SELECT answer_file FROM submissions WHERE exam_id='$id'");
    while($a = mysqli_fetch_assoc($ans)){
        @unlink($a['answer_file']);
    }

    mysqli_query($conn, "DELETE FROM submissions WHERE exam_id='$id'");
    mysqli_query($conn, "DELETE FROM exams WHERE id='$id'");

    header("Location: subject_page.php?class=$class&section=$section&subject=$subject");
}


/* ================= EDIT TITLE ================= */
if(isset($_POST['edit_title'])){
    $id = $_POST['exam_id'];
    $new = $_POST['new_title'];

    mysqli_query($conn, "UPDATE exams SET title='$new' WHERE id='$id'");

    header("Location: subject_page.php?class=$class&section=$section&subject=$subject");
}


/* ================= ANSWER UPLOAD ================= */
if(isset($_POST['submit_answer'])){
    $exam_id = $_POST['exam_id'];
    $student_name = $_POST['student_name'];

    $file = $_FILES['answer']['name'];
    $tmp = $_FILES['answer']['tmp_name'];

    $path = "uploads/answers/" . time() . "_" . $file;

    move_uploaded_file($tmp, $path);

    mysqli_query($conn, "INSERT INTO submissions (exam_id, student_name, answer_file)
                         VALUES ('$exam_id','$student_name','$path')");

    header("Location: subject_page.php?class=$class&section=$section&subject=$subject");
}


/* ================= DELETE SUBMISSION ================= */
if(isset($_GET['delete_submission'])){
    $id = $_GET['delete_submission'];

    $file = mysqli_fetch_assoc(mysqli_query($conn, "SELECT answer_file FROM submissions WHERE id='$id'"));
    if($file) @unlink($file['answer_file']);

    mysqli_query($conn, "DELETE FROM submissions WHERE id='$id'");

    header("Location: subject_page.php?class=$class&section=$section&subject=$subject");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $subject; ?> | EduTrack Pro</title>

    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include "navbar.php"; ?>

<div class="container mt-5">

    <h2 class="text-center">📘 <?php echo $subject; ?></h2>
    <h5 class="text-center mb-4">Class <?php echo $class; ?> - <?php echo $section; ?></h5>

    <!-- EXAM UPLOAD -->
    <form method="POST" enctype="multipart/form-data" class="mb-4">
        <input type="text" name="title" class="form-control mb-2" placeholder="Exam Title" required>
        <input type="file" name="question" class="form-control mb-2" required>
        <button name="upload_exam" class="btn btn-success w-100">Upload Exam</button>
    </form>

    <hr>

<?php
$exams = mysqli_query($conn, "SELECT * FROM exams 
                             WHERE class='$class' AND section='$section' AND subject='$subject'
                             ORDER BY id DESC");

while($exam = mysqli_fetch_assoc($exams)){

    $count = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM submissions WHERE exam_id='{$exam['id']}'"));
?>

<div class="card p-3 mb-4">

    <!-- TITLE + BADGE -->
    <h5>
        <?php echo $exam['title']; ?>
        <span class="badge bg-dark"><?php echo $count; ?> submissions</span>
    </h5>

    <!-- ACTIONS -->
    <div class="mb-2">
        <a href="<?php echo $exam['question_file']; ?>" target="_blank" class="btn btn-primary btn-sm">View</a>

        <a href="?class=<?php echo $class ?>&section=<?php echo $section ?>&subject=<?php echo $subject ?>&delete_exam=<?php echo $exam['id']; ?>"
           class="btn btn-danger btn-sm"
           onclick="return confirm('Delete exam + all submissions?')">
           Delete
        </a>
    </div>

    <!-- EDIT TITLE -->
    <form method="POST" class="mb-2 d-flex gap-2">
        <input type="hidden" name="exam_id" value="<?php echo $exam['id']; ?>">
        <input type="text" name="new_title" class="form-control" placeholder="Edit title">
        <button name="edit_title" class="btn btn-warning btn-sm">Update</button>
    </form>

    <!-- ANSWER UPLOAD -->
    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="exam_id" value="<?php echo $exam['id']; ?>">
        <input type="text" name="student_name" class="form-control mb-2" placeholder="Student Name" required>
        <input type="file" name="answer" class="form-control mb-2" required>
        <button name="submit_answer" class="btn btn-success w-100">Submit Answer</button>
    </form>

    <hr>

    <!-- SUBMISSIONS -->
    <h6>Submissions:</h6>

<?php
$subs = mysqli_query($conn, "SELECT * FROM submissions WHERE exam_id='{$exam['id']}'");

if(mysqli_num_rows($subs)==0){
    echo "<p>No submissions</p>";
} else {
    while($s = mysqli_fetch_assoc($subs)){
?>
    <div class="d-flex justify-content-between align-items-center mb-1">
        <span><?php echo $s['student_name']; ?></span>

        <div>
            <a href="<?php echo $s['answer_file']; ?>" target="_blank" class="btn btn-primary btn-sm">View</a>

            <a href="?class=<?php echo $class ?>&section=<?php echo $section ?>&subject=<?php echo $subject ?>&delete_submission=<?php echo $s['id']; ?>"
               class="btn btn-danger btn-sm"
               onclick="return confirm('Delete submission?')">
               Delete
            </a>
        </div>
    </div>
<?php }} ?>

</div>

<?php } ?>

</div>

<?php include "footer.php"; ?>

</body>
</html>