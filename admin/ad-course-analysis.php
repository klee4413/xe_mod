<?php
// [TIMESTAMP: 2026-03-02] - AIGC HIGH-VISIBILITY ANALYSIS 2.0
session_start();
require_once __DIR__ . '/../../db-connect.php';

// --- 1. THE DEPARTMENT LOGIC MAP ---
$dept_logic = [
    'AI Dept.'           => ['AI', 'Intelligence', 'Neural', 'Prompt', 'GenAI'],
    'Data Science Dept.' => ['Data', 'Analysis', 'SQL', 'Database', 'Mining'],
    'Language Dept.'     => ['French', 'English', 'Grammar', 'Spanish', 'Writing'],
    'Math Dept.'         => ['Math', 'Calculus', 'Algebra', 'Geometry'],
    'Statistics Dept.'   => ['Statistics', 'Probability', 'Regression', 'Sampling'],
];

try {
    // 5. ALPHABETICAL SORT GROUNDING
    $sql = "SELECT * FROM classes ORDER BY class_name ASC";
    $classes = $pdo->query($sql)->fetchAll();
} catch (PDOException $e) {
    die("Sovereign Logic Fault: " . $e->getMessage());
}

// Initialize departments
$structure = [];
foreach (array_keys($dept_logic) as $d) $structure[$d] = [];
$structure['Other Dept.'] = [];

// --- 2. THE CLASSIFICATION ENGINE ---
foreach ($classes as $c) {
    $found = false;
    foreach ($dept_logic as $dept => $keywords) {
        foreach ($keywords as $key) {
            if (stripos($c['class_name'], $key) !== false) {
                $structure[$dept][] = $c;
                $found = true;
                break 2;
            }
        }
    }
    if (!$found) $structure['Other Dept.'][] = $c;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AIGC | High-Vis Analysis</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* 4. DOUBLE-SIZED TEXT LOGIC */
        body { font-size: 1.5rem; } /* Base font is 150% standard */
        .gac-text-giant { font-size: 2.5rem; line-height: 1; } /* Titles are 2.5x bigger */
        .gac-card-text { font-size: 1.75rem; } 
        .bg-dark-green { background-color: #064e3b; } /* Tailwind emerald-900 equivalent */
        .bg-gac-green { background-color: #059669; } /* Tailwind emerald-600 equivalent */
    </style>
</head>
<body class="bg-gray-50 min-h-screen font-black text-gray-900">

    <header class="bg-dark-green text-white p-10 text-center shadow-2xl border-b-8 border-green-400">
        <h1 class="gac-text-giant uppercase tracking-tighter">AIGC DEPT & COURSE ANALYSIS</h1>
		<button type="button" onclick="toHome()" 
                        class="gac-green text-blue px-20 py-5 rounded-2xl font-black uppercase text-sm tracking-widest shadow-xl hover:scale-105 transition-all">
                   Back To Home
                </button>
        <!--p class="text-green-300 mt-4 font-bold uppercase tracking-widest">Sovereign Roadmap Control</p-->
		
    </header>

    <main class="max-w-5xl mx-auto p-6 space-y-12">

        <?php foreach ($structure as $deptName => $deptClasses): ?>
            <section class="bg-white rounded-[40px] shadow-2xl overflow-hidden border-4 border-gray-100">
                <div class="bg-gac-green p-8 flex justify-between items-center text-white">
                    <h2 class="gac-card-text uppercase"><?php echo $deptName; ?></h2>
                    <span class="bg-dark-green px-6 py-2 rounded-full text-xl"><?php echo count($deptClasses); ?></span>
                </div>

                <div class="p-4">
                    <?php if (empty($deptClasses)): ?>
                        <p class="text-gray-300 italic p-6">No courses grounded yet.</p>
                    <?php else: ?>
                        <ul class="divide-y-4 divide-gray-50">
                            <?php foreach ($deptClasses as $c): ?>
                                <li class="p-8 flex flex-col md:flex-row justify-between items-center hover:bg-green-50 transition-all">
                                    <div class="text-center md:text-left">
                                        <div class="text-green-600 text-sm font-black"><?php echo $c['class_id']; ?></div>
                                        <div class="gac-card-text leading-tight"><?php echo $c['class_name']; ?></div>
                                    </div>
                                    <div class="mt-4 md:mt-0">
                                        <span class="px-4 py-3 rounded-2xl text-white font-black <?php echo $c['status'] == 'UNLOCK' ? 'bg-gac-green' : 'bg-red-600'; ?>">
                                            <?php echo $c['status']."  ".$c['no']; ?>
                                        </span>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </section>
        <?php endforeach; ?>
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
    </main>
<script>function toHome() {window.location.href = 'admin-offices.php';}</script>

    <footer class="p-20 text-center text-gray-400 font-bold uppercase tracking-widest">
        &copy; AI Gemini College 2026