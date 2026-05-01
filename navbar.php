<?php $page = basename($_SERVER['PHP_SELF']); ?>

<style>
.navbar {
    position: sticky;
    top: 0;
    z-index: 1000;
}

.nav-link {
    transition: 0.3s;
    font-weight: 500;
}

.nav-link:hover {
    color: #ffc107 !important;
    transform: translateY(-2px);
    text-shadow: 0 0 5px rgba(255,193,7,0.6);
}

.active-link {
    color: #ffc107 !important;
    font-weight: bold;
}

.navbar-brand {
    letter-spacing: 1px;
    transition: 0.3s;
}

.navbar-brand:hover {
    color: #ffc107 !important;
}

.btn-danger {
    border-radius: 8px;
    padding: 5px 12px;
}
</style>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow">
  <div class="container-fluid">

    <a class="navbar-brand fw-bold" href="dashboard.php">
      🎓 EduTrack Pro
    </a>

    <div class="collapse navbar-collapse">
      <ul class="navbar-nav me-auto">

        <li class="nav-item">
          <a class="nav-link <?php if($page=='dashboard.php') echo 'active-link'; ?>" href="dashboard.php">
            📊 Dashboard
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link <?php if($page=='view_students.php') echo 'active-link'; ?>" href="view_students.php">
            👥 Students
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link <?php if($page=='add_student.php') echo 'active-link'; ?>" href="add_student.php">
            ➕ Add Student
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link <?php if($page=='attendance.php') echo 'active-link'; ?>" href="attendance.php">
            📝 Attendance
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link <?php if($page=='view_attendance.php') echo 'active-link'; ?>" href="view_attendance.php">
            📋 Records
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link <?php if($page=='timetable.php') echo 'active-link'; ?>" href="timetable.php">
            📅 Timetable
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link <?php if($page=='view_timetable.php') echo 'active-link'; ?>" href="view_timetable.php">
            ⏰ View Timetable
          </a>
        </li>

        <!-- 💀 SUBJECTS EXPLORER -->
        <li class="nav-item">
          <a class="nav-link <?php if($page=='subjects_view.php') echo 'active-link'; ?>" href="subjects_view.php">
            📚 Subjects
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link <?php if($page=='analytics.php') echo 'active-link'; ?>" href="analytics.php">
            📊 Analytics
          </a>
        </li>

      </ul>

      <a href="logout.php" class="btn btn-danger btn-sm">
        🔴 Logout
      </a>

    </div>
  </div>
</nav>