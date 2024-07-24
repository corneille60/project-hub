<?php
session_start();
if (!isset($_SESSION['reg_number'])) {
    header('Location: login.php');
    exit;
}

$role = $_SESSION['role'];

echo "Welcome to the dashboard! Your role is: $role";

if ($role == 'student') {
    echo '<a href="student_dashboard.php">Student Dashboard</a>';
} elseif ($role == 'supervisor') {
    echo '<a href="supervisor_dashboard.php">Supervisor Dashboard</a>';
} elseif ($role == 'department') {
    echo '<a href="department_dashboard.php">Department Dashboard</a>';
}
?>
