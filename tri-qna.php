<?php
// tri-qna.php - Surgical Knowledge Hub
session_start();
// Database connection same as above...

// 1. DATABASE GATEWAY
$host = 'localhost'; $db = 'ai-hi-work'; $user = 'root'; $pass = ''; 
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
    <title>GAC | Unified Q&A Hub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .q-card { border-left: 4px solid #BC4A3C; transition: transform 0.2s; }
        .q-card:hover { transform: translateX(4px); }
    </style>
</head>
<body class="bg-slate-50 py-12 px-6">
    <div class="max-w-4xl mx-auto">
        <header class="mb-10 text-center">
            <h1 class="text-3xl font-black text-slate-900">GAC Unified Knowledge Q&A Hub</h1>
            <p class="text-slate-500 mt-2">Search by Building, Keyword, or Category</p>
        </header>

        <input type="text" id="triSearch" onkeyup="triFilter()" placeholder="Search 'Bursar', 'Technical', 'Login'..." 
               class="w-full p-5 rounded-2xl border-none shadow-xl mb-10 focus:ring-2 focus:ring-[#30C89F] outline-none">

        <div id="qnaGrid" class="space-y-4">
            <?php
            $stmt = $pdo->query("SELECT * FROM qnachat_table ORDER BY category DESC");
            while($row = $stmt->fetch()): ?>
                <div class="q-card bg-white p-6 rounded-r-xl shadow-sm" 
                     data-keyword="<?php echo strtolower($row['keyword_trigger']); ?>"
                     data-name="<?php echo strtolower($row['building_name']); ?>"
                     data-category="<?php echo strtolower($row['category']); ?>">
                    
                    <div class="flex justify-between items-center mb-3">
                        <span class="text-[10px] font-bold uppercase tracking-widest text-emerald-600 bg-emerald-50 px-2 py-1 rounded">
                            <?php echo $row['category']; ?>
                        </span>
                        <span class="text-[9px] font-mono text-slate-400">
                            LOC: <?php echo $row['building_name'] ?: 'General'; ?>
                        </span>
                    </div>
                    <h3 class="font-bold text-slate-800 mb-2"><?php echo $row['question']; ?></h3>
                    <p class="text-sm text-slate-600 leading-relaxed"><?php echo $row['answer']; ?></p>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

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