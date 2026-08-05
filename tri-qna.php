<?php
// tri-qna.php - communication Knowledge Hub

session_start();
require_once __DIR__ . '/../db-connect.php';
//require_once 'db-connect.php';
$student_id = $_SESSION['user_id']    ?? 0;
$first_name = $_SESSION['first_name'] ?? 'Scholar';
$last_name  = $_SESSION['last_name']  ?? '';
$email     = $_SESSION['email']      ?? '';

// 1. DATABASE GATEWAY
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    $db_error = "Database Connection Fault: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AIGC | Unified Q&A Hub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Neo-Brutalist card hover animation preserving search display */
        .q-card {
            transition: all 0.15s ease-in-out;
        }
        .q-card:hover {
            transform: translate(-2px, -2px);
            box-shadow: 6px 6px 0px #000;
        }
    </style>
</head>
<body class="bg-emerald-700 min-h-screen p-3 md:p-8 font-sans">

    <!-- Outer Thick Green Framed Container -->
    <div class="max-w-5xl mx-auto bg-amber-50 rounded-2xl border-8 border-emerald-600 shadow-[10px_10px_0px_#000] p-6 md:p-10">
        
        <!-- Header with Top-Right Campus Button -->
        <header class="mb-8 pb-6 border-b-4 border-black flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-3xl md:text-4xl font-black text-black tracking-tight uppercase">
                    AIGC Knowledge Q&A
                </h1>
                <p class="text-sm font-bold text-gray-700 mt-1 uppercase tracking-wide">
                    Search by Building, Keyword, or Category
                </p>
            </div>

            <!-- Top Right Campus Link Button -->
            <a href="campus.php" 
               class="bg-yellow-400 hover:bg-yellow-300 text-black font-black py-2.5 px-5 rounded-xl border-3 border-black shadow-[4px_4px_0px_#000] active:translate-x-0.5 active:translate-y-0.5 active:shadow-[2px_2px_0px_#000] transition-all flex items-center gap-2 text-xs uppercase tracking-wider shrink-0">
                <i class="fa-solid fa-arrow-left"></i>
                Back to Campus
            </a>
        </header>

        <!-- Search Input (Neo-Brutalist Box) -->
        <div class="mb-8">
            <input type="text" id="triSearch" onkeyup="triFilter()" 
                   placeholder="Search 'Bursar', 'Technical', 'Login'..." 
                   class="w-full p-4 md:p-5 rounded-xl border-4 border-black bg-white text-black font-bold placeholder-gray-400 shadow-[5px_5px_0px_#000] focus:outline-none focus:bg-cyan-50 focus:shadow-[7px_7px_0px_#000] transition-all text-base md:text-lg">
        </div>

        <!-- Q&A Cards Grid -->
        <div id="qnaGrid" class="space-y-5">
            <?php
            $stmt = $pdo->query("SELECT * FROM qnachat_table ORDER BY category ASC");
            while($row = $stmt->fetch()): ?>
                <div class="q-card bg-white p-6 rounded-xl border-3 border-black shadow-[4px_4px_0px_#000]" 
                     data-keyword="<?php echo htmlspecialchars(strtolower($row['keyword_trigger'])); ?>"
                     data-name="<?php echo htmlspecialchars(strtolower($row['building_name'])); ?>"
                     data-category="<?php echo htmlspecialchars(strtolower($row['category'])); ?>">
                    
                    <div class="flex justify-between items-center mb-3">
                        <!-- Category Tag -->
                        <span class="text-xs font-black uppercase tracking-wider text-black bg-cyan-300 px-3 py-1 rounded-md border-2 border-black shadow-[2px_2px_0px_#000]">
                            <?php echo htmlspecialchars($row['category']); ?>
                        </span>
                        
                        <!-- Location Tag -->
                        <span class="text-xs font-mono font-bold text-black bg-rose-200 px-2.5 py-0.5 rounded border border-black">
                            LOC: <?php echo htmlspecialchars($row['building_name'] ?: 'General'); ?>
                        </span>
                    </div>

                    <h3 class="font-extrabold text-black text-lg mb-2">
                        <?php echo htmlspecialchars($row['question']); ?>
                    </h3>
                    <p class="text-sm font-medium text-gray-800 leading-relaxed bg-stone-100 p-3 rounded-lg border border-black/20">
                        <?php echo htmlspecialchars($row['answer']); ?>
                    </p>
                </div>
            <?php endwhile; ?>
        </div>

        <!-- Footer -->
        <footer class="mt-10 pt-6 border-t-4 border-black text-center font-bold text-xs text-black uppercase tracking-wider">
            &copy; 2026 AI Gemini College. Unified Knowledge Hub.
        </footer>

    </div>

    <!-- Client-Side Search Script -->
    <script>
        function triFilter() {
            const query = document.getElementById('triSearch').value.toLowerCase();
            const cards = document.querySelectorAll('.q-card');

            cards.forEach(card => {
                // TRI-VARIABLE LOGIC GATE
                const matchK = card.getAttribute('data-keyword').includes(query);
                const matchN = card.getAttribute('data-name').includes(query);
                const matchC = card.getAttribute('data-category').includes(query);

                card.style.display = (matchK || matchN || matchC) ? "block" : "none";
            });
        }
    </script>
</body>
</html>