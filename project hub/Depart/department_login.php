<?php
session_start();
if (isset($_SESSION['user_id']) && $_SESSION['role'] == 'department') {
    header('Location: department_dashboard.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include_once '../config/Database.php';
    include_once 'models/User.php';

    $database = new Database();
    $db = $database->connect();

    $user = new User($db);
    $user->username = $_POST['username'];
    $user->password = $_POST['password'];

    if ($user->login('department')) {
        $_SESSION['user_id'] = $user->id;
        $_SESSION['role'] = 'department';
        header('Location: department_dashboard.php');
    } else {
        $error = "Invalid credentials!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Department Login</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <div class="container">
        <h2>Department Login</h2>
        <?php if (isset($error)) echo "<p>$error</p>"; ?>
        <form method="POST" action="department_login.php">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
