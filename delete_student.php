<?php
include "db.php";

// Check if ID exists
if (isset($_GET['id'])) {

    $id = $_GET['id'];

    // Delete query
    $query = "DELETE FROM students WHERE id=$id";

    if (mysqli_query($conn, $query)) {
        header("Location: view_students.php");
        exit();
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }

} else {
    echo "Invalid Request";
}
?>