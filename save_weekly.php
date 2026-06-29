<?php
$host = "sql112.infinityfree.com";
$username = "if0_41826308";
$password = "this27isme";
$database = "if0_41826308_studentupgrade";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) die("Connection failed.");

$userId = $_POST['userId'];
$weekNumber = $_POST['weekNumber'];
$upgrade = $_POST['upgrade'];
$drain = $_POST['drain'];
$focus = $_POST['focus'];

$sql = "UPDATE upgrade_log SET weekly_upgrade=?, weekly_drain=?, weekly_focus=? WHERE user_id=? AND week_number=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssis", $upgrade, $drain, $focus, $userId, $weekNumber);

if ($stmt->execute()) echo "success";
else echo "Error: " . $stmt->error;

$stmt->close();
$conn->close();
?>
