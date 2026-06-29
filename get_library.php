<?php
$host = "sql112.infinityfree.com";
$username = "if0_41826308";
$password = "this27isme";
$database = "if0_41826308_studentupgrade";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) die("Connection failed.");

$result = $conn->query("SELECT * FROM resource_library ORDER BY grade_level, subject, title");
$resources = [];
while ($row = $result->fetch_assoc()) { $resources[] = $row; }
echo json_encode($resources);
$conn->close();
?>
