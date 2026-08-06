<?php
// [TIMESTAMP: 2026-03-01] - GAC COURSE CATALOG course_list.php
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

// Fetch all 53 classes from the logic gate course_list.php
try {
    $stmt = $pdo->query("SELECT * FROM classes ORDER BY class_id ASC LIMIT 4");
    $classes = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Catalog Logic Fault: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GAC | Course Catalog</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .gac-bg { background-color: #5ABD55; }
        .card-shadow { box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05); }
        .unlocked-icon { color: #059669; }
        .locked-icon { color: #9ca3af; }
    </style>
</head>
<body class="gac-bg min-h-screen p-4 md:p-8 font-sans text-gray-800">

    <div class="max-w-4xl mx-auto">
        <header class="flex justify-between items-center mb-8 px-2">
            <h1 class="text-xl font-black text-gray-700 tracking-tight">Gemini AI College</h1>
            <p class="text-[15px] font-bold text-black-400 uppercase tracking-widest">Student ID: <?php echo $student_id; ?></p>
        </header>

        <div class="bg-white rounded-[2rem] border border-gray-200 p-6 md:p-10 card-shadow">
            <h2 class="text-3xl font-black mb-2 text-gray-900">Course Catalog</h2>
            <p class="text-xs text-black-400 mb-8">List of Available and Future classes to begin your learning journey.</p>

            <div class="space-y-4">
                <?php foreach ($classes as $c): 
                    $isLocked = (strtoupper($c['status']) === 'LOCKED');
                ?>
                    <div class="group relative bg-white border border-gray-100 rounded-2xl p-6 transition-all hover:border-green-200 hover:bg-green-50/30">
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                            
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="bg-gray-100 text-[12px] font-black px-2 py-0.5 rounded text-gray-500 uppercase"><?php echo $c['class_id']; ?></span>
                                    <span class="text-[12px] font-black text-gray-900 uppercase">Tier <?php echo $c['tier']; ?></span>
                                </div>
                                
                                <h3 class="text-lg font-bold text-gray-900 mb-1"><?php echo $c['class_name']; ?></h3>
                                <p class="text-lg text-gray-500 leading-relaxed"><?php echo $c['syllabus']; ?></p>
                                
                                <!--div class="mt-4 flex items-center gap-2 text-[10px] text-gray-400 font-bold uppercase tracking-wider">
                                    <span>📖 Ready for lecture</span>
                                </div-->
                            </div>

                            <div class="flex flex-col items-end gap-1">
                                <div class="flex items-center gap-1.5 <?php echo $isLocked ? 'locked-icon' : 'unlocked-icon'; ?>">
                                    <span class="text-[10px] font-black uppercase tracking-widest">
                                        <?php echo $isLocked ? '🔒 Locked' : '🔓 Unlocked'; ?>
                                    </span>
                                </div>
                                <span class="text-[10px] font-bold text-gray-300"><?php echo $c['credit_hour']; ?> Credits</span>
                            </div>

                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

</body>
</html>
