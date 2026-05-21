<?php
// [TIMESTAMP: 2026-04-11] - GAC WEBBOOK FOUNDRY (wb-gemni-do.php)
require_once 'db-connect.php';

// 1. DATA EXTRACTION: TARGET ID=7
try {
    $stmt = $pdo->prepare("SELECT * FROM webbooks WHERE id = 7");
    $stmt->execute();
    $book = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$book) {
        die("Data Grounding Fault: WebBook ID 7 not found in MariaDB.");
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
        /* 3. COLOR ARCHITECTURE & 16PX TYPOGRAPHY */
        body { background-color: #059669; font-size: 16px; line-height: 1.5; } 
        .chapter-box { background-color: #DCFCE7; border: 2px solid #10B981; }
        .gac-shadow { box-shadow: 0 20px 60px rgba(0,0,0,0.4); }
        .copy-active { background-color: #000 !important; color: #00FF7F !important; }
        
        /* Cake Layer Reading Experience: Vertical Single-Column Stack */
        .cake-layer { display: flex; flex-direction: column; gap: 2.5rem; }
		.scholar-font { 
    white-space: pre-line; 
}
    </style>
</head>
<body class="min-h-screen p-4 md:p-10 flex flex-col items-center font-sans">

    <div class="max-w-4xl w-full bg-white rounded-[2.5rem] gac-shadow overflow-hidden mb-12">
        
        <div class="p-8 md:p-14 text-center border-b border-gray-100">
            <h1 class="text-[11px] font-black uppercase tracking-[0.4em] text-emerald-600 mb-3">
                <?php echo htmlspecialchars($book['category'] ?? 'AI EXECUTION FOUNDRY'); ?>
            </h1>
            <h2 class="text-3xl md:text-4xl font-black text-gray-900 uppercase italic tracking-tighter mb-6">
                <?php echo htmlspecialchars($book['title']); ?>
            </h2>
            <p class="text-gray-700 font-bold leading-relaxed px-4">
                <!--?php echo htmlspecialchars($book['hero']); ?-->
				<!--?php echo nl2br(htmlspecialchars($ch_text)); ?-->
				<?php echo nl2br(htmlspecialchars($book['hero'])); ?>
            </p>
        </div>

        <div class="p-6 md:p-14 cake-layer">
            
            <div class="bg-blue-50 border-2 border-blue-200 p-6 rounded-3xl mb-2">
                <h3 class="text-blue-800 font-black text-xs uppercase tracking-widest mb-2 flex items-center gap-2">
                    <span>🔵</span> Visual Mapping Note
                </h3>
                <p class="text-blue-900 text-sm font-bold leading-snug">
                    Map these execution steps into a linear gantt-style mental model. Treat each PHP function as a 'Station' in a production line. When prompting Gemini, ask for output that visualizes the 'Handshake' between data input and AI result.
                </p>
            </div>

            <?php 
            for($i = 1; $i <= 7; $i++): 
                $ch_name = $book["chname$i"];
                $ch_text = $book["chapter$i"];
                if(!empty($ch_name)): 
            ?>
                <div class="chapter-box p-8 md:p-12 rounded-[2.5rem] relative transition-transform hover:scale-[1.01]">
                    <div class="flex justify-between items-start mb-6 border-b border-emerald-200 pb-4">
                        <div>
                            <span class="text-[10px] font-black text-emerald-800 uppercase tracking-widest block mb-1">Task Unit 0<?php echo $i; ?></span>
                            <h4 class="text-xl md:text-2xl font-black text-gray-900 uppercase tracking-tighter italic">
                                <?php echo htmlspecialchars($ch_name); ?>
                            </h4>
                        </div>
                        <button onclick="copyModule(<?php echo $i; ?>)" id="btn-<?php echo $i; ?>" class="bg-black text-white px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-gray-800 shadow-lg">
                            Copy
                        </button>
                    </div>
                    
                    <div id="content-<?php echo $i; ?>" class="text-gray-900 font-medium whitespace-pre-wrap leading-relaxed">
                        <?php echo htmlspecialchars($ch_text); ?>
                    </div>
                </div>
            <?php 
                endif;
            endfor; 
            ?>
        </div>

        <div class="bg-gray-100 p-8 md:p-14 border-t-4 border-black flex flex-col md:flex-row justify-between items-center gap-8">
            <div class="text-center md:text-left">
                <p class="text-[11px] font-black text-emerald-600 uppercase tracking-[0.3em] mb-2">GAC Sovereign Identity</p>
                <p class="text-lg font-black text-black uppercase italic leading-none">
                    <?php echo htmlspecialchars($book['author']); ?> | © <?php echo $book['year']; ?>
                </p>
            </div>
            
            <div class="flex gap-4 w-full md:w-auto font-black">
                <a href="<?php echo htmlspecialchars($book['linkto']); ?>" class="flex-1 text-center bg-black text-white px-12 py-5 rounded-2xl uppercase text-xs tracking-widest hover:bg-emerald-700 transition-all shadow-xl">Campus</a>
                <a href="index.html" class="flex-1 text-center border-4 border-black text-black px-12 py-5 rounded-2xl uppercase text-xs tracking-widest hover:bg-black hover:text-white transition-all shadow-xl">Home</a>
            </div>
        </div>
    </div>

    <div class="text-[10px] font-black text-emerald-200 uppercase tracking-[0.8em] mb-12 opacity-40">
        GAC Foundry | Node: 35.239.81.206
    </div>

    <script>
        function copyModule(id) {
            const text = document.getElementById('content-' + id).innerText;
            const btn = document.getElementById('btn-' + id);
            
            navigator.clipboard.writeText(text).then(() => {
                const originalText = btn.innerText;
                btn.innerText = "DONE";
                btn.classList.add('copy-active');
                setTimeout(() => {
                    btn.innerText = originalText;
                    btn.classList.remove('copy-active');
                }, 1500);
            });
        }
    </script>
</body>
</html>