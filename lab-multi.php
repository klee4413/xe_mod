<?php
// GAC FOUNDRY: Multi-Language Laboratory v5.1 (Visual Mirror & SDK Compliant)
session_start();
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
        textarea { font-family: 'Fira Code', monospace; resize: none; border: none; }
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
            <a href="campus.php" class="flex items-center gap-2 px-4 py-2 border border-slate-700 rounded-lg text-[10px] font-black uppercase text-slate-400 hover:text-white hover:border-pink-500 transition-all duration-300 group">
                <i data-lucide="home" class="w-3 h-3 group-hover:text-pink-500"></i>
                Back to Campus
            </a>
            <button onclick="executeLogic()" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-black uppercase text-xs transition-all flex items-center gap-2">
                <i data-lucide="play" class="w-4 h-4"></i> Run Engine
            </button>
        </div>
    </header>

    <main class="flex-grow flex p-4 gap-4 overflow-hidden">
        
        <nav class="w-16 flex flex-col gap-4 items-center py-4 glass-panel rounded-2xl">
            <i data-lucide="layout" class="w-6 h-6 text-slate-500 hover:text-pink-400 cursor-pointer"></i>
            <i data-lucide="code-2" class="w-6 h-6 text-slate-500 hover:text-pink-400 cursor-pointer"></i>
            <i data-lucide="database" class="w-6 h-6 text-slate-500 hover:text-pink-400 cursor-pointer"></i>
            <i data-lucide="cpu" class="w-6 h-6 text-slate-500 hover:text-pink-400 cursor-pointer"></i>
            <i data-lucide="git-branch" class="w-6 h-6 text-slate-500 hover:text-pink-400 cursor-pointer"></i>
            <i data-lucide="settings" class="w-6 h-6 text-slate-500 hover:text-pink-400 cursor-pointer"></i>
        </nav>

        <section class="flex-grow flex flex-col gap-2">
            <div class="flex justify-between items-center px-2"> 
                <span id="editor-label" class="text-[10px] font-black uppercase text-slate-500 tracking-widest">HTML Logic Editor</span>
                <button onclick="clearEditor()" class="text-[10px] text-slate-600 hover:text-white uppercase">Clear Space</button>
            </div>
            <textarea id="code-input" class="w-full flex-grow glass-panel rounded-2xl p-6 outline-none text-blue-300 focus:border-pink-500/50 transition-all" spellcheck="false" placeholder="Write logic here..."></textarea>
        </section>

        <section class="w-1/3 flex flex-col gap-4">
            <div class="h-1/4 flex flex-col gap-2">
                <span class="text-[10px] font-black uppercase text-slate-500 tracking-widest">Executed Output</span>
                <div id="output-pane" class="flex-grow glass-panel rounded-2xl p-4 overflow-auto font-mono text-xs">
                    <div class="text-slate-600 italic">Ready for logic...</div>
                </div>
            </div>

            <div class="h-3/4 flex flex-col gap-2">
                <span class="text-[10px] font-black uppercase text-pink-500 tracking-widest">Tutor Insights and Explanation</span>
                <div id="tutor-pane" class="flex-grow glass-panel rounded-2xl p-6 overflow-auto text-sm text-slate-300 border-t-2 border-pink-500/30">
                    <p class="italic opacity-50">Run code to trigger neural analysis...</p>
                </div>
            </div>
        </section>
    </main>
const res = await fetch('/aigc-core/public/api/ai/analyze-code', { ...
    <script>
        function updateLangMode() {
            const lang = document.querySelector('input[name="lang"]:checked').value;
            document.getElementById('editor-label').innerText = lang.toUpperCase() + " Logic Editor";
            document.getElementById('output-pane').innerHTML = `<div class="text-slate-600 italic">Ready for ${lang.toUpperCase()}...</div>`;
        }

        async function executeLogic() {
            const lang = document.querySelector('input[name="lang"]:checked').value;
            const code = document.getElementById('code-input').value;
            const output = document.getElementById('output-pane');
            const tutor = document.getElementById('tutor-pane');

            tutor.innerHTML = `<div class="animate-pulse text-pink-400 font-black text-xs uppercase">Analyzing Neural Patterns...</div>`;

            // Local Execution Simulation
            if (lang === 'html') {
                output.innerHTML = `<iframe id="preview-frame"></iframe>`;
                const doc = document.getElementById('preview-frame').contentWindow.document;
                doc.open(); doc.write(code); doc.close();
            } else {
                output.innerHTML = `<div class="text-green-400">> Simulating ${lang.toUpperCase()} context...</div>`;
            }

            // TRIGGER SECURE SDK BRIDGE
            try {
				    const res = await fetch('/aigc-core/public/api/ai/analyze-code', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ lang: lang, code: code })
                });
                const data = await res.json();
                tutor.innerHTML = marked.parse(data.analysis);
            } catch (e) {
                tutor.innerHTML = `<div class="text-red-500 font-black">NEURAL LINK FAILED. ENSURE CORE IS ONLINE.</div>`;
            }
        }

        function clearEditor() { document.getElementById('code-input').value = ""; }
        lucide.createIcons();
    </script>
</body>
</html>