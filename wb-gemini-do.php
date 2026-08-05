<?php
// [TIMESTAMP: 2026-04-11] - GAC WEBBOOK FOUNDRY (wb-gemni-do.php)
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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            scroll-behavior: smooth; 
            background-color: #065f46; /* Emerald Deep Dark Outer Background */
            margin: 0;
            padding: 12px;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            box-sizing: border-box;
        }

        /* Outer Frame Container */
        #main-outer-frame {
            width: 100%;
            max-width: 1380px;
            height: calc(100vh - 24px);
            background-color: #fffbeb; /* Warm Light Amber Background */
            border: 8px solid #059669; /* Thick Green Frame */
            border-radius: 20px;
            box-shadow: 12px 12px 0px #000000;
            display: flex;
            overflow: hidden;
            position: relative;
        }

        /* Sidebar Navigation */
        #sidebar { 
            width: 320px; 
            min-width: 280px;
            background-color: #ffffff; 
            padding: 24px; 
            border-right: 4px solid #000000; 
            overflow-y: auto; 
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        /* Main Content Scroll Area */
        #content-area {
            flex-grow: 1;
            overflow-y: auto;
            padding: 32px;
            background-color: #fef3c7; /* Soft Amber Content Area */
        }

        /* Neo-Brutalist Copy Active Button State */
        .copy-active { 
            background-color: #000000 !important; 
            color: #00FF7F !important; 
            box-shadow: 2px 2px 0px #00FF7F !important;
        }

        @media (max-width: 868px) {
            #main-outer-frame {
                flex-direction: column;
                height: auto;
                max-height: none;
            }
            #sidebar { 
                width: 100%; 
                max-height: none; 
                border-right: none; 
                border-bottom: 4px solid #000000; 
            }
            #content-area {
                padding: 16px;
            }
        }
    </style>
