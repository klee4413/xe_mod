<?php
/** * AICG PROMPT EVALUATION LAB - V1.1.0 gemini-3.1-flash-lite-preview
 * MISSION: DYNAMIC API RETRIEVAL & GCP STABILITY
 */
session_start();
require_once __DIR__ . '/../../db-connect.php'; // Ensure this file has your PDO $pdo connection
date_default_timezone_set('America/Los_Angeles');

// 1. DYNAMIC API KEY RETRIEVAL (Institutional Hook)
$apiKey = "";
try {
    // We target the most recent 'Google AI Studio' key for 'AIGC' project
    $stmt = $pdo->prepare("SELECT api_key FROM `api-hook` WHERE provider = 'Google AI Studio' ORDER BY seq_no DESC LIMIT 1");
    $stmt->execute();
    $keyRow = $stmt->fetch(PDO::FETCH_ASSOC);
    $apiKey = $keyRow['api_key'] ?? "";
} catch (PDOException $e) {
    error_log("Foundry Key Failure: " . $e->getMessage());
}

// 2. SECURE BACKEND PROXY
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['api'])) {
    header('Content-Type: application/json');
    
    if (empty($apiKey)) {
        echo json_encode(["error" => "Identity Dissonance: API Key not grounded in database."]);
        exit;
    }

    $input = json_decode(file_get_contents('php://input'), true);
    $type = $input['type'] ?? 'polish';

    $systemPrompts = [
        'polish' => "Refactor this prompt into a high-performance 'Prompt-as-Code' structure. Use Imperative language, Variable Placeholders [CONTEXT], and Logic Gates.",
        'ambiguity' => "Focus: Ambiguity Check. Identify 'fuzzy' words (some, maybe) and suggest precise alternatives.",
        'strength' => "Focus: Strength Test. Analyze command authority. Score 'Strong' vs 'Weak' directives.",
        'syntactic' => "Focus: Syntactic Test. Review governing structure and variable coverage.",
        'constraint' => "Focus: Constraint Strength. Evaluate rules/exclusions to prevent hallucinations.",
        'format' => "Focus: Format Specification. Verify if output schema (JSON/Table) is explicitly defined.",
        'logic' => "Focus: Workflow Logic. Analyze Chain-of-Thought path for circular dependencies."
    ];

    $promptText = "SYSTEM INSTRUCTION: " . $systemPrompts[$type] . "\n\nPAYLOAD TO ANALYZE:\n" . $input['prompt'];

    $payload = [
        "contents" => [["parts" => [["text" => $promptText]]]],
        "generationConfig" => ["temperature" => 0.4, "maxOutputTokens" => 600]
    ];

    // High-Velocity Handshake with Gemini-3-Flash
    $ch = curl_init("https://generativelanguage.googleapis.com/v1beta/models/gemini-3.1-flash-lite-preview:generateContent?key=" . $apiKey);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

    $response = curl_exec($ch);
    echo $response;
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
	<link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AICG | Prompt Evaluation Lab G3.1fl-0.4-600</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;700&display=swap');
        body { font-family: 'Inter', sans-serif; background-color: #0F1117; color: #E2E8F0; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; border-radius: 10px; }
        /* Professional Lab Transitions */
        .eval-pane { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
    </style>
</head>
<body class="h-screen flex flex-col overflow-hidden">

    <header class="h-16 border-b border-slate-800 flex items-center justify-between px-6 bg-[#161922] shrink-0">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center shadow-lg shadow-indigo-500/20">
                <i data-lucide="sparkles" class="w-5 h-5 text-white"></i>
            </div>
            <h1 class="text-lg font-bold tracking-tight text-white uppercase">AI Gemini College <span class="text-sm text-indigo-100"> - Prompt Generation & Evaluation Lab with gemini-3.1-flash-lite-preview, temperature=0.4, token counts=600</span></h1>
        </div>
        <div class="flex items-center gap-4">
            <a href="campus.php" class="text-[10px] font-black text-slate-500 hover:text-white uppercase tracking-widest mr-4">← To Campus</a>
            <button onclick="location.reload()" class="p-2 hover:bg-slate-800 rounded-md text-slate-400 transition-all" title="Reset Lab">
                <i data-lucide="trash-2" class="w-5 h-5"></i>
            </button>
            <button onclick="runApiCall('polish')" class="flex items-center gap-2 px-6 py-2 bg-indigo-600 hover:bg-indigo-500 rounded-md text-sm font-black uppercase tracking-widest transition-all shadow-lg shadow-indigo-500/20">
                <i data-lucide="zap" class="w-4 h-4"></i>
                <span><class="text-sm text-white-100">Polish by Linter</span>
            </button>
        </div>
    </header>

    <main class="flex-1 flex overflow-hidden">
        
        <aside class="w-72 border-r border-slate-800 bg-[#161922] flex flex-col shrink-0">
            <div class="p-4 border-b border-slate-800 bg-[#1A1D27]/50">
                <h2 class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-500">Evaluation Path</h2>
            </div>
            <nav class="flex-1 overflow-y-auto p-2 space-y-1 custom-scrollbar">
                <?php
                $menu = [
                    ['id' => 'ambiguity', 'title' => 'Ambiguity Check', 'desc' => 'Find fuzzy words that confuse AI', 'icon' => 'search'],
                    ['id' => 'strength', 'title' => 'Strength Test', 'desc' => 'Strong vs Weak words analysis', 'icon' => 'zap'],
                    ['id' => 'syntactic', 'title' => 'Syntactic Test', 'desc' => 'Sentence structure & grammar', 'icon' => 'code'],
                    ['id' => 'constraint', 'title' => 'Constraint Strength', 'desc' => 'Firmness of rules & exclusions', 'icon' => 'shield-alert'],
                    ['id' => 'format', 'title' => 'Format Spec', 'desc' => 'Output structure clarity', 'icon' => 'layout'],
                    ['id' => 'logic', 'title' => 'Workflow Logic', 'desc' => 'Chain-of-Thought linear path', 'icon' => 'git-branch'],
                ];
                foreach ($menu as $item): ?>
                <button onclick="runApiCall('<?= $item['id'] ?>')" class="w-full text-left p-4 rounded-xl hover:bg-indigo-600/10 text-slate-400 group transition-all border border-transparent hover:border-indigo-500/30">
                    <div class="flex items-center gap-3">
                        <i data-lucide="<?= $item['icon'] ?>" class="w-5 h-5 text-slate-500 group-hover:text-indigo-400"></i>
                        <div>
                            <div class="text-xs font-black text-slate-200 uppercase tracking-tighter"><?= $item['title'] ?></div>
                            <div class="text-[9px] opacity-50 font-medium"><?= $item['desc'] ?></div>
                        </div>
                    </div>
                </button>
                <?php endforeach; ?>
            </nav>
        </aside>

        <div class="flex-1 flex flex-col overflow-hidden">
            <section class="h-1/2 border-b border-slate-800 p-6 flex flex-col">
                <div class="flex items-center justify-between mb-3 px-1">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 flex items-center gap-2">
                        <i data-lucide="terminal" class="w-3 h-3"></i> Human Prompt Entry
                    </label>
                    <span id="char-stats" class="text-[10px] text-white-600 font-mono italic">0 CHARS | 0 WORDS</span>
                </div>
                <textarea id="promptInput" oninput="updateStats()" 
                    class="flex-1 bg-[#1A1D27] border-2 border-slate-800 rounded-2xl p-6 font-mono text-sm focus:outline-none focus:border-indigo-500/50 resize-none transition-all placeholder:text-slate-700"
                    placeholder="// Input your natural language payload here..."></textarea>
            </section>

            <section class="h-1/2 p-6 flex flex-col bg-[#0B0D12]">
                <div class="flex items-center justify-between mb-3 px-1">
                    <label class="text-[10px] font-black uppercase tracking-widest text-indigo-400 flex items-center gap-2">
                        <i data-lucide="sparkles" class="w-3 h-3"></i> AI Tutor Insights: Polished Prompt
                    </label>
                    <button id="copy-btn" onclick="copyToClipboard()" class="hidden text-[9px] text-emerald-400 font-black hover:text-white uppercase tracking-[0.2em] transition-all">
                        Copy to Clipboard
                    </button>
                </div>
                <div class="flex-1 bg-[#1A1D27] border-2 border-slate-800 rounded-2xl p-6 overflow-y-auto relative custom-scrollbar">
                    <div id="polish-loader" class="hidden absolute inset-0 flex flex-col items-center justify-center gap-3 bg-[#1A1D27]/95 backdrop-blur-sm z-50">
                        <div class="w-12 h-12 border-4 border-indigo-500/20 border-t-indigo-500 rounded-full animate-spin"></div>
                        <p class="text-[10px] text-slate-400 font-black tracking-widest uppercase">Refining Logic Foundry...</p>
                    </div>
                    <div id="polished-content" class="prose prose-invert prose-indigo max-w-none font-mono text-slate-400 text-sm leading-relaxed">
                        <div class="h-full flex flex-col items-center justify-center opacity-20 py-12 select-none">
                            <i data-lucide="box" class="w-10 h-10 mb-4 text-slate-500"></i>
                            <p class="text-xs uppercase font-black tracking-widest">Awaiting Execution</p>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <aside class="w-[450px] border-l border-slate-800 bg-[#161922] flex flex-col shrink-0">
            <div class="p-4 border-b border-slate-800 bg-[#1A1D27]">
                <h2 class="text-[10px] font-black uppercase tracking-widest text-slate-500 flex items-center gap-2">
                    <i data-lucide="bar-chart-3" class="w-3 h-3"></i> Output: Evaluation Result
                </h2>
            </div>
            <div id="result-pane" class="flex-1 overflow-y-auto p-8 custom-scrollbar bg-[#11141D]">
                <div id="empty-state" class="h-full flex flex-col items-center justify-center text-slate-700 opacity-40 text-center space-y-4">
                    <i data-lucide="microscope" class="w-12 h-12"></i>
                    <p class="text-[10px] font-black uppercase tracking-widest leading-tight">Select Evaluation Path<br>to analyze payload</p>
                </div>
                <div id="active-result" class="hidden animate-in fade-in slide-in-from-right-4 duration-500">
                    <div class="flex items-center gap-3 text-indigo-400 mb-8 border-b-2 border-slate-800 pb-4">
                        <i id="res-icon" data-lucide="activity" class="w-6 h-6"></i>
                        <h3 id="res-title" class="text-lg font-black text-white uppercase tracking-tighter italic">Analysis</h3>
                    </div>
                    <div id="res-body" class="prose prose-invert prose-sm max-w-none text-slate-300 leading-relaxed"></div>
                </div>
            </aside>
        </div>
    </main>

    <footer class="h-8 border-t border-slate-800 bg-[#161922] px-6 flex items-center justify-between text-[9px] text-slate-500 font-mono shrink-0 uppercase tracking-widest">
        <div class="flex gap-4">
            <span class="flex items-center gap-2 font-black"><span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span> Foundry Active</span>
            <span class="border-l border-slate-800 pl-4">Engine: Gemini-1.5-Flash</span>
        </div>
        <span>&copy; 2026 GAC PROMPT EVALUATION LAB | AMOS LEE</span>
    </footer>

    <script>
        // 1. STATS ENGINE (Real-time monitoring)
        function updateStats() {
            const val = document.getElementById('promptInput').value.trim();
            const charCount = val.length;
            const wordCount = val ? val.split(/\s+/).length : 0;
            document.getElementById('char-stats').innerText = `${charCount} CHARS | ${wordCount} WORDS`;
        }

        // 2. SOVEREIGN API DISPATCHER
        async function runApiCall(type) {
            const input = document.getElementById('promptInput').value.trim();
            if (!input) return alert("System Instruction: Input Payload Required.");

            const isPolish = (type === 'polish');
            const resultArea = isPolish ? document.getElementById('polished-content') : document.getElementById('res-body');
            
            // UI Handshake
            if (!isPolish) {
                document.getElementById('empty-state').classList.add('hidden');
                document.getElementById('active-result').classList.remove('hidden');
                document.getElementById('res-body').innerHTML = `<div class="animate-pulse space-y-4">
                    <div class="h-4 bg-slate-800 rounded w-3/4"></div>
                    <div class="h-4 bg-slate-800 rounded w-1/2"></div>
                </div>`;
                document.getElementById('res-title').innerText = type.toUpperCase() + " ANALYSIS";
            } else {
                document.getElementById('polish-loader').classList.remove('hidden');
            }

            try {
                const response = await fetch('?api=true', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ type: type, prompt: input })
                });
                
                const data = await response.json();
                
                if (data.error) throw new Error(data.error);
                
                const resultText = data.candidates[0].content.parts[0].text;
                
                if (isPolish) {
                    resultArea.innerHTML = marked.parse(resultText);
                    document.getElementById('copy-btn').classList.remove('hidden');
                    window.lastPolished = resultText;
                } else {
                    resultArea.innerHTML = marked.parse(resultText);
                }
            } catch (e) {
                console.error(e);
                alert("Neural Link Failure: " + e.message);
            } finally {
                document.getElementById('polish-loader').classList.add('hidden');
                lucide.createIcons();
            }
        }

        function copyToClipboard() {
            if (window.lastPolished) {
                navigator.clipboard.writeText(window.lastPolished);
                const btn = document.getElementById('copy-btn');
                btn.innerText = "COPIED TO CLIPBOARD!";
                setTimeout(() => btn.innerText = "COPY TO CLIPBOARD", 2000);
            }
        }

        // Initialize Sovereign Icons
        lucide.createIcons();
    </script>
</body>
</html>