<?php
// Set response headers to prevent XSS
header("X-XSS-Protection: 1; mode=block");
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: DENY");

// Initialize PDO object
$db = new PDO("mysql:host=db;dbname=TMA101", "root", "1234");

// Validate file extension before accepting upload
$allowedExtensions = array('pdf', 'doc', 'docx', 'txt');
$extension = pathinfo($_FILES['userFile']['name'], PATHINFO_EXTENSION);
if (!in_array($extension, $allowedExtensions)) {
    die("Error: Invalid file type.");
}

// Validate file size before accepting upload
$maxFileSize = 1000000; // 1 MB
if ($_FILES['userFile']['size'] > $maxFileSize) {
    die("Error: File size exceeds limit.");
}

// Check for file upload errors
if ($_FILES['userFile']['error'] !== UPLOAD_ERR_OK) {
    die("Error: File upload failed with error code " . $_FILES['userFile']['error']);
}

// Prepare SQL statement with placeholders for user input
$stmt = $db->prepare('INSERT INTO group_documents (path, filename) VALUES (:path, :filename)');

// Sanitize user input for SQL query
$path = filter_var($_POST['path'], FILTER_SANITIZE_STRING);
$filename = filter_var($_FILES['userFile']['name'], FILTER_SANITIZE_STRING);

// Bind user input to placeholders
$stmt->bindParam(':path', $path);
$stmt->bindParam(':filename', $filename);

// Execute prepared statement
$stmt->execute();

// Sanitize user input for display in HTML
$filename = htmlspecialchars($filename, ENT_QUOTES, 'UTF-8');

// Close database connection
$pdo = null;
?>

