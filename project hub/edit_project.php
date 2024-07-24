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
    $project_data = $project->getProjectById($project_id);

    if (!$project_data) {
        echo "Project not found.";
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $project->ProjectCode = $_POST['ProjectCode'];
    $project->ProjectName = $_POST['ProjectName'];
    $project->ProjectProblems = $_POST['ProjectProblems'];
    $project->ProjectSolutions = $_POST['ProjectSolutions'];
    $project->ProjectAbstract = $_POST['ProjectAbstract'];

    if ($project->updateProject($project->ProjectCode)) {
        echo "Project updated successfully.";
        header("Location:student_dashboard.php");
    } else {
        echo "Failed to update project.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Project</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Edit Project</h2>
        <form method="POST" action="edit_project.php">
            <input type="hidden" name="ProjectCode" value="<?php echo $project_data['ProjectCode']; ?>">
            <label for="ProjectName">Project Title:</label>
            <input type="text" id="ProjectName" name="ProjectName" value="<?php echo $project_data['ProjectName']; ?>" required>
            <label for="ProjectProblems">Project Problems:</label>
            <textarea id="ProjectProblems" name="ProjectProblems" required><?php echo $project_data['ProjectProblems']; ?></textarea>
            <label for="ProjectSolutions">Project Solutions:</label>
            <textarea id="ProjectSolutions" name="ProjectSolutions" required><?php echo $project_data['ProjectSolutions']; ?></textarea>
            <label for="ProjectAbstract">Project Abstract:</label>
            <textarea id="ProjectAbstract" name="ProjectAbstract" required><?php echo $project_data['ProjectAbstract']; ?></textarea>
            <button type="submit">Update</button>
        </form>
    </div>
</body>
</html>
