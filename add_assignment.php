<?php
session_start();
require 'db.php';

// Check if teacher is logged in
if (!isset($_SESSION['teacher_id'])) {
    header("Location: login.php");
    exit;
}

$teacherId = $_SESSION['teacher_id'];

// Fetch all courses and user details
$stmt = $pdo->prepare("
    SELECT courses.*, users.username AS creator_name, users.role AS creator_role
    FROM courses
    LEFT JOIN users ON courses.teacher_id = users.id
");
$stmt->execute();
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $due_date = $_POST['due_date'];
    $course_id = $_POST['course_id'];
    $user_id = $_POST['user_id'];  // The user ID to assign the task to

    // Handle file upload
    $filePath = '';
    if (!empty($_FILES['attachment']['name'])) {
        $uploadDir = "uploads/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = basename($_FILES["attachment"]["name"]);
        $filePath = $uploadDir . time() . "_" . $fileName;

        move_uploaded_file($_FILES["attachment"]["tmp_name"], $filePath);
    }

    // Insert assignment into the database with the assigned user
    $stmt = $pdo->prepare("INSERT INTO assignments (title, description, due_date, course_id, file_path, user_id) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$title, $description, $due_date, $course_id, $filePath, $user_id]);

    header("Location: view_assignments.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Assignment</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="p-4">
<div class="container">
    <h2 class="mb-4">📝 Add New Assignment</h2>

    <form method="POST" enctype="multipart/form-data" class="form-control p-4">
        <div class="mb-3">
            <label for="title">Title:</label>
            <input type="text" name="title" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="description">Description:</label>
            <!-- Plain textarea for the description (No TinyMCE) -->
            <textarea name="description" class="form-control" rows="5" required></textarea>
        </div>

        <div class="mb-3">
            <label for="due_date">Due Date:</label>
            <input type="date" name="due_date" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="course_id">Course:</label>
            <select name="course_id" class="form-select" required>
                <option value="">-- Select Course --</option>
                <?php foreach ($courses as $course): ?>
                    <option value="<?= $course['id'] ?>">
                        <?= htmlspecialchars($course['course_name']) ?>
                        (by <?= htmlspecialchars($course['creator_name']) ?> - <?= htmlspecialchars($course['creator_role']) ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="user_id">Assign to User:</label>
            <select name="user_id" class="form-select" required>
                <option value="">-- Select User --</option>
                <?php
                // Fetch all users with role 'user'
                $stmt = $pdo->prepare("SELECT id, username FROM users WHERE role = 'user'");
                $stmt->execute();
                $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                foreach ($users as $user): ?>
                    <option value="<?= $user['id'] ?>"><?= htmlspecialchars($user['username']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="attachment">Attach File (optional):</label>
            <input type="file" name="attachment" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">➕ Add Assignment</button>
    </form>
</div>
</body>
</html>
