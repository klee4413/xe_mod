<?php
// GAC FOUNDRY: Unified Video Entry & Monitor
require_once __DIR__ . '/../../db-connect.php';

$msg = "";

// 1. HANDLE POST ACTIONS
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category = $_POST['category'];
    $title = $_POST['title'];
    $url = $_POST['url'];

    $stmt = $pdo->prepare("INSERT INTO video_list (category, title, url, status) VALUES (?, ?, ?, 'active')");
    if ($stmt->execute([$category, $title, $url])) {
        $msg = "Video added successfully to the GAC Library.";
    }
}

// 2. FETCH EXISTING VIDEOS FOR THE LIST
$stmt = $pdo->query("SELECT id, category, title, url FROM video_list WHERE status = 'active' ORDER BY id DESC");
$videos = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>GAC | Video Entry Portal</title>
    <style>
        :root { --bg: #0f0f0f; --card: #1e1e1e; --accent: #28a745; --text: #ffffff; --border: #333; }
        body { background: var(--bg); color: var(--text); font-family: 'Inter', sans-serif; display: flex; flex-direction: column; align-items: center; padding: 40px 20px; }
        
        /* FORM CONTAINER */
        .form-container { background: var(--card); padding: 30px; border-radius: 12px; width: 100%; max-width: 500px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); margin-bottom: 30px; }
        h2 { border-bottom: 2px solid var(--accent); padding-bottom: 10px; margin-bottom: 20px; color: var(--accent); font-size: 1.5rem; }
        label { font-size: 14px; color: #aaa; }
        input, select { width: 100%; padding: 12px; margin: 8px 0 18px 0; background: #2a2a2a; border: 1px solid #444; color: white; border-radius: 4px; box-sizing: border-box; outline: none; }
        input:focus { border-color: var(--accent); }
        button { width: 100%; padding: 15px; background: var(--accent); border: none; color: white; font-weight: bold; cursor: pointer; border-radius: 4px; font-size: 16px; }
        .success-msg { color: var(--accent); margin-bottom: 15px; font-weight: bold; font-size: 14px; text-align: center; }

        /* VIDEO LIST PANEL */
        .list-container { width: 100%; max-width: 800px; background: var(--card); border-radius: 12px; padding: 20px; border: 1px solid var(--border); }
        .list-header { color: var(--accent); margin-bottom: 15px; font-size: 18px; font-weight: bold; }
        .video-item { display: flex; justify-content: space-between; align-items: center; padding: 12px; border-bottom: 1px solid var(--border); transition: background 0.2s; }
        .video-item:hover { background: #252525; }
        .video-info { display: flex; flex-direction: column; gap: 4px; }
        .vid-cat { font-size: 10px; background: #28a74533; color: var(--accent); padding: 2px 6px; border-radius: 3px; align-self: flex-start; text-transform: uppercase; font-weight: bold; }
        .vid-title { font-size: 15px; color: #eee; }
        .vid-link { color: #58a6ff; font-size: 12px; text-decoration: none; }
        .vid-link:hover { text-decoration: underline; }
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
	<button type="button" onclick="toHome()" 
                        class="gac-blue text-black px-20 py-5 rounded-2xl font-black uppercase text-sm tracking-widest shadow-xl hover:scale-105 transition-all">
                   Back To Home
                </button>
</div>

<div class="list-container">
    <div class="list-header">Current Curriculum Library (Recent First)</div>
    <?php if (empty($videos)): ?>
        <p style="color: #666; text-align: center;">No videos found in the repository.</p>
    <?php else: ?>
        <?php foreach ($videos as $row): ?>
            <div class="video-item">
                <div class="video-info">
                    <span class="vid-cat"><?= htmlspecialchars($row['category']); ?></span>
                    <span class="vid-title"><?= htmlspecialchars($row['title']); ?></span>
                    <a href="<?= htmlspecialchars($row['url']); ?>" target="_blank" class="vid-link">Watch on YouTube →</a>
                </div>
                <div style="color: #000000; font-size: 15px;">ID: <?= $row['id']; ?></div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

</body>
<script>function toHome() {window.location.href = 'admin-offices.php';}</script>
</html>