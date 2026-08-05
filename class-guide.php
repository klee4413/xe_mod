<?php
// [TIMESTAMP: 2026-04-02] - GAC CLASS GUIDE: Commitment Pattern Interface
session_start();
//require_once 'db-connect.php';
require_once __DIR__ . '/../db-connect.php';

$student_id = $_SESSION['user_id']    ?? 0;
$first_name = $_SESSION['first_name'] ?? 'Scholar';
$last_name  = $_SESSION['last_name']  ?? '';
$email      = $_SESSION['email']      ?? '';

// Security Gate: Ensure the scholar is grounded in the session
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) { 
    die("Database Connection Fault."); 
}

// 1. SURGICAL DATA RETRIEVAL: Targeting ID 2
$stmt = $pdo->prepare("SELECT * FROM webbooks WHERE id = 2 LIMIT 1");
$stmt->execute();
$book = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$book) { 
    die("GAC Logic Alert: Class Guide (ID 2) not grounded in repository."); 
}
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GAC Guide | <?php echo htmlspecialchars($book['title']); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .neo-card {
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }
        .neo-card:hover {
            transform: translate(-3px, -3px);
            box-shadow: 7px 7px 0px #000;
        }
    </style>
</head>
<body class="bg-emerald-800 min-h-screen p-3 md:p-8 font-sans">

    <!-- Outer Thick Green Framed Container -->
    <div class="max-w-7xl mx-auto bg-amber-50 rounded-2xl border-8 border-emerald-600 shadow-[12px_12px_0px_#000] p-6 md:p-10">
        
        <!-- Header Banner Section -->
        <header class="bg-emerald-300 border-4 border-black shadow-[6px_6px_0px_#000] rounded-xl p-6 md:p-10 mb-10 relative overflow-hidden">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
                <!-- Scholar Badge -->
                <div class="bg-yellow-300 border-2 border-black px-4 py-2 rounded-lg shadow-[3px_3px_0px_#000] font-mono font-bold text-xs md:text-sm text-black">
                    <i class="fa-solid fa-user-graduate mr-1"></i> ID: <?php echo htmlspecialchars($_SESSION['user_id']); ?> | <?php echo htmlspecialchars($_SESSION['first_name'] . " " . $_SESSION['last_name']); ?>
                </div>

                <!-- Go to Class Button Header -->
                <a href="<?php echo htmlspecialchars($book['linkto']); ?>" 
                   class="bg-rose-400 hover:bg-rose-300 text-black font-black px-6 py-2.5 rounded-xl border-3 border-black shadow-[4px_4px_0px_#000] active:translate-x-0.5 active:translate-y-0.5 active:shadow-[2px_2px_0px_#000] transition-all text-xs uppercase tracking-wider flex items-center gap-2">
                    <i class="fa-solid fa-door-open"></i> Go to Class
                </a>
            </div>

            <h1 class="text-3xl md:text-5xl font-black text-black uppercase tracking-tight mb-2">
                <?php echo htmlspecialchars($book['title']); ?>
            </h1>
            <p class="font-mono text-xs md:text-sm font-bold uppercase tracking-wider text-black/80">
                GAC WebBook Guide & Module Reference
            </p>
        </header>

        <!-- Main Body Grid: Left Sidebar + Right Content -->
        <div class="flex flex-col lg:flex-row gap-8">
            
            <!-- Left Sidebar Navigation Bar -->
            <nav class="lg:w-1/4">
                <div class="sticky top-8 bg-white rounded-xl p-6 border-4 border-black shadow-[6px_6px_0px_#000]">
                    <h2 class="text-xs font-black text-black uppercase tracking-widest mb-4 pb-2 border-b-2 border-black flex items-center gap-2">
                        <i class="fa-solid fa-list-ol"></i> Curriculum Menu
                    </h2>
                    
                    <ul class="space-y-3">
                        <?php for($i=1; $i<=6; $i++): if(empty($book['chname'.$i])) continue; ?>
                        <li>
                            <a href="#section-<?php echo $i; ?>" 
                               class="group flex items-center gap-3 p-2.5 rounded-lg border-2 border-black bg-emerald-100 hover:bg-yellow-300 text-black font-extrabold text-xs uppercase transition-all shadow-[2px_2px_0px_#000] active:translate-x-0.5 active:translate-y-0.5 active:shadow-[1px_1px_0px_#000]">
                                <span class="w-6 h-6 rounded bg-black text-white flex items-center justify-center font-mono text-xs shrink-0">
                                    <?php echo $i; ?>
                                </span>
                                <span class="truncate"><?php echo htmlspecialchars($book['chname'.$i]); ?></span>
                            </a>
                        </li>
                        <?php endfor; ?>
                    </ul>

                    <div class="mt-6 pt-4 border-t-2 border-black">
                        <a href="<?php echo htmlspecialchars($book['linkto']); ?>" 
                           class="w-full bg-cyan-300 hover:bg-cyan-200 text-black font-black py-2.5 px-4 rounded-lg border-2 border-black shadow-[3px_3px_0px_#000] transition-all text-xs uppercase tracking-wider flex items-center justify-center gap-2">
                            <i class="fa-solid fa-arrow-right-to-bracket"></i> Launch Class
                        </a>
                    </div>
                </div>
            </nav>

            <!-- Main Content Area -->
            <main class="lg:w-3/4 space-y-8">
                
                <?php for($i=1; $i<=6; $i++): if(empty($book['chname'.$i])) continue; ?>
                <section id="section-<?php echo $i; ?>" class="scroll-mt-8">
                    <article class="neo-card bg-white rounded-xl p-6 md:p-8 border-4 border-black shadow-[5px_5px_0px_#000] relative">
                        
                        <!-- Chapter Header Badge -->
                        <div class="flex items-center gap-3 mb-4">
                            <span class="bg-black text-white font-mono font-bold text-xs px-3 py-1 rounded border border-black shadow-[2px_2px_0px_#000]">
                                Section 0<?php echo $i; ?>
                            </span>
                            <h2 class="text-xl md:text-2xl font-black text-black uppercase tracking-tight">
                                <?php echo htmlspecialchars($book['chname'.$i]); ?>
                            </h2>
                        </div>

                        <!-- Chapter Content -->
                        <div class="text-black font-medium text-base md:text-lg leading-relaxed bg-stone-50 p-4 md:p-6 rounded-lg border-2 border-black/80">
                            <?php echo nl2br(htmlspecialchars($book['chapter'.$i])); ?>
                        </div>

                    </article>
                </section>
                <?php endfor; ?>

                <!-- Student Performance Report Footer Card -->
                <footer class="bg-yellow-300 rounded-xl p-8 md:p-10 border-4 border-black shadow-[6px_6px_0px_#000] text-center mt-12">
                    <h3 class="text-2xl md:text-3xl font-black text-black uppercase mb-4 tracking-tight">
                        Student Study Performance Report
                    </h3>
                    <p class="text-black font-bold text-base md:text-lg italic mb-8 max-w-2xl mx-auto bg-white p-4 rounded-lg border-2 border-black shadow-[3px_3px_0px_#000]">
                        "<?php echo htmlspecialchars($book['footer']); ?>"
                    </p>
                    
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="<?php echo htmlspecialchars($book['linkto2']); ?>" 
                           class="bg-emerald-400 hover:bg-emerald-300 text-black font-black px-8 py-3.5 rounded-xl border-3 border-black shadow-[4px_4px_0px_#000] active:translate-x-0.5 active:translate-y-0.5 active:shadow-[2px_2px_0px_#000] transition-all text-xs uppercase tracking-wider flex items-center justify-center gap-2">
                            <i class="fa-solid fa-chart-line"></i> Go to Campus
                        </a>
                        <a href="<?php echo htmlspecialchars($book['linkto']); ?>" 
                           class="bg-rose-400 hover:bg-rose-300 text-black font-black px-8 py-3.5 rounded-xl border-3 border-black shadow-[4px_4px_0px_#000] active:translate-x-0.5 active:translate-y-0.5 active:shadow-[2px_2px_0px_#000] transition-all text-xs uppercase tracking-wider flex items-center justify-center gap-2">
                            <i class="fa-solid fa-graduation-cap"></i> Go to Class
                        </a>
                    </div>
                </footer>

            </main>
        </div>

        <!-- Page Footer -->
        <footer class="mt-12 pt-6 border-t-4 border-black text-center font-bold text-xs text-black uppercase tracking-wider">
            &copy; 2026 AI Gemini College. Class Guide System.
        </footer>

    </div>

</body>
</html>