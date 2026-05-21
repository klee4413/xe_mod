<?php
// [TIMESTAMP: 2026-04-02] - Enrollment & Grading Hub course-regit2.php
session_start();
//$host = 'localhost'; $db = 'ai-hi-work'; $user = 'root'; $pass = ''; 
require_once 'db_connect_local.php';
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) { die("Database Connection Fault."); } 
$student_id = $_SESSION['user_id'] ?? 9999;
$last_name  = $_SESSION['last_name'] ?? 'Guest';
$error_msg = ""; 
$success_msg = "";
$date=date("Y-m-d") ; 
//$load_stmt->execute([$student_id]);
//$current_load = (int)$load_stmt->fetchColumn();

// 1. CALCULATE CURRENT LOAD (Surgical Single-Table Logic)
// Now reading directly from course_regit since credit_hour is grounded there.
$load_stmt = $pdo->prepare("SELECT SUM(credit_hour) FROM course_regit WHERE student_id = ?");
$load_stmt->execute([$student_id]);
$current_load = (int)$load_stmt->fetchColumn();//

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

// 3. PROCESSING REGISTRATION (The "Confirm & Register" Click)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_enrollment'])) {
    if (!empty($pending_courses)) {
        if (($current_load + $pending_credits) > 12) {
            $error_msg = "ENROLLMENT BLOCKED: Total load (" . ($current_load + $pending_credits) . ") exceeds the 12-credit limit.";
        } else {
            // SURGICAL INSERT: Corrected syntax and using credit_hour column
            $insert = $pdo->prepare("INSERT INTO course_regit (student_id, course_code, credit_hour) VALUES (?, ?, ?)");
            $stmt_info = $pdo->prepare("SELECT credit_hour FROM classes WHERE class_id = ?");

            foreach ($pending_courses as $code) {
                $stmt_info->execute([$code]);
                $hour = $stmt_info->fetchColumn();
                $insert->execute([$student_id, $code, $hour]);
            }

            unset($_SESSION['pending_enrollment']);
            $current_load += $pending_credits;
            $pending_count = 0;
            $pending_credits = 0;
            $success_msg = "Registration Successful! Your courses are added.";
        }
    }
}

// 4. FINAL LIST RETRIEVAL: Pulling updated grading columns
$stmt = $pdo->prepare("SELECT * FROM course_regit WHERE student_id = ? ORDER BY regit_date DESC");
$stmt->execute([$student_id]);
$my_courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>AIGC | Enrollment & Progress</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background-color: #f8fafc; font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="p-8">
    <div class="max-w-5xl mx-auto">
        <header class="mb-10 flex justify-between items-end">
            <div>
                <h1 class="text-3xl font-black text-slate-900 uppercase">AIGC-Current Enrollment</h1>
                <p class="text-slate-500 font-bold">Student ID: <?php echo $student_id.' , Name  '. $last_name; ?></p>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                <span class="text-[10px] font-black uppercase text-slate-400 tracking-widest block mb-1">Total Credit Load</span>
                <div class="text-3xl font-black <?php echo $current_load >= 12 ? 'text-red-500' : 'text-emerald-600'; ?>">
                    <?php echo $current_load; ?> / 12 Credits
                </div>
            </div>
        </header>

        <?php if($success_msg): ?>
            <div class="mb-8 p-4 bg-emerald-50 border border-emerald-200 text-emerald-600 rounded-xl font-bold italic"><?php echo $success_msg; ?></div>
        <?php endif; ?>

        <div class="bg-white rounded-[2rem] shadow-xl overflow-hidden border border-slate-200">
            <table class="w-full text-left">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="p-6 text-[10px] uppercase font-black text-slate-400">Course</th>
                        <th class="p-6 text-[10px] uppercase font-black text-slate-400">Credits</th>
                        <th class="p-6 text-[10px] uppercase font-black text-slate-400">Registered</th>
                        <th class="p-6 text-[10px] uppercase font-black text-slate-400 text-center">Mid Term</th>
                        <th class="p-6 text-[10px] uppercase font-black text-slate-400 text-center">Final Marks</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    <?php foreach($my_courses as $course): ?>
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="p-6 font-black text-slate-900"><?php echo $course['course_code']; ?></td>
                        <td class="p-6 text-sm text-slate-600"><?php echo $course['credit_hour']; ?></td>
                        <td class="p-6 text-xs text-slate-400 font-mono"><?php echo $course['regit_date']; ?></td>
                        <td class="p-6 text-center font-black text-emerald-600"><?php echo number_format($course['marks_mid'], 2); ?></td>
                        <td class="p-6 text-center font-black text-emerald-600"><?php echo number_format($course['marks_final'], 2); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <?php if ($pending_count > 0): ?>
        <div class="mt-12 bg-amber-50 border-2 border-dashed border-amber-200 rounded-3xl p-10 shadow-inner">
            <h2 class="text-xl font-black text-amber-800 uppercase tracking-tighter mb-8">! Registering Courses Selected</h2>
            <ul class="space-y-4 mb-8">
                <?php foreach ($pending_courses as $code): ?>
                <li class="flex justify-between items-center bg-white p-5 rounded-2xl shadow-sm border border-amber-100">
                    <span class="font-black text-slate-800"><?php echo htmlspecialchars($code); ?></span>
                    <span class="text-[10px] font-black text-amber-500 uppercase bg-amber-50 px-3 py-1 rounded-lg">Awaiting Registration</span>
                </li>
                <?php endforeach; ?>
            </ul>
            <div class="flex items-center justify-between">
                <p class="text-sm font-bold text-amber-700">Pending Load: <?php echo $pending_credits; ?> Credits</p>
                <form method="POST">
                    <button type="submit" name="confirm_enrollment" class="bg-amber-500 hover:bg-amber-600 text-white font-black px-10 py-4 rounded-2xl uppercase tracking-widest text-xs shadow-lg transition-all transform hover:scale-105">Confirm & Register</button>
                </form>
            </div>
        </div>
        <?php endif; ?>

        <div class="mt-12 flex flex-col md:flex-row gap-4">
            <a href="bursar.php" class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white font-black py-5 rounded-2xl shadow-lg text-center transition-all uppercase tracking-widest text-sm">Go to Bursar's Office for not paid yet</a>
            <a href="campus.php" class="flex-1 bg-slate-800 hover:bg-slate-900 text-white font-black py-5 rounded-2xl shadow-lg text-center transition-all uppercase tracking-widest text-sm">Back to Campus</a>
        </div>
    </div>
</body>
</html>