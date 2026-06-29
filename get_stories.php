<?php
$host = "sql112.infinityfree.com";
$username = "if0_41826308";
$password = "this27isme";
$database = "if0_41826308_studentupgrade";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) die("Connection failed.");

$series = $_GET['series'] ?? '';
$chapter = $_GET['chapter'] ?? '';

function getStoriesFromFiles($seriesName = '', $chapterNum = '') {
    $baseDir = __DIR__ . '/stories/';
    if (!is_dir($baseDir)) return [];
    $allSeries = [];
    $seriesDirs = glob($baseDir . '*', GLOB_ONLYDIR);
    foreach ($seriesDirs as $dir) {
        $folderName = basename($dir);
        $infoFile = $dir . '/info.json';
        $seriesDisplayName = $folderName;
        if (file_exists($infoFile)) { $info = json_decode(file_get_contents($infoFile), true); $seriesDisplayName = $info['name'] ?? $folderName; }
        $chapterFiles = glob($dir . '/chapter-*.txt');
        $chapters = [];
        foreach ($chapterFiles as $file) {
            $content = file_get_contents($file);
            $parts = explode('---', $content, 2);
            $metaText = trim($parts[0]);
            $storyText = isset($parts[1]) ? trim($parts[1]) : '';
            $meta = [];
            foreach (explode("\n", $metaText) as $line) {
                $colon = strpos($line, ':');
                if ($colon !== false) { $key = strtolower(trim(substr($line, 0, $colon))); $value = trim(substr($line, $colon + 1)); $meta[$key] = $value; }
            }
            preg_match('/chapter-(\d+)\.txt/', basename($file), $matches);
            $chNum = $matches[1] ?? 0;
            $chapters[] = ['id' => 'file_' . $folderName . '_' . $chNum, 'series_name' => $seriesDisplayName, 'chapter_number' => (int)$chNum, 'title' => $meta['title'] ?? 'Untitled', 'level' => (int)($meta['level'] ?? 3), 'read_time' => (int)($meta['read_time'] ?? 10), 'vocab_focus' => $meta['vocab'] ?? '', 'grammar_focus' => $meta['grammar'] ?? '', 'story_text' => $storyText, 'question_1' => $meta['q1'] ?? '', 'question_2' => $meta['q2'] ?? '', 'question_3' => $meta['q3'] ?? '', 'cliffhanger' => $meta['cliffhanger'] ?? '', 'date_added' => date('Y-m-d', filemtime($file))];
        }
        usort($chapters, function($a, $b) { return $a['chapter_number'] - $b['chapter_number']; });
        $allSeries[] = ['name' => $seriesDisplayName, 'folder' => $folderName, 'chapters' => $chapters];
    }
    if ($seriesName) {
        foreach ($allSeries as $s) {
            if ($s['folder'] === $seriesName || $s['name'] === $seriesName) {
                if ($chapterNum !== '') { foreach ($s['chapters'] as $ch) { if ($ch['chapter_number'] == $chapterNum) return [$ch]; } return []; }
                return $s['chapters'];
            }
        }
        return [];
    }
    $names = [];
    foreach ($allSeries as $s) { $names[] = $s['name']; }
    return $names;
}

if ($series && $chapter !== '') {
    $sql = "SELECT * FROM stories WHERE series_name = ? AND chapter_number = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $series, $chapter);
} elseif ($series) {
    $sql = "SELECT id, chapter_number, title, level, read_time, date_added FROM stories WHERE series_name = ? ORDER BY chapter_number ASC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $series);
} else {
    $result = $conn->query("SELECT DISTINCT series_name FROM stories ORDER BY series_name ASC");
    $dbSeries = [];
    while ($row = $result->fetch_assoc()) { $dbSeries[] = $row['series_name']; }
    $fileSeries = getStoriesFromFiles();
    $allSeries = array_unique(array_merge($dbSeries, $fileSeries));
    echo json_encode(array_values($allSeries));
    exit;
}

$stmt->execute();
$result = $stmt->get_result();
$dbData = [];
while ($row = $result->fetch_assoc()) { $dbData[] = $row; }
$stmt->close();
$conn->close();

$fileData = getStoriesFromFiles($series, $chapter);
if ($chapter !== '') {
    if (!empty($fileData)) echo json_encode($fileData);
    else echo json_encode($dbData);
} else {
    $merged = []; $seen = [];
    foreach ($dbData as $d) { $key = $d['chapter_number']; $seen[$key] = true; $merged[] = $d; }
    foreach ($fileData as $f) { $key = $f['chapter_number']; if (!isset($seen[$key])) $merged[] = $f; }
    usort($merged, function($a, $b) { return $a['chapter_number'] - $b['chapter_number']; });
    echo json_encode($merged);
}
?>
