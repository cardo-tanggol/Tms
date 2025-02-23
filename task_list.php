<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit();
}

require 'db.php';

// Fetch tasks from database
$sql = "SELECT * FROM tasks ORDER BY created_at DESC";
$result = $conn->query($sql);

// Error handling
if (!$result) {
    die("Database Query Failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background: linear-gradient(to right, #6a11cb, #2575fc);
            min-height: 100vh;
        }
        .navbar {
            background-color: #4b0082 !important;
            padding: 15px;
        }
        .navbar-brand {
            color: #ffffff !important;
            font-weight: bold;
        }
        .navbar-toggler {
            background-color: #fff;
            padding: 8px 12px;
            border-radius: 5px;
        }
        .navbar-toggler i {
            color: #4b0082;
            font-size: 1.2rem;
        }
        .nav-item-link {
            color: #ffffff !important;
            transition: all 0.3s ease-in-out;
        }
        .nav-item-link:hover {
            color: #ffcc00 !important;
            transform: scale(1.1);
        }
        .nav-item .btn-danger {
            transition: all 0.3s ease-in-out;
        }
        .nav-item .btn-danger:hover {
            background-color: #dc3545;
            transform: scale(1.05);
        }
        .navbar-nav .nav-item .active {
            color: #ffcc00 !important;
            font-weight: bold;
            border-bottom: 2px solid #ffcc00;
        }
        .container {
            margin-top: 50px;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            max-width: 90%;
        }
        h2 {
            color: #4b0082;
        }
        .btn-primary {
            background-color: #4b0082;
            border-color: #4b0082;
        }
        .table th {
            background-color: #4b0082;
            color: white;
        }
        .table tbody tr:hover {
            background-color: #f5f5f5;
        }
        .btn-edit {
            background-color: #ffc107;
            color: white;
        }
        .btn-edit:hover {
            background-color: #e0a800;
        }
        .btn-delete {
            background-color: #dc3545;
            color: white;
        }
        .btn-delete:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="dashboard.php">
                <i class="fas fa-home"></i> Dashboard
            </a>
            <button class="navbar-toggler border-0 shadow-sm" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <i class="fas fa-bars"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link nav-item-link" href="dashboard.php">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-item-link" href="add_task.php">
                            <i class="fas fa-plus"></i> Add Task
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-item-link active" href="task_list.php">
                            <i class="fas fa-list"></i> Task List
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-danger px-3 py-1 ms-2" href="logout.php">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Task List -->
    <div class="container">
        <h2><i class="fas fa-tasks"></i> Task List</h2>
        <table class="table table-bordered mt-4">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Due Date</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($task = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $task['id']; ?></td>
                            <td><?php echo htmlspecialchars($task['title']); ?></td>
                            <td><?php echo htmlspecialchars($task['description']); ?></td>
                            <td><?php echo $task['due_date']; ?></td>
                            <td><?php echo $task['priority']; ?></td>
                            <td>
                                <?php if ($task['status'] == "Completed"): ?>
                                    <span class="badge bg-success">Completed</span>
                                <?php else: ?>
                                    <span class="badge bg-warning text-dark">Pending</span>
                                <?php endif; ?>
                            </td>
<td>  
    <a href="edit_task.php?id=<?php echo $task['id']; ?>" class="btn btn-edit btn-sm">  
        <i class="fas fa-edit"></i> Edit  
    </a>  
    <a href="delete_task.php?id=<?php echo $task['id']; ?>" class="btn btn-delete btn-sm" onclick="return confirm('Are you sure?');">  
        <i class="fas fa-trash"></i> Delete  
    </a>  
    <?php if ($task['status'] != "Completed"): ?>  
        <a href="complete_task.php?id=<?php echo $task['id']; ?>" class="btn btn-success btn-sm">  
            <i class="fas fa-check"></i> Complete  
        </a>  
    <?php endif; ?>  
</td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">No tasks available.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let currentPage = window.location.pathname.split("/").pop();
            let navLinks = document.querySelectorAll(".nav-item-link");

            navLinks.forEach(link => {
                if (link.getAttribute("href") === currentPage) {
                    link.classList.add("active");
                }
            });
        });
    </script>

</body>
</html>