</head>
<body>

    <!-- Outer Thick Green Framed Container -->
    <div id="main-outer-frame">

        <!-- LEFT MENU SIDEBAR -->
        <aside id="sidebar">
            <div>
                <div class="bg-emerald-400 text-black border-2 border-black shadow-[3px_3px_0px_#000] px-3 py-1 rounded-lg font-black text-xs uppercase tracking-wider inline-block mb-2">
                    GEMINI Prompt Practice
                </div>
                <h1 class="text-xl font-black text-black uppercase tracking-tight">
                    <?php echo htmlspecialchars($book['category'] ?? 'Learn Gemini'); ?>
                </h1>
                <div class="h-1 bg-black w-full my-3"></div>
            </div>

            <!-- Chapter Navigation Menu -->
            <div class="flex-grow flex flex-col gap-2">
                <span class="text-[10px] font-black uppercase text-black tracking-wider bg-yellow-300 border-2 border-black px-2 py-1 rounded shadow-[2px_2px_0px_#000] text-center">
                    Task Navigation
                </span>
                <nav class="space-y-2 mt-2">   
                    <?php for($i = 1; $i <= 7; $i++): 
                        if(!empty($book["chname$i"])): ?>
                            <a href="#task-unit-<?php echo $i; ?>" class="flex items-center gap-2 p-2.5 rounded-xl bg-white border-2 border-black shadow-[2px_2px_0px_#000] hover:bg-emerald-200 text-xs font-black text-black transition-all active:translate-x-0.5 active:translate-y-0.5">
                                <span class="bg-emerald-400 text-black px-1.5 py-0.5 rounded border border-black text-[10px]">0<?php echo $i; ?></span>
                                <span class="truncate"><?php echo htmlspecialchars($book["chname$i"]); ?></span>
                            </a>
                    <?php endif; endfor; ?>
                </nav>
            </div>

            <!-- Sidebar Footer Info & Quick Links -->
            <div class="pt-4 border-t-4 border-black flex flex-col gap-3">
                <div class="bg-cyan-200 border-2 border-black shadow-[2px_2px_0px_#000] rounded-xl p-3 text-center">
                    <span class="text-[9px] font-black text-black uppercase block">Author & Year</span>
                    <p class="text-xs font-black text-black mt-0.5">
                        <?php echo htmlspecialchars($book['author']); ?> | © <?php echo htmlspecialchars($book['year']); ?>
                    </p>
                </div>
                
                <div class="flex gap-2">
                    <a href="<?php echo htmlspecialchars($book['linkto']); ?>" class="flex-1 text-center bg-emerald-400 hover:bg-emerald-300 text-black border-2 border-black shadow-[2px_2px_0px_#000] py-2 rounded-xl text-xs font-black uppercase tracking-wider active:translate-x-0.5 active:translate-y-0.5 transition-all">
                        Campus
                    </a>
                    <a href="index.html" class="flex-1 text-center bg-rose-400 hover:bg-rose-300 text-black border-2 border-black shadow-[2px_2px_0px_#000] py-2 rounded-xl text-xs font-black uppercase tracking-wider active:translate-x-0.5 active:translate-y-0.5 transition-all">
                        Home
                    </a>
                </div>
            </div>
        </aside>

        <!-- MAIN CONTENT AREA -->
        <main id="content-area">
            
            <!-- Hero Header Card -->
            <div class="bg-white border-4 border-black rounded-2xl p-6 md:p-10 shadow-[8px_8px_0px_#000] mb-8">
                <div class="bg-emerald-400 border-2 border-black px-3 py-1 rounded-lg text-xs font-black uppercase tracking-wider inline-block shadow-[2px_2px_0px_#000] mb-3">
                    <?php echo htmlspecialchars($book['category'] ?? 'AI EXECUTION FOUNDRY'); ?>
                </div>
                <h2 class="text-2xl md:text-4xl font-black text-black uppercase italic tracking-tight mb-4">
                    <?php echo htmlspecialchars($book['title']); ?>
                </h2>
                <!--div class="text-gray-900 font-bold leading-relaxed bg-amber-100 border-2 border-black p-4 rounded-xl shadow-[3px_3px_0px_#000]">
                    <?php echo nl2br(htmlspecialchars($book['hero'])); ?>
                </div-->
				<div class="text-gray-900 font-bold leading-relaxed bg-amber-100 border-2 border-black p-4 rounded-xl shadow-[3px_3px_0px_#000]">
                     <?php echo nl2br(html_entity_decode($book['hero'])); ?>
                </div>
				
            </div>

            <!-- Visual Mapping Callout Note -->
            <div class="bg-cyan-200 border-4 border-black p-6 rounded-2xl shadow-[6px_6px_0px_#000] mb-8">
                <h3 class="text-black font-black text-xs uppercase tracking-widest mb-2 flex items-center gap-2">
                    <span class="bg-white border border-black rounded-full px-1.5 py-0.5">🔵</span> Visual Mapping Note
                </h3>
                <p class="text-black text-sm font-bold leading-snug">
                    Map these execution steps into a linear gantt-style mental model. Treat each PHP function as a 'Station' in a production line. When prompting Gemini, ask for output that visualizes the 'Handshake' between data input and AI result.
                </p>
            </div>

            <!-- Task Units Stack (Cake Layer Reading Experience) -->
            <div class="space-y-8">
                <?php 
                for($i = 1; $i <= 7; $i++): 
                    $ch_name = $book["chname$i"];
                    $ch_text = $book["chapter$i"];
                    if(!empty($ch_name)): 
                ?>
                    <section id="task-unit-<?php echo $i; ?>" class="bg-emerald-100 border-4 border-black p-6 md:p-8 rounded-2xl shadow-[6px_6px_0px_#000] scroll-mt-6">
                        <div class="flex justify-between items-start mb-4 pb-3 border-b-4 border-black">
                            <div>
                                <span class="bg-yellow-300 text-black border-2 border-black px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-wider shadow-[2px_2px_0px_#000]">
                                    Task Unit 0<?php echo $i; ?>
                                </span>
                                <h4 class="text-lg md:text-2xl font-black text-black uppercase tracking-tight italic mt-2">
                                    <?php echo htmlspecialchars($ch_name); ?>
                                </h4>
                            </div>
                            <button onclick="copyModule(<?php echo $i; ?>)" id="btn-<?php echo $i; ?>" class="bg-black text-white hover:bg-emerald-700 border-2 border-black px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider shadow-[3px_3px_0px_#000] active:translate-x-0.5 active:translate-y-0.5 transition-all">
                                Copy
                            </button>
                        </div>
                        
                        <div id="content-<?php echo $i; ?>" class="bg-white border-2 border-black p-4 md:p-6 rounded-xl text-black font-medium whitespace-pre-wrap leading-relaxed shadow-[3px_3px_0px_#000]">
                            <?php echo htmlspecialchars($ch_text); ?>
                        </div>
                    </section>
                <?php 
                    endif;
                endfor; 
                ?>
            </div>

            <!-- Page Footer -->
            <div class="mt-12 bg-white border-4 border-black p-6 rounded-2xl shadow-[6px_6px_0px_#000] flex flex-col md:flex-row justify-between items-center gap-4">
                <div>
                    <p class="text-[10px] font-black text-emerald-800 uppercase tracking-widest">GAC Sovereign Identity</p>
                    <p class="text-sm font-black text-black uppercase italic">
                        <?php echo htmlspecialchars($book['author']); ?> | © <?php echo htmlspecialchars($book['year']); ?>
                    </p>
                </div>
                <div class="text-[10px] font-black text-black uppercase tracking-widest bg-yellow-300 border-2 border-black px-3 py-1.5 rounded-lg shadow-[2px_2px_0px_#000]">
                    GAC Foundry | Node: 35.239.81.206
                </div>
            </div>

        </main>
    </div>

    <!-- Copy Clipboard Script -->
    <script>
        function copyModule(id) {
            const text = document.getElementById('content-' + id).innerText;
            const btn = document.getElementById('btn-' + id);
            
            navigator.clipboard.writeText(text).then(() => {
                const originalText = btn.innerText;
                btn.innerText = "DONE!";
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