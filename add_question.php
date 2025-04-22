<?php
session_start();
require 'db.php';

// Check if teacher is logged in
if ($_SESSION['role'] !== 'teacher') {
    header("Location: login.php");
    exit;
}

// Get quiz ID from URL
$quizId = $_GET['quiz_id'] ?? null;
if (!$quizId) {
    echo "No quiz selected.";
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $question = $_POST['question'];
    $a = $_POST['option_a'];
    $b = $_POST['option_b'];
    $c = $_POST['option_c'];
    $d = $_POST['option_d'];
    $correct = $_POST['correct_option'];

    $stmt = $pdo->prepare("INSERT INTO questions 
        (quiz_id, question_text, option_a, option_b, option_c, option_d, correct_option) 
        VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$quizId, $question, $a, $b, $c, $d, $correct]);

    $successMessage = "Question added successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Question</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 60%;
            margin: 50px auto;
            padding: 30px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            border-radius: 8px;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-top: 15px;
            font-weight: bold;
        }

        input[type="text"], textarea, select {
            padding: 10px;
            font-size: 16px;
            margin-top: 5px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        button {
            margin-top: 25px;
            padding: 10px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #45a049;
        }

        .success {
            color: green;
            font-weight: bold;
            margin-top: 20px;
            text-align: center;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Add Question to Quiz #<?= htmlspecialchars($quizId) ?></h2>

    <?php if (!empty($successMessage)): ?>
        <div class="success"><?= $successMessage ?></div>
    <?php endif; ?>

    <form method="POST">
        <label for="question">Question:</label>
        <textarea id="question" name="question" rows="4" required></textarea>

        <label for="option_a">Option A:</label>
        <input type="text" id="option_a" name="option_a" required>

        <label for="option_b">Option B:</label>
        <input type="text" id="option_b" name="option_b" required>

        <label for="option_c">Option C:</label>
        <input type="text" id="option_c" name="option_c" required>

        <label for="option_d">Option D:</label>
        <input type="text" id="option_d" name="option_d" required>

        <label for="correct_option">Correct Option:</label>
        <select id="correct_option" name="correct_option" required>
            <option value="">Select Correct Option</option>
            <option value="A">A</option>
            <option value="B">B</option>
            <option value="C">C</option>
            <option value="D">D</option>
        </select>

        <button type="submit">Add Question</button>
    </form>

    <a class="back-link" href="quizzes.php">← Back to Quizzes</a>
</div>

</body>
</html>
