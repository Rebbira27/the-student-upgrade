<?php
$host = "sql112.infinityfree.com";
$username = "if0_41826308";
$password = "this27isme";
$database = "if0_41826308_studentupgrade";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) die("Connection failed.");

$id = $_POST['id'] ?? 0;
$sql = "SELECT filename FROM resource_library WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $filepath = __DIR__ . '/library/' . $row['filename'];
    if (file_exists($filepath)) unlink($filepath);
    $conn->query("DELETE FROM resource_library WHERE id = $id");
    echo "deleted";
} else { echo "not found"; }
$stmt->close();
$conn->close();
?>
