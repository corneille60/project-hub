<?php
session_start();

include_once 'config.php';
include_once 'classes/Project.php';

if (!isset($_SESSION['stdRegNumber'])) {
    header("Location: login.php");
    exit();
}

$database = new Database();
$db = $database->getConnection();

$project = new Project($db);

$success_message = "";
$error_message = "";

if ($_POST) {
    $project->ProjectName = $_POST['ProjectName'];
    $project->ProjectProblems = $_POST['ProjectProblems'];
    $project->ProjectSolutions = $_POST['ProjectSolutions'];
    $project->ProjectAbstract = $_POST['ProjectAbstract'];
    $project->ProjectDissertation = $_POST['ProjectDissertation'];
    $project->ProjectSourceCodes = $_POST['ProjectSourceCodes'];
    $project->DepartmentCode = $_POST['DepartmentCode'];

    if ($project->submitProposal()) {
        $success_message = "Project proposal submitted.";
    } else {
        $error_message = "Unable to submit project proposal.";
    }
}

// Fetch departments
$departments = [];
$query = "SELECT DepartmentCode, DepartmentName FROM department";
$stmt = $db->prepare($query);
$stmt->execute();
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $departments[] = $row;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Submit Proposal</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .container {
            margin-top: 50px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Submit Project Proposal</div>
                    <div class="card-body">
                        <?php
                        if (!empty($success_message)) {
                            echo "<div class='alert alert-success'>$success_message</div>";
                        }
                        if (!empty($error_message)) {
                            echo "<div class='alert alert-danger'>$error_message</div>";
                        }
                        ?>
                        <form action="submit_proposal.php" method="post">
                            <div class="form-group">
                                <label for="ProjectName">Project Name:</label>
                                <input type="text" class="form-control" id="ProjectName" name="ProjectName" required>
                            </div>
                            <div class="form-group">
                                <label for="ProjectProblems">Project Problems:</label>
                                <input type="text" class="form-control" id="ProjectProblems" name="ProjectProblems" required>
                            </div>
                            <div class="form-group">
                                <label for="ProjectSolutions">Project Solutions:</label>
                                <input type="text" class="form-control" id="ProjectSolutions" name="ProjectSolutions" required>
                            </div>
                            <div class="form-group">
                                <label for="ProjectAbstract">Project Abstract:</label>
                                <input type="text" class="form-control" id="ProjectAbstract" name="ProjectAbstract" required>
                            </div>
                            <div class="form-group">
                                <label for="ProjectDissertation">Project Dissertation:</label>
                                <input type="text" class="form-control" id="ProjectDissertation" name="ProjectDissertation" required>
                            </div>
                            <div class="form-group">
                                <label for="ProjectSourceCodes">Project Source Codes:</label>
                                <input type="text" class="form-control" id="ProjectSourceCodes" name="ProjectSourceCodes" required>
                            </div>
                            <div class="form-group">
                                <label for="DepartmentCode">Department Code:</label>
                                <select class="form-control" id="DepartmentCode" name="DepartmentCode" required>
                                    <option value="">Select Department</option>
                                    <?php foreach ($departments as $department): ?>
                                        <option value="<?php echo $department['DepartmentCode']; ?>">
                                            <?php echo $department['DepartmentCode']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit Proposal</button>
                        </form>
                    </div>
                </div>
                <br>
                <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
