<?php
// GAC FOUNDRY: Prompt Temperature Laboratory - Refined v1.1
require_once 'db-connect.php';

$generated_prompt = "";
$user_task = $_POST['user_task'] ?? ""; // Persist context after POST

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_compile'])) {
    $temp = $_POST['temp_setting'] ?? "0.6";

    // Logic Gate Implementation
    $final_temp = (float)$temp < 0.2 ? "0.2" : $temp;

    $generated_prompt = "
# DIRECTIVE: GENERATE_PYTHON_SDK_IMPLEMENTATION
# TARGET_MODEL: gemini-3.0-flash
# CONTEXT: {$user_task}

[INPUT_VARIABLES]
- TEMPERATURE_SETTING: {$final_temp}
- MAX_OUTPUT_TOKENS: 400
- SDK_LIBRARY: google-generativeai

[LOGIC_GATES]
IF TEMPERATURE_SETTING < 0.2:
    SET TEMPERATURE_SETTING = 0.2
ELSE:
    PROCEED_WITH_SELECTED_TEMPERATURE

[EXECUTION_STEPS]
1. INITIALIZE_CLIENT: Configure SDK using API_KEY.
2. CONFIGURE_MODEL: Params { temp: {$final_temp}, tokens: 400 }
3. IMPLEMENT_AGENTIC_WORKFLOW: Low-latency wrapper.
4. VALIDATE: Production-ready check.

[OUTPUT_REQUIREMENT]
RETURN: Clean Python code block with error handling.
";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GAC | Temperature Test Lab</title>
    <link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32.png">
    <style>
        :root { --bg: #0d1117; --card: #161b22; --accent: #58a6ff; --text: #c9d1d9; --neon: #39ff14; }
        body { background: var(--bg); color: var(--text); font-family: 'Courier New', Courier, monospace; padding: 20px; }
        .lab-container { max-width: 900px; margin: auto; background: var(--card); border: 1px solid #30363d; border-radius: 8px; padding: 30px; position: relative; }
        
        /* 1. GOOGLE MODEL NAME AT TOP RIGHT */
        .model-anchor { position: absolute; top: 15px; right: 20px; color: var(--accent); font-weight: bold; font-size: 14px; opacity: 0.8; }
        
        h2 { color: var(--accent); border-bottom: 1px solid #30363d; padding-bottom: 10px; margin-top: 0; }
        
        .panel-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 5px; }
        
        /* TEXTAREAS & INPUTS */
        textarea { width: 100%; height: 120px; background: #0d1117; color: var(--accent); border: 1px solid #30363d; padding: 15px; box-sizing: border-box; border-radius: 4px; resize: none; font-size: 14px; }
        .radio-group { margin: 25px 0; display: flex; gap: 25px; align-items: center; background: #0d1117; padding: 15px; border-radius: 6px; }
        
        /* OUTPUT BOX */
        .output-box { background: #000; color: var(--neon); padding: 20px; border-radius: 6px; white-space: pre-wrap; border: 1px solid #333; margin-top: 10px; font-size: 13px; line-height: 1.5; }
        
        /* BUTTONS */
        .btn { border: none; border-radius: 4px; cursor: pointer; font-weight: bold; transition: opacity 0.2s; }
        .btn-compile { background: #238636; color: white; padding: 12px 30px; font-size: 16px; }
        .btn-utility { background: #30363d; color: white; padding: 5px 15px; font-size: 12px; border: 1px solid #444; }
        .btn:hover { opacity: 0.8; }
        
        .button-row { display: flex; gap: 10px; }
    </style>
</head>
<body>

<div class="lab-container">
    <div class="model-anchor">Model: Gemini 3.0 Flash</div>
    
    <h2>AI GEMINI COLLEGE PROMPT GENERATOR & TEMPERATURE ANALYSIS</h2>
    <p style="font-size: 13px; color: #8b949e;">Adjust variables to generate a Logic-Gated Prompt-as-Code block.</p>
    
    <form method="POST" id="labForm">
        <div class="panel-header">
            <label>CONTEXT (User Task):</label>
            <button type="button" class="btn btn-utility" onclick="clearTop()">Clear</button>
        </div>
        
        <textarea name="user_task" id="topText" placeholder="e.g. Build a data visualization script..."><?= htmlspecialchars($user_task); ?></textarea>

        <div class="radio-group">
            <label style="font-weight: bold;">TEMPERATURE:</label>
            <input type="radio" name="temp_setting" value="0.2" checked> 0.2 (Rigid)
            <input type="radio" name="temp_setting" value="0.6"> 0.6 (Balanced)
            <input type="radio" name="temp_setting" value="1.0"> 1.0 (Creative)
        </div>

        <button type="submit" name="action_compile" class="btn btn-compile">COMPILE PROMPT</button>
    </form>

    <?php if ($generated_prompt): ?>
        <div style="margin-top: 30px;">
            <div class="panel-header">
                <label>Generated Output:</label>
                <div class="button-row">
                    <button type="button" class="btn btn-utility" onclick="copyOutput()">Copy</button>
                    <button type="button" class="btn btn-utility" onclick="clearBottom()">Clear</button>
                </div>
            </div>
            
            <div class="output-box" id="bottomText"><?= htmlspecialchars($generated_prompt); ?></div>
        </div>
    <?php endif; ?>
</div>

<script>
    // Functions for UI Utility
    function clearTop() {
        document.getElementById('topText').value = "";
    }

    function clearBottom() {
        const out = document.getElementById('bottomText');
        if(out) out.innerHTML = "<span style='color:#444'>Output cleared. Ready for next compilation.</span>";
    }

    function copyOutput() {
        const text = document.getElementById('bottomText').innerText;
        navigator.clipboard.writeText(text).then(() => {
            alert("Compiled Prompt copied to clipboard!");
        });
    }
</script>

</body>
</html>