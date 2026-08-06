<?php
// [TIMESTAMP: 2026-03-06] - GAC EXAM ANALYTICS & DEAN'S REPORT exam_result.php
session_start();
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
// 1. IDENTITY GROUNDING
$student_id = $_POST['student_id'] ?? '2026-AI-001';
$last_name = $_POST['last_name'] ?? 'Lee';
$date = date("F j, Y");

try {
    // 2. FETCH ALL RECORDED ANSWERS FOR THIS SESSION
    $stmt = $pdo->query("SELECT * FROM gac_exam_answers");
    $all_answers = $stmt->fetchAll();

    $correct = [];
    $incorrect = [];
    $incorrect_text = ""; // For AI Analysis

    foreach ($all_answers as $row) {
        if ($row['answer'] === $row['student_answer']) {
            $correct[] = $row;
        } else {
            $incorrect[] = $row;
            $incorrect_text .= $row['quiz'] . " "; // Bundle missed content for Gemini
        }
    }

    // --- GEMINI AI ANALYSIS SPARK ---
    // (Assuming $gemini_key is pulled from your api-hook as established previously)
    // For this prompt, we generate the analysis based on the missed quiz content.
    
    $analysis_prompt = "Analyze these missed quiz questions: '$incorrect_text'. 
    1. Identify a 'Strong Area' based on successful participation (General AI). 
    2. Identify a 'Weak Area' (Max 50 words each). 
    3. Provide a 'Study Direction' (Max 15 words).";

    // [Note: Placeholder for the cURL API call we built in prompt-lab.php]
    $strong_area = "Your performance in general AI conceptualization is exemplary. You demonstrate a clear understanding of Multi-Source Synthesis and prompt logic.";
    $weak_area = "Analysis of errors suggests a need for deeper focus on technical implementation, specifically database schema relationships and SQL syntax nuances.";
    $study_direction = "Focus on SQL JOIN logic and Firebase data structural relationships for 30 minutes daily.";

} catch (PDOException $e) {
    die("Result Logic Fault: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GAC | Exam Result</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-size: 20px; }
        .gac-frame-green { border: 4px solid #059669; padding: 15px; border-radius: 15px; color: #059669; font-weight: 900; }
        .gac-frame-red { border: 4px solid #dc2626; padding: 15px; border-radius: 15px; color: #dc2626; font-weight: 900; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body class="bg-gray-50 p-4 md:p-10 font-sans text-gray-900">

    <div class="max-w-5xl mx-auto bg-white shadow-2xl rounded-[3rem] overflow-hidden border-t-[15px] border-[#064e3b]">
        
        <header class="p-10 bg-gray-50 border-b border-gray-100 flex justify-between items-end">
            <div>
                <h1 class="text-4xl font-black uppercase tracking-tighter">Result of Term Exam</h1>
                <p class="text-gray-500 font-bold mt-2 uppercase tracking-widest"><?php echo $date; ?></p>
            </div>
            <div class="text-right">
                <p class="text-sm font-bold text-green-600 uppercase">Scholar Profile</p>
                <p class="font-black text-2xl"><?php echo htmlspecialchars($last_name); ?> (<?php echo htmlspecialchars($student_id); ?>)</p>
            </div>
        </header>

        <main class="p-10 space-y-16">
            
            <section>
                <h2 class="text-2xl font-black text-green-700 mb-6 uppercase">✓ Section 1: Correctly Answered</h2>
                <div class="space-y-4 mb-8">
                    <?php foreach($correct as $index => $c): ?>
                        <div class="bg-green-50 p-6 rounded-2xl text-sm">
                            <span class="font-black text-green-800"><?php echo $index+1; ?>.</span> 
                            <?php echo htmlspecialchars($c['quiz']); ?>
                            <span class="ml-4 font-black text-green-800">[Answer: <?php echo $c['answer']; ?>]</span>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="gac-frame-green text-center text-xl">
                    Total count of correctly answer is <?php echo count($correct); ?>
                </div>
            </section>

            <section>
                <h2 class="text-2xl font-black text-red-700 mb-6 uppercase">✗ Section 2: Incorrectly Answered</h2>
                <div class="space-y-4 mb-8">
                    <?php foreach($incorrect as $index => $i): ?>
                        <div class="bg-red-50 p-6 rounded-2xl text-sm">
                            <span class="font-black text-red-800"><?php echo $index+1; ?>.</span> 
                            <?php echo htmlspecialchars($i['quiz']); ?>
                            <span class="ml-4 font-black text-red-600">[Key: <?php echo $i['answer']; ?>]</span>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="gac-frame-red text-center text-xl">
                    Total count of incorrectly answer is <?php echo count($incorrect); ?>
                </div>
            </section>

            <section class="bg-blue-50 p-10 rounded-[2rem] border-2 border-blue-100">
                <h2 class="text-2xl font-black text-blue-900 mb-6 uppercase tracking-tight">Academic Performance Summary</h2>
                <div class="grid md:grid-cols-2 gap-10">
                    <div>
                        <h3 class="font-black text-blue-800 mb-2">1. Strong Area of Study</h3>
                        <p class="text-base leading-relaxed"><?php echo $strong_area; ?></p>
                    </div>
                    <div>
                        <h3 class="font-black text-red-800 mb-2">2. Weak Area of Study</h3>
                        <p class="text-base leading-relaxed"><?php echo $weak_area; ?></p>
                    </div>
                </div>
            </section>

            <section class="text-center py-10 border-t-4 border-dashed border-gray-100">
                <h2 class="text-xs font-black text-gray-400 uppercase tracking-[0.3em] mb-4">Recommended Study Direction</h2>
                <p class="text-2xl font-black text-[#064e3b] italic">"<?php echo $study_direction; ?>"</p>
            </section>

            <div class="flex justify-end no-print pt-10">
                <button onclick="window.print()" class="bg-gray-900 text-white px-10 py-4 rounded-2xl font-black uppercase text-sm shadow-xl hover:bg-black transition-all transform active:scale-95">
                    🖨️ Print Result
                </button>
            </div>

        </main>
    </div>

    <footer class="p-10 text-center text-gray-400 font-bold uppercase tracking-widest">
        &copy; 2026 Gemini AI College | Official Transcript
    </footer>

</body>
</html>
