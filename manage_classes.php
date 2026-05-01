<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Classes & Sections</title>

    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include "navbar.php"; ?>

<div class="container mt-5">

    <h2 class="text-center mb-4">Classes & Sections</h2>

    <div class="row text-center">

        <!-- CLASSES -->
        <div class="col-md-6">
            <div class="card p-4">
                <h4>Classes</h4>
                <h5>11</h5>
                <h5>12</h5>
            </div>
        </div>

        <!-- SECTIONS -->
        <div class="col-md-6">
            <div class="card p-4">
                <h4>Sections</h4>
                <h5>PCM</h5>
                <h5>PCB</h5>
            </div>
        </div>

    </div>

</div>

<?php include "footer.php"; ?>
</body>
</html>