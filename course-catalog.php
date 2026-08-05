<?php
// [TIMESTAMP: 2026-03-05] - GAC COURSE CATALOG course-catalog.php
session_start();

// Environment-Aware DB Connection Switch
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
    // Limits the view for the initial dashboard display as requested
    $stmt = $pdo->query("SELECT * FROM classes ORDER BY class_id ASC LIMIT 15");
    $classes = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Catalog Logic Fault: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AIGC | Course Catalog</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .neo-card {
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }
        .neo-card:hover {
            transform: translate(-2px, -2px);
            box-shadow: 7px 7px 0px #000;
        }
    </style>
</head>
<body id="top" class="bg-emerald-800 min-h-screen p-3 md:p-8 font-sans">

    <!-- Outer Thick Green Framed Container -->
    <div class="max-w-4xl mx-auto bg-amber-50 rounded-2xl border-8 border-emerald-600 shadow-[12px_12px_0px_#000] p-6 md:p-10 relative">
        
        <!-- Header Section -->
        <header class="bg-emerald-300 border-4 border-black shadow-[6px_6px_0px_#000] rounded-xl p-6 mb-8 flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="text-center md:text-left">
                <span class="bg-yellow-300 border-2 border-black px-3 py-1 rounded-md text-[10px] font-black uppercase tracking-widest text-black shadow-[2px_2px_0px_#000]">
                    Academic Curriculum
                </span>
                <h1 class="text-2xl md:text-3xl font-black text-black tracking-tight uppercase mt-2">
                    AI Gemini College
                </h1>
            </div>

            <!-- Header Action Buttons -->
            <div class="flex gap-3 shrink-0">
                <a href="campus.php" 
                   class="bg-rose-400 hover:bg-rose-300 text-black font-black px-5 py-2.5 rounded-xl border-3 border-black shadow-[4px_4px_0px_#000] active:translate-x-0.5 active:translate-y-0.5 active:shadow-[2px_2px_0px_#000] transition-all text-xs uppercase tracking-wider flex items-center gap-2">
                    <i class="fa-solid fa-graduation-cap"></i> To Campus
                </a>
                <a href="index.php" 
                   class="bg-yellow-400 hover:bg-yellow-300 text-black font-black px-5 py-2.5 rounded-xl border-3 border-black shadow-[4px_4px_0px_#000] active:translate-x-0.5 active:translate-y-0.5 active:shadow-[2px_2px_0px_#000] transition-all text-xs uppercase tracking-wider flex items-center gap-2">
                    <i class="fa-solid fa-house"></i> Home
                </a>
            </div>
        </header>

        <!-- Main Catalog Container -->
        <div class="bg-white rounded-xl border-4 border-black p-6 md:p-8 shadow-[6px_6px_0px_#000] relative">
            
            <div class="border-b-4 border-black pb-4 mb-6">
                <h2 class="text-3xl font-black text-black uppercase tracking-tight">Course Catalog</h2>
                <p class="text-xs font-bold text-gray-700 uppercase tracking-wide mt-1">
                    Available and Future classes to begin your study journey.
                </p>
            </div>

            <!-- Course Cards List -->
            <div class="space-y-5">
                <?php foreach ($classes as $c): 
                    $isLocked = (strtoupper($c['status']) === 'LOCKED');
                ?>
                    <div class="neo-card bg-stone-50 border-3 border-black rounded-xl p-5 shadow-[4px_4px_0px_#000]">
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                            
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="bg-black text-white text-[11px] font-mono font-black px-2.5 py-0.5 rounded border border-black shadow-[2px_2px_0px_#000]">
                                        <?php echo htmlspecialchars($c['class_id']); ?>
                                    </span>
                                    <span class="bg-cyan-300 text-black text-[11px] font-black px-2 py-0.5 rounded border border-black shadow-[2px_2px_0px_#000] uppercase">
                                        Tier <?php echo htmlspecialchars($c['tier']); ?>
                                    </span>
                                </div>
                                
                                <h3 class="text-lg font-extrabold text-black mb-1">
                                    <?php echo htmlspecialchars($c['class_name']); ?>
                                </h3>
                                <p class="text-sm font-medium text-gray-800 leading-relaxed bg-white p-3 rounded-lg border border-black/30 mt-2">
                                    <?php echo htmlspecialchars($c['syllabus']); ?>
                                </p>
                            </div>

                            <!-- Lock Status & Credits Badge -->
                            <div class="flex flex-col items-end gap-2 shrink-0 self-end md:self-center">
                                <span class="<?php echo $isLocked ? 'bg-rose-200 text-black' : 'bg-emerald-300 text-black'; ?> text-xs font-black px-3 py-1 rounded-md border-2 border-black shadow-[2px_2px_0px_#000] uppercase tracking-wider">
                                    <?php echo $isLocked ? '🔒 Locked' : '🔓 Unlocked'; ?>
                                </span>
                                
                                <span class="bg-yellow-200 text-black text-[10px] font-black px-2 py-0.5 rounded border border-black">
                                    <?php echo htmlspecialchars($c['credit_hour']); ?> Credits
                                </span>
                            </div>

                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Coming Soon Banner -->
            <div class="mt-10 p-4 bg-yellow-300 border-3 border-black shadow-[4px_4px_0px_#000] rounded-xl text-center">
                <h3 class="text-xl font-black text-black uppercase tracking-tight">
                    <i class="fa-solid fa-hourglass-half mr-2"></i> More Courses Are Coming
                </h3>
            </div>

            <!-- Back To Top Button -->
            <div class="flex justify-end mt-8">
                <a href="#top" class="bg-cyan-300 hover:bg-cyan-200 text-black font-black px-4 py-2 rounded-xl border-3 border-black shadow-[4px_4px_0px_#000] active:translate-x-0.5 active:translate-y-0.5 active:shadow-[2px_2px_0px_#000] transition-all text-xs uppercase tracking-wider flex items-center gap-2">
                    <i class="fa-solid fa-arrow-up"></i> Back to Top
                </a>
            </div>

        </div>
 
        <!-- Footer -->
        <footer class="mt-8 pt-4 border-t-4 border-black text-center font-bold text-xs text-black uppercase tracking-wider">
            &copy; 2026 AI GEMINI COLLEGE | All Rights Reserved
        </footer>

    </div>

</body>
</html>