<?php
session_start();
if (!isset($_SESSION['reg_number']) || $_SESSION['role'] != 'student') {
    header('Location: login.php');
    exit;
}

include_once 'config/Database.php';
include_once 'models/Project.php';
include_once 'models/Student.php';

$database = new Database();
$db = $database->connect();

$project = new Project($db);
$student = new Student($db);
$reg_number = $_SESSION['reg_number'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_project'])) {
    $project->ProjectName = $_POST['ProjectName'];
    $project->ProjectProblems = $_POST['ProjectProblems'];
    $project->ProjectSolutions = $_POST['ProjectSolutions'];
    $project->ProjectAbstract = $_POST['ProjectAbstract'];
    $project->DepartmentCode = $_POST['DepartmentCode'];
    $project->ProjectDissertation = ''; // assuming empty initially
    $project->ProjectSourceCodes = ''; // assuming empty initially

    if ($project->submit($reg_number)) {
        $message = "Project submitted successfully!";
    } else {
        $message = "Failed to submit project.";
    }
}

$projects = $project->getProjectsByStudent($reg_number);
$completed_projects = $project->getCompletedProjects();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 900px;
            margin: 50px auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2, h3, h4 {
            color: #333;
        }

        p, ul, form {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"], textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            display: inline-block;
            padding: 10px 15px;
            background: #007BFF;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background 0.3s;
        }

        button:hover {
            background: #0056b3;
        }

        .project-list li {
            margin-bottom: 10px;
            list-style-type: none;
        }

        .project-list li a {
            margin-left: 10px;
            color: #007BFF;
            text-decoration: none;
        }

        .project-list li a:hover {
            text-decoration: underline;
        }

        .message {
            padding: 10px;
            margin-bottom: 20px;
            background: #e7f5ff;
            border-left: 5px solid #007BFF;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Student Dashboard</h2>
        <?php if (isset($message)) echo "<p class='message'>$message</p>"; ?>

        <h3>Submit Project Proposal</h3>
        <form method="POST" action="student_dashboard.php">
            <label for="ProjectName">Project Title:</label>
            <input type="text" id="ProjectName" name="ProjectName" required>
            <label for="ProjectProblems">Project Problems:</label>
            <textarea id="ProjectProblems" name="ProjectProblems" required></textarea>
            <label for="ProjectSolutions">Project Solutions:</label>
            <textarea id="ProjectSolutions" name="ProjectSolutions" required></textarea>
            <label for="ProjectAbstract">Project Abstract:</label>
            <textarea id="ProjectAbstract" name="ProjectAbstract" required></textarea>
            <label for="DepartmentCode">Department Code:</label>
            <input type="text" id="DepartmentCode" name="DepartmentCode" required>
            <button type="submit" name="submit_project">Submit Project</button>
        </form>

        <h3>Your Projects</h3>
        <?php if ($projects): ?>
            <ul class="project-list">
                <?php foreach ($projects as $project): ?>
                    <li>
                        <strong><?php echo $project['ProjectName']; ?></strong> - <?php echo $project['Status']; ?>
                        <a href="edit_project.php?id=<?php echo $project['ProjectCode']; ?>">Edit</a>
                        <a href="delete_project.php?id=<?php echo $project['ProjectCode']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>You have not submitted any projects.</p>
        <?php endif; ?>

        <h3>Search Completed Projects</h3>
        <form method="GET" action="student_dashboard.php">
            <label for="search">Search by Title:</label>
            <input type="text" id="search" name="search" required>
            <button type="submit">Search</button>
        </form>

        <?php if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['search'])): ?>
            <?php
            $search = $_GET['search'];
            $search_results = $project->searchCompletedProjects($search);
            ?>
            <h4>Search Results</h4>
            <?php if ($search_results): ?>
                <ul class="project-list">
                    <?php foreach ($search_results as $result): ?>
                        <li><?php echo $result['ProjectName']; ?> - <?php echo $result['status']; ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>No projects found.</p>
            <?php endif; ?>
        <?php endif; ?>

        <h3>Completed Projects</h3>
        <ul class="project-list">
            <?php foreach ($completed_projects as $project): ?>
                <li><?php echo $project['ProjectName']; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>
</html>
