<?php
// prompt-nlmv2.php - STUDENT PRACTICE VERSION (JSON ONLY) prompt-nlmv2.php
$msg = "";
$json_output = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $practice_data = [
        "timestamp" => date("Y-m-d H:i:s"),
        "persona" => $_POST['persona'],
        "role" => $_POST['role'],
        "goal_task" => $_POST['goal_task'],
        "format" => $_POST['format'],
        "context" => $_POST['context'],
        "application" => $_POST['application'],
        "rules" => $_POST['rules'],
        "examples" => $_POST['examples']
        //"Final_Prompt" => $_POST['preview_full']
    ];
    // PRETTY_PRINT ensures the JSON is readable for the student
    $json_output = json_encode($practice_data, JSON_PRETTY_PRINT);
    $msg = "<div class='success'>Prompt Logic Synthesized for NotebookLM!</div>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>NotebookLM and GEMINI Prompt Lab</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f8fafc; padding: 20px; color: #1e293b; }
        .container { max-width: 1200px; margin: auto; background: white; padding: 30px; border-radius: 15px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); }
        h1 { color: #1e40af; border-bottom: 2px solid #bfdbfe; padding-bottom: 10px; }
        .practice-box { background: #eff6ff; padding: 25px; border-radius: 12px; border: 1px solid #dbeafe; margin-bottom: 30px; }
        .grid-8 { display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; }
        input, textarea { padding: 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; width: 100%; box-sizing: border-box; }
        textarea { height: 80px; }
        .full-width { grid-column: span 2; }
        .preview-area { background: #fdf2f8; border: 2px dashed #ec4899; color: #831843; font-family: monospace; }
        
        /* JSON STORAGE BOX STYLES */
        .json-container { position: relative; margin-top: 20px; }
        .json-area { background: #1e293b; color: #38bdf8; font-family: 'Courier New', monospace; padding: 20px; border-radius: 8px; overflow-x: auto; font-size: 12px; white-space: pre-wrap; }
        .copy-btn { position: absolute; top: 10px; right: 10px; background: #38bdf8; color: #0f172a; border: none; padding: 5px 12px; border-radius: 4px; cursor: pointer; font-weight: bold; font-size: 11px; }
        .copy-btn:hover { background: #7dd3fc; }

        .btn-blue { background: #2563eb; color: white; padding: 15px; border: none; border-radius: 8px; cursor: pointer; font-weight: bold; width: 100%; margin-top: 10px; }
        .success { background: #dcfce7; color: #166534; padding: 15px; border-radius: 8px; margin-bottom: 20px; text-align: center; font-weight: bold; }
    </style>
</head>
<body>

<div class="container">
    <h1>NotebookLM, Gemini, Claude, ChatGpt or Copilot - General Prompt Lab<span style="color:#3b82f6">-AIGC</span></h1>
    <p><em>Methods: P.G.B.V. | P.T.C.F. | C.R.T.F. | I.R.A.C.- Entries: 1.Persona 2.Role 3.Goal 4.Format 5.Context 6.Application 7.Rules 8.Example</em></p>
     <a href="campus.php" class="text-[10px] font-black text-slate-500 hover:text-white uppercase tracking-widest mr-4">To Campus</a>
    <?php echo $msg; ?>

    <div class="practice-box">
        <form method="POST" id="nlmForm">
            <div class="grid-8">
                <input type="text" name="persona" class="in" placeholder="1. Persona (Instructor, webmaster)" required>
                <input type="text" name="role" class="in" placeholder="2. Role (Teaching & Advising)" required>
                <input type="text" name="goal_task" class="in" placeholder="3. Goal / Task (Socratic Analysis)" required>
                <input type="text" name="format" class="in" placeholder="4. Format (Text,Table 2x3, image)" required>
                <textarea name="context" class="in" placeholder="5. Context (Efficiency of Socratic Teaching, Interactive study)"></textarea>
                <textarea name="application" class="in" placeholder="6. Application (Practice Study)"></textarea>
                <textarea name="rules" class="full-width in" placeholder="7. Rules (Clear Questions, Logical description)"></textarea>
                <textarea name="examples" class="full-width in" placeholder="8. Examples (What is the best way to learn?)"></textarea>
                
                <div class="full-width">
                    <label><strong>Final Prompt Payload:</strong></label>
                    <textarea name="preview_full" id="preview_full" class="preview-area full-width" readonly style="height: 150px;"></textarea>
                </div>
                <button type="submit" class="btn-blue">Find Prompt Synthesized in JSON Format to use at NoteboomLM or Gemini</button>
            </div>
        </form>
    </div>

    <?php if ($json_output): ?>
    <div class="json-container">
        <h3>JSON Prompt Output for NotebookLM:</h3>
        <button class="copy-btn" onclick="copyJSON()">Copy</button>
        <pre class="json-area" id="jsonOutput"><?php echo htmlspecialchars($json_output); ?></pre>
    </div>
    <?php endif; ?>
</div>

<script>
// PTCF Synthesis Script
const form = document.getElementById('nlmForm');
const preview = document.getElementById('preview_full');
const inputs = document.querySelectorAll('.in');

function updatePreview() {
    const d = new FormData(form);
    let s = `PRESONA/ROLE: ${d.get('role')} acting as ${d.get('persona')}\n`;
    s += `GOAL: ${d.get('goal_task')}\n`;
    s += `CONTEXT: ${d.get('context')}\n`;
	s += `APPLICATION: ${d.get('application')}\n`;
    s += `RULES: ${d.get('rules')}\n`;
    s += `FORMAT: ${d.get('format')}\n`;
    s += `EXAMPLES: ${d.get('examples')}`;
    preview.value = s;
}

// CLIPBOARD LOGIC: The "Manual Spark" for Student Portability
function copyJSON() {
    const jsonText = document.getElementById('jsonOutput').innerText;
    navigator.clipboard.writeText(jsonText).then(() => {
        const btn = document.querySelector('.copy-btn');
        btn.innerText = "COPIED!";
        btn.style.background = "#22c55e"; // Turn Green on success
        setTimeout(() => {
            btn.innerText = "Copy";
            btn.style.background = "#38bdf8";
        }, 2000);
    }).catch(err => {
        console.error('Logic Error: Could not copy text: ', err);
    });
}

inputs.forEach(i => i.addEventListener('input', updatePreview));
</script>

</body>
</html>