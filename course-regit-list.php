<?php
// [TIMESTAMP: 2026-04-02] - GAC Enrollment & Credit Logic Gate course-regit-list.php
session_start();
require_once __DIR__ . '/../db-connect.php';
//require_once 'db-connect.php';
$student_id = $_SESSION['user_id']    ?? 0;
$first_name = $_SESSION['first_name'] ?? 'Scholar';
$last_name  = $_SESSION['last_name']  ?? '';
$email      = $_SESSION['email']      ?? '';
//$host = 'localhost'; $db = 'ai-hi-work'; $user = 'root'; $pass = ''; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) { die("Database Connection Fault."); } 
$date = date("Y-m-d") ;
$student_id = $_SESSION['user_id'] ?? 9999;
$error_msg = ""; 
$success_msg = "";

// 1. CALCULATE CURRENT LOAD (Surgical Single-Table Logic)
$load_stmt = $pdo->prepare("SELECT SUM(credit_hour) FROM course_regit WHERE student_id = ?");
$load_stmt->execute([$student_id]);
$current_load = (int)$load_stmt->fetchColumn();

// 2. CAPTURE PENDING SELECTION (From Session Bridge)
$pending_courses = $_SESSION['pending_enrollment'] ?? [];
$pending_count = count($pending_courses);
$pending_credits = 0;

if (!empty($pending_courses)) {
    $placeholders = implode(',', array_fill(0, $pending_count, '?'));
    $stmt_p = $pdo->prepare("SELECT SUM(credit_hour) FROM classes WHERE class_id IN ($placeholders)");
    $stmt_p->execute($pending_courses);
    $pending_credits = (int)$stmt_p->fetchColumn();
}

$total_projected_load = $current_load + $pending_credits;

// 3. PROCESSING REGISTRATION (The Logic Gate)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_enrollment'])) {
    
    // REQUEST 1: If total credits >= 12, block and warn.
    if ($total_projected_load > 12) {
        $error_msg = "ENROLLMENT BLOCKED: Your projected load of $total_projected_load credits exceeds the 12-credit limit. Please reduce your selection.";
    } 
    // REQUEST 2: If credits < 13 (meaning 12 or less), perform insert.
    else if ($total_projected_load <= 12 && !empty($pending_courses)) {
        try {
            $pdo->beginTransaction();
            
            $insert = $pdo->prepare("INSERT INTO course_regit (student_id, course_code, credit_hour) VALUES (?, ?, ?)");
            $stmt_info = $pdo->prepare("SELECT credit_hour FROM classes WHERE class_id = ?");

            foreach ($pending_courses as $code) {
                $stmt_info->execute([$code]);
                $hour = $stmt_info->fetchColumn();
                $insert->execute([$student_id, $code, $hour]);
            }

            $pdo->commit();
            
            // PURIFICATION: Clear the session and set success states
            unset($_SESSION['pending_enrollment']);
            $success_msg = "REGISTRATION SUCCESSFUL: $pending_count courses grounded. Total credits now: $total_projected_load.";
            
            // Update local variables for UI rendering
            $current_load = $total_projected_load;
            $pending_courses = []; 
            $pending_count = 0;
            
        } catch (Exception $e) {
            $pdo->rollBack();
            $error_msg = "System Fault: Registration failed. " . $e->getMessage();
        }
    }
}

