<?php
$host = "sql112.infinityfree.com";
$username = "if0_41826308";
$password = "this27isme";
$database = "if0_41826308_studentupgrade";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) die("Connection failed.");

$title = $_POST['title'];
$subject = $_POST['subject'];
$grade = $_POST['grade'];
$type = $_POST['type'];
$description = $_POST['description'];
$today = date('Y-m-d');

if (empty($title) || empty($subject) || empty($grade) || empty($type)) die("Missing required fields.");

$targetDir = __DIR__ . '/library/';
if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);

$filename = basename($_FILES['file']['name']);
$targetFile = $targetDir . $filename;
$fileSize = round($_FILES['file']['size'] / 1024, 1) . ' KB';

if (move_uploaded_file($_FILES['file']['tmp_name'], $targetFile)) {
    $sql = "INSERT INTO resource_library (title, subject, grade_level, type, description, filename, file_size, date_added) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssss", $title, $subject, $grade, $type, $description, $filename, $fileSize, $today);
    if ($stmt->execute()) echo "success|Resource uploaded!";
    else echo "Database error: " . $stmt->error;
    $stmt->close();
} else { echo "File upload failed."; }
$conn->close();
?>
