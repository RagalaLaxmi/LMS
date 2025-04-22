
<?php
include 'db.php';

// Fetch all courses
$query = "SELECT * FROM courses";
$stmt = $pdo->query($query);
$courses = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $course_id = $_POST['course_id'];
    $announcement = $_POST['announcement'];

    // Insert the announcement into the database
    $stmt = $pdo->prepare("INSERT INTO announcements (course_id, announcement) VALUES (?, ?)");
    $stmt->execute([$course_id, $announcement]);

    // Optionally, send email notifications to all students in that course.
    $studentsQuery = "SELECT email FROM students WHERE id IN (SELECT student_id FROM enrollments WHERE course_id = ?)";
    $studentsStmt = $pdo->prepare($studentsQuery);
    $studentsStmt->execute([$course_id]);
    $students = $studentsStmt->fetchAll();

    foreach ($students as $student) {
        // Send email to each student (for simplicity, this example doesn't include email sending)
        // mail($student['email'], "New Announcement", $announcement);
    }

    header("Location: send_announcements.php");
}
?>

<div class="send-announcements">
    <h2>Send Announcements</h2>
    <form id="send-announcement-form">
        <label for="announcement-title">Title:</label>
        <input type="text" id="announcement-title" name="title" required>

        <label for="announcement-content">Content:</label>
        <textarea id="announcement-content" name="content" required></textarea>

        <button type="submit">Send Announcement</button>
    </form>
</div>
