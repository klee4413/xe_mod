<?php
// =========================================================================
// AIGC COGNITIVE FOUNDRY — PURE SERVER INTERFACE : interview.php
// PURPOSE: PURE PHP READ, RANDOMIZE, AND FORM SUBMIT LAYER (NO JAVASCRIPT)
// =========================================================================
session_start();
$student_id = $_SESSION['user_id']    ?? 9990;
$first_name = $_SESSION['first_name'] ?? 'Scholar';
$last_name  = $_SESSION['last_name']  ?? '';
$email      = $_SESSION['email']  ?? '';
require_once __DIR__ . '/../db-connect.php';
date_default_timezone_set('America/Los_Angeles');

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);

    // 1. FORM SUBMIT HANDLER: Catch pure POST array from browser submittal
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_interview'])) {
        $selected_checkboxes = $_POST['selected_records'] ?? [];
        $displayed_ids = $_POST['displayed_ids'] ?? [];

        if (empty($selected_checkboxes)) {
            echo "<script>alert('Please select at least one statement!'); window.history.back();</script>";
            exit;
        }

        // 2. FETCH DETAILED FIELDS TO MAP BACK TO LOCALSTORAGE BUCKETS
        // Pulls records matching what the user saw to maintain data trail integrity
        $placeholders = implode(',', array_fill(0, count($displayed_ids), '?'));
        $stmt = $pdo->prepare("SELECT `no`, `response`, `attribute`, `importance`, `np` FROM `interview-response` WHERE `no` IN ($placeholders)");
        $stmt->execute($displayed_ids);
        $all_displayed_records = $stmt->fetchAll();

        $selectedData = [];
        $unselectedData = [];
  
		
		foreach ($all_displayed_records as $row) {
            $quizItem = [
                'no'     => (string)$row['no'],
                'quiz'   => str_replace('"', '&quot;', $row['response']),
                'result' => trim($row['attribute'] ?? 'General'),
                'weight' => (string)$row['importance'],
                'pn'     => trim($row['np'] ?? 'p')
            ];

            // If the row key ID exists in the submitted array, it's selected; otherwise, unselected
            if (in_array($row['no'], $selected_checkboxes)) {
                // FIXED: Removed the JavaScript .push() line that caused the crash
                $selectedData[] = $quizItem;
            } else {
                $unselectedData[] = $quizItem;
            }
        }
		

        // Convert backend arrays back to JSON objects for client session persistence compatibility
        $_SESSION['gac_selected_json'] = json_encode($selectedData);
        $_SESSION['gac_unselected_json'] = json_encode($unselectedData);

        // Redirect directly forward to Step 2 App Layer
        header("Location: interview-summary.php");
        exit;
    }

    // 3. SECURE HIGH-VELOCITY RUNTIME SHUFFLE (Done server-side inside MySQL Engine)
    $query = "SELECT `no`, `response`, `attribute`, `rank`, `importance`, `np` 
              FROM `interview-response` 
              ORDER BY RAND() 
              LIMIT 40";
    $records = $pdo->query($query)->fetchAll();

} catch (PDOException $e) {
    die("Foundry Ingestion Failure: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gemini AI College Admission Review</title>
    <style>
        /* Modern, phone-friendly reset */
        * { box-sizing: border-box; margin: 0; padding: 0; }
        
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            background-color: #bdf2bd; 
            padding: 10px; 
            color: #333;
        }

        .quiz-container { 
            max-width: 800px; 
            margin: 20px auto; 
            background: white; 
            padding: 15px; 
            border-radius: 15px; 
            box-shadow: 0 4px 10px rgba(0,0,0,0.1); 
        }

        h1 { font-size: 1.5rem; margin-bottom: 10px; text-align: center; }
        p { text-align: center; margin-bottom: 20px; font-size: 0.9rem; }

        /* Responsive framework layout grid */
        .quiz-row { 
            display: grid; 
            grid-template-columns: 40px 50px 1fr 40px; 
            gap: 5px;
            align-items: center; 
            padding: 12px 5px; 
            border-bottom: 1px solid #eee; 
            font-size: 0.85rem;
        }

        .header-row { 
            font-weight: bold; 
            background-color: #2e7d32; 
            color: white; 
            border-radius: 8px; 
            position: sticky; 
            top: 0;
            z-index: 10;
        }

        .checkbox-cell { text-align: center; }
        .quiz-check { width: 20px; height: 20px; cursor: pointer; }
        .random-cell { font-weight: bold; color: #FFFFFF; text-align: right; font-size: 0.75rem; }
        .random-no { font-weight: bold; color: #666; text-align: right; font-size: 0.75rem; }

        .btn-container { text-align: center; margin-top: 20px; }
        .btn-finish { 
            padding: 15px 40px; background: #2e7d32; color: white; border: none; 
            border-radius: 10px; cursor: pointer; font-size: 1.1rem; font-weight: bold;
            width: 100%; max-width: 300px; display: inline-block; text-transform: uppercase;
        }
        .btn-finish:hover { background-color: #1b5e20; }

        @media (max-width: 480px) {
            .quiz-row { grid-template-columns: 30px 40px 1fr 30px; font-size: 0.8rem; }
            h1 { font-size: 1.2rem; }
        }

        .AIGC-footer {
            background-color: #66ff00; height: 0.5in; display: flex; 
            align-items: center; justify-content: center; width: 100%;
            margin-top: 30px; border-top: 2px solid #2e7d32; 
        }
        .footer-text { font-size: 0.9rem; font-weight: bold; color: #1b5e20; }
    </style>
</head>
<body>

<div class="quiz-container">
    <h1>AIGC Admission Review Interview</h1>
    <p>Please select topics, thoughts, ideas, and desires those you agree with.</p>
    
    <form method="POST" action="interview.php">
        <input type="hidden" name="submit_interview" value="1">

        <div class="quiz-row header-row">
            <div>No.</div>
            <div class="checkbox-cell">Select</div>
            <div>Statements</div>
            <div class="random-cell">Random</div>
        </div>

        <div id="quiz-display">
            <?php if (empty($records)): ?>
                <div style="padding: 20px; text-align: center; color: #b91c1c; font-weight: bold;">
                    Database table `interview-response` is currently empty or unpopulated.
                </div>
            <?php else: ?>
                <?php foreach ($records as $index => $row): ?>
                    <div class="quiz-row">
                        <div><b><?php echo $index + 1; ?></b></div>
                        <div class="checkbox-cell">
                            <input type="checkbox" name="selected_records[]" value="<?php echo $row['no']; ?>" class="quiz-check">
                            <input type="hidden" name="displayed_ids[]" value="<?php echo $row['no']; ?>">
                        </div>
                        <div><?php echo htmlspecialchars($row['response'], ENT_QUOTES, 'UTF-8'); ?></div>
                        <div class="random-no"><?php echo $row['no']; ?></div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <div class="btn-container">
            <button type="submit" class="btn-finish">Finish</button>
        </div>
    </form>
</div>
<center>
<a href="interview-guide.php" class="text-red-400 hover:text-white transition-all whitespace-nowrap">
<i class="fa-solid fa-building-columns"></i> Exit </a></center>
<footer class="AIGC-footer">
    <div class="footer-text">Copyright AI Gemini College 2026 -- Applied HPAM Interview Evaluation Model by AIGC research</div>
</footer>

<script>
    // Injects data seamlessly into localStorage so your subsequent page tools can read it without modification
    const selectedJsonString = <?php echo isset($_SESSION['gac_selected_json']) ? $_SESSION['gac_selected_json'] : 'null'; ?>;
    const unselectedJsonString = <?php echo isset($_SESSION['gac_unselected_json']) ? $_SESSION['gac_unselected_json'] : 'null'; ?>;

    if (selectedJsonString !== null) {
        localStorage.setItem('gac_selected_quizzes', JSON.stringify(selectedJsonString));
    }
    if (unselectedJsonString !== null) {
        localStorage.setItem('gac_unselected_quizzes', JSON.stringify(unselectedJsonString));
    }
</script>
</body>
</html>