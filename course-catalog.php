<?php
// [TIMESTAMP: 2026-03-05] - GAC COURSE CATALOG course-catalog.php
session_start();
require_once 'db_connect.php';
$student_id = $_SESSION['user_id'] ?? '';
$date = date("Y-m-d") ;
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
    <style>
        .gac-bg { background-color: #74F46D; }
        .card-shadow { box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05); }
        .unlocked-icon { color: #059669; }
        .locked-icon { color: #9ca3af; }
        /* Sticky header fix for buttons */
        .gac-btn { transition: all 0.2s ease; font-weight: 900; text-transform: uppercase; letter-spacing: 0.05em; }
    </style>
</head>
<body id="top" class="gac-bg min-h-screen p-4 md:p-8 font-sans text-gray-800">
    <div class="max-w-4xl mx-auto relative">
        <header class="flex flex-col md:flex-row justify-between items-center mb-8 px-2 gap-4">
            <div class="text-center md:text-left">
                <h1 class="text-xl font-black text-gray-700 tracking-tight">AI GEMINI COLLEGE </h1>
                <!--p class="text-[15px] font-bold text-black-400 uppercase tracking-widest">Student ID: <?php echo $student_id; ?></p-->
            </div>
            <div class="flex gap-3">
                <a href="campus.php" 
                   class="gac-btn bg-[#059669] text-white px-8 py-3 rounded-xl text-xl shadow-lg hover:bg-[#064e3b] active:scale-95">
                    To Campus
                </a>
                <a href="index.php" 
                   class="gac-btn bg-[#059669] text-white px-8 py-3 rounded-xl text-xl shadow-lg hover:bg-[#064e3b] active:scale-95">
                   HOME
                </a>
            </div>
        </header>
        <div class="bg-white rounded-[2rem] border border-gray-200 p-6 md:p-10 card-shadow relative">
            <h2 class="text-3xl font-black mb-2 text-gray-900">Course Catalog</h2>
            <p class="text-xs text-black-400 mb-8">List of Available and Future classes to begin your study journey.</p>

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
                            </div>

                            <div class="flex flex-col items-end gap-1">
                                <div class="flex items-center gap-1.5 <?php echo $isLocked ? 'locked-icon' : 'unlocked-icon'; ?>">
                                    <span class="text-[10px] font-black uppercase tracking-widest">
                                        <?php echo $isLocked ? '🔒 Locked' : '🔓 Unlocked'; ?>
                                    </span>
                                </div>
                                <span class="text-[10px] font-bold text-black-500"><?php echo $c['credit_hour']; ?> Credits</span>
                            </div>

                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
 <center><h2 class="text-3xl font-black mb-2 text-gray-900">More Courses Are Coming</h2></center>
            <div class="flex justify-end mt-6">
                <a href="#top" class="flex flex-col items-center gap-1 text-[#3b82f6] font-black uppercase text-[10px] hover:scale-110 transition-transform">
                    <div class="bg-[#3b82f6] p-2 rounded-full text-white shadow-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 15l7-7 7 7" />
                        </svg>
                    </div>
                    Back to Top
                </a>
            </div>
        </div>
 
        <footer class="text-center p-8 text-white/60 text-[10px] font-bold uppercase tracking-widest">
            &copy; 2026 AI GEMINI COLLEGE | All Rights Reserved
        </footer>
    </div>

</body>
</html>