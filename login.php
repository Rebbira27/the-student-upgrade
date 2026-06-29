<?php
$host = "sql112.infinityfree.com";
$username = "if0_41826308";
$password = "this27isme";
$database = "if0_41826308_studentupgrade";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) die("Connection failed.");

$user = $_POST['username'];
$pass = $_POST['password'];
if (empty($user) || empty($pass)) die("All fields are required.");

$sql = "SELECT id, username, password FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();
    if (password_verify($pass, $row['password'])) {
        echo "success|" . $row['id'] . "|" . $row['username'];
    } else { echo "Incorrect password."; }
} else { echo "User not found."; }

$stmt->close();
$conn->close();
?>
