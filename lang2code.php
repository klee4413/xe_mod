<?php
// AIGC FOUNDRY: Multi-Language Laboratory v4.1  lab-lang2code.php
require_once 'db-connect.php'; //DATABASE ID AND PASSWORD EXTRACTED SECURELY through app db-connect.php 
                               //which can be used localhost and google cloud server
// Use backticks around the table name to handle the hyphen
// API KEY FETCHES SECURELY FROM DB
$hook_stmt = $pdo->prepare("SELECT `api_key` FROM `api-hook` WHERE `seq_no` = '1'");// Use backticks around the table name to handle the hyphen
$hook_stmt->execute();
$hook_data = $hook_stmt->fetch();
$active_api_key = ($hook_data) ? $hook_data['api_key'] : "";
$first_name = $_SESSION['first_name'] ?? 'Scholar';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>GAC | Language-to-Code Lab</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --gac-navy: #000080; --gac-pink: #ec4899; }
        body { background: #020617; color: #f8fafc; font-family: 'Inter', sans-serif; height: 100vh; overflow: hidden; }
        .glass-panel { background: rgba(15, 23, 42, 0.8); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.1); }
        .code-output { font-family: 'Fira Code', monospace; background: #000; color: #39ff14; }
		.code-output { 
    font-family: 'Fira Code', monospace; 
    background: #000; 
    color: #39ff14; 
    /* NEW: Enable Scrolling Logic */
    overflow-y: auto; 
    max-height: 100%; 
}

/* Optional: Style the scrollbar to match the GAC Pink theme */
.code-output::-webkit-scrollbar {
    width: 8px;
}
.code-output::-webkit-scrollbar-track {
    background: #050505;
}
.code-output::-webkit-scrollbar-thumb {
    background: #ec4899;
    border-radius: 10px;
}
 .container {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
	/* Adjust width to 3/4 of the screen */
    width: 75%;
    
    /* Keep it centered horizontally */
    margin: 0 auto;
  }
    </style>
</head>
<body class="flex flex-col ">

    <header class="bg-[#000080] border-b-2 border-[#ec4899] py-4 px-8 flex justify-between items-center shadow-2xl">
        <div class="flex items-center gap-4">
            <h1 class="text-2xl font-black italic uppercase tracking-tighter text-white">
                AIGC <span class="text-pink-400">Common Language to Programing Code Generation </span> Lab
            </h1>             
        </div>

        <div class="flex items-center gap-4">
            <!--span class="text-[10px] font-bold text-slate-400 uppercase">Scholar: <?= $first_name ?></span-->
            <button onclick="window.location.reload();" class="border border-blue-400 px-4 py-2 rounded text-xs hover:bg-blue-800 transition-all">
                <i class="fa-solid fa-rotate"></i>
            </button>
            <a href="campus.php" class="bg-pink-600 hover:bg-pink-700 text-white px-6 py-2 rounded-lg font-black uppercase text-xs transition-all flex items-center gap-2">
                <i class="fa-solid fa-house-chimney"></i> Back to Campus
            </a>
        </div>
    </header>

    <main class="flex-grow flex flex-col p-6 gap-6 overflow-hidden ">
        
        <section class="h-1/3 flex flex-col gap-2">
            <div class="flex justify-between items-center px-2">
                <span class="text-[10px] font-black uppercase text-slate-500 tracking-widest">Natural Human Language Instruction</span>
                <span class="text-[10px] text-pink-400 font-bold uppercase italic">Input Target logic below</span>
            </div>
            <textarea id="instruction-input" 
                class=" .container {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
  } flex-grow glass-panel rounded-2xl p-6 outline-none text-blue-300 focus:border-pink-500/50 transition-all text-lg" 
                placeholder="Example: give me javascript code for Repetitive Tasks (Loops) for shuffle number generation"></textarea>
            
            <div class="flex justify-end">
                <button onclick="generateCode()" class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-xl font-black uppercase text-sm shadow-lg transition-all flex items-center gap-2">
                    <i class="fa-solid fa-microchip"></i> Synthesize Code
                </button>
            </div>
        </section>

         
    <section class="flex-grow flex flex-col gap-2 min-h-0 container">
    
	<div class="flex items-center px-2">
    <span class="text-[10px] font-black uppercase text-slate-500 tracking-widest mr-16">Generated Code Snippet</span>
    <button onclick="copyCode()" class="text-[10px] text-slate-500 hover:text-white uppercase font-bold">Copy to Clipboard</button>
    </div>
    <div id="code-output" class="w-full flex-grow glass-panel rounded-2xl p-6 overflow-auto code-output text-sm whitespace-pre border-t-2 border-pink-500/20">
        <div class="italic opacity-30">Awaiting neural synthesis...</div>
    </div>
    </section>
    </main>

    <script>
        const API_KEY = "<?= $active_api_key ?>";

        async function generateCode() {
            const prompt = document.getElementById('instruction-input').value;
            const output = document.getElementById('code-output');

            if (!prompt) { alert("Foundry Error: Instruction Required."); return; }

            output.innerHTML = `<div class="animate-pulse text-pink-400 font-black text-xs uppercase">Connecting to Neural Engine...</div>`;

            try {
                // System Instruction to ensure only clean code is returned
                const systemInstruction = "Act as the GAC Code Synthesis Engine. Provide only clean, production-ready code snippets with minimal comments. No conversational filler.";
                
                const res = await fetch(`https://generativelanguage.googleapis.com/v1beta/models/gemini-3.1-flash-lite-preview:generateContent?key=${API_KEY}`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ 
                        contents: [{ parts: [{ text: `${systemInstruction}\n\nTask: ${prompt}` }] }],
                        generationConfig: {
                            temperature: 0.2,
                            maxOutputTokens: 400
                        }
                    })
                });

                const data = await res.json();
                const codeResult = data.candidates[0].content.parts[0].text;
                
                // Remove markdown code blocks if present
                const cleanCode = codeResult.replace(/```[a-z]*\n/g, '').replace(/```/g, '');
                output.innerText = cleanCode;

            } catch (e) {
                output.innerHTML = `<div class="text-red-500 font-black uppercase">NEURAL HANDSHAKE FAILED. CHECK API CONNECTION.</div>`;
            }
        }

        function copyCode() {
            const code = document.getElementById('code-output').innerText;
            navigator.clipboard.writeText(code);
            alert("Foundry Logic Copied.");
        }
    </script>
</body>
</html>