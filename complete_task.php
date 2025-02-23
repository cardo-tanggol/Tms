<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit();
}

if (isset($_GET['id'])) {
    $task_id = intval($_GET['id']);

    $sql = "UPDATE tasks SET status='Completed' WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $task_id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Task marked as completed!";
    } else {
        $_SESSION['error'] = "Failed to mark task as completed.";
    }

    $stmt->close();
    $conn->close();
}

header('Location: task_list.php');
exit();