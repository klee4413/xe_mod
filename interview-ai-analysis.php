<?php
// =========================================================================
// AIGC FOUNDRY ENGINE — ADMISSION ANALYTICS : interview-ai-analysis.php
// ARCHITECTURE: DEEP COGNITIVE ANALYSIS PIPELINE ENGINE
// =========================================================================
session_start();
// 0. AUTHENTICATION & DEMOGRAPHIC FALLBACK BOUNDARIES
$student_id = $_SESSION['user_id']    ?? 9990;
$first_name = $_SESSION['first_name'] ?? 'Scholar';
$last_name  = $_SESSION['last_name']  ?? '';
$email      = $_SESSION['email']  ?? '';
 
require_once __DIR__ . '/../db-connect.php'; // Reusing your secure $pdo connection

date_default_timezone_set('America/Los_Angeles');
//require_once 'l-link.php';
// 1. API KEY HANDSHAKE RETRIEVAL
$hook_stmt = $pdo->prepare("SELECT `api_key` FROM `api-hook` WHERE `seq_no` = '1'");
$hook_stmt->execute();
$hook_data = $hook_stmt->fetch();
$active_api_key = ($hook_data) ? $hook_data['api_key'] : "";

// 2. RETRIEVE ALL RAW DATA FOR GEMINI INGESTION
try {
    // Collect Selected Data
    $selected_rows = $pdo->query("SELECT * FROM temp_selected_factors ORDER BY rating_avg DESC")->fetchAll(PDO::FETCH_ASSOC);
    // Collect Unselected Data
    $unselected_rows = $pdo->query("SELECT * FROM temp_unselected_factors ORDER BY rating_avg DESC")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Foundry Memory Interruption: Ensure temporary data tables are currently populated. Error: " . $e->getMessage());
}

