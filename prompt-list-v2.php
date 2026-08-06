<?php
// Database connection prompt-list-v2.php
session_start(); // Ensures $_SESSION values are accessible

$isLocal = in_array($_SERVER['HTTP_HOST'] ?? '', ['localhost', '127.0.0.1']) 
        || str_contains($_SERVER['HTTP_HOST'] ?? '', 'localhost:');

if ($isLocal) {
    require_once 'db-connect.php';
} else {
    require_once __DIR__ . '/../db-connect.php';
}

$student_id = $_SESSION['user_id']    ?? 0;
$first_name = $_SESSION['first_name'] ?? 'Scholar';
$last_name  = $_SESSION['last_name']  ?? '';
$email      = $_SESSION['email']      ?? '';
$date       = date("Y-m-d");

try {
    // Execute query via PDO ($pdo object is inherited from db-connect.php)
    $stmt = $pdo->query("SELECT id, category, description FROM prompt_code");
    
    // Fetch all records as an associative array
    $prompts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database Logic Fault: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GAC | Core Code Examples for Code Test Practice List v2</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: #065f46; /* Emerald Dark Outer Canvas */
            margin: 0; 
            padding: 16px; 
            min-height: 100vh;
            box-sizing: border-box;
        }

        /* Outer Frame Container */
        .container { 
            max-width: 1200px; 
            margin: auto; 
            background: #fffbeb; /* Warm Light Amber Background */
            padding: 24px; 
            border: 8px solid #059669; /* Thick Neo Green Frame */
            border-radius: 20px; 
            box-shadow: 12px 12px 0px #000000; 
        }

        /* FREEZE SEARCH BAR SECTION */
        .sticky-search-container {
            position: sticky;
            top: 0;
            background-color: #ffffff;
            padding: 16px;
            z-index: 1000;
            border: 4px solid #000000;
            border-radius: 12px;
            box-shadow: 6px 6px 0px #000000;
            margin-bottom: 24px;
        }

        /* BIG SEARCH BOX WITH THICK BLACK FRAME */
        #searchInput { 
            width: 100%; 
            padding: 16px; 
            font-size: 18px; 
            font-weight: 800;
            border: 3px solid #000000; 
            border-radius: 8px; 
            box-sizing: border-box;
            outline: none;
            background-color: #fef08a; /* Yellow Neo Accent */
            box-shadow: 3px 3px 0px #000000;
        }

        table { width: 100%; border-collapse: separate; border-spacing: 0; margin-top: 10px; background-color: #ffffff; border: 4px solid #000000; border-radius: 12px; overflow: hidden; box-shadow: 6px 6px 0px #000000; }
        th, td { border-bottom: 2px solid #000000; border-right: 2px solid #000000; padding: 12px; text-align: left; color: #000; }
        th { background-color: #a7f3d0; font-weight: 900; font-size: 14px; text-transform: uppercase; border-bottom: 3px solid #000; }
        tr:last-child td { border-bottom: none; }
        td:last-child, th:last-child { border-right: none; }
        tr:hover { background-color: #fef08a; }
        
        /* Selected Box Style */
        #selected-container { 
            margin-top: 30px; 
            padding: 20px; 
            border: 4px solid #000000; 
            border-radius: 12px; 
            background-color: #bae6fd; /* Light Blue Neo Accent */
            box-shadow: 6px 6px 0px #000000;
        }
        
        .selected-item { 
            background: #ffffff; 
            border: 3px solid #000000; 
            padding: 12px; 
            margin-bottom: 12px; 
            border-radius: 8px; 
            box-shadow: 3px 3px 0px #000000;
        }
        
        .selected-item textarea { 
            width: 100%; 
            border: 2px solid #000000; 
            border-radius: 6px;
            padding: 8px;
            resize: vertical; 
            font-family: inherit; 
            font-size: 14px; 
            font-weight: 600;
            background-color: #fff;
            box-sizing: border-box;
        }
        
        /* Button Styles */
        .button-group { margin-top: 20px; display: flex; gap: 12px; flex-wrap: wrap; }
        .btn { 
            padding: 12px 24px; 
            border: 3px solid #000000; 
            border-radius: 8px; 
            cursor: pointer; 
            font-weight: 900; 
            color: #000000; 
            font-size: 14px; 
            text-transform: uppercase;
            box-shadow: 4px 4px 0px #000000;
            transition: all 0.1s ease;
        }
        .btn:active { transform: translate(2px, 2px); box-shadow: 2px 2px 0px #000000; }
        .btn-copy { background-color: #4ade80; }
        .btn-reset { background-color: #f87171; }
        .btn-finish { background-color: #60a5fa; }
    </style>
</head>
<body>

<div class="container">
    <div class="sticky-search-container">
        <h2 style="color: #000; font-weight: 900; text-transform: uppercase; letter-spacing: -0.5px; border-bottom: 3px solid #000; padding-bottom: 8px; margin-top: 0; margin-bottom: 16px;">
            AI Gemini College Core Programming Code List v2
        </h2>
        <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Search Category or Description Contents...">
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 80px;">Select</th>
                <th style="width: 60px;">ID</th>
                <th style="width: 220px;">Category</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody id="promptTableBody">
            <?php if (!empty($prompts) && count($prompts) > 0): ?>
                <?php foreach ($prompts as $row): ?>
                    <tr>
                        <td style="text-align: center;">
                            <input type="checkbox" class="prompt-check" style="width: 18px; height: 18px; cursor: pointer;"
                                   data-id="<?php echo $row['id']; ?>" 
                                   data-category="<?php echo htmlspecialchars($row['category']); ?>" 
                                   data-desc="<?php echo htmlspecialchars($row['description']); ?>" 
                                   onchange="updateSelection()">
                        </td>
                        <td><strong><?php echo $row['id']; ?></strong></td>
                        <td><strong><?php echo htmlspecialchars($row['category']); ?></strong></td>
                        <td style="font-weight: 600;"><?php echo nl2br(html_entity_decode($row['description'])); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" style="text-align: center; font-weight: 800; padding: 20px;">No prompt codes found in repository.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div id="selected-container">
        <h3 style="margin-top: 0; font-weight: 900; text-transform: uppercase; color: #000;">Selected Prompts (Editable)</h3>
        <div id="selected-list"></div>
        
        <div class="button-group">
            <button class="btn btn-copy" onclick="copyAll()">Copy All to Clipboard</button>
            <button class="btn btn-reset" onclick="resetSelection()">Reset Selection</button>
            <button class="btn btn-finish" onclick="finishToEval()">Return to Campus</button>             
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
        if (row.cells.length < 4) return;
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
        div.innerHTML = `<strong style="font-weight: 900;">ID: ${id} | Category: ${category}</strong><br>
                         <textarea rows="3" class="mt-2">${desc}</textarea>`;
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
