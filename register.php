<?php
$host = "sql112.infinityfree.com";
$username = "if0_41826308";
$password = "this27isme";
$database = "if0_41826308_studentupgrade";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) die("Connection failed.");

$user = $_POST['username'];
$email = $_POST['email'];
$pass = $_POST['password'];

if (empty($user) || empty($email) || empty($pass)) die("All fields are required.");

$hashed_password = password_hash($pass, PASSWORD_DEFAULT);
$sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $user, $email, $hashed_password);

if ($stmt->execute()) echo "success";
else echo "Error: " . $stmt->error;

$stmt->close();
$conn->close();
?>
