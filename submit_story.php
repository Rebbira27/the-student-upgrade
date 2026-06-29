<?php
$host = "sql112.infinityfree.com";
$username = "if0_41826308";
$password = "this27isme";
$database = "if0_41826308_studentupgrade";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) die("Connection failed.");

$userId = $_POST['userId'] ?? 0;
$authorName = $_POST['authorName'] ?? '';
$title = $_POST['title'] ?? '';
$storyText = $_POST['storyText'] ?? '';
$level = $_POST['level'] ?? 3;
$today = date('Y-m-d');

if (empty($userId) || empty($title) || empty($storyText)) die("Missing required fields.");

$sql = "INSERT INTO student_stories (user_id, author_name, title, story_text, level, status, date_submitted) VALUES (?, ?, ?, ?, ?, 'pending', ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("isssis", $userId, $authorName, $title, $storyText, $level, $today);

if ($stmt->execute()) echo "success|Story submitted! It will be reviewed soon.";
else echo "Error: " . $stmt->error;

$stmt->close();
$conn->close();
?>
