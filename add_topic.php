<?php
$host = "sql112.infinityfree.com";
$username = "if0_41826308";
$password = "this27isme";
$database = "if0_41826308_studentupgrade";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) die("Connection failed.");

$userId = $_POST['userId'];
$subject = $_POST['subject'];
$topic = $_POST['topic'];
$today = date('Y-m-d');

if (empty($userId) || empty($subject) || empty($topic)) die("All fields required.");

$review1 = date('Y-m-d', strtotime($today . ' + 1 day'));
$review2 = date('Y-m-d', strtotime($today . ' + 3 days'));
$review3 = date('Y-m-d', strtotime($today . ' + 7 days'));
$review4 = date('Y-m-d', strtotime($today . ' + 14 days'));
$review5 = date('Y-m-d', strtotime($today . ' + 30 days'));

$sql = "INSERT INTO study_topics (user_id, subject, topic, date_added, review_1, review_2, review_3, review_4, review_5, current_review) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 0)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("issssssss", $userId, $subject, $topic, $today, $review1, $review2, $review3, $review4, $review5);

if ($stmt->execute()) echo "success|Topic added! Next review: " . $review1;
else echo "Error: " . $stmt->error;

$stmt->close();
$conn->close();
?>
