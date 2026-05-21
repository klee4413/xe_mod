<?php
// [TIMESTAMP: 2026-04-05] - GAC TERM EXAM FOUNDRY (Purified)  exam-mid-list.php
session_start();
require_once 'db_connect.php';
$date = date("F j, Y");
// 1. UNPACKING THE SOVEREIGN IDENTITY
$student_id          = $_SESSION['user_id'] ?? null;  
$selected_course = $_SESSION['active_exam_course'] ?? null; 
$selected_type =    $_SESSION['active_exam_type'] ?? null;//MID or FIN
$first_name      = $_SESSION['exam_first_name'] ?? 'Scholar';
$last_name       = $_SESSION['exam_last_name'] ?? '';
$start_time      = $_SESSION['exam_start_time'] ?? time();

// Security Gate: Ensure the session bridge is grounded
if (!$student_id || !$selected_course) {
    header("Location: exam-mid-sel.php?error=session_loss");
    exit();
}

try {
    // 2. INITIALIZATION: Clear the temporary answers table
    $pdo->exec("TRUNCATE TABLE gac_exam_answers");

    // 3. SECURE QUESTION RETRIEVAL
    // Logic: Fixed the variable name and removed the unused ? parameter that caused HY093
    $stmt = $pdo->prepare("
        SELECT * FROM gac_exam_questions 
        WHERE course_code = ? 
        AND CAST(level AS UNSIGNED) < 6 
        ORDER BY RAND() 
        LIMIT 50
    ");
    
    // We pass only ONE parameter because there is only ONE '?' in the query above
    $stmt->execute([$selected_course]); 
    $quizzes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 4. FOUNDRY INSERT (Grounding questions into the answers table)
    // Matches your columns: id, class_id, course_code, quiz, answer
    $insert_stmt = $pdo->prepare("
        INSERT INTO gac_exam_answers (id, class_id, course_code, quiz, answer, student_answer) 
        VALUES (?, ?, ?, ?, ?, NULL)
    ");

    foreach ($quizzes as $q) {
        $insert_stmt->execute([
            $q['id'], 
            $q['class_id'], 
            $q['course_code'], 
            $q['quiz'], 
            $q['answer']
        ]);
    }
} catch (PDOException $e) {
    die("GAC Infrastructure Fault: " . $e->getMessage());
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AIGC |MID Term Exam - <?php echo htmlspecialchars($selected_class); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-size: 20px; }
        .gac-frame { border: 12px solid #064e3b; }
        input[type="radio"] { width: 25px; height: 25px; vertical-align: middle; }
        /* Frozen State */
        .btn-frozen { background-color: #9ca3af !important; cursor: not-allowed; opacity: 0.5; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen p-2 md:p-6 font-sans">

    <div class="max-w-4xl mx-auto gac-frame bg-white rounded-3xl shadow-2xl overflow-hidden">
        <header class="bg-[#064e3b] text-white p-8 text-center">
            <h1 class="text-3xl font-black uppercase tracking-tighter">AIGC MID Term Examination </h1>
            <p class="text-green-300 font-bold mt-2 uppercase"><?php echo htmlspecialchars($last_name); ?> | <?php echo htmlspecialchars($selected_course.'  | '.$date); ?></p>
        </header>

        <form id="examForm" action="exam-mid-result.php" method="POST" class="p-6 md:p-10">
            <div class="space-y-12 mb-12">
                <?php foreach ($quizzes as $index => $q): ?>
                    <div class="border-b-4 border-gray-100 pb-10">
                        <p class="font-bold text-gray-900 mb-6"><?php echo ($index+1) . ". " . htmlspecialchars($q['quiz']); ?></p>
                        <div class="grid grid-cols-2 gap-4">
                            <label class="flex items-center gap-4 p-5 bg-green-50 rounded-2xl cursor-pointer">
                                <input type="radio" name="student_answers[<?php echo $q['id']; ?>]" value="T" required>
                                <span class="font-black text-green-800">TRUE</span>
                            </label>
                            <label class="flex items-center gap-4 p-5 bg-red-50 rounded-2xl cursor-pointer">
                                <input type="radio" name="student_answers[<?php echo $q['id']; ?>]" value="F">
                                <span class="font-black text-red-800">FALSE</span>
                            </label>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="flex flex-col gap-6 items-center">
                <button type="button" id="reviewBtn" onclick="saveProgress()" class="w-full max-w-md bg-yellow-500 hover:bg-yellow-600 text-white py-6 rounded-full font-black text-2xl uppercase shadow-xl transition-all">
                    1. Review & Confirm
                </button>

                <button type="submit" id="submitBtn" disabled class="w-full max-w-md btn-frozen text-white py-6 rounded-full font-black text-2xl uppercase shadow-xl transition-all">
                    2. Finish & Submit
                </button>
            </div>
        </form>
    </div>

    <script>
        function saveProgress() {
            const form = document.getElementById('examForm');
            const formData = new FormData(form);

            // Manual Spark: Sending data to background saver
            fetch('save_answers.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                alert("GAC Logic: Answers Saved to Database. Step 2 Unlocked.");
                const submitBtn = document.getElementById('submitBtn');
                submitBtn.disabled = false;
                submitBtn.classList.remove('btn-frozen');
                submitBtn.classList.add('bg-[#064e3b]', 'hover:bg-black');
                
                document.getElementById('reviewBtn').innerText = "✓ Answers Saved";
                document.getElementById('reviewBtn').disabled = true;
                document.getElementById('reviewBtn').classList.add('opacity-50');
            })
            .catch(error => alert("Infrastructure Fault: " + error));
        }
    </script>
</body>
</html>