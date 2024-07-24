<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'department') {
    header('Location: department_login.php');
    exit;
}

include_once '../config/Database.php';
include_once 'models/Project.php';
include_once 'models/Student.php';
include_once 'models/Supervisor.php';

$database = new Database();
$db = $database->connect();

$project = new Project($db);
$student = new Student($db);
$supervisor = new Supervisor($db);

// Fetch submitted projects
$submitted_projects = $project->getSubmittedProjects();

// Fetch all supervisors
$supervisors = $supervisor->getAllSupervisors();

// Assign project to supervisor
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['assign_project'])) {
    $project_code = $_POST['ProjectCode'];
    $supervisor_email = $_POST['SupervisorEmail'];

    if ($project->assignToSupervisor($project_code, $supervisor_email)) {
        $message = "Project assigned successfully!";
    } else {
        $message = "Failed to assign project.";
    }
}

// Insert student
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['insert_student'])) {
    $student->StdRegNumber = $_POST['StdRegNumber'];
    $student->StdFname = $_POST['StdFname'];
    $student->StdLname = $_POST['StdLname'];
    $student->StdGender = $_POST['StdGender'];
    $student->StdEmail = $_POST['StdEmail'];
    $student->StdPhoneNumber = $_POST['StdPhoneNumber'];

    if ($student->create()) {
        $message = "Student inserted successfully!";
    } else {
        $message = "Failed to insert student.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Department Dashboard</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <div class="container">
        <h2>Department Dashboard</h2>
        <?php if (isset($message)) echo "<p>$message</p>"; ?>

        <h3>Submitted Projects</h3>
        <?php if ($submitted_projects): ?>
            <ul>
                <?php foreach ($submitted_projects as $project_item): ?>
                    <li>
                        <strong><?php echo $project_item['ProjectName']; ?></strong> - <?php echo $project_item['Status']; ?>
                        <form method="POST" action="department_dashboard.php">
                            <input type="hidden" name="ProjectCode" value="<?php echo $project_item['ProjectCode']; ?>">
                            <label for="SupervisorEmail">Assign to Supervisor:</label>
                            <select name="SupervisorEmail" required>
                                <option value="" disabled selected>Select Supervisor</option>
                                <?php foreach ($supervisors as $supervisor): ?>
                                    <option value="<?php echo $supervisor['SupervisorEmail']; ?>"><?php echo $supervisor['SupervisorFname'] . ' ' . $supervisor['SupervisorLname']; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <button type="submit" name="assign_project">Assign</button>
                        </form>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No projects submitted.</p>
        <?php endif; ?>

        <h3>Insert Student</h3>
        <form method="POST" action="department_dashboard.php">
            <label for="StdRegNumber">Registration Number:</label>
            <input type="text" id="StdRegNumber" name="StdRegNumber" required>
            <label for="StdFname">First Name:</label>
            <input type="text" id="StdFname" name="StdFname" required>
            <label for="StdLname">Last Name:</label>
            <input type="text" id="StdLname" name="StdLname" required>
            <label for="StdGender">Gender:</label>
            <input type="text" id="StdGender" name="StdGender" required>
            <label for="StdEmail">Email:</label>
            <input type="email" id="StdEmail" name="StdEmail" required>
            <label for="StdPhoneNumber">Phone Number:</label>
            <input type="number" id="StdPhoneNumber" name="StdPhoneNumber" required>
            <button type="submit" name="insert_student">Insert Student</button>
        </form>

        <p><a href="../logout.php">Logout</a></p>
    </div>
</body>
</html>
