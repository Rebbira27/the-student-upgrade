<?php
$host = "sql112.infinityfree.com";
$username = "if0_41826308";
$password = "this27isme";
$database = "if0_41826308_studentupgrade";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) die("Connection failed.");

$topicId = $_POST['topicId'];
$currentReview = $_POST['currentReview'];
if (empty($topicId)) die("Missing topic ID.");

$nextReview = $currentReview + 1;
if ($nextReview >= 5) $sql = "UPDATE study_topics SET completed = 1, current_review = ? WHERE id = ?";
else $sql = "UPDATE study_topics SET current_review = ? WHERE id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $nextReview, $topicId);

if ($stmt->execute()) echo "success";
else echo "Error: " . $stmt->error;

$stmt->close();
$conn->close();
?>
