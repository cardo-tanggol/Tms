<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit();
}

require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $due_date = $_POST['due_date'];
    $priority = $_POST['priority'];
    $status = $_POST['status'];

    $sql = "INSERT INTO tasks (title, description, due_date, priority, status) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $title, $description, $due_date, $priority, $status);

    if ($stmt->execute()) {
        header("Location: task_list.php");
        exit();
    } else {
        echo "Error adding task.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Task</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background: linear-gradient(to right, #6a11cb, #2575fc);
            min-height: 100vh;
            font-family: 'Arial', sans-serif;
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
            max-width: 600px;
        }
        h2 {
            color: #4b0082;
            font-weight: bold;
        }
        .btn-primary {
            background-color: #4b0082;
            border-color: #4b0082;
        }
        .btn-primary:hover {
            background-color: #5a0099;
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
                        <a class="nav-link nav-item-link active" href="add_task.php">
                            <i class="fas fa-plus"></i> Add Task
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-item-link" href="task_list.php">
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

    <!-- Add Task Form -->
    <div class="container">
        <h2><i class="fas fa-plus-circle"></i> Add Task</h2>
        <form method="post">
            <div class="mb-3">
                <label for="title" class="form-label"><i class="fas fa-tasks"></i> Task Title</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label"><i class="fas fa-align-left"></i> Description</label>
                <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
            </div>

            <div class="mb-3">
                <label for="due_date" class="form-label"><i class="fas fa-calendar-alt"></i> Due Date</label>
                <input type="date" class="form-control" id="due_date" name="due_date" required>
            </div>

            <div class="mb-3">
                <label for="priority" class="form-label"><i class="fas fa-flag"></i> Priority</label>
                <select class="form-control" id="priority" name="priority" required>
                    <option value="High">High</option>
                    <option value="Medium">Medium</option>
                    <option value="Low">Low</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="status" class="form-label"><i class="fas fa-check-circle"></i> Status</label>
                <select class="form-control" id="status" name="status" required>
                    <option value="Pending">Pending</option>
                    <option value="Completed">Completed</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Task</button>
            <a href="task_list.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Cancel</a>
        </form>
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