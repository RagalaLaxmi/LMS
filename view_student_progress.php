<?php
session_start();

// Check if the user is logged in and is a teacher
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'teacher') {
    header('Location: login.php');
    exit;
}

// Include the PDO database connection
require_once 'db_connect.php';

// Fetch progress data for all students
$sql = "SELECT users.name AS student_name, courses.title AS course_title, progress.percentage, progress.grade 
        FROM progress 
        JOIN users ON progress.user_id = users.id 
        JOIN courses ON progress.course_id = courses.id 
        WHERE users.role = 'student'
        ORDER BY users.name";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Progress</title>
    <link rel="stylesheet" href="styles10.css">
    <style>
        .main-content {
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: left;
        }