// 3. API BRIDGE ROUTER (Triggered via Asynchronous JS Fetch)
if (isset($_GET['action']) && $_GET['action'] === 'run_analysis') {
    header('Content-Type: application/json');
    
    if (empty($active_api_key)) {
        echo json_encode(['error' => 'API Configuration Fault: Key validation missing in api-hook.']);
        exit;
    }

    // Format the prompt data payload into plain text for the model context window
    $selected_text = "";
    foreach($selected_rows as $r) {
        $selected_text .= "- {$r['attribute']}: Count={$r['selection_count']}, Avg Rating={$r['rating_avg']}\n";
    }
    
    $unselected_text = "";
    foreach($unselected_rows as $r) {
        $unselected_text .= "- {$r['attribute']}: Count={$r['unselected_count']}, Avg Rating={$r['rating_avg']}\n";
    }

    $systemInstruction = "You are the Senior Admissions Officer at AI Gemini College. Analyze this psychometric and performance data for an applicant to determine if they possess high AI Study Adaptability and a positive mindset toward automated self-study platforms (No login, asynchronous environment). 

    Provide your final assessment exactly inside these clean, headerless, highly professional markdown blocks:
    ### 1. CORE COGNITIVE STRENGTHS
    [Analyze the highest selected ratings like Intellectual Humility and Honesty and how they benefit self-directed learning]

    ### 2. INTERFACE MINDSET & BIAS ANOMALIES
    [Analyze anomalies like high Failure processing vs very low Winning scores and what it means for working with AI systems]

    ### 3. EXECUTIVE ADMISSION VERDICT
    [Give an explicit, definitive, clear recommendation for admission with recommended placement]";

    $userPrompt = "Applicant Raw Metrics Profile:\n\nSELECTED FACTORS:\n{$selected_text}\n\nUNSELECTED FACTORS:\n{$unselected_text}";

    // Using the stable, production-ready general available gemini-3.0-pro  gemini-3.1-flash-lite-preview gemini-2.5-flash
    $ch = curl_init("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=" . trim($active_api_key));
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        
        // Ensure local server network stack ignores IPv6 resolution errors
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
            "contents" => [["parts" => [["text" => "{$systemInstruction}\n\n{$userPrompt}"]]]],
            "generationConfig" => [
                "temperature" => 0.4,       // Balances analytical insight with strict structural compliance
                "maxOutputTokens" => 800,   // Exact required token limit boundary
                
                // CRITICAL FIX: Stops the model from burning tokens on an internal reasoning essay
                "thinkingConfig" => [
                    "thinkingBudget" => 0
                ]
            ]
        ]));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

    
    // AIGC FOUNDRY TIMEOUT SHIELDS
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);         
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);  
    
    $response = curl_exec($ch);
    $err = curl_error($ch);
    curl_close($ch);
    
    if ($err) {
        echo json_encode(['error' => 'Neural Transmission Handshake Failed: ' . $err]);
    } else {
        echo $response;
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AIGC | AI Interview Core Analysis</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { background-color: #020617; color: #f8fafc; font-family: 'Inter', sans-serif; }
        .glass-panel { background: rgba(15, 23, 42, 0.7); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.05); }
        .ai-terminal { font-family: 'Fira Code', 'Courier New', monospace; background: #000000; color: #39ff14; }
    </style>
</head>
<body class="min-h-screen flex flex-col justify-between">

    <header class="w-full max-w-7xl mx-auto mt-4 px-4 md:px-8 py-4 bg-[#000080] border-b-2 border-[#ec4899] flex justify-between items-center shadow-2xl rounded-2xl">
        <div>
            <h1 class="text-xl md:text-2xl font-black italic uppercase tracking-tighter text-white">
                AIGC ADMISSION INTERVIEW ANALYSIS & DECISION SUPPORT INTELLIGENCE PANEL
            </h1>
            <p class="text-[12px] text-emerald-400 font-mono tracking-widest uppercase mt-0.5">
                Analytic Logic Engine: Automated Decision Support System by AI Gemini College.
            </p>
        </div>
        <a href="campus.php" class="bg-emerald-600 hover:bg-pink-700 text-white px-4 py-2 rounded-xl font-black uppercase text-xs transition-all flex items-center gap-2">
            <i class="fa-solid fa-house-chimney"></i> Campus
        </a>
    </header>

    <main class="w-full max-w-7xl mx-auto px-4 py-8 grid grid-cols-1 lg:grid-cols-3 gap-8 items-start flex-grow">
        
        <section class="lg:col-span-1 space-y-6">
            <div class="glass-panel p-5 rounded-2xl space-y-4">
                <h2 class="text-xs font-black uppercase tracking-wider text-emerald-500 border-b border-slate-800 pb-2">
                    <i class="fa-solid fa-database"></i> Live Ingested Datastream
                </h2>
                
                <div class="space-y-4">
                    <div>
                        <span class="text-[10px] font-black uppercase text-slate-400 tracking-wider block mb-2">Selected Performance Summary</span>
                        <div class="space-y-1.5 max-h-48 overflow-y-auto border border-slate-800 rounded-xl p-2 bg-slate-950/40">
                            <?php foreach($selected_rows as $row): ?>
                                <div class="flex justify-between items-center text-xs border-b border-slate-900 pb-1 last:border-0">
                                    <span class="text-slate-300 font-medium"><?php echo $row['attribute']; ?></span>
                                    <span class="font-mono font-bold text-emerald-400"><?php echo number_format($row['rating_avg'], 2); ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div>
                        <span class="text-[10px] font-black uppercase text-slate-400 tracking-wider block mb-2">Unselected Control Summary</span>
                        <div class="space-y-1.5 max-h-48 overflow-y-auto border border-slate-800 rounded-xl p-2 bg-slate-950/40">
                            <?php foreach($unselected_rows as $row): ?>
                                <div class="flex justify-between items-center text-xs border-b border-slate-900 pb-1 last:border-0">
                                    <span class="text-slate-400"><?php echo $row['attribute']; ?></span>
                                    <span class="font-mono font-bold text-slate-500"><?php echo number_format($row['rating_avg'], 2); ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <button onclick="executeAiAnalysis()" class="w-full bg-green-600 hover:bg-green-500 text-white font-black py-3.5 rounded-xl text-xs uppercase tracking-widest shadow-xl transition-all flex items-center justify-center gap-2">
                    <i class="fa-solid fa-brain"></i> Click for AI Analysis
                </button>

                <!-- REALIGNMENT: INTERVIEWEE NAME AND DECISION MATRICES MOVED TO SIDEBAR -->
                <div class="pt-4 border-t border-slate-800 space-y-4">
                    <div class="text-xl font-black text-white leading-none">
                        Name: <?php echo htmlspecialchars($last_name ?: 'Doe'); ?>
                    </div>

                    <div class="flex items-center gap-4 bg-slate-950/60 border border-slate-800 px-4 py-3 rounded-xl justify-between">
                        <label class="inline-flex items-center gap-1.5 cursor-pointer group">
                            <input type="radio" name="admission_status" value="accepted" checked
                                class="w-4 h-4 text-emerald-600 bg-slate-900 border-slate-700 focus:ring-emerald-500 focus:ring-offset-slate-900 focus:ring-2">
                            <span class="text-[10px] font-black uppercase tracking-wider text-slate-300 group-hover:text-white transition-colors">Accepted</span>
                        </label>
                        
                        <label class="inline-flex items-center gap-1.5 cursor-pointer group">
                            <input type="radio" name="admission_status" value="pending"
                                class="w-4 h-4 text-emerald-600 bg-slate-900 border-slate-700 focus:ring-emerald-500 focus:ring-offset-slate-900 focus:ring-2">
                            <span class="text-[10px] font-black uppercase tracking-wider text-slate-400 group-hover:text-white transition-colors">Pending</span>
                        </label>
                        
                        <label class="inline-flex items-center gap-1.5 cursor-pointer group">
                            <input type="radio" name="admission_status" value="reject"
                                class="w-4 h-4 text-emerald-600 bg-slate-900 border-slate-700 focus:ring-emerald-500 focus:ring-offset-slate-900 focus:ring-2">
                            <span class="text-[10px] font-black uppercase tracking-wider text-slate-400 group-hover:text-white transition-colors">Reject</span>
                        </label>
                    </div>

                    <button onclick="executeNoticeDispatch()" class="w-full bg-emerald-600 hover:bg-emerald-500 text-white font-black py-3 rounded-xl text-xs uppercase tracking-widest shadow-xl transition-all flex items-center justify-center gap-2">
                        <i class="fa-solid fa-paper-plane text-[10px]"></i> Send Notice
                    </button>
                </div>
            </div>
        </section>

        <section class="lg:col-span-2 h-full flex flex-col justify-stretch">
            <div class="flex justify-between items-center px-2 mb-2">
                <span class="text-[10px] font-black uppercase text-emerald-500 tracking-widest">Synthesized Admission Decision Support Result</span>
                <span class="text-[9px] bg-slate-900 text-slate-400 font-mono px-2 py-0.5 rounded border border-pink-800">Status: Idle</span>
            </div>
            
            <div id="analysis-viewport" class="w-full flex-grow glass-panel rounded-3xl p-6 md:p-8 overflow-auto text-sm whitespace-pre-wrap border border-slate-800 shadow-2xl min-h-[450px]">
                <div class="text-slate-500 italic text-center py-24">
                    <i class="fa-solid fa-circle-nodes text-3xl block mb-3 opacity-30 animate-pulse"></i>
                    Awaiting authorization gateway link execution. Click 'Click for AI Analysis' to map metrics into semantic layers.
                </div>
            </div>
        </section>
 
        <footer class="w-full lg:col-span-3 border-t border-slate-900 py-4 px-4 flex flex-col sm:flex-row justify-between items-center gap-2">
            <p class="text-[7px] font-black text-gray-300 uppercase tracking-widest italic sm:ml-auto">
                © 2026 AI GEMINI COLLEGE
            </p>
        </footer>

    </main>
 
    <script>
        function executeNoticeDispatch() {
            const selectedStatus = document.querySelector('input[name="admission_status"]:checked').value;
            alert(`Foundry Dispatch initiated. Transmission pipeline status set to: ${selectedStatus.toUpperCase()}`);
            // Your processing logic for sending notifications loops here
        }
        
        async function executeAiAnalysis() {
            const viewport = document.getElementById('analysis-viewport');
            
            // Clean visual footprint into initialization loader state
            viewport.innerHTML = `
                <div class="flex flex-col items-center justify-center py-24 space-y-4">
                    <div class="animate-spin text-pink-500 text-3xl"><i class="fa-solid fa-spinner"></i></div>
                    <div class="text-pink-400 font-black text-xs uppercase tracking-widest font-mono">
                        Executing Neural Ingestion... Mapping Metrics Array
                    </div>
                </div>
            `;

            try {
                const res = await fetch('?action=run_analysis');
                const data = await res.json();
                
                if (data.error) throw new Error(data.error);

                // Safely isolate the returned string text content
                let aiOutput = data.candidates[0].content.parts[0].text;
                
                // Stripping markdown wrapper parameters cleanly
                aiOutput = aiOutput.replace(/```[a-z]*\n/g, '').replace(/```/g, '');
                
                // Format headings beautifully inside the Cake Layer rendering framework
                viewport.innerHTML = `<div class="prose prose-invert max-w-none text-slate-300 space-y-4">${formatOutputMarkdown(aiOutput)}</div>`;

            } catch (e) {
                console.error(e);
                viewport.innerHTML = `
                    <div class="p-4 bg-red-950/40 border border-red-500/20 text-red-400 rounded-xl font-bold uppercase text-xs font-mono tracking-wide">
                        <i class="fa-solid fa-triangle-exclamation mr-2"></i> Handshake Execution Failed: ${JSON.stringify(e)}
                    </div>
                `;
            }
        }

        // Lightweight clean renderer for output presentation headers
        function formatOutputMarkdown(text) {
            return text
                .replace(/^### (.*$)/gim, '<h3 class="text-sm font-black uppercase text-pink-400 tracking-wider mt-6 mb-2 border-b border-slate-800/60 pb-1 flex items-center gap-2"><i class="fa-solid fa-chevron-right text-[10px]"></i> $1</h3>')
                .replace(/\*\*(.*?)\*\*/g, '<strong class="text-white font-bold">AI</strong>');
        }
    </script>
</body>
</html>