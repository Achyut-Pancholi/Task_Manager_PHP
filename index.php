<?php 
session_start(); // Start session to track the user
require 'db_connect.php'; 

// 1. AUTH CHECK: Redirect to login if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id']; // Store current user ID

// 2. ADD TASK (Linked to user_id)
if (isset($_POST['add_task'])) {
    $task = $_POST['task_name'];
    if (!empty($task)) {
        $task = mysqli_real_escape_string($conn, $task);
        // Include user_id in the insert query
        mysqli_query($conn, "INSERT INTO tasks (task_name, user_id) VALUES ('$task', '$user_id')");
        header("Location: index.php");
        exit();
    }
}

// 3. DELETE TASK (Filter by user_id for security)
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    mysqli_query($conn, "DELETE FROM tasks WHERE id=$id AND user_id=$user_id");
    header("Location: index.php");
    exit();
}

// 4. MARK AS COMPLETED (Filter by user_id)
if (isset($_GET['complete'])) {
    $id = (int)$_GET['complete'];
    mysqli_query($conn, "UPDATE tasks SET status='Completed' WHERE id=$id AND user_id=$user_id");
    header("Location: index.php");
    exit();
}

// 5. FETCH TASKS: Only for the logged-in user
$result = mysqli_query($conn, "SELECT * FROM tasks WHERE user_id=$user_id ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Manager | Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container shadow-lg">
        <header style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></h2>
                <p class="subtitle">Your personal task dashboard</p>
            </div>
            <a href="logout.php" style="color: #e74c3c; text-decoration: none; font-weight: bold;">Logout</a>
        </header>
        
        <form method="POST" action="index.php" class="task-form">
            <input type="text" name="task_name" placeholder="What needs to be done? (max 30 chars)..." maxlength="30" required>
            <button type="submit" name="add_task">Add Task</button>
        </form>

        <table class="styled-table">
            <thead>
                <tr>
                    <th>Task</th>
                    <th>Created</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($result)) { 
                    $timestamp = date("d M, h:i A", strtotime($row['created_at']));
                    $status_class = strtolower($row['status']);
                ?>
                <tr>
                    <td class="task-text"><?php echo htmlspecialchars($row['task_name']); ?></td>
                    <td class="time-text"><?php echo $timestamp; ?></td>
                    <td><span class="status-badge <?php echo $status_class; ?>"><?php echo $row['status']; ?></span></td>
                    <td class="actions">
                        <?php if($row['status'] == 'Pending'): ?>
                            <a href="index.php?complete=<?php echo $row['id']; ?>" class="link-complete">Complete</a>
                        <?php endif; ?>
                        <a href="index.php?delete=<?php echo $row['id']; ?>" class="link-delete" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
                <?php } ?>
                <?php if(mysqli_num_rows($result) == 0): ?>
                    <tr><td colspan="4" style="text-align:center;">No tasks found. Start by adding one!</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
                            
        <div class="stats-box">
            <div class="stat-item total">
                <span class="label">Total</span>
                <span class="count"><?php echo mysqli_num_rows($result); ?></span>
            </div>
            
            <?php 
                // Updated stats queries to count only the current user's tasks
                $pending_count = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM tasks WHERE status='Pending' AND user_id=$user_id"));
                $completed_count = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM tasks WHERE status='Completed' AND user_id=$user_id"));
            ?>

            <div class="stat-item pending">
                <span class="label">Pending</span>
                <span class="count"><?php echo $pending_count; ?></span>
            </div>
            <div class="stat-item completed">
                <span class="label">Completed</span>
                <span class="count"><?php echo $completed_count; ?></span>
            </div>
        </div>
    </div>
</body>
</html>