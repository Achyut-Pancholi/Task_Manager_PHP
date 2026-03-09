<?php 
include 'db_connect.php'; 

// 1. ADD TASK
if (isset($_POST['add_task'])) {
    $task = $_POST['task_name'];
    if (!empty($task)) {
        // Professional tip: use mysqli_real_escape_string to prevent SQL errors with apostrophes
        $task = mysqli_real_escape_string($conn, $task);
        mysqli_query($conn, "INSERT INTO tasks (task_name) VALUES ('$task')");
        header("Location: index.php"); // Prevents re-submission on refresh
        exit();
    }
}

// 2. DELETE TASK
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    mysqli_query($conn, "DELETE FROM tasks WHERE id=$id");
    header("Location: index.php");
    exit();
}

// 3. MARK AS COMPLETED
if (isset($_GET['complete'])) {
    $id = (int)$_GET['complete'];
    mysqli_query($conn, "UPDATE tasks SET status='Completed' WHERE id=$id");
    header("Location: index.php");
    exit();
}

$result = mysqli_query($conn, "SELECT * FROM tasks ORDER BY created_at DESC");
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
        <header>
            <h2>Task Management System</h2>
            <p class="subtitle">Efficiently track your daily goals</p>
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
                    // Formatting the timestamp for a professional look
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
                $pending_count = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM tasks WHERE status='Pending'"));
                $completed_count = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM tasks WHERE status='Completed'"));
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