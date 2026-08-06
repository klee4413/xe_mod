<?php
// [TIMESTAMP: 2026-04-10] - GAC WEBBOOK FOUNDRY (wb-prompt.php - Stacked Reading)
 
require_once __DIR__ . '/../db-connect.php';

// 1. DATA EXTRACTION: TARGET ID=3
try {
    $stmt = $pdo->prepare("SELECT * FROM webbooks WHERE id = 3");
    $stmt->execute();
    $book = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$book) {
        die("Data Grounding Fault: WebBook ID 3 not found in MariaDB.");
    }
} catch (PDOException $e) {
    die("Foundry Link Failure: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GAC | <?php echo htmlspecialchars($book['title']); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* 3. COLOR ARCHITECTURE (GAC Green Palette) */
        body { background-color: #059669; } /* Solid Green Background */
        .chapter-box { background-color: #DCFCE7; border: 2px solid #10B981; } /* Light Green Chapters */
        .hero-section { background: rgba(255, 255, 255, 0.95); }
        .gac-shadow { box-shadow: 0 20px 60px rgba(0,0,0,0.35); }
        
        /* 4. CAKE LAYER & READING LOGIC */
        /* Removed line-clamp and grid constraints. The layout is now stacked vertically. */
        .chapter-box { transition: all 0.3s ease; }
        .chapter-box:hover { transform: translateY(-3px); }
        
        /* Smooth scrolling for internal navigation if needed */
        html { scroll-behavior: smooth; }
    </style>
</head>
<body class="min-h-screen p-4 md:p-8 flex items-center justify-center font-sans">

    <div class="max-w-4xl w-full bg-white rounded-[2.5rem] gac-shadow overflow-hidden">
        
        <div class="p-8 md:p-14 border-b border-gray-100 text-center">
            <h1 class="text-[11px] font-black uppercase tracking-[0.4em] text-emerald-600 mb-2">
                <?php echo htmlspecialchars($book['category'] ?? 'AI PROMPT FOUNDATION'); ?>
            </h1>
            <h2 class="text-3xl md:text-4xl font-black text-gray-900 uppercase italic tracking-tighter mb-5">
                <?php echo htmlspecialchars($book['title']); ?>
            </h2>
            <p class="max-w-2xl mx-auto text-gray-700 font-medium leading-relaxed text-sm md:text-base">
                <?php echo htmlspecialchars($book['hero']); ?>
            </p>
        </div>

        <div class="p-8 md:p-14 space-y-8">
            <?php 
            // Loop through the 6 potential chapters defined in SQL
            for($i = 1; $i <= 6; $i++): 
                $ch_name = $book["chname$i"];
                $ch_text = $book["chapter$i"];
                if(!empty($ch_name)): // Only display if chapter has data
            ?>
                <div class="chapter-box p-8 md:p-10 rounded-3xl shadow-sm">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-2 mb-6 border-b border-emerald-200 pb-4">
                        <div class="flex items-center gap-3">
                            <span class="bg-emerald-600 text-white font-black text-xs px-3 py-1.5 rounded-full">0<?php echo $i; ?></span>
                            <h3 class="text-[10px] font-black text-emerald-800 uppercase tracking-[0.2em]">Chapter Foundation</h3>
                        </div>
                        <center><h4 class="text-xl md:text-2xl font-black text-gray-900 tracking-tight leading-tight">
                            <?php echo htmlspecialchars($ch_name); ?>
                        </h4> </center>
                    </div>
                    
                    <div class="prose max-w-none text-gray-800 leading-relaxed font-medium space-y-4 text-sm md:text-base">
                        <?php echo nl2br(htmlspecialchars($ch_text)); ?>
                    </div>
                </div>
            <?php 
                endif;
            endfor; 
            ?>
        </div>

        <div class="bg-gray-50 p-8 md:p-12 border-t border-gray-100 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="text-center md:text-left">AIGC Institutional Record</p>
                <p class="text-sm font-black text-gray-800 uppercase italic"><?php echo htmlspecialchars($book['author']); ?> | <?php echo $book['year']; ?></p>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-4 w-full sm:w-auto">
                <a href="<?php echo htmlspecialchars($book['linkto']); ?>" class="w-full sm:w-auto bg-black text-white px-8 py-4 rounded-2xl font-black uppercase text-[10px] tracking-widest hover:bg-emerald-700 transition-colors text-center shadow">
                    Return to Campus
                </a>
                <a href="webbook-lib.php" class="w-full sm:w-auto border-2 border-gray-200 text-gray-400 px-8 py-4 rounded-2xl font-black uppercase text-[10px] tracking-widest hover:border-black hover:text-black transition-all text-center">
                    Library Home
                </a>
            </div>
        </div>

    </div>

    <div class="fixed bottom-3 right-3 text-[9px] font-bold text-emerald-200 uppercase tracking-widest opacity-40">
        Foundry Unit: 35.239.81.206 | AIGC Scholar Portal
    </div>

</body>
</html>
