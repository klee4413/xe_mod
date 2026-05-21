<?php
// [TIMESTAMP: 2026-04-05] -exam-fin-sel.php
require_once 'db-connect.php';
session_start();
$student_id = $_SESSION['user_id'] ?? null;
$first_name = $_SESSION['first_name'] ?? 'Scholar';
$last_name  = $_SESSION['last_name'] ?? '';
if (!$student_id) {    header("Location: login.php");    exit();}
$date = date("F j, Y");
// 2. DATABASE CONNECTIVITY
//$host = 'localhost'; $db = 'ai-hi-work'; $user = 'root'; $pass = ''; 
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) { die("Database Connection Fault."); }

// 3. RETRIEVE CURRENT TRANSCRIPT DATA
$stmt = $pdo->prepare("SELECT course_code, marks_mid, marks_final, regit_date FROM course_regit WHERE student_id = ?");
$stmt->execute([$student_id]);
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 4. SESSION GENERATION LOGIC (The Manual Spark)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['typed_course'])) {
    // Grounding the two extra variables you requested
    $_SESSION['active_exam_course'] = trim($_POST['typed_course']);
    $_SESSION['active_exam_type']   = trim($_POST['typed_exam_term']);//MID or FIN
    
    // Ensure ID and Name remain grounded
    $_SESSION['exam_student_id']    = $student_id;
    $_SESSION['exam_first_name']    = $first_name;
    $_SESSION['exam_last_name']     = $last_name;
    $_SESSION['exam_start_time']    = time();

    header("Location:exam-fin-list.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>AIGC | Transcript Exam Gate</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background-color: #218067; font-family: 'Inter', sans-serif; }
        .glass-card { background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.1); }
    </style>
</head>
<body class="min-h-screen p-8 text-white">

    <div class="max-w-4xl mx-auto">
        <header class="text-center mb-10">
            <h1 class="text-4xl font-black uppercase  tracking-tighter">AIGC Final Exam Selection</h1>
            <p class="text-white-400 font-bold tracking-widest mt-2">
                <?php echo "$first_name $last_name (ID: $student_id)"; ?>
            </p>
        </header>

        <div class="glass-card rounded-[2rem] p-8 mb-8">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-white-600 text-[10px] uppercase tracking-[0.3em] border-b border-white/10">
                        <th class="pb-4">Course Selected</th>
                        <th class="pb-4">Mid Score</th>
                        <th class="pb-4">Final Score</th>
						<th class="pb-4">Start Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    <?php foreach ($courses as $c): ?>
                    <tr class="font-bold text-sm">
                        <td class="py-4 uppercase"><?php echo htmlspecialchars($c['course_code']); ?></td>
                        <td class="py-4 text-white-400"><?php echo htmlspecialchars($c['marks_mid']); ?></td>
                        <td class="py-4 text-white-400"><?php echo htmlspecialchars($c['marks_final']); ?></td>
						<td class="py-4 text-white-400"><?php echo htmlspecialchars($c['regit_date']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="glass-card rounded-[2rem] p-10">
            <h2 class="text-xl font-white uppercase bold mb-8 text-center text-black-500">Enter Course Code & Exam Type</h2>
            
            <form method="POST" class="space-y-8"><form id="examForm" action="exam-fin-list.php" method="POST" class="p-6 md:p-10"> 
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-emerald-500 mb-3">Type Course Code in</label>
                        <input type="text" name="typed_course" required placeholder="AIM100"
                               class="w-full bg-black/40 border border-white/20 rounded-xl px-6 py-4 text-white font-bold focus:border-white-500 outline-none uppercase">
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-emerald-500 mb-3">Type Exam Term (fin) in</label>
                        <input type="text" name="typed_exam_term" required placeholder="FIN"
                               class="w-full bg-black/40 border border-white/20 rounded-xl px-6 py-4 text-white font-bold focus:border-white-500 outline-none uppercase">
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full bg-yellow-600 hover:bg-emerald-500 text-slate-950 font-black py-5 rounded-2xl uppercase tracking-widest text-sm transition-all shadow-xl transform hover:-translate-y-1">
                        Confirm Selection & Proceed to Final Exam
                    </button>
                </div>
            </form>
        </div>

        <div class="mt-10 text-center">
            <a href="campus.php" class="text-sm font-bold text-white/40 hover:text-emerald-400 transition-colors uppercase tracking-[0.4em]">
                &larr; Return to Campus
            </a>
        </div>
    </div>

</body>
</html>