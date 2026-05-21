<?php
// AIGC FOUNDRY: Multi-Language Laboratory v4.1  lab-multi-lang1.php
require_once 'db-connect.php'; //DATABASE ID AND PASSWORD EXTRACTED SECURELY through app db-connect.php 
                               //which can be used localhost and google cloud server
// Use backticks around the table name to handle the hyphen
// API KEY FETCHES SECURELY FROM DB
$hook_stmt = $pdo->prepare("SELECT `api_key` FROM `api-hook` WHERE `seq_no` = '1'");
$hook_stmt->execute();
$hook_data = $hook_stmt->fetch();
$active_api_key = ($hook_data) ? $hook_data['api_key'] : "";
$first_name = $_SESSION['first_name'] ?? 'Scholar';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>AIGC | Programming Language Lab</title>
    <link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lucide/0.263.0/lucide.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/marked/9.1.2/marked.min.js"></script>
    <style>
        :root { --gac-navy: #000080; --gac-pink: #ec4899; }
        body { background: #020617; color: #f8fafc; font-family: 'Inter', sans-serif; height: 100vh; overflow: hidden; }
        .glass-panel { background: rgba(15, 23, 42, 0.8); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.1); }
        .neon-glow { box-shadow: 0 0 15px rgba(236, 72, 153, 0.3); }
        textarea { font-family: 'Fira Code', monospace; resize: none; }
        iframe { background: white; width: 100%; height: 100%; border-radius: 8px; }
    </style>
</head>
<body class="flex flex-col">

    <header class="bg-[#000080] border-b-2 border-[#ec4899] py-4 px-8 flex justify-between items-center">
        <div class="flex items-center gap-4">
            <div class="bg-pink-500 p-2 rounded-lg neon-glow">
                <i data-lucide="terminal" class="text-white w-6 h-6"></i>
            </div>
            <div>
                <h1 class="text-2xl font-black italic uppercase tracking-tighter text-white">
                    AIGC <span class="text-pink-400">Programming Language Lab</span>
                </h1>
                <p class="text-[10px] uppercase tracking-[0.2em] text-blue-300">Ai Gemini College | First Learner Challenge Edition</p>
            </div>
        </div>
        
        <div class="flex bg-slate-900/50 p-1 rounded-xl border border-slate-700">
            <?php 
            $langs = ['HTML', 'JS', 'Python', 'C++', 'PHP', 'SQL'];
            foreach($langs as $index => $l): ?>
                <label class="cursor-pointer group">
                    <input type="radio" name="lang" value="<?= strtolower($l) ?>" class="hidden peer" <?= $index === 0 ? 'checked' : '' ?> onchange="updateLangMode()">
                    <span class="px-4 py-2 rounded-lg text-xs font-black uppercase transition-all peer-checked:bg-pink-600 peer-checked:text-white text-slate-400 group-hover:text-slate-200 block">
                        <?= $l ?>
                    </span>
                </label>
            <?php endforeach; ?>
        </div>

        <div class="flex items-center gap-4">
		<a href="campus.php" 
       class="flex items-center gap-2 px-4 py-2 border border-slate-700 rounded-lg text-[10px] font-black uppercase text-slate-400 hover:text-white hover:border-pink-500 transition-all duration-300 group">
        <i data-lucide="home" class="w-3 h-3 group-hover:text-pink-500"></i>
        Back to Campus
    </a>
            <!--span class="text-[10px] font-bold text-slate-400 uppercase">Scholar: <?= $first_name ?></span-->
            <button onclick="executeLogic()" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-black uppercase text-xs transition-all flex items-center gap-2">
                <i data-lucide="play" class="w-4 h-4"></i> Run Engine
            </button>
        </div>
    </header>

    <main class="flex-grow flex p-4 gap-4 overflow-hidden">
         
		<section class="w-1/2 flex flex-col gap-2">
        <div class="flex justify-between items-center px-2">
            <span class="text-[10px] font-black uppercase text-slate-500 tracking-widest">Source Code Entry Terminal</span>
            <button onclick="clearSpace()" class="text-[10px] text-slate-500 hover:text-white transition-colors uppercase font-bold">Clear Space</button>
        </div>
        <div id="editor-container" class="flex-grow glass-panel rounded-2xl overflow-hidden relative border border-white/10">
             <textarea id="code-input" class="w-full h-full bg-transparent p-8 text-lg font-mono text-pink-400 outline-none resize-none focus:ring-2 focus:ring-pink-500/20 transition-all" placeholder="// Enter your logic here..."></textarea>
        </div>
    </section>

        <section class="w-1/2 flex flex-col gap-4">
            <div class="h-1/4 flex flex-col gap-2">
                <span class="text-[10px] font-black uppercase text-slate-500 tracking-widest">Executed Output</span>
                <div id="output-pane" class="flex-grow glass-panel rounded-2xl p-4 overflow-auto">
                    </div>
            </div>

            <div class="h-3/4 flex flex-col gap-2">
                <span class="text-[10px] font-black uppercase text-pink-500 tracking-widest">Tutor Insights and Explanation</span>
                <div id="tutor-pane" class="flex-grow glass-panel rounded-2xl p-6 overflow-auto text-sm text-slate-300 border-t-2 border-pink-500/30">
                    <p class="italic opacity-50">Select a language and run code to trigger neural analysis...</p>
                </div>
            </div>
        </section>
    </main>

    <script>
        const API_KEY = "<?= $active_api_key ?>";

        function updateLangMode() {
            const lang = document.querySelector('input[name="lang"]:checked').value;
            document.getElementById('editor-label').innerText = lang + " Logic Editor";
            document.getElementById('output-pane').innerHTML = `<div class="text-slate-600 italic">Ready for ${lang}...</div>`;
        }

        async function executeLogic() {
            const lang = document.querySelector('input[name="lang"]:checked').value;
            const code = document.getElementById('code-input').value;
            const output = document.getElementById('output-pane');
            const tutor = document.getElementById('tutor-pane');

            tutor.innerHTML = `<div class="animate-pulse text-pink-400 font-black text-xs uppercase">Analyzing Neural Patterns...</div>`;

            // 1. HANDLING EXECUTION
            if (lang === 'html') {
                output.innerHTML = `<iframe id="preview-frame"></iframe>`;
                const doc = document.getElementById('preview-frame').contentWindow.document;
                doc.open(); doc.write(code); doc.close();
            } else if (lang === 'js') {
                output.innerHTML = "";
                const log = (m) => output.innerHTML += `<div class="text-green-400 font-mono text-xs">> ${m}</div>`;
                try { 
                    const func = new Function('console', code);
                    func({ log }); 
                } catch(e) { output.innerHTML = `<div class="text-red-400">${e.message}</div>`; }
            } else {
                output.innerHTML = `<div class="text-blue-400 italic text-xs">Simulating ${lang.toUpperCase()} environment via AI Bridge...</div>`;
            }

            // 2. TRIGGER AI TUTOR (The June 5 Presentation Edge)
            try {
                const prompt = `Act as an expert ${lang} instructor at Gemini AI College. Analyze this code: \n\n${code}\n\n1. Explain what it does.\n2. Identify any errors.\n3. Suggest an advanced "Foundry" improvement. Use Markdown.`;
                
                const res = await fetch(`https://generativelanguage.googleapis.com/v1beta/models/gemini-3.1-flash-lite-preview:generateContent?key=${API_KEY}`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ contents: [{ parts: [{ text: prompt }] }] })
                });
                const data = await res.json();
                tutor.innerHTML = marked.parse(data.candidates[0].content.parts[0].text);
            } catch (e) {
                tutor.innerHTML = `<div class="text-red-500 font-black">NEURAL LINK FAILED. CHECK API KEY.</div>`;
            }
        }

        function clearEditor() { document.getElementById('code-input').value = ""; }
        lucide.createIcons();
    </script>
</body>
</html>