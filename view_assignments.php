<?php
session_start();
require 'db.php';

// Check if logged in and is a teacher
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'teacher') {
    header('Location: login.php');
    exit;
}

$teacherId = $_SESSION['teacher_id'];

// Fetch assignments related to this teacher
$stmt = $pdo->prepare("
    SELECT a.id, a.title, a.description, a.due_date, c.course_name
    FROM assignments a
    INNER JOIN courses c ON a.course_id = c.id
    WHERE c.teacher_id = ?
");
$stmt->execute([$teacherId]);
$assignments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Assignments</title>
    <link rel="stylesheet" href="styles10.css">
    <style>
        .action-buttons {
            margin-bottom: 20px;
        }

        .action-buttons a {
            margin-right: 10px;
            text-decoration: none;
            padding: 10px 15px;
            border-radius: 5px;
            color: #fff;
        }

        .btn-add { background-color: #28a745; }
        .btn-delete { background-color: #dc3545; }
        .btn-update { background-color: #007bff; }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>Teacher Dashboard</h2>
    <ul>
        <li><a href="view_course1.php">Manage Courses</a></li>
        <li><a href="view_assignments.php">Manage Assignments</a></li>
        <li><a href="view_quizzes.php">Manage Quizzes</a></li>
        <li><a href="view_student_Progress.php">View Student Progress</a></li>
        <li><a href="view_Grade_Assignments.php">Grade Assignments</a></li>
        <li><a href="send_Announcements.php">Send Announcements</a></li>
        <li><a href="student_interaction.php">Student Interaction</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</div>

<div class="main-content">
    <h1>Manage Assignments</h1>

    <!-- Top Action Buttons -->
    <div class="action-buttons">
        <a href="add_assignment.php" class="btn-add">➕ Add Assignment</a>
        <!-- You can enable these later -->
        <!-- <a href="#" class="btn-update">✏️ Update Selected</a> -->
        <!-- <a href="#" class="btn-delete">🗑️ Delete Selected</a> -->
    </div>

    <?php if ($assignments): ?>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Course</th>
                    <th>Due Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($assignments as $a): ?>
                    <tr>
                        <td><?= $a['id'] ?></td>
                        <td><?= htmlspecialchars($a['title']) ?></td>
                        <td><?= htmlspecialchars($a['description']) ?></td>
                        <td><?= htmlspecialchars($a['course_name']) ?></td>
                        <td><?= htmlspecialchars($a['due_date']) ?></td>
                        <td>
                            <a href="edit_assignment.php?id=<?= $a['id'] ?>" class="btn btn-primary">✏️ Edit</a>
                            <a href="copy_assignment.php?id=<?= $a['id'] ?>" class="btn btn-info">📋 Copy</a>
                            <a href="delete_assignment.php?id=<?= $a['id'] ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this assignment?')">🗑️ Delete</a>
                        </td>
                    </tr>
              /*  <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No assignments found.</p>
    <?php endif; ?>
</div>*/

</body>
</html>
