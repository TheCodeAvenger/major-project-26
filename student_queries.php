<?php
session_start();
include "db.php";

if ($_SESSION['user']['role'] != 'student') {
    header("Location: login.php");
}

if(isset($_POST['submit'])){
    $name = $_SESSION['user']['name'];
    $msg = $_POST['message'];

    mysqli_query($conn, "INSERT INTO queries (student_name, message) VALUES ('$name','$msg')");
    echo "<script>alert('Query submitted');</script>";
}
?>

<form method="POST">
    <textarea name="message" placeholder="Write your query..." required></textarea>
    <button name="submit">Submit</button>
</form>