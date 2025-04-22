<?php
// view_enrollments.php

session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

require 'db.php'; // Include the DB connection

// Fetch enrollments from the database using PDO
try {
    $sql = "SELECT e.id, u.username, c.course_name, e.enrollment_date
            FROM enrollments e
            JOIN users u ON e.user_id = u.id
            JOIN courses c ON e.course_id = c.id";
    
    // Prepare the query
    $stmt = $pdo->prepare($sql);
    // Execute the query
    $stmt->execute();

    // Fetch results
    $enrollments = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Enrollments</title>
    <link rel="stylesheet" href="styles50.css">
    <style>
        /* Basic Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            width: 80%;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
        }

        .add-btn {
            display: block;
            margin: 20px auto;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: #fff;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
        }

        .add-btn:hover {
            background-color: #45a049;
        }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        td a {
            text-decoration: none;
            padding: 5px 10px;
            margin: 0 5px;
            border-radius: 3px;
        }

        td a.edit {
            background-color: #4CAF50;
            color: white;
        }

        td a.delete {
            background-color: #f44336;
            color: white;
        }

        td a:hover {
            opacity: 0.7;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Manage Enrollments</h1>

        <!-- Add New Enrollment Button -->
        <a href="add_enrollment.php" class="add-btn">Add New Enrollment</a>

        <!-- Enrollments Table -->
        <table>
            <tr>
                <th>Enrollment ID</th>
                <th>User</th>
                <th>Course</th>
                <th>Enrollment Date</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($enrollments as $row): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['username'] ?></td>
                    <td><?= $row['course_name'] ?></td>
                    <td><?= $row['enrollment_date'] ?></td>
                    <td>
                        <a href="edit_enrollment.php?id=<?= $row['id'] ?>" class="edit">Edit</a>
                        <a href="delete_enrollment.php?id=<?= $row['id'] ?>" class="delete" onclick="return confirm('Are you sure you want to delete this enrollment?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

</body>
</html>
