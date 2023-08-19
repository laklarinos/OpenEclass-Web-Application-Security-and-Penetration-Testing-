<?php
// Set response headers to prevent XSS
header('Content-Type: text/html; charset=utf-8');
header('X-Content-Type-Options: nosniff');

// Get assignment submission data
$assignment_submit = $_POST['assignment_submit'];
$uid = $_SESSION['user_id'];
$assignment_id = $_POST['assignment_id'];
$submission_date = date("Y-m-d H:i:s");
$submission_ip = $_SERVER['REMOTE_ADDR'];
$file_path = $_POST['file_path'];
$file_name = $_POST['file_name'];
$comments = $_POST['comments'];

// Sanitize input data
$assignment_submit = filter_var($assignment_submit, FILTER_SANITIZE_STRING);
$assignment_id = filter_var($assignment_id, FILTER_SANITIZE_NUMBER_INT);
$file_path = filter_var($file_path, FILTER_SANITIZE_STRING);
$file_name = filter_var($file_name, FILTER_SANITIZE_STRING);
$comments = filter_var($comments, FILTER_SANITIZE_STRING);

// Prevent SQL injection
$pdo = new PDO("mysql:host=db;dbname=TMA101", "root", "1234");
$stmt = $pdo->prepare('INSERT INTO assignments_submit (uid, assignment_id, submission_date, submission_ip, file_path, file_name, comments) VALUES (:uid, :assignment_id, :submission_date, :submission_ip, :file_path, :file_name, :comments)');

// Bind parameters to prevent SQL injection
$stmt->bindParam(':uid', $uid, PDO::PARAM_INT);
$stmt->bindParam(':assignment_id', $assignment_id, PDO::PARAM_INT);
$stmt->bindParam(':submission_date', $submission_date, PDO::PARAM_STR);
$stmt->bindParam(':submission_ip', $submission_ip, PDO::PARAM_STR);
$stmt->bindParam(':file_path', $file_path, PDO::PARAM_STR);
$stmt->bindParam(':file_name', $file_name, PDO::PARAM_STR);
$stmt->bindParam(':comments', $comments, PDO::PARAM_STR);

// Execute prepared statement
$stmt->execute();

// Display success message
echo "<p>Assignment submitted successfully!</p>";

// Close database connection
$pdo = null;
?>
