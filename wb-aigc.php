<?php
// [TIMESTAMP: 2026-04-11] - GAC WEBBOOK FOUNDRY (wb-ppt.php - 16px Stacked)
require_once 'db-connect.php';

// 1. DATA EXTRACTION: TARGET ID=6
try {
    $stmt = $pdo->prepare("SELECT * FROM webbooks WHERE id = 9");
    $stmt->execute();
    $book = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$book) {
        die("Data Grounding Fault: WebBook ID 9 not found in MariaDB.");
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
    <title>AIGC | <?php echo htmlspecialchars($book['title']); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* 3. COLOR ARCHITECTURE & 16PX TYPOGRAPHY */
        body { background-color: #059669; font-size: 16px; line-height: 1.5; } 
        .chapter-box { background-color: #DCFCE7; border: 2px solid #10B981; }
        .gac-shadow { box-shadow: 0 20px 50px rgba(0,0,0,0.3); }
        .copy-success { background-color: #000 !important; color: #00FF7F !important; }
        
        /* Cake Layer Reading Experience: Vertical Single-Column Stack */
        .cake-layer { display: flex; flex-direction: column; gap: 2rem; }
    </style>
</head>
<body class="min-h-screen p-4 md:p-10 flex flex-col items-center font-sans">

    <div class="max-w-4xl w-full bg-white rounded-[2.5rem] gac-shadow overflow-hidden mb-10">
        
        <div class="p-8 md:p-14 text-center border-b border-gray-100">
            <h1 class="text-[20px] font-black uppercase tracking-[0.4em] text-emerald-600 mb-3">
                <?php echo htmlspecialchars($book['category'] ?? 'AI GEMINI COLLEGE'); ?>
            </h1>
            <h2 class="text-3xl md:text-4xl font-black text-gray-900 uppercase italic tracking-tighter mb-6">
                <?php echo htmlspecialchars($book['title']); ?>
            </h2>
            <p class="text-gray-700 font-semibold leading-relaxed px-4">
                <?php echo htmlspecialchars($book['hero']); ?>
            </p>
        </div>

        <div class="p-6 md:p-14 cake-layer">
            
            <!--div class="bg-blue-50 border-2 border-blue-200 p-6 rounded-3xl mb-4">
                <h3 class="text-blue-800 font-black text-xs uppercase tracking-widest mb-2 flex items-center gap-2">
                    <span>🔵</span> Visual Mapping Note
                </h3>
                <p class="text-blue-900 text-sm font-bold leading-snug">
                    Translate logic into space. When generating slides, map PHP conditional blocks to flow-chart nodes and treat array data as structured visual clusters. Avoid bullet points; use spatial metaphors to represent the architecture of the code. If you do not understany anything here, ask it to NotebookLM!
                </p>
            </div-->

            <?php 
            for($i = 1; $i <= 7; $i++): 
                $ch_name = $book["chname$i"];
                $ch_text = $book["chapter$i"];
                if(!empty($ch_name)): 
            ?>
                <div class="chapter-box p-8 md:p-10 rounded-[2rem] relative">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <span class="text-[10px] font-black text-emerald-700 uppercase tracking-widest block mb-1">Module 0<?php echo $i; ?></span>
                            <h4 class="text-xl md:text-2xl font-black text-gray-900 tracking-tight uppercase italic">
                                <?php echo htmlspecialchars($ch_name); ?>
                            </h4>
                        </div>
                        <!--button onclick="copyChapter(<?php echo $i; ?>)" id="btn-<?php echo $i; ?>" class="bg-black text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest hover:scale-105 transition-all shadow-lg">
                            Copy Prompt
                        </button-->
                    </div>
                    
                    <div id="content-<?php echo $i; ?>" class="text-gray-900 font-medium whitespace-pre-wrap">
                        <?php echo htmlspecialchars($ch_text); ?>
                    </div>
                </div>
            <?php 
                endif;
            endfor; 
            ?>
        </div>

        <div class="bg-gray-50 p-8 md:p-12 border-t border-gray-100 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="text-center md:text-left">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest italic">Institutional Repository</p>
                <p class="text-base font-black text-gray-800 uppercase italic"><?php echo htmlspecialchars($book['author']); ?> | <?php echo $book['year']; ?></p>
            </div>
            
            <div class="flex gap-4 w-full md:w-auto">
                <a href="<?php echo htmlspecialchars($book['linkto']); ?>" class="flex-1 text-center bg-black text-white px-10 py-5 rounded-2xl font-black uppercase text-xs tracking-widest hover:bg-emerald-700 transition-all">Campus</a>
                <!--a href="prompt-trade1.php" class="flex-1 text-center border-2 border-gray-300 text-gray-400 px-10 py-5 rounded-2xl font-black uppercase text-xs tracking-widest hover:border-black hover:text-black transition-all">View Slide</a-->
            </div>
        </div>
    </div>

    <div class="text-[10px] font-black text-emerald-200 uppercase tracking-[0.6em] mb-10 opacity-50">
        GAC Foundry | Node: 35.239.81.206
    </div>

    <script>
        function copyChapter(id) {
            const text = document.getElementById('content-' + id).innerText;
            const btn = document.getElementById('btn-' + id);
            
            navigator.clipboard.writeText(text).then(() => {
                const originalText = btn.innerText;
                btn.innerText = "COPIED!";
                btn.classList.add('copy-success');
                setTimeout(() => {
                    btn.innerText = originalText;
                    btn.classList.remove('copy-success');
                }, 2000);
            });
        }
    </script>
</body>
</html>