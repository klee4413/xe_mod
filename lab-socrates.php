<?php
// AIGC Socratic Tutor Lab v1.0 Socratic-tutor.php
//require_once __DIR__ . '/../db-connect.php'; //SECURED DB ID AND PASSWORD ACCESS FROM DATABASE
require_once __DIR__ . '/../db-connect.php'; //SECURED DB ID AND PASSWORD ACCESS FROM DATABASE
//AIGC INFRASTRUCTURE: CONVERSATIONAL RETENTION ACTIVE. SOCRATIC MEMORY ENGAGED. 
//AIGC FOUNDRY STATUS: FULL OPERATIONAL CAPACITY.
// API FETCH FROM SECURED DATABASE 
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
    <title>GAC | Socratic Tutor Lab</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --gac-navy: #000080; --gac-pink: #ec4899; }
        body { background: #020617; color: #f8fafc; font-family: 'Inter', sans-serif; height: 100vh; overflow: hidden; }
        .glass-panel { background: rgba(15, 23, 42, 0.8); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.1); }
        .tutor-output { 
            font-family: 'Inter', sans-serif; 
            background: #000; 
            color: #39ff14; 
            overflow-y: auto; 
            max-height: 100%; 
        }
        .tutor-output::-webkit-scrollbar { width: 8px; }
        .tutor-output::-webkit-scrollbar-track { background: #050505; }
        .tutor-output::-webkit-scrollbar-thumb { background: #ec4899; border-radius: 10px; }
        .container-box {
            display: flex;
            flex-direction: column;
            width: 75%;
            margin: 0 auto;
            height: calc(100vh - 120px);
        }
    </style>
</head>
<body class="flex flex-col">

    <header class="bg-[#000080] border-b-2 border-[#ec4899] py-4 px-8 flex justify-between items-center shadow-2xl">
        <div class="flex items-center gap-4">
            <h1 class="text-2xl font-black italic uppercase tracking-tighter text-white">
                AIGC <span class="text-pink-400">Socratic Tutor : Dialogue - Driven</span> Lab
            </h1>             
        </div>

        <div class="flex items-center gap-4">
            <button onclick="window.location.reload();" class="border border-blue-400 px-4 py-2 rounded text-xs hover:bg-blue-800 transition-all">
                <i class="fa-solid fa-rotate"></i>
            </button>
            <a href="campus.php" class="bg-pink-600 hover:bg-pink-700 text-white px-6 py-2 rounded-lg font-black uppercase text-xs transition-all flex items-center gap-2">
                <i class="fa-solid fa-house-chimney"></i> Back to Campus
            </a>
        </div>
    </header>

    <main class="container-box p-6 gap-6">
        
        <section class="h-1/3 flex flex-col gap-2">
            <div class="flex justify-between items-center px-2">
                <span class="text-[8px] font-black uppercase text-slate-300 tracking-widest">Student Questions and Responses - Do not Delete or Reset until Completion of Dialog - Keep Append Your responses</span>
                <span class="text-[10px] text-pink-400 font-bold uppercase italic">Input Topic</span>
            </div>
            <textarea id="instruction-input" 
                class="flex-grow glass-panel rounded-2xl p-6 outline-none text-blue-300 focus:border-pink-500/50 transition-all text-lg" 
                placeholder="Example: Explain or ask the logic of prompt or about faulty logic..."></textarea>
            
            <div class="flex justify-end mt-2">
                <button onclick="generateSocraticResponse()" class="bg-green-600 hover:bg-green-600 text-white px-5 py-3 rounded-xl font-black uppercase text-sm shadow-lg transition-all flex items-center gap-2">
                    <i class="fa-solid fa-brain"></i> Click for Tutor Instruction or Response
                </button>
            </div>
        </section>

        <section class="flex-grow flex flex-col gap-2 min-h-0">
            <div class="flex justify-between items-center px-2">
                <span class="text-[10px] font-black uppercase text-slate-500 tracking-widest">Instructor Guidance & Question</span>
                <button onclick="copyResponse()" class="text-[10px] text-slate-500 hover:text-white uppercase font-bold">Copy to Clipboard</button>
            </div>
            <div id="tutor-output" class="w-full flex-grow glass-panel rounded-2xl p-6 overflow-auto tutor-output text-sm whitespace-pre-wrap border-t-2 border-pink-500/20">
                <div class="italic opacity-30">Awaiting Socratic synthesis...</div>
            </div>
        </section>
    </main>

    <script>
        const API_KEY = "<?= $active_api_key ?>";

        async function generateSocraticResponse() {
            const prompt = document.getElementById('instruction-input').value;
            const output = document.getElementById('tutor-output');

            if (!prompt) { alert("Foundry Error: Topic Required."); return; }

            output.innerHTML = `<div class="animate-pulse text-pink-400 font-black text-xs uppercase">Initializing Socratic Dialogue...</div>`;

            try {
                // Modified System Instruction for Socratic Logic
                const systemInstruction = `Act as a Socratic Tutor for AI Gemini College. 
                1. Provide a brief, high-level summary of the logic behind the requested topic. 
                2. Do not give a complete answer or code if asked for it. 
                3. Instead, end your response by asking ONE challenging, open-ended question that forces the student to explain the underlying logic. 
                4. Maintain a supportive yet rigorous academic tone.`;
                
                const res = await fetch(`https://generativelanguage.googleapis.com/v1beta/models/gemini-3.1-flash-lite-preview:generateContent?key=${API_KEY}`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ 
                        contents: [{ parts: [{ text: `${systemInstruction}\n\nStudent Request: ${prompt}` }] }],
                        generationConfig: {
                            temperature: 0.7, // Slightly higher for more creative questioning
                            maxOutputTokens: 800
                        }
                    })
                });

                const data = await res.json();
                const tutorResult = data.candidates[0].content.parts[0].text;
                
                // Clean markdown artifacts
                const cleanText = tutorResult.replace(/```[a-z]*\n/g, '').replace(/```/g, '');
                output.innerText = cleanText;

            } catch (e) {
                output.innerHTML = `<div class="text-red-500 font-black uppercase">NEURAL HANDSHAKE FAILED. CHECK API CONNECTION.</div>`;
            }
        }

        function copyResponse() {
            const text = document.getElementById('tutor-output').innerText;
            navigator.clipboard.writeText(text);
            alert("Guidance Copied.");
        }
    </script>
</body>
</html>