<?php
$host = "sql112.infinityfree.com";
$username = "if0_41826308";
$password = "this27isme";
$database = "if0_41826308_studentupgrade";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) die("Connection failed.");

$totalUsers = $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'];
$today = date('Y-m-d');
$weekAgo = date('Y-m-d', strtotime('-7 days'));

$activeThisWeek = 0;
$activeResult = $conn->query("SELECT COUNT(DISTINCT user_id) as count FROM upgrade_log WHERE log_date >= '$weekAgo'");
if ($activeResult) $activeThisWeek = $activeResult->fetch_assoc()['count'];

$totalTopics = 0;
$topicResult = $conn->query("SELECT COUNT(*) as count FROM study_topics");
if ($topicResult) $totalTopics = $topicResult->fetch_assoc()['count'];

$totalReads = 0;
$readResult = $conn->query("SELECT COUNT(*) as count FROM reading_progress WHERE completed = 1");
if ($readResult) $totalReads = $readResult->fetch_assoc()['count'];

$recentUsers = [];
$result = $conn->query("SELECT id, username, email FROM users ORDER BY id DESC LIMIT 10");
while ($row = $result->fetch_assoc()) $recentUsers[] = $row;

$recentLogs = [];
$logResult = $conn->query("SELECT u.username, ul.action_text, ul.feeling, ul.log_date FROM upgrade_log ul JOIN users u ON ul.user_id = u.id ORDER BY ul.id DESC LIMIT 10");
if ($logResult) while ($row = $logResult->fetch_assoc()) $recentLogs[] = $row;

echo json_encode(['totalUsers' => $totalUsers, 'activeThisWeek' => $activeThisWeek, 'totalTopics' => $totalTopics, 'totalReads' => $totalReads, 'recentUsers' => $recentUsers, 'recentLogs' => $recentLogs]);
$conn->close();
?>
