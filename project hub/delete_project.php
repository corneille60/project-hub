<?php
session_start();
if (!isset($_SESSION['reg_number']) || $_SESSION['role'] != 'student') {
    header('Location: login.php');
    exit;
}

include_once 'config/Database.php';
include_once 'models/Project.php';

$database = new Database();
$db = $database->connect();

$project = new Project($db);

if (isset($_GET['id'])) {
    $project_id = $_GET['id'];

    if ($project->deleteProject($project_id)) {
        echo "Project deleted successfully.";
        header("Location:student_dashboard.php");
    } else {
        echo "Failed to delete project.";
    }
}
?>
