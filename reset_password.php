<?php
$host = "sql112.infinityfree.com";
$username = "if0_41826308";
$password = "this27isme";
$database = "if0_41826308_studentupgrade";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) die("Connection failed.");

$email = $_POST['email'];
if (empty($email)) die("Please enter your email.");

$sql = "SELECT id, username FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();
    $newPassword = substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"), 0, 10);
    $hashed_password = password_hash($newPassword, PASSWORD_DEFAULT);
    $updateSql = "UPDATE users SET password = ? WHERE id = ?";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bind_param("si", $hashed_password, $row['id']);
    
    if ($updateStmt->execute()) {
        $to = $email;
        $subject = "Password Reset - The Student Upgrade";
        $message = "Hello " . $row['username'] . ",\r\n\r\nYour password has been reset.\r\n\r\nUsername: " . $row['username'] . "\r\nNew Password: " . $newPassword . "\r\n\r\nLog in at: https://studentupgrade.infinityfree.me\r\n\r\n- The Student Upgrade";
        
        $smtpHost = "smtp.gmail.com"; $smtpPort = 587;
        $smtpUser = "rebbirragize@gmail.com"; $smtpPass = "YOUR_APP_PASSWORD_HERE";
        
        $smtpConnection = @fsockopen($smtpHost, $smtpPort, $errno, $errstr, 30);
        if ($smtpConnection) {
            fgets($smtpConnection, 515);
            fwrite($smtpConnection, "EHLO test\r\n");
            while ($line = fgets($smtpConnection, 515)) { if (substr($line, 3, 1) == " ") break; }
            fwrite($smtpConnection, "STARTTLS\r\n"); fgets($smtpConnection, 515);
            stream_socket_enable_crypto($smtpConnection, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);
            fwrite($smtpConnection, "EHLO test\r\n");
            while ($line = fgets($smtpConnection, 515)) { if (substr($line, 3, 1) == " ") break; }
            fwrite($smtpConnection, "AUTH LOGIN\r\n"); fgets($smtpConnection, 515);
            fwrite($smtpConnection, base64_encode($smtpUser) . "\r\n"); fgets($smtpConnection, 515);
            fwrite($smtpConnection, base64_encode($smtpPass) . "\r\n"); fgets($smtpConnection, 515);
            fwrite($smtpConnection, "MAIL FROM: <" . $smtpUser . ">\r\n"); fgets($smtpConnection, 515);
            fwrite($smtpConnection, "RCPT TO: <" . $to . ">\r\n"); fgets($smtpConnection, 515);
            fwrite($smtpConnection, "DATA\r\n"); fgets($smtpConnection, 515);
            fwrite($smtpConnection, "From: The Student Upgrade <" . $smtpUser . ">\r\nTo: " . $to . "\r\nSubject: " . $subject . "\r\nContent-Type: text/plain; charset=UTF-8\r\n\r\n" . $message . "\r\n.\r\n");
            fgets($smtpConnection, 515);
            fwrite($smtpConnection, "QUIT\r\n"); fclose($smtpConnection);
            echo "success|email_sent";
        } else {
            echo "success|" . $newPassword . "|" . $row['username'];
        }
    }
    $updateStmt->close();
} else { echo "Email not found."; }
$stmt->close();
$conn->close();
?>
