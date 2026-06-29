<?php
$host = "sql112.infinityfree.com";
$username = "if0_41826308";
$password = "this27isme";
$database = "if0_41826308_studentupgrade";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) die("Connection failed.");

$userId = $_POST['userId'];
$date = $_POST['date'];
$action = $_POST['action'];
$feeling = $_POST['feeling'];
$learned = $_POST['learned'];
$schedule = $_POST['schedule'] ?? '';
$weekNumber = $_POST['weekNumber'] ?? date('W');

if (empty($userId) || empty($date) || empty($action)) die("Missing required fields.");

$checkSql = "SELECT id FROM upgrade_log WHERE user_id = ? AND log_date = ?";
$checkStmt = $conn->prepare($checkSql);
$checkStmt->bind_param("is", $userId, $date);
$checkStmt->execute();
$checkResult = $checkStmt->get_result();

if ($checkResult->num_rows > 0) {
    $row = $checkResult->fetch_assoc();
    $sql = "UPDATE upgrade_log SET action_text=?, feeling=?, learned=?, schedule_text=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sissi", $action, $feeling, $learned, $schedule, $row['id']);
} else {
    $sql = "INSERT INTO upgrade_log (user_id, log_date, action_text, feeling, learned, schedule_text, week_number) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ississi", $userId, $date, $action, $feeling, $learned, $schedule, $weekNumber);
}

if ($stmt->execute()) echo "success";
else echo "Error: " . $stmt->error;

$stmt->close();
$conn->close();
?>
