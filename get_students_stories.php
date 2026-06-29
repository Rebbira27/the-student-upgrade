<?php
$host = "sql112.infinityfree.com";
$username = "if0_41826308";
$password = "this27isme";
$database = "if0_41826308_studentupgrade";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) die("Connection failed.");

$status = $_GET['status'] ?? 'approved';
if ($status === 'all') $result = $conn->query("SELECT * FROM student_stories ORDER BY id DESC");
else {
    $sql = "SELECT * FROM student_stories WHERE status = ? ORDER BY id DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $status);
    $stmt->execute();
    $result = $stmt->get_result();
}
$stories = [];
while ($row = $result->fetch_assoc()) $stories[] = $row;
echo json_encode($stories);
$conn->close();
?>