// 4. FINAL LIST RETRIEVAL
$stmt = $pdo->prepare("SELECT * FROM course_regit WHERE student_id = ? ORDER BY regit_date DESC");
$stmt->execute([$student_id]);
$my_courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>GAC | Enrollment Logic Gate</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style> body { background-color: #AEFCAA; font-family: 'Inter', sans-serif; } </style>
</head>
<body class="p-8">
    <div class="max-w-5xl mx-auto">
        <header class="mb-10 flex justify-between items-end">
            <div>
                <h1 class="text-3xl font-black text-slate-900 uppercase tracking-tighter">AIGC Academic Transcript</h1>
                <p class="text-slate-500 font-bold">Student ID: <?php echo $student_id. " Name:  " . $_SESSION['last_name']." ".$date; ?></p>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 text-center">
                <span class="text-[10px] font-black uppercase text-slate-800 tracking-widest block">Max Simultaneous Study Credit Hours are 12</span>
                <div class="text-3xl font-black <?php echo $current_load > 12 ? 'text-red-500' : 'text-emerald-600'; ?>">
                    <?php echo 'Total: '. $current_load; ?> / 12 Hours Max  
                </div>
            </div>
        </header>

        <?php if($error_msg): ?>
            <div class="mb-8 p-6 bg-red-50 border-2 border-red-200 text-red-700 rounded-2xl font-black italic shadow-lg flex items-center gap-4">
                <span class="text-2xl">⚠️</span> <?php echo $error_msg; ?>
            </div>
        <?php endif; ?>

        <?php if($success_msg): ?>
            <div class="mb-8 p-6 bg-emerald-50 border-2 border-emerald-200 text-emerald-700 rounded-2xl font-black italic shadow-lg flex items-center gap-4">
                <span class="text-2xl">✅</span> <?php echo $success_msg; ?>
            </div>
        <?php endif; ?>

        <div class="bg-white rounded-[2rem] shadow-xl overflow-hidden border border-slate-200 mb-12">
            <table class="w-full text-left">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="p-6 text-[10px] uppercase font-black text-slate-400">Course Code</th>
                        <th class="p-6 text-[10px] uppercase font-black text-slate-400">Credits</th>
                        <th class="p-6 text-[10px] uppercase font-black text-slate-400 text-center">Mid Term</th>
                        <th class="p-6 text-[10px] uppercase font-black text-slate-400 text-center">Final Marks</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-bold text-slate-700">
                    <?php foreach($my_courses as $course): ?>
                    <tr class="hover:bg-slate-50/50">
                        <td class="p-6"><?php echo $course['course_code']; ?></td>
                        <td class="p-6"><?php echo $course['credit_hour']; ?></td>
                        <td class="p-6 text-center text-emerald-600"><?php echo number_format($course['marks_mid'], 2); ?></td>
                        <td class="p-6 text-center text-emerald-600"><?php echo number_format($course['marks_final'], 2); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <?php if ($pending_count > 0 && empty($success_msg)): ?>
        <div class="bg-slate-900 rounded-[2.5rem] p-10 text-white shadow-2xl relative overflow-hidden">
            <div class="absolute top-0 right-0 p-8 opacity-10">
                <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
            </div>
            
            <h2 class="text-xl font-black uppercase tracking-widest mb-8 text-emerald-400 italic">Pending Enrollment Validation</h2>
            <ul class="space-y-3 mb-10">
                <?php foreach ($pending_courses as $code): ?>
                <li class="flex justify-between items-center bg-slate-800 p-4 rounded-xl border border-slate-700">
                    <span class="font-black"><?php echo htmlspecialchars($code); ?></span>
                    <span class="text-[9px] font-black uppercase text-slate-500">Validation Required</span>
                </li>
                <?php endforeach; ?>
            </ul>

            <div class="flex items-center justify-between border-t border-slate-800 pt-8">
                <div>
                    <span class="text-[10px] uppercase text-slate-500 font-bold block mb-1">Projected Total Load</span>
                    <p class="text-2xl font-black"><?php echo $total_projected_load; ?> Credits</p>
                </div>
                <form method="POST">
                    <button type="submit" name="confirm_enrollment" class="bg-emerald-500 hover:bg-emerald-600 text-slate-950 font-black px-12 py-4 rounded-2xl uppercase tracking-widest text-xs transition-all shadow-lg transform hover:-translate-y-1">
                        Confirm & Ground Enrollment
                    </button>
                </form>
            </div>
        </div>
        <?php endif; ?>

        <div class="mt-8 text-center">
            <a href="campus.php" class="text-[20px] font-black text-slate-800 uppercase tracking-widest hover:text-slate-900 transition-colors">Return to Campus</a>
        </div>
		 <!--div class="mt-8 text-center">
            <a href="exam-select-now1.php" class="text-[20px] font-black text-slate-800 uppercase tracking-widest hover:text-slate-900 transition-colors">Go to Exam Process</a>
        </div-->
    </div>
</body>
</html>