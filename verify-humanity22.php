<?php
// =========================================================================
// verify-humanity22.php
// PURPOSE: PREMIUM TURING GATEWAY INTERFACE WITH INTEGRATED GEMINI cURL ENGINE
// =========================================================================
session_start();
require_once __DIR__ . '/../db-connect.php';
date_default_timezone_set('America/Los_Angeles');

// 1. AJAX/FETCH HANDSHAKE ROUTER FOR LIVE SUBMISSIONS
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['verify_human'])) {
    header('Content-Type: application/json');
    if ($_POST['verify_human'] === 'true') {
        $_SESSION['human_verified'] = true;
        echo json_encode(["status" => "redirect", "url" => "demo.php"]);
    } else {
        $_SESSION['human_verified'] = false;
        echo json_encode(["status" => "redirect", "url" => "no-human1.html"]);
    }
    exit;
}

// 2. BACKEND ENGINE: GENERATE DYNAMIC TURING COMPILATION VIA cURL
$challenge_json_payload = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['request_turing_challenge'])) {
    header('Content-Type: application/json');
    try {
        // EXTRACT API KEY FROM HOOK LEDGER
        $hook_stmt = $pdo->prepare("SELECT `api_key` FROM `api-hook` WHERE `seq_no` = '1'");
        $hook_stmt->execute();
        $hook_data = $hook_stmt->fetch(PDO::FETCH_ASSOC);
        $active_api_key = ($hook_data) ? trim($hook_data['api_key']) : "";

        if (empty($active_api_key)) {
            throw new Exception("API Key Missing");
        }

        // DESIGN STRUCTURED TURING PROMPT ARCHITECTURE
        $systemInstruction = "You are a reading couch for Gemini AI College.
Your job is to generate a single multiple-choice question designed to easily distinguish a human web developer from an automated scraper bot.
Focus the question topic on fundamental web development concepts for elementary student.

CRITICAL OUTPUT PARAMETERS:
- Format your response ONLY as a raw, flat JSON object.
- Do not wrap the JSON output in markdown formatting, code fences, or backticks.
- The JSON object must contain exactly three keys:
  1. \"q\": The challenge question string (keep it concise and distinct).
  2. \"options\": A flat JSON array containing exactly three short string options.
  3. \"ans\": The string containing the exact correct answer (must match one of the options perfectly).";

        // EXECUTE DIRECT cURL TRANSPORT LAYERING
        $ch = curl_init("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=" . $active_api_key);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
            "contents" => [["parts" => [["text" => "{$systemInstruction}\n\nGenerate unique serialization matrix challenge."]]]],
            "generationConfig" => [
                "temperature" => 0.8, 
                "responseMimeType" => "application/json",
                "maxOutputTokens" => 300
            ]
        ]));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_TIMEOUT, 8); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        $curl_err = curl_error($ch);
        curl_close($ch);

        if ($curl_err) {
            throw new Exception("cURL Transport Error");
        }

        // PARSE STRIPPED RAW TELEMETRY
        $result_data = json_decode($response, true);
        $raw_json_text = $result_data['candidates'][0]['content']['parts'][0]['text'] ?? '';
        
        // Validate structural parameters before serving to client
        $challenge_object = json_decode(trim($raw_json_text), true);
        if (!isset($challenge_object['q']) || !isset($challenge_object['options']) || !isset($challenge_object['ans'])) {
            throw new Exception("Parsing Failure");
        }

        echo json_encode([
            "status" => "success",
            "challenge" => $challenge_object
        ]);

    } catch (Exception $e) {
        // FAILSAFE: Trigger localized recovery scenario fallback parameters
        echo json_encode([
            "status" => "fallback",
            "message" => $e->getMessage()
        ]);
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Identity Gateway — Gemini AI College</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #0f172a; }
        .neubrutal-shadow { box-shadow: 8px 8px 0px 0px #000000; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4 antialiased">

    <div id="ai-guard-card" class="bg-slate-900 border-2 border-slate-800 rounded-2xl max-w-md w-full p-8 text-center transition-all duration-300 neubrutal-shadow">
        
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-rose-500/10 border border-rose-500/20 text-rose-500 mb-6 relative">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400/10 opacity-75"></span>
            <i class="fa-solid fa-fingerprint text-2xl"></i>
        </div>

        <h1 class="text-white text-xl font-black tracking-tight uppercase">AI Identity Gateway<br>AI Model:gemini-2.5-flash</h1>
        <p class="text-slate-400 text-xs mt-2 leading-relaxed">
            Bypassing legacy credentials. Confirm your human status via our live Touring verification loop.
        </p>

        <div class="bg-slate-950/60 border border-slate-800/80 rounded-xl p-5 my-6 min-h-[90px] flex items-center justify-center">
            <p id="turing-challenge" class="text-sm font-bold tracking-normal text-slate-200 leading-snug">
                Click the validation hook below to construct your unique neural challenge matrix.
            </p>
        </div>

        <div id="turing-actions" class="flex flex-col gap-2.5 min-h-[50px] items-center justify-center">
            <button onclick="triggerAiHumanCheck()" class="w-full bg-amber-400 hover:bg-amber-300 text-neutral-950 font-black py-3 rounded-xl text-xs uppercase tracking-wider transition-all transform active:scale-95 shadow shadow-amber-400/20 flex items-center justify-center gap-2">
                <i class="fa-solid fa-shield-halved"></i> Click to Request Human Validation
            </button>
        </div>

        <div class="mt-6 pt-4 border-t border-slate-800/60 flex items-center justify-between text-[10px] text-slate-500 font-medium">
            <span id="ai-guard-zone" class="uppercase">Status: Unverified</span>
            <a href="no-human.html" class="hover:text-rose-400 transition-colors uppercase tracking-wider font-bold">Abort Connection</a>
        </div>
    </div>

    <script>
        const fallbackChallenges = [
		    { q: "What is normally found swimming inside a natural freshwater lake?", options: ["Tiger", "Fish", "Bird"], ans: "Fish" },
	        { q: "Which of these items is used to protect your head from rain?", options: ["Umbrella", "Hammer", "Laptop"], ans: "Umbrella" },
            { q: "If you freeze liquid water inside a refrigerator, what does it become?", options: ["Steam", "Ice", "Wood"], ans: "Ice" },
            { q: "Select the structural logic block used for looping data:", options: ["WHILE", "VARCHAR", "INT"], ans: "WHILE" },
            { q: "Which character designates a local parameter variable in PHP?", options: ["$", "#", "@"], ans: "$" },
            { q: "Identify the asset folder used for books today:", options: ["uploads/", "vendor/", "node_modules/"], ans: "uploads/" }
        ];
        
        let activeChallenge = null;

        function triggerAiHumanCheck() {
            const challengeText = document.getElementById('turing-challenge');
            const actionsContainer = document.getElementById('turing-actions');
            
            challengeText.innerText = "Generating unique neural challenge matrix...";
            actionsContainer.innerHTML = `
                <span class="text-xs text-slate-500 animate-pulse font-semibold flex items-center gap-2">
                    <i class="fa-solid fa-spinner fa-spin text-amber-400"></i> Contacting Gemini Security Gateway...
                </span>`;

            // CALL SELF WITH SPECIFIC CONTROLLER PARAMETER TO EXECUTE EMBEDDED PHP cURL
            fetch(window.location.href, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'request_turing_challenge=true'
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    activeChallenge = data.challenge;
                } else {
                    console.warn("Gateway shifted to localized fallback matrix due to telemetry rules.");
                    activeChallenge = fallbackChallenges[Math.floor(Math.random() * fallbackChallenges.length)];
                }
                renderChallengeMatrix(challengeText, actionsContainer);
            })
            .catch(err => {
                console.error("Connection drop fallback handled:", err);
                activeChallenge = fallbackChallenges[Math.floor(Math.random() * fallbackChallenges.length)];
                renderChallengeMatrix(challengeText, actionsContainer);
            });
        }

        function renderChallengeMatrix(textContainer, buttonsContainer) {
            textContainer.innerText = activeChallenge.q;
            buttonsContainer.innerHTML = `<div class="grid grid-cols-3 gap-2 w-full">` + 
                activeChallenge.options.map(opt => `
                    <button onclick="evaluateHumanity('${opt}')" class="bg-slate-800 hover:bg-slate-200 border border-slate-700 hover:border-white text-slate-200 hover:text-slate-950 p-3 rounded-xl text-xs font-bold transition-all shadow-sm truncate">
                        ${opt}
                    </button>
                `).join('') + `</div>`;
        }

        function evaluateHumanity(selection) {
            const isHuman = (selection === activeChallenge.ans);
            const verifiedPayload = isHuman ? 'true' : 'false';

            fetch(window.location.href, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'verify_human=' + verifiedPayload
            })
            .then(res => res.json())
            .then(data => {
                if (isHuman) {
                    document.getElementById('ai-guard-zone').innerHTML = `
                        <div class="text-emerald-400 font-black uppercase flex items-center gap-1">
                            <i class="fa-solid fa-circle-check animate-pulse"></i> Human Verified
                        </div>`;
                    alert("Handshake Confirmed. Access granted to corporate ecosystem.");
                } else {
                    alert("Handshake Aborted. Identity anomalies matched algorithmic profiles.");
                }
                
                if (data.url) {
                    window.location.href = data.url;
                }
            });
        }
    </script>
</body>
</html>
