<?php
session_start();
include 'db_connect.php';

if (isset($_POST['login'])) {
    $user = mysqli_real_escape_string($conn, $_POST['username']);
    $pass = $_POST['password'];

    $res = mysqli_query($conn, "SELECT * FROM users WHERE username='$user'");
    $row = mysqli_fetch_assoc($res);

    if ($row && password_verify($pass, $row['password'])) { // Verify hashed password
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['username'] = $row['username'];
        header("Location: index.php");
        exit();
    } else {
        $error = "Invalid username or password!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | Task Manager</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container shadow-lg">
    <header>
        <h2>User Login</h2>
        <p class="subtitle">Access your personal dashboard</p>
    </header>
    <form method="POST" class="task-form" style="flex-direction: column;">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="login" style="width: 100%;">Login</button>
    </form>
    <p style="text-align: center; margin-top: 15px;">
        Need an account? <a href="register.php" class="link-complete">Register here</a>
    </p>
    <?php if(isset($error)) echo "<p style='color:red; text-align:center;'>$error</p>"; ?>
</div>
</body>
</html>