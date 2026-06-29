<?php
$host = "sql112.infinityfree.com";
$username = "if0_41826308";
$password = "this27isme";
$database = "if0_41826308_studentupgrade";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) die("Connection failed.");

$storyId = $_POST['storyId'] ?? 0;
$status = $_POST['status'] ?? '';
$feedback = $_POST['feedback'] ?? '';
$today = date('Y-m-d');

if (empty($storyId) || empty($status)) die("Missing required fields.");

$sql = "UPDATE student_stories SET status = ?, feedback = ?, date_reviewed = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssi", $status, $feedback, $today, $storyId);

if ($stmt->execute()) echo "success";
else echo "Error: " . $stmt->error;

$stmt->close();
$conn->close();
?>
