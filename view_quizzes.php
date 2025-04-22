<?php
session_start(); // Start the session
require 'db.php'; // Include the database connection file

// Check if the user is logged in and has the role of 'teacher'
if ($_SESSION['role'] !== 'teacher') {
    header("Location: login.php"); // Redirect to login if not a teacher
    exit;
}

// Get the teacher's ID from the session
$teacherId = $_SESSION['teacher_id'];

// Query to get all quizzes created by the teacher
$stmt = $pdo->prepare("
    SELECT q.*, c.course_name 
    FROM quizzes q
    JOIN courses c ON q.course_id = c.id
    WHERE c.teacher_id = ?
");
$stmt->execute([$teacherId]);
$quizzes = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Quizzes</title>
    <link rel="stylesheet" href="stylesquize.css"> <!-- Link to your CSS file -->
</head>
<body>

    <div class="container">
        <h2>Your Quizzes</h2>
        <a href="add_quiz.php">+ Create New Quiz</a> <!-- Link to the page where you can add new quizzes -->
        <table border="1">
            <tr>
                <th>Title</th>
                <th>Course</th>
                <th>Actions</th>
            </tr>

            <?php foreach ($quizzes as $quiz): ?>
                <tr>
                    <td><?= htmlspecialchars($quiz['quiz_title']) ?></td> <!-- Quiz title -->
                    <td><?= htmlspecialchars($quiz['course_name']) ?></td> <!-- Course name -->
                    <td>
                        <a href="edit_quiz.php?id=<?= $quiz['id'] ?>">Edit</a> | <!-- Edit link -->
                        <a href="edit_questions.php?quiz_id=<?= $quiz['id'] ?>">Questions</a> | <!-- Manage questions link -->
                        <a href="delete_quiz.php?id=<?= $quiz['id'] ?>" onclick="return confirm('Delete this quiz?')">Delete</a> <!-- Delete link with confirmation -->
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

</body>
</html>
