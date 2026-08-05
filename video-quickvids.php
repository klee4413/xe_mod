<?php
require_once __DIR__ . '/../db-connect.php';
session_start();

//require_once 'db-config.php';
$student_id = $_SESSION['user_id']    ?? 0;
$first_name = $_SESSION['first_name'] ?? '';
$last_name  = $_SESSION['last_name']  ?? '';
$email      = $_SESSION['email']  ?? '';
// Fetch all active videos from the MariaDB Foundry
$stmt = $pdo->query("SELECT category, title, url FROM video_list WHERE status = 'active' ORDER BY category ASC, id DESC");
$videos = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AIGC | Quick VIDs Lab</title>
    <style>
        :root {
            --bg-color: #050505;
            --panel-bg: #121212;
            --accent-red: #ff0000;
            --text-main: #ffffff;
            --text-dim: #2e7d32; /* Green for directory headers */
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-main);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        /* LEFT SIDEBAR: Video Directory */
        .sidebar {
            width: 25%;
            padding: 20px;
            overflow-y: auto;
            border-right: 1px solid #222;
        }

        .directory-header {
            color: var(--text-dim);
            font-size: 1.3rem;
            font-weight: bold;
            margin-bottom: 18px;
        }

        .video-link {
            display: block;
            color: #ddd;
            text-decoration: none;
            padding: 8px 0;
            font-size: 0.95rem;
            cursor: pointer;
            transition: color 0.2s;
        }

        .video-link:hover {
            color: #00ff00; /* Neon highlight on hover */
        }

        /* RIGHT PANEL: Theatre Area */
        .main-lab {
            width: 75%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
        }

        .lab-title {
            font-size: 1.8rem;
            margin-bottom: 30px;
            font-weight: bold;
        }

        .lab-red { color: var(--accent-red); }

        /* THE PLAYER BOX */
        .player-frame {
            width: 90%;
            max-width: 1000px;
            aspect-ratio: 16 / 9;
            background: #000;
            border: 8px solid #1a1a1a;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        iframe {
            width: 100%;
            height: 100%;
            border-radius: 4px;
            border: none;
        }

        /* STATUS CONTROLS (Top Right) */
        .controls {
            position: absolute;
            top: 20px;
            right: 20px;
            display: flex;
            gap: 5px;
        }

        .btn-status {
            padding: 5px 12px;
            font-size: 12px;
            font-weight: bold;
            border-radius: 4px;
            border: none;
        }

        .btn-start { background: #ff0000; color: white; }
        .btn-stop { background: #ff0000; color: white; }

        /* FOOTER NAVIGATION */
        .footer-nav {
            position: absolute;
            bottom: 15px;
            right: 20px;
        }

        .footer-nav a {
            color: #aaa;
            text-decoration: none;
            font-size: 14px;
        }
    </style>
</head>
<body>

    
	<!----------------------->
	<div class="sidebar">
    <div class="directory-header">[Category] - Click a Title to Watch</div>
    <?php foreach ($videos as $v): ?>
        <a class="video-link" onclick="playVideo('<?= addslashes($v['url']) ?>')">
            <span style="color: #28a745; font-weight: bold; font-size: 0.8rem; text-transform: uppercase;">
                [<?= htmlspecialchars($v['category']) ?>]
            </span> 
            <?= htmlspecialchars($v['title']) ?>
        </a>
    <?php endforeach; ?>
</div>
	
	<!----------------------->

    <div class="main-lab">
        <div class="controls">
            <button class="btn-status btn-start">AIGC</button>
            <button class="btn-status btn-stop">Quick Vids</button>
        </div>

        <div class="lab-title">AIGC Quick VIDs <span class="lab-red">Lab</span></div>

        <div class="player-frame" id="player-area">
            <div id="placeholder-text" style="color: #444; font-size: 1.2rem;">Awaiting Video Signal...</div>
        </div>

        <div class="footer-nav">
            <a href="study-report.php">To Study Report</a>
        </div>
    </div>

    <script>
        /**
         * Vanilla JS Logic to switch videos without page refresh
         * Extract YouTube ID from URL and inject Iframe
         */
        function playVideo(rawUrl) {
            const playerArea = document.getElementById('player-area');
            let videoId = "";

            // Handle Standard and Shortened (youtu.be) URLs
            const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
            const match = rawUrl.match(regExp);

            if (match && match[2].length === 11) {
                videoId = match[2];
                playerArea.innerHTML = `
                    <iframe 
                        src="https://www.youtube.com/embed/${videoId}?autoplay=1" 
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                        allowfullscreen>
                    </iframe>`;
            } else {
                alert("Neural Link Error: Video ID not found.");
            }
        }
    </script>
</body>
</html>