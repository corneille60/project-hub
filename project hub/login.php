<?php
session_start();
include_once 'config/Database.php';
include_once 'models/User.php';

$database = new Database();
$db = $database->connect();

$user = new User($db);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user->reg_number = $_POST['reg_number'];
    $user->password = $_POST['password'];

    if ($user->login()) {
        $_SESSION['user_id'] = $user->id;
        $_SESSION['reg_number'] = $user->reg_number;
        $_SESSION['role'] = $user->role;
        header('Location: dashboard.php');
        exit; // Ensure that script stops after redirection
    } else {
        $loginError = 'Login failed. Please check your credentials.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Basic styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            width: 100%;
            max-width: 400px;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .form-container {
            text-align: center;
        }
        .form-container form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .form-container h2 {
            margin-bottom: 20px;
        }
        .form-container label, .form-container input {
            margin-bottom: 10px;
            width: 100%;
            max-width: 300px;
        }
        .form-container button {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        .form-container button:hover {
            background-color: #0056b3;
        }
        .form-container p {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <form method="POST" action="login.php">
                <h2>Login</h2>
                <?php if(isset($loginError)): ?>
                    <p style="color: red;"><?php echo $loginError; ?></p>
                <?php endif; ?>
                <label for="loginRegNumber">Registration Number:</label>
                <input type="text" id="loginRegNumber" name="reg_number" required>
                <label for="loginPassword">Password:</label>
                <input type="password" id="loginPassword" name="password" required>
                <button type="submit">Login</button>
                <p>Don't have an account? <a href="register.php">Register here</a></p>
            </form>
        </div>
    </div>
</body>
</html>
