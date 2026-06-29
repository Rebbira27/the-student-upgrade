<?php
$host = "sql112.infinityfree.com";
$username = "if0_41826308";
$password = "this27isme";
$database = "if0_41826308_studentupgrade";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) die("Connection failed.");

$userId = $_POST['userId'];
$weekNumber = $_POST['weekNumber'] ?? date('W');
if (empty($userId)) die("Not logged in.");

$sql = "SELECT * FROM upgrade_log WHERE user_id = ? AND week_number = ? ORDER BY log_date ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $userId, $weekNumber);
$stmt->execute();
$result = $stmt->get_result();

$logs = [];
$schedule = "";
while ($row = $result->fetch_assoc()) {
    $logs[] = $row;
    if (!empty($row['schedule_text'])) $schedule = $row['schedule_text'];
}

echo json_encode(["logs" => $logs, "schedule" => $schedule, "weekNumber" => $weekNumber]);
$stmt->close();
$conn->close();
?>
