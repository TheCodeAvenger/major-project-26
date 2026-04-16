<?php
include "db.php";

// Single record delete
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    mysqli_query($conn, "DELETE FROM attendance WHERE id=$id");
}

// Date delete
if (isset($_GET['date'])) {
    $date = $_GET['date'];
    mysqli_query($conn, "DELETE FROM attendance WHERE date='$date'");
}

// Month delete
if (isset($_GET['month'])) {
    $month = $_GET['month'];
    mysqli_query($conn, 
        "DELETE FROM attendance 
         WHERE DATE_FORMAT(date, '%Y-%m') = '$month'");
}

// Redirect back
header("Location: view_attendance.php");
?>