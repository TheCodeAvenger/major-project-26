<?php
session_start();
include "db.php";

if (isset($_POST['login'])) {

    $email = $_POST['email'];
    $password = $_POST['password'];

    // 🔥 USERS TABLE LOGIN
    $query = "SELECT * FROM users WHERE email='$email' AND password='$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {

        $user = mysqli_fetch_assoc($result);

        // 💀 SESSION STORE
        $_SESSION['user'] = $user;

        // 💀 ROLE BASED REDIRECT
        if ($user['role'] == 'admin') {
            header("Location: dashboard.php");
        } elseif ($user['role'] == 'teacher') {
            header("Location: teacher_dashboard.php");
        } else {
            header("Location: student_dashboard.php");
        }

    } else {
        echo "<script>alert('Invalid Login ❌');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login | EduTrack Pro</title>

    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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

        <!-- 🔥 EMAIL INPUT -->
        <input type="email" name="email" class="form-control mb-3" placeholder="📧 Email" required>

        <!-- 🔑 PASSWORD -->
        <input type="password" name="password" class="form-control mb-3" placeholder="🔑 Password" required>

        <button name="login" class="btn btn-primary w-100">
            <i class="fas fa-sign-in-alt"></i> Login
        </button>

    </form>

</div>

</body>
</html>