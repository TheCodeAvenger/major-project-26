<?php
session_start();
include "db.php";

if (isset($_POST['login'])) {

    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM admin WHERE username='$username' AND password='$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $_SESSION['admin'] = $username;
        header("Location: dashboard.php");
    } else {
        echo "<script>alert('Invalid Login');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>

    <link rel="stylesheet" href="style.css">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body {
            background: url('https://images.unsplash.com/photo-1523240795612-9a054b0db644') no-repeat center center/cover;
            height: 100vh;
        }

        .login-box {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(12px);
            border-radius: 15px;
            padding: 30px;
            color: white;
        }

        input {
            border-radius: 10px !important;
        }

        .btn {
            border-radius: 10px;
            font-weight: bold;
        }
    </style>
</head>
<body class="d-flex justify-content-center align-items-center">

<div class="login-box col-md-4 text-center">

<h2 class="text-center mb-2 small-text">
    🎓 Welcome Back To
</h2>

<h1 class="text-center brand-text mb-3">
    EduTrack Pro
</h1>

<p class="text-center">Manage your students smartly 🚀</p>

    <form method="POST">
        <input type="text" name="username" class="form-control mb-3" placeholder="👤 Username" required>
        <input type="password" name="password" class="form-control mb-3" placeholder="🔑 Password" required>

        <button name="login" class="btn btn-primary w-100">
            <i class="fas fa-sign-in-alt"></i> Login
        </button>
    </form>

</div>

</body>
</html>