<?php
$host = "sql112.infinityfree.com";
$username = "if0_41826308";
$password = "this27isme";
$database = "if0_41826308_studentupgrade";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) die("Connection failed.");

$userId = $_POST['userId'];
$storyId = $_POST['storyId'];
$reflection = $_POST['reflection'] ?? '';
$vocab = $_POST['vocab'] ?? '';
$today = date('Y-m-d');

$checkSql = "SELECT id FROM reading_progress WHERE user_id = ? AND story_id = ?";
$checkStmt = $conn->prepare($checkSql);
$checkStmt->bind_param("ii", $userId, $storyId);
$checkStmt->execute();
$checkResult = $checkStmt->get_result();

if ($checkResult->num_rows > 0) {
    $sql = "UPDATE reading_progress SET reflection_text = ?, vocab_collected = ?, date_read = ? WHERE user_id = ? AND story_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssii", $reflection, $vocab, $today, $userId, $storyId);
} else {
    $sql = "INSERT INTO reading_progress (user_id, story_id, completed, reflection_text, vocab_collected, date_read) VALUES (?, ?, 1, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iisss", $userId, $storyId, $reflection, $vocab, $today);
}

if ($stmt->execute()) echo "success";
else echo "Error: " . $stmt->error;

$stmt->close();
$conn->close();
?>
