<?php
include 'db_connect.php';
if (isset($_POST['register'])) {
    $user = mysqli_real_escape_string($conn, $_POST['username']);
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT); // Secure hashing
    
    $sql = "INSERT INTO users (username, password) VALUES ('$user', '$pass')";
    if (mysqli_query($conn, $sql)) {
        header("Location: login.php?msg=Registered successfully");
    } else {
        $error = "Username already exists!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register | Task Manager</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container shadow-lg">
    <header>
        <h2>Create Account</h2>
        <p class="subtitle">Join the system to manage your tasks</p>
    </header>
    <form method="POST" class="task-form" style="flex-direction: column;">
        <input type="text" name="username" placeholder="Choose a Username" required>
        <input type="password" name="password" placeholder="Choose a Password" required>
        <button type="submit" name="register" style="width: 100%;">Register</button>
    </form>
    <p style="text-align: center; margin-top: 15px;">
        Already have an account? <a href="login.php" class="link-complete">Login here</a>
    </p>
    <?php if(isset($error)) echo "<p style='color:red; text-align:center;'>$error</p>"; ?>
</div>
</body>
</html>