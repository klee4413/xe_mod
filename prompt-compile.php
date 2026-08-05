<?php
// 1. DATABASE CONNECTION (Using PDO for security) prompt-compile.php
//$host = 'localhost'; $db = 'book_db'; $user = 'root'; $pass = ''; // Adjust if needed
require_once __DIR__ . '/../db-connect.php';
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (\PDOException $e) { die("⚠️ DB Error: " . $e->getMessage()); }

// 2. FETCH SYMBOLS (Sorted as requested previously)
$symbols = $pdo->query("SELECT * FROM symbol_table ORDER BY persona_name ASC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AIGC | Prompt Token Compile Format Lab v1.0</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <style>
        :root { --slate-750: #1e293b; --slate-900: #0f172a; }
        body { font-family: 'Segoe UI', system-ui, sans-serif; }
        .compiled-box { font-family: 'Consolas', 'Monaco', monospace; line-height: 1.6; }
        /* Style for data attributes */
        .symbol-row:hover { background-color: rgba(6, 182, 212, 0.1) !important; cursor: pointer; }
    </style>
</head>
<body class="bg-slate-900 text-slate-100 p-6 md:p-10">

    <div class="max-w-screen-2xl mx-auto">
        <!--h1 class="text-3xl font-extrabold mb-10 text-cyan-400">AIGC PROMPT FORMAT PRACTICE <span class="text-slate-500 font-medium"> ver 1</span></h1-->
<h1>
    AIGC Advanced <span style="color:#ec4899">AIGC PROMPT FORMAT PRACTICE </span>
    <a href="campus.php" 
       style="font-size: 15px; color: #64748b; text-decoration: none; margin-left: 30px; font-weight: normal; border: 1px solid #334155; padding: 4px 10px; border-radius: 4px; vertical-align: middle; transition: all 0.3s;"
       onmouseover="this.style.borderColor='#ec4899'; this.style.color='#fff';"
       onmouseout="this.style.borderColor='#334155'; this.style.color='#64748b';">
       <i class="fa-solid fa-house-chimney" style="margin-right: 5px;"></i> Back to Campus
    </a>
</h1><br>
        <div class="grid grid-cols-1 lg:grid-cols-[1fr,minmax(500px,600px)] gap-10 mb-12">
            
            <div class="bg-slate-800 p-8 rounded-2xl border border-slate-700 shadow-xl">
                <h2 class="text-xl font-semibold mb-6 text-emerald-400">Edit the entries to fit your purpose.</h2>
                <form id="compilerForm" class="space-y-5">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="flex flex-col gap-1.5">
                            <label class="text-sm text-slate-400 font-medium">Persona (Who?)</label>
                            <input type="text" name="persona" id="personaInput" placeholder="e.g. Database Sensei" class="bg-slate-900 p-3 rounded-lg border border-slate-700 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 outline-none transition" required>
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label class="text-sm text-slate-400 font-medium">Constraints (What NOT to do?)</label>
                            <input type="text" name="constraints" id="constraintsInput" placeholder="e.g. No jargon, No code yet" class="bg-slate-900 p-3 rounded-lg border border-slate-700 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 outline-none transition">
                        </div>
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <label class="text-sm text-slate-400 font-medium">Task (The Action Verb)</label>
                        <input type="text" name="task" id="taskInput" placeholder="e.g. Generate a SQL Schema normalization plan." class="bg-slate-900 p-3 rounded-lg border border-slate-700 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 outline-none transition" required>
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <label class="text-sm text-slate-400 font-medium">Goal/Workflow Steps (Pre-filled from DB)</label>
                        <textarea name="goal" id="goalInput" placeholder="Click a symbol below to load logic or type manually..." class="bg-slate-900 p-3 rounded-lg border border-slate-700 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 outline-none transition h-28" required></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="flex flex-col gap-1.5">
                            <label class="text-sm text-slate-400 font-medium">Output Format (The Result Type)</label>
                            <input type="text" name="format" id="formatInput" placeholder="e.g. Standard Text, JSON, Checklist" class="bg-slate-900 p-3 rounded-lg border border-slate-700 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 outline-none transition">
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label class="text-sm text-slate-400 font-medium">Tone</label>
                            <input type="text" name="tone" id="toneInput" placeholder="Professional, Academic, Encouraging" class="bg-slate-900 p-3 rounded-lg border border-slate-700 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 outline-none transition">
                        </div>
                    </div>

                    <button type="button" id="compileBtn" class="bg-blue-600 hover:bg-blue-500 w-full text-white font-bold py-4 rounded-xl transition-all shadow-lg text-lg uppercase tracking-wider">Compile Prompt</button>
                </form>
            </div>

            <div class="bg-black p-8 rounded-2xl border border-slate-700 shadow-2xl relative">
                <h2 class="text-xl font-semibold mb-6 text-cyan-300">Compiled Output (System Prompt)</h2>
                <button id="copyBtn" class="absolute top-6 right-6 text-xs text-red-600 hover:text-black-500 font-mono">[Copy and paste it to Gemini or NotebookLM]</button>
                
                <div id="outputDisplay" class="compiled-box text-slate-200 text-base space-y-4">
                    <p class="text-slate-600 italic">Configure input and click 'Compile'...</p>
                    </div>
            </div>
        </div>

        <div class="bg-slate-800 p-6 rounded-2xl border border-slate-700 shadow-sm overflow-hidden mb-10">
            <h2 class="text-lg mb-5 font-semibold text-slate-400">Database-Driven Symbol Token Table (Click a Persona to auto inject data to above box)</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="bg-slate-700/50 text-slate-300">
                            <th class="p-4 font-semibold uppercase tracking-wider w-48">Persona</th>
                            <th class="p-4 font-semibold uppercase tracking-wider w-48">Task</th>
                            <th class="p-4 font-semibold uppercase tracking-wider">Goal</th>
                        </tr>
                    </thead>
                    <tbody id="symbolTableBody" class="divide-y divide-slate-700">
                        <?php foreach ($symbols as $row): ?>
                        <tr class="symbol-row transition-colors"
                            data-persona="<?= htmlspecialchars($row['persona_name']) ?>"
                            data-task="<?= htmlspecialchars($row['task_name']) ?>"
                            data-goal="<?= htmlspecialchars($row['goal']) ?>"
                            data-format="<?= htmlspecialchars($row['output_format']) ?>"
                            data-tone="<?= htmlspecialchars($row['tone_modifier']) ?>">
                            
                            <td class="p-4 font-bold text-cyan-300">
                                <?= htmlspecialchars($row['persona_name']) ?>
                            </td>
                            <td class="p-4 text-slate-300"><?= htmlspecialchars($row['task_name']) ?></td>
                            <td class="p-4 text-slate-400 italic leading-relaxed">
                                <?= htmlspecialchars($row['goal']) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <script>
        $(document).ready(function() {

            // 1. Logic: Click Table Row -> Populate Form
            $('.symbol-row').on('click', function() {
                // Get data attributes from the clicked row
                var persona = $(this).attr('data-persona');
                var task    = $(this).attr('data-task');
                var goal    = $(this).attr('data-goal');
                var format  = $(this).attr('data-format');
                var tone    = $(this).attr('data-tone');

                // Populate form inputs
                $('#personaInput').val(persona);
                $('#taskInput').val("How to " + task); // Slightly reframe standard task
                $('#goalInput').val(goal);
                $('#formatInput').val(format);
                $('#toneInput').val(tone);

                // Visual feedback
                $('body, html').animate({ scrollTop: 0 }, 'fast'); // Scroll up
                $('#compilerForm').addClass('border-2 border-emerald-500 rounded-lg'); // Flash Green
                setTimeout(function() { $('#compilerForm').removeClass('border-2 border-emerald-500'); }, 800);
            });

            // 2. Logic: Compile Form -> Generate Markdown System Prompt
            $('#compileBtn').on('click', function() {
                // Fetch form values
                var p = $('#personaInput').val();
                var c = $('#constraintsInput').val();
                var t = $('#taskInput').val();
                var g = $('#goalInput').val();
                var f = $('#formatInput').val();
                var tn= $('#toneInput').val();

                // Build the prompt structure (Markdown)
                var prompt = "### YOUR AI HELPER INITIALIZATION\n";
                prompt += "[ACT AS PERSONA]: **" + (p || "[Undefined Persona]") + "**\n\n";
                
                if(c){ prompt += "### NEGATIVE CONSTRAINTS\n[DO NOT]: " + c + "\n\n"; }

                prompt += "### OBJECTIVE DESCRIPTION\n";
                prompt += "[TASK]: " + t + "\n\n";
                
                prompt += "### EXECUTION LOGIC / GOAL\n";
                prompt += "[FOLLOW THIS GOAL]:\n\n" + g + "\n\n";

                prompt += "### OUTPUT SPECIFICATIONS\n";
                prompt += "[FORMAT AS]: " + (f || "Standard Text/Markdown") + "\n";
                if(tn){ prompt += "[TONE]: " + tn; }

                // Convert prompt text into HTML paragraphs for Consolas display
                var formattedHtml = "";
                prompt.split('\n\n').forEach(section => {
                     formattedHtml += "<p>" + section.replace(/\n/g, '<br>') + "</p>";
                });

                // Update Display
                $('#outputDisplay').html(formattedHtml);
            });

            // 3. Logic: Copy Output to Clipboard
            $('#copyBtn').on('click', function() {
                var promptText = $('#outputDisplay').text(); // Get raw text
                navigator.clipboard.writeText(promptText).then(function() {
                     alert("System Prompt copied to clipboard!");
                });
            });
        });
    </script>
</body>
</html>