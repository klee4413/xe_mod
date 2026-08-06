<?php
// =========================================================================
// AIGC COGNITIVE FOUNDRY — PLATFORM AGENT LAYER : ai-news1.php
// PURPOSE: AUTOMATED GOOGLE AI NEWS AGENT & REPOSITORY UPDATE CONTROLLER
// CONFIG: HARDENED DYNAMIC ROUTING & LOW-LATENCY INFRASTRUCTURE
// =========================================================================
session_start();
require_once __DIR__ . '/../db-connect.php';
date_default_timezone_set('America/Los_Angeles');

// 1. ASYNC BACKGROUND STREAM INTERCEPTOR
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'fetch_ai_news') {
    header('Content-Type: application/json');
    
    // REVISED: Pull BOTH required parameter columns from the infrastructure hook table
    $hook_stmt = $pdo->prepare("SELECT `api_key`, `key_name` FROM `api-hook` WHERE `seq_no` = '1'");
    $hook_stmt->execute();
    $hook_data = $hook_stmt->fetch(PDO::FETCH_ASSOC);
    
    $active_api_key = ($hook_data) ? trim($hook_data['api_key']) : "";
    $active_model   = ($hook_data && !empty($hook_data['key_name'])) ? trim($hook_data['key_name']) : "gemini-2.5-flash";

    if (empty($active_api_key)) {
        echo json_encode(['status' => 'error', 'message' => 'Foundry Exception: API key missing from `api-hook` table.']);
        exit;
    }

    try {
        // Hardened Prompt Constraints: Allows flexible token math to prevent trailing structural parsing typos
        $systemInstruction = "You are an automated real-time AI news reporter for AI Gemini College. 
Your task is to compile a news record entry regarding recent breakthrough developments in Google AI (such as Gemini updates, NotebookLM, or Google Cloud Vertex AI) for May 2026.

Format your response ONLY as a clean, flat, valid JSON object. Do not wrap the output in markdown code blocks or backtick fences.

The JSON object must contain exactly these 4 keys:
1. \"title\": A concise, catchy headline starting exactly with the prefix 'NEWS BY AI: ' followed by a 5 to 7 word summary.
2. \"timestamp\": The current system date/time string formatted exactly as YYYY-MM-DD HH:MM:SS.
3. \"description\": A high-level, comprehensive professional analysis summary of this specific news event (approximately 90 to 100 words long).
4. \"url\": A short, unclickable source domain path string (e.g., 'blog.google/gemini-update').";

        $userPrompt = "Run telemetry sync. Serialize the latest breaking Google AI deployment news entry into the raw JSON matrix schema.";
 
        // FIX 1: Cleaned the clipboard link garbage from the endpoint URL
        $ch = curl_init("https://generativelanguage.googleapis.com/v1beta/models/" . $active_model . ":generateContent?key=" . $active_api_key);
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        
        // FIX 2: Force IPv4 Resolution to bypass live cloud server routing bugs
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
            "contents" => [["parts" => [["text" => "{$systemInstruction}\n\n{$userPrompt}"]]]],
            "generationConfig" => [
                "temperature" => 0.25,
                "responseMimeType" => "application/json",
                "maxOutputTokens" => 1000, // Safe headroom allocation
                // FIX 3: Disable reasoning essay loops to keep response latency under 1 second
                "thinkingConfig" => [
                    "thinkingBudget" => 0
                ]
            ]
        ]));
        
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        $curl_err = curl_error($ch);
        curl_close($ch);

        if ($curl_err) throw new Exception("Neural Pipeline Transport Fail: " . $curl_err);

        $result_data = json_decode($response, true);
        if (!isset($result_data['candidates'][0]['content']['parts'][0]['text'])) {
            throw new Exception("API Network Sync Error. Connection channel timed out.");
        }

        $raw_json_text = $result_data['candidates'][0]['content']['parts'][0]['text'];
        $news_object = json_decode(trim($raw_json_text), true);

        if (!is_array($news_object) || !isset($news_object['title']) || !isset($news_object['description'])) {
            throw new Exception("Telemetry Parse Failure: Payload didn't match structural matrix standards.");
        }
 
        // FIX 4: Assigned title payload safely to avoid undefined variable drops
        $title_payload = trim($news_object['title']);
        $body_payload = "Timestamp: " . $news_object['timestamp'] . "\n\n" . 
                        "Description: " . $news_object['description'] . "\n\n" . 
                        "Source Link: " . $news_object['url'];

        // Sync persistence update sequence to target row #11
        $update_stmt = $pdo->prepare("UPDATE `webbooks` SET `chname7` = ?, `chapter7` = ? WHERE `id` = 11");
        $update_stmt->execute([$title_payload, $body_payload]);

        echo json_encode([
            'status' => 'success',
            'title' => $title_payload,
            'timestamp' => $news_object['timestamp'],
            'description' => $news_object['description'],
            'url' => $news_object['url']
        ]);

    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>GAC | Recent AI News</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { background-color: #bdf2bd; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; }
        .blueprint-frame { border: 4px solid #1e3a8a; box-shadow: 0 10px 25px rgba(30, 58, 138, 0.15); }
    </style>
</head>
<body class="min-h-screen p-3 md:p-6 flex flex-col justify-between items-center">

    <main class="w-full max-w-xl mx-auto bg-white rounded-3xl p-5 md:p-6 blueprint-frame my-auto space-y-5">
        
        <header class="text-center border-b-2 border-slate-100 pb-3">
            <h1 class="text-xl md:text-2xl font-black text-slate-900 tracking-tight uppercase">
                Recent AI News: Model gemini-2.5-flash
            </h1>
            <p class="text-[9px] font-mono font-bold text-blue-900 uppercase tracking-widest mt-0.5">
                Google AI Automation Stream Gateway
            </p>
        </header>

        <div id="news-viewport-pane" class="space-y-4">
            
            <div id="news-loading-state" class="p-8 text-center space-y-3 animate-pulse">
                <i class="fa-solid fa-satellite-dish text-4xl text-blue-800 animate-bounce"></i>
                <p class="text-xs font-black uppercase text-slate-800 tracking-wider">Polling Google AI News Nodes...</p>
                <p class="text-[10px] text-slate-500 max-w-xs mx-auto leading-normal">Synchronizing secure keys, mining recent discourse timelines, and populating repository ledger fields.</p>
            </div>

            <div id="news-content-display" class="hidden space-y-4">
                
                <div class="flex items-center gap-1.5 text-slate-500 font-mono text-[11px] font-bold border-b border-dashed border-slate-200 pb-1">
                    <i class="fa-solid fa-clock text-blue-800"></i>
                    <span id="output-timestamp"></span>
                </div>

                <h2 id="output-title" class="text-base font-black text-slate-900 uppercase tracking-tight leading-snug"></h2>

                <p id="output-description" class="text-slate-700 text-xs leading-relaxed font-medium text-justify bg-slate-50 p-3 rounded-xl border border-slate-100 shadow-inner"></p>

                <div class="bg-slate-100 p-2.5 rounded-xl border border-slate-200 flex items-center justify-between text-[11px] font-mono text-slate-600 select-none">
                    <span class="font-bold text-slate-500"><i class="fa-solid fa-link text-xs"></i> Source:</span>
                    <span id="output-url" class="font-semibold text-slate-700 select-all"></span>
                </div>
            </div>
        </div>

        <div class="border-t border-slate-100 pt-3 flex items-center justify-between gap-2">
            <span class="text-[9px] font-black text-emerald-800 tracking-widest uppercase">
                AI GEMINI COLLEGE
            </span>
            <a href="demo.php" class="h-9 px-4 bg-slate-900 hover:bg-slate-800 text-white font-black text-xs uppercase tracking-wider rounded-xl transition-all shadow flex items-center justify-center gap-1">
                <i class="fa-solid fa-house text-[10px]"></i> Portal Home
            </a>
        </div>
    </main>

    <footer class="w-full max-w-xl mx-auto pt-2 flex justify-between text-[8px] font-mono text-emerald-900/80 font-bold uppercase tracking-wider">
        <span>Target Hook: ID=11 Table: webbooks</span>
        <span>Compiled Workspace &bull; 2026</span>
    </footer>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const loader = document.getElementById('news-loading-state');
            const contentBlock = document.getElementById('news-content-display');
            const pane = document.getElementById('news-viewport-pane');

            fetch('?action=fetch_ai_news', {
                method: 'POST'
            })
            .then(res => res.json())
            .then(data => {
                loader.remove();

                if (data.status === 'success') {
                    document.getElementById('output-timestamp').innerText = data.timestamp;
                    document.getElementById('output-title').innerText = data.title;
                    document.getElementById('output-description').innerText = data.description;
                    document.getElementById('output-url').innerText = data.url;
                    contentBlock.classList.remove('hidden');
                } else {
                    const errBox = document.createElement('div');
                    errBox.className = "p-4 bg-red-50 border-2 border-red-800 rounded-xl text-red-900 font-mono text-xs space-y-1";
                    errBox.innerHTML = `
                        <div class="font-black uppercase text-red-800"><i class="fa-solid fa-circle-exclamation"></i> Sync Interrupted</div>
                        <p class="font-semibold">${data.message}</p>
                    `;
                    pane.appendChild(errBox);
                }
            })
            .catch(err => {
                loader.remove();
                const failBox = document.createElement('div');
                failBox.className = "p-4 bg-red-50 border-2 border-red-800 rounded-xl text-red-900 font-mono text-xs";
                failBox.innerHTML = `<strong>Network Fault Trace:</strong> ${err.message}`;
                pane.appendChild(failBox);
            });
        });
    </script>
</body>
</html>
