<?php
require_once __DIR__ . '/../../db-connect.php';

$msg = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category = $_POST['category'];
    $title = $_POST['title'];
    $url = $_POST['url'];

    $stmt = $pdo->prepare("INSERT INTO video_list (category, title, url, status) VALUES (?, ?, ?, 'active')");
    if ($stmt->execute([$category, $title, $url])) {
        $msg = "Video added successfully to the GAC Library.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>GAC | Video Entry Portal</title>
    <style>
        :root { --bg: #0f0f0f; --card: #1e1e1e; --accent: #28a745; --text: #ffffff; }
        body { background: var(--bg); color: var(--text); font-family: sans-serif; display: flex; justify-content: center; padding-top: 50px; }
        .form-container { background: var(--card); padding: 30px; border-radius: 12px; width: 450px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); }
        h2 { border-bottom: 2px solid var(--accent); padding-bottom: 10px; margin-bottom: 20px; color: var(--accent); }
        input, select { width: 100%; padding: 12px; margin: 10px 0; background: #2a2a2a; border: 1px solid #444; color: white; border-radius: 4px; box-sizing: border-box; }
        button { width: 100%; padding: 15px; background: var(--accent); border: none; color: white; font-weight: bold; cursor: pointer; border-radius: 4px; margin-top: 10px; }
        .success-msg { color: #28a745; margin-bottom: 15px; font-weight: bold; }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Video Repository Entry</h2>
    <?php if($msg): ?> <div class="success-msg"><?= $msg; ?></div> <?php endif; ?>
    
    <form method="POST">
        <label>Curriculum Category:</label>
        <select name="category" required>
            <option value="Prompting">Prompting</option>
            <option value="NotebookLM">NotebookLM</option>
            <option value="Gemini">Gemini</option>
            <option value="Drive">Google Drive</option>
            <option value="Sheets">Google Sheets</option>
            <option value="Automation">Automation</option>
        </select>

        <label>Video Title:</label>
        <input type="text" name="title" maxlength="50" placeholder="e.g. Mastering Gemini 2026" required>

        <label>YouTube URL:</label>
        <input type="url" name="url" placeholder="https://www.youtube.com/watch?v=..." required>

        <button type="submit">COMMIT TO DATABASE</button>
    </form>
</div>

</body>
</html>