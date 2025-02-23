<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit();
}

require 'db.php';

// Get task statistics
$sql = "SELECT 
    (SELECT COUNT(*) FROM tasks WHERE status = 'Pending') AS pending,
    (SELECT COUNT(*) FROM tasks WHERE status = 'Completed') AS completed,
    (SELECT COUNT(*) FROM tasks) AS total";
$result = $conn->query($sql);
$stats = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
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
            max-width: 800px;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease-in-out;
        }
        .card:hover {
            transform: translateY(-5px);
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
                        <a class="nav-link nav-item-link active" href="dashboard.php">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-item-link" href="add_task.php">
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

    <!-- Dashboard Content -->
    <div class="container">
        <h2><i class="fas fa-tachometer-alt"></i> Dashboard</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card text-white bg-warning">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-clock"></i> Pending Tasks</h5>
                        <p class="card-text fs-4"><?= $stats['pending'] ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-success">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-check-circle"></i> Completed Tasks</h5>
                        <p class="card-text fs-4"><?= $stats['completed'] ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-info">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-tasks"></i> Total Tasks</h5>
                        <p class="card-text fs-4"><?= $stats['total'] ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <a href="add_task.php" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New Task
            </a>
            <a href="task_list.php" class="btn btn-secondary">
                <i class="fas fa-list"></i> View Tasks
            </a>
        </div>
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