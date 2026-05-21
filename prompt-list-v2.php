<?php
//  prompt-list-v2.php 
//require_once 'l-link.php'; 
require_once 's-link.php'; 
date_default_timezone_set('America/Los_Angeles');
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT id, category, description FROM prompt_website";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Gemini College Human Created Prompt Practice List</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background-color: #f4f4f9; }
        .container { max-width: 1200px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        
        /* FREEZE SEARCH BAR SECTION */
        .sticky-search-container {
            position: sticky;
            top: 0;
            background-color: white; /* Matches container bg */
            padding: 10px 0 20px 0;
            z-index: 1000;
            border-bottom: 1px solid #eee; /* Subtle separator */
        }

        /* BIG SEARCH BOX WITH THICK BLACK FRAME */
        #searchInput { 
            width: 100%; 
            padding: 20px; /* Twice as big */
            font-size: 20px; /* Larger text */
            border: 4px solid #000000; /* Thick black frame */
            border-radius: 8px; 
            box-sizing: border-box;
            outline: none;
        }

        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #f2f2f2; }
        tr:hover { background-color: #f9f9f9; }
        
        /* Selected Box Style */
        #selected-container { margin-top: 30px; padding: 20px; border: 2px dashed #007bff; border-radius: 8px; background-color: #f0f7ff; }
        .selected-item { background: white; border: 1px solid #ccc; padding: 10px; margin-bottom: 10px; border-radius: 4px; }
        .selected-item textarea { width: 100%; border: none; resize: vertical; font-family: inherit; font-size: 14px; }
        
        /* Button Styles */
        .button-group { margin-top: 20px; display: flex; gap: 10px; flex-wrap: wrap; }
        .btn { padding: 12px 24px; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; color: white; font-size: 16px; }
        .btn-copy { background-color: #28a745; }
        .btn-reset { background-color: #dc3545; }
        .btn-finish { background-color: #007bff; }
    </style>
</head>
<body>

<div class="container">
    <div class="sticky-search-container">
        <!--h2>AI Gemini College Human Created Prompts  List to use for Practice at AIGC Prompt Labs</h2-->
		<h2 style="color: #000080; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; border-bottom: 2px solid #000080; padding-bottom: 8px; margin-bottom: 20px;">
    AI Gemini College Human Created Prompts List for Practice at Prompt Labs
</h2>
        <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Search Category or Description Contents...">
    </div>

    <table>
        <thead>
            <tr>
                <th>Select</th>
                <th>ID</th>
                <th>Category</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody id="promptTableBody">
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><input type="checkbox" class="prompt-check" 
                                   data-id="<?php echo $row['id']; ?>" 
                                   data-category="<?php echo htmlspecialchars($row['category']); ?>" 
                                   data-desc="<?php echo htmlspecialchars($row['description']); ?>" 
                                   onchange="updateSelection()"></td>
                        <td><?php echo $row['id']; ?></td>
                        <td><strong><?php echo htmlspecialchars($row['category']); ?></strong></td>
                        <td><?php echo htmlspecialchars($row['description']); ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <div id="selected-container">
        <h3>Selected Prompts (Editable)</h3>
        <div id="selected-list"></div>
        
        <div class="button-group">
            <button class="btn btn-copy" onclick="copyAll()">Copy All to Clipboard</button>
            <button class="btn btn-reset" onclick="resetSelection()">Reset Selection</button>
            <button class="btn btn-finish" onclick="finishToEval()">Campus</button>			 
        </div>
        </div>
    </div>
</div>

<script>
// SEARCH FILTER LOGIC
function filterTable() {
    const input = document.getElementById("searchInput");
    const filter = input.value.toLowerCase();
    const rows = document.querySelectorAll("#promptTableBody tr");

    rows.forEach(row => {
        const category = row.cells[2].textContent.toLowerCase();
        const description = row.cells[3].textContent.toLowerCase();
        if (category.includes(filter) || description.includes(filter)) {
            row.style.display = "";
        } else {
            row.style.display = "none";
        }
    });
}

// SELECTION LOGIC
function updateSelection() {
    const selectedList = document.getElementById("selected-list");
    selectedList.innerHTML = "";
    const checkboxes = document.querySelectorAll(".prompt-check:checked");

    checkboxes.forEach(cb => {
        const id = cb.getAttribute("data-id");
        const category = cb.getAttribute("data-category");
        const desc = cb.getAttribute("data-desc");

        const div = document.createElement("div");
        div.className = "selected-item";
        div.innerHTML = `<strong>ID: ${id} | Category: ${category}</strong><br>
                         <textarea rows="3">${desc}</textarea>`;
        selectedList.appendChild(div);
    });
}

// RESET LOGIC
function resetSelection() {
    if(confirm("Are you sure you want to clear all selections?")) {
        const checkboxes = document.querySelectorAll(".prompt-check");
        checkboxes.forEach(cb => cb.checked = false);
        updateSelection();
    }
}

// COPY LOGIC
function copyAll() {
    const textareas = document.querySelectorAll("#selected-list textarea");
    let fullText = "";
    textareas.forEach(ta => { fullText += ta.value + "\n\n"; });

    if (!fullText) {
        alert("Nothing to copy!");
        return;
    }

    navigator.clipboard.writeText(fullText).then(() => {
        alert("Copied to clipboard!");
    });
}

// FINISH LOGIC
function finishToEval() {
    window.location.href = 'campus.php';
}
</script>

</body>
</html>
<?php $conn->close(); ?>