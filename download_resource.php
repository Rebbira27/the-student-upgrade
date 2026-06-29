<?php
$host = "sql112.infinityfree.com";
$username = "if0_41826308";
$password = "this27isme";
$database = "if0_41826308_studentupgrade";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) die("Connection failed.");

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) die("Invalid resource ID.");

$sql = "SELECT * FROM resource_library WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $filepath = __DIR__ . '/library/' . $row['filename'];
    if (file_exists($filepath)) {
        $conn->query("UPDATE resource_library SET downloads = downloads + 1 WHERE id = $id");
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($row['filename']) . '"');
        header('Content-Length: ' . filesize($filepath));
        ob_clean(); flush();
        readfile($filepath);
        exit;
    }
    echo "File not found on server.";
} else { echo "Resource not found."; }
$stmt->close();
$conn->close();
?>
