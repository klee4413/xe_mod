<?php
// [TIMESTAMP: 2026-03-27] - GAC Tri-Variable Chat Engine  
require_once __DIR__ . '/../db-connect.php';
//require_once 'db-connect.php';
$student_id = $_SESSION['user_id']    ?? 0;
$first_name = $_SESSION['first_name'] ?? 'Scholar';
$last_name  = $_SESSION['last_name']  ?? '';
$email      = $_SESSION['email']      ?? '';
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) { 
    $db_error = "Database offline."; 
}

// FIX: Only run this logic if it is an AJAX request
if (isset($_GET['ajax'])) {
    header('Content-Type: application/json');
    $input = json_decode(file_get_contents('php://input'), true);
    $query = strtolower($input['query'] ?? '');

    $response_text = "Scholar, no logic gate found for that query. Try 'Bursar', 'Lab', or 'Admin'.";

    if ($query && $pdo) {
        // TRI-VARIABLE SEARCH ARGUMENT
        $stmt = $pdo->query("SELECT * FROM qnachat_table WHERE is_active = 1");
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			//$k_arr = array_map('trim', explode(',', strtolower($row['keyword_trigger'] ?? '')));
            $k = strtolower($row['keyword_trigger'] ?? '');
            $c = strtolower($row['category'] ?? '');
            $n = strtolower($row['building_name'] ?? '');

            // Priority Logic: Keyword, Name, or Category
            if (str_contains($query, $k) || str_contains($query, $n) || str_contains($query, $c)) {
                $loc = $row['building_name'] ? " (Building #{$row['building_no']}: {$row['building_name']})" : "";
                $response_text = $row['answer'] . $loc;
                break; 
            }
        }
    }
    echo json_encode(["response" => $response_text]);
    exit; // ONLY exit during an AJAX call
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>GAC | Campus AI Assistant</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root { --gac-green: #30C89F; --gac-brick: #BC4A3C; }
        body { background-color: #0B0D10; font-family: 'Inter', sans-serif; }
        .chat-box { height: 450px; scrollbar-width: thin; scrollbar-color: #334155 #0B0D10; }
        .gac-msg { background: #1e293b; color: #f8fafc; border-radius: 12px 12px 12px 2px; border-left: 3px solid var(--gac-green); }
        .user-msg { background: var(--gac-green); color: white; border-radius: 12px 12px 2px 12px; }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-4">

    <div class="w-full max-w-md bg-slate-900 rounded-2xl shadow-2xl border border-slate-800 overflow-hidden">
        <div class="bg-slate-800 p-4 border-b border-slate-700 flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 rounded-lg bg-[#30C89F] flex items-center justify-center font-bold text-slate-900">AIGC</div>
                <h2 class="text-white font-bold text-sm">AIGC Campus Help</h2>
            </div>
            <!--span class="text-[10px] text-green-500 font-mono animate-pulse">● ONLINE</span-->
			 <a href="campus.php" class="touch-target bg-white hover:bg-slate-100 text-slate-600 font-black text-sm  tracking-wider border-2 border-slate-600 rounded-xl shadow-sm transition-all flex items-center justify-center gap-1">
                <i class="fa-solid fa-house text-xs text-emerald-700"></i> Campus
            </a>
        </div>

        <div id="chatLog" class="chat-box p-4 overflow-y-auto space-y-4">
            <div class="gac-msg p-3 text-xs leading-relaxed">
                Welcome, Scholar. Ask me about any campus building (e.g., "Where is the <strong>Bursar</strong>?" or "I need the <strong> Lab</strong>").
            </div>
        </div>

        <div class="p-4 bg-slate-800/50">
            <div class="flex space-x-2">
                <input type="text" id="userInput" placeholder="Enter keyword..." 
                       class="flex-1 bg-slate-900 border border-slate-700 rounded-lg px-4 py-2 text-white text-sm focus:ring-2 focus:ring-[#30C89F] outline-none">
                <button onclick="askGacAI()" class="bg-[#30C89F] text-slate-900 px-4 py-2 rounded-lg font-bold text-sm hover:brightness-110 transition-all">
                    Send
                </button>
            </div>
        </div>
    </div>

    <script>
        async function askGacAI() {
            const input = document.getElementById('userInput');
            const log = document.getElementById('chatLog');
            const text = input.value.trim();
            if(!text) return;

            log.innerHTML += `<div class="flex justify-end"><div class="user-msg p-3 text-xs max-w-[80%]">${text}</div></div>`;
            input.value = "";
            log.scrollTop = log.scrollHeight;

            try {
                // Ensure this fetch matches the ?ajax=1 condition in PHP
                const response = await fetch('?ajax=1', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({ query: text })
                });
                const data = await response.json();

                log.innerHTML += `<div class="gac-msg p-3 text-xs leading-relaxed animate-in fade-in duration-300">${data.response}</div>`;
                log.scrollTop = log.scrollHeight;
            } catch (e) {
                log.innerHTML += `<div class="text-red-400 text-[10px] text-center">Tell me one more time with other words.</div>`;
				//log.innerHTML += `<div class="text-red-400 text-[10px] text-center">Logic Error: Database unreachable.</div>`;
            }
        }
        document.getElementById('userInput').addEventListener('keypress', (e) => { if(e.key === 'Enter') askGacAI(); });
    </script>
</body>
</html>