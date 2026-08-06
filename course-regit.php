<?php
// [TIMESTAMP: 2026-04-01] - GAC Enrollment Logic (Purified)
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
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) { die("Database Connection Fault."); } 

$student_id = $_SESSION['user_id'] ?? 9999;
$error_msg = ""; 
$success_msg = "";

// 1. CALCULATE CURRENT LOAD (From Database)
$load_stmt = $pdo->prepare("
    SELECT SUM(c.credit_hour) 
    FROM course_regit r 
    JOIN classes c ON r.course_code = c.class_id 
    WHERE r.student_id = ?
");
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

// 3. PROCESSING REGISTRATION (The "Confirm & Register" Click)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_enrollment'])) {
    if (!empty($pending_courses)) {
        if (($current_load + $pending_credits) > 12) {
            $error_msg = "ENROLLMENT BLOCKED: Total load (" . ($current_load + $pending_credits) . ") exceeds the 12-credit limit.";
        } else {
            // SURGICAL INSERT: Using your updated column name 'credit_hour'
            $insert = $pdo->prepare("INSERT INTO course_regit (student_id, course_code, credit_hour) VALUES (?, ?, ?)");
            
            // We fetch individual credit hours to insert into the log
            $stmt_info = $pdo->prepare("SELECT credit_hour FROM classes WHERE class_id = ?");

            foreach ($pending_courses as $code) {
                $stmt_info->execute([$code]);
                $hour = $stmt_info->fetchColumn();
                $insert->execute([$student_id, $code, $hour]);
            }

            // PURIFICATION: Clear the session bridge
            unset($_SESSION['pending_enrollment']);
            
            // UI Update
            $current_load += $pending_credits;
            $pending_count = 0;
            $pending_credits = 0;
            $success_msg = "Registration Successful! Your courses are locked in.";
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
    <title>GAC | Enrollment Summary</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background-color: #f8fafc; font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="p-8 text-slate-800">
    <div class="max-w-4xl mx-auto">
        <header class="mb-10 flex justify-between items-end">
            <div>
                <h1 class="text-3xl font-black uppercase">Current Enrollment</h1>
                <p class="text-slate-500 font-bold">Scholar ID: <?php echo $student_id; ?></p>
            </div>
            <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-200">
                <span class="text-xs font-black uppercase text-slate-400 tracking-widest">Total Credit Load</span>
                <div class="text-2xl font-black <?php echo $current_load >= 12 ? 'text-red-500' : 'text-emerald-600'; ?>">
                    <?php echo $current_load; ?> / 12 Credits
                </div>
            </div>
        </header>

        <?php if($error_msg): ?>
            <div class="mb-8 p-4 bg-red-50 border border-red-200 text-red-600 rounded-xl font-bold italic"><?php echo $error_msg; ?></div>
        <?php endif; ?>

        <?php if($success_msg): ?>
            <div class="mb-8 p-4 bg-emerald-50 border border-emerald-200 text-emerald-600 rounded-xl font-bold italic"><?php echo $success_msg; ?></div>
        <?php endif; ?>

        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-slate-200 mb-12">
            <table class="w-full text-left">
                <thead class="bg-slate-50 border-b">
                    <tr>
                        <th class="p-6 text-[10px] uppercase font-black text-slate-400">Course</th>
                        <th class="p-6 text-[10px] uppercase font-black text-slate-400">Credits</th>
                        <th class="p-6 text-[10px] uppercase font-black text-slate-400">Registered</th>
                        <th class="p-6 text-[10px] uppercase font-black text-slate-400 text-center">Mid Term Marks</th>
						 <th class="p-6 text-[10px] uppercase font-black text-slate-400 text-center">Final Marks</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php foreach($my_courses as $course): ?>
                    <tr class="hover:bg-slate-50">
                        <td class="p-6 font-bold"><?php echo $course['course_code']; ?></td>
                        <td class="p-6 text-sm"><?php echo $course['credit_hour']; ?></td>
                        <td class="p-6 text-xs text-slate-500 font-mono"><?php echo $course['regit_date']; ?></td>
                        <td class="p-6 text-center font-black text-emerald-600"><?php echo number_format($course['marks_mid'], 2); ?></td>
						<td class="p-6 text-center font-black text-emerald-600"><?php echo number_format($course['marks_final'], 2); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <?php if ($pending_count > 0): ?>
        <div class="mt-12 bg-amber-50 border-2 border-dashed border-amber-200 rounded-2xl p-8 shadow-inner">
            <h2 class="text-xl font-black text-amber-800 uppercase tracking-tighter mb-6">! Registering Courses (Pending)</h2>
            <ul class="space-y-3 mb-6">
                <?php foreach ($pending_courses as $code): ?>
                <li class="flex justify-between bg-white p-4 rounded-xl shadow-sm border border-amber-100 font-bold">
                    <span><?php echo htmlspecialchars($code); ?></span>
                    <span class="text-amber-500 text-[10px] uppercase">Awaiting Registration</span>
                </li>
                <?php endforeach; ?>
            </ul>
            <div class="flex items-center justify-between">
                <p class="text-[11px] text-amber-700 italic font-medium">Pending Total: <?php echo $pending_credits; ?> Credits</p>
                <form method="POST">
                    <button type="submit" name="confirm_enrollment" class="bg-amber-500 hover:bg-amber-600 text-white text-[10px] font-black px-8 py-3 rounded-xl uppercase shadow-md">Confirm & Register</button>
                </form>
            </div>
        </div>
        <?php endif; ?>

        <div class="mt-12 flex flex-col md:flex-row gap-4">
            <a href="bursar.php" class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white font-black py-4 rounded-xl text-center uppercase tracking-widest transition-all shadow-lg">Go to Bursar's Office</a>
            <a href="campus.php" class="flex-1 bg-slate-800 hover:bg-slate-900 text-white font-black py-4 rounded-xl text-center uppercase tracking-widest transition-all shadow-lg">Back to Campus</a>
        </div>
    </div>
</body>
</html>
