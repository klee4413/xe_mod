<?php
// prompt-cot2-ym.php - ADVANCED LOGIC LAB (CoT, CiC, SOCRATIC)
$msg = "";
$json_output = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 1. FILTER LOGIC: Remove empty strings from the POST data
    $filtered_data = array_filter($_POST, function($value) {
        return $value !== ''; 
    });

    // 2. REMOVE PREVIEW_FULL: As requested, we remove this from the final JSON
    unset($filtered_data['preview_full']);
    unset($filtered_data['action']); // Standard cleanup

    $json_output = json_encode($filtered_data, JSON_PRETTY_PRINT);
    $msg = "<div class='success'><b>Enter Prompt at Any Section to Synthesize Logically!</b></div>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>AIGC | Advanced CoT Lab</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #fdf2f8; padding: 20px; color: #1e293b; }
        .container { max-width: 1200px; margin: auto; background: white; padding: 30px; border-radius: 15px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
        h1 { color: #be185d; border-bottom: 2px solid #fbcfe8; padding-bottom: 10px; }
        .method-section { background: #fff1f2; padding: 20px; border-radius: 10px; border: 1px solid #fecdd3; margin-bottom: 20px; }
        .grid-steps { display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; margin-top: 10px; }
        input, textarea { padding: 10px; border: 1px solid #fda4af; border-radius: 6px; font-size: 13px; width: 100%; box-sizing: border-box; }
        
        /* JSON AREA WITH COPY BUTTON */
        .json-container { position: relative; margin-top: 20px; }
        .json-area { background: #1e293b; color: #fb7185; font-family: 'Courier New', monospace; padding: 25px; border-radius: 8px; font-size: 13px; line-height: 1.5; white-space: pre-wrap; }
        .copy-btn { position: absolute; top: 15px; right: 15px; background: #fb7185; color: white; border: none; padding: 6px 15px; border-radius: 5px; cursor: pointer; font-weight: bold; transition: 0.3s; z-index: 10; }
        .copy-btn:hover { background: #e11d48; }

        .btn-pink { background: #db2777; color: white; padding: 15px; border: none; border-radius: 8px; cursor: pointer; font-weight: bold; width: 100%; margin-top: 20px; font-size: 16px; }
    </style>
</head>
<body>

<div class="container">
    <!--h1>AIGC Advanced <span style="color:#ec4899">Shot-Based, CoT and Socratic Prompt Logic Lab</span></h1-->
	<h1>
    AIGC Advanced <span style="color:#ec4899">Shot-Based, CoT and Socratic Prompt Logic Lab</span>
    <a href="campus.php" 
       style="font-size: 12px; color: #64748b; text-decoration: none; margin-left: 20px; font-weight: normal; border: 1px solid #334155; padding: 4px 10px; border-radius: 4px; vertical-align: middle; transition: all 0.3s;"
       onmouseover="this.style.borderColor='#ec4899'; this.style.color='#fff';"
       onmouseout="this.style.borderColor='#334155'; this.style.color='#64748b';">
       <i class="fa-solid fa-house-chimney" style="margin-right: 5px;"></i> Back to Campus
    </a>
</h1>
    <?php echo $msg; ?>

    <form method="POST" id="cotForm">
        <div class="method-section">
            <h3>1. Shot-Based Prompting (4 Max)</h3>
            <div class="grid-steps">
                <input type="text" name="shot_1" class="in" placeholder="Shot 1">
                <input type="text" name="shot_2" class="in" placeholder="Shot 2">
                <input type="text" name="shot_3" class="in" placeholder="Shot 3">
                <input type="text" name="shot_4" class="in" placeholder="Shot 4">
            </div>
        </div>

        <div class="method-section">
            <h3>2. Chain-of-Thought (6 Steps)</h3>
            <div class="grid-steps">
                <?php for($i=1; $i<=6; $i++): ?>
                    <input type="text" name="cot_step_<?php echo $i; ?>" class="in" placeholder="Step <?php echo $i; ?>">
                <?php endfor; ?>
            </div>
        </div>

        <div class="method-section">
            <h3>3. Socratic Questions (6 Questions)</h3>
            <div class="grid-steps">
                <?php for($i=1; $i<=6; $i++): ?>
                    <input type="text" name="socratic_q_<?php echo $i; ?>" class="in" placeholder="Question <?php echo $i; ?>">
                <?php endfor; ?>
            </div>
        </div>

        <input type="hidden" name="preview_full" id="preview_full">

        <button type="submit" class="btn-pink">Click to Synthesize Advanced JSON Prompts to Enter NotebookLM or Gemini</button>
    </form>

    <?php if ($json_output): ?>
    <div class="json-container">
        <button class="copy-btn" onclick="copyJSON()">Copy JSON</button>
        <pre class="json-area" id="jsonOutput"><?php echo htmlspecialchars($json_output); ?></pre>
    </div>
    <?php endif; ?>
</div>

<script>
function copyJSON() {
    const jsonText = document.getElementById('jsonOutput').innerText;
    navigator.clipboard.writeText(jsonText).then(() => {
        const btn = document.querySelector('.copy-btn');
        btn.innerText = "COPIED!";
        btn.style.background = "#166534";
        setTimeout(() => {
            btn.innerText = "Copy JSON";
            btn.style.background = "#fb7185";
        }, 2000);
    }).catch(err => {
        console.error('Logic Error: ', err);
    });
}
function convertToNumberedYAML($data) {
    $output = "";
    $i = 1;
    foreach ($data as $key => $value) {
        // Cleaning keys for visual clarity (e.g., shot_1 becomes Shot 1)
        $cleanKey = ucwords(str_replace('_', ' ', $key));
        $output .= sprintf("%02d. %s: \"%s\"\n", $i, $cleanKey, addslashes($value));
        $i++;
    }
    return $output;
}
</script>

</body>
</html>