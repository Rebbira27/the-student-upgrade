<?php
$host = "sql112.infinityfree.com";
$username = "if0_41826308";
$password = "this27isme";
$database = "if0_41826308_studentupgrade";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) die("Connection failed.");

$userId = $_POST['userId'];
$today = date('Y-m-d');
if (empty($userId)) die("Not logged in.");

$sql = "SELECT id, subject, topic, review_1, review_2, review_3, review_4, review_5, current_review, completed FROM study_topics WHERE user_id = ? AND completed = 0 ORDER BY review_1 ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$todayReviews = [];
$upcoming = [];

while ($row = $result->fetch_assoc()) {
    $reviewDates = [$row['review_1'], $row['review_2'], $row['review_3'], $row['review_4'], $row['review_5']];
    $nextReview = null;
    for ($i = $row['current_review']; $i < 5; $i++) { if ($reviewDates[$i]) { $nextReview = $reviewDates[$i]; break; } }
    if ($nextReview && $nextReview <= $today) $todayReviews[] = $row;
    else $upcoming[] = $row;
}

echo json_encode(["today" => $todayReviews, "upcoming" => $upcoming]);
$stmt->close();
$conn->close();
?>
