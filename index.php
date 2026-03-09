<?php 
include 'db_connect.php'; 

// 1. ADD TASK
if (isset($_POST['add_task'])) {
    $task = $_POST['task_name'];
    if (!empty($task)) {
        mysqli_query($conn, "INSERT INTO tasks (task_name) VALUES ('$task')");
    }
}

// 2. DELETE TASK
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM tasks WHERE id=$id");
    header("Location: index.php");
}

// 3. MARK AS COMPLETED
if (isset($_GET['complete'])) {
    $id = $_GET['complete'];
    mysqli_query($conn, "UPDATE tasks SET status='Completed' WHERE id=$id");
    header("Location: index.php");
}

$result = mysqli_query($conn, "SELECT * FROM tasks ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Task Manager</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Task Management System</h2>
        
        <form method="POST" action="index.php">
            <input type="text" name="task_name" placeholder="Enter task (max 30 chars)..." maxlength="30" required>
            <button type="submit" name="add_task">Add Task</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>Task</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo $row['task_name']; ?></td>
                    <td><?php echo $row['status']; ?></td>
                    <td>
                        <?php if($row['status'] == 'Pending'): ?>
                            <a href="index.php?complete=<?php echo $row['id']; ?>" class="btn-complete">Complete</a>
                        <?php endif; ?>
                        <a href="index.php?delete=<?php echo $row['id']; ?>" class="btn-delete">Delete</a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
                            
        <div class="stats-box">
            <span class="btn-stat total">Total: <?php echo mysqli_num_rows($result); ?></span>
            
            <?php 
                $pending_count = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM tasks WHERE status='Pending'"));
                $completed_count = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM tasks WHERE status='Completed'"));
            ?>

            <span class="btn-stat pending">Pending: <?php echo $pending_count; ?></span>
            <span class="btn-stat completed">Completed: <?php echo $completed_count; ?></span>
        </div>
    </div>
</body>
</html>