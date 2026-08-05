<?php
// [TIMESTAMP: 2026-04-01] - AIGC WEBBOOK READER: Google Drive & AI Workspace
session_start();
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
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) { die("Database Connection Fault."); }

// 1. SURGICAL DATA RETRIEVAL: Pulling the specific Google Drive volume
$stmt = $pdo->prepare("SELECT * FROM webbooks WHERE title LIKE '%Google Drive%' LIMIT 1");
$stmt->execute();
$book = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$book) { die("AIGC Logic Alert: Webbook volume not found in repository."); }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AIGC GOOGLE Library | <?php echo htmlspecialchars($book['title']); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            scroll-behavior: smooth; 
            background-color: #065f46; /* Emerald Deep Outer Dark Background */
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
            max-height: calc(100vh - 24px);
            background-color: #fffbeb; /* Light Amber Canvas */
            border: 8px solid #059669; /* Thick Green Neo Frame */
            border-radius: 20px;
            box-shadow: 12px 12px 0px #000000;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            position: relative;
        }

        .scrollable-body {
            overflow-y: auto;
            height: 100%;
        }

        .sticky-sidebar { 
            top: 24px; 
        }

        /* Dropcap styling for neo-brutalist feel */
        .chapter-text::first-letter {
            font-size: 3.5rem;
            font-weight: 900;
            color: #000;
            float: left;
            line-height: 1;
            margin-right: 12px;
            background-color: #fde047;
            padding: 4px 12px;
            border: 3px solid #000;
            box-shadow: 3px 3px 0px #000;
            border-radius: 6px;
        }
    </style>
</head>
<body>

    <!-- Outer Thick Green Framed Container -->
    <div id="main-outer-frame">

        <!-- Top Navigation Bar -->
        <nav class="bg-white border-b-4 border-black px-6 py-4 flex justify-between items-center z-50">
            <div class="flex items-center gap-3">
                <div class="bg-emerald-400 text-black border-2 border-black shadow-[3px_3px_0px_#000] px-3 py-1 rounded-lg font-black text-xs uppercase tracking-wider">
                    AIGC
                </div>
                <h1 class="font-black uppercase tracking-tight text-black text-lg">Overview: Google Drive</h1>
            </div>
            <div class="flex gap-3 text-xs font-black uppercase tracking-wider">
                <a href="campus.php" class="bg-cyan-300 hover:bg-cyan-200 text-black border-2 border-black shadow-[2px_2px_0px_#000] px-3 py-1.5 rounded-lg transition-all active:translate-x-0.5 active:translate-y-0.5">
                    Campus
                </a>
                <a href="library.php" class="bg-amber-300 hover:bg-amber-200 text-black border-2 border-black shadow-[2px_2px_0px_#000] px-3 py-1.5 rounded-lg transition-all active:translate-x-0.5 active:translate-y-0.5">
                    Library Archive
                </a>
            </div>
        </nav>

        <!-- Main Scrollable Content Wrapper -->
        <div class="scrollable-body p-6 lg:p-10">
            <div class="max-w-7xl mx-auto flex flex-col lg:flex-row gap-8">
                
                <!-- Sidebar -->
                <aside class="lg:w-1/4">
                    <div class="bg-white rounded-2xl p-6 border-4 border-black shadow-[6px_6px_0px_#000] sticky sticky-sidebar">
                        <div class="bg-emerald-300 border-2 border-black shadow-[2px_2px_0px_#000] rounded-lg p-2 text-center mb-6">
                            <h3 class="text-xs font-black uppercase text-black tracking-wider">Volume Chapters</h3>
                        </div>
                        
                        <nav class="space-y-2.5">
                            <?php for($i=1; $i<=6; $i++): ?>
                                <a href="#ch<?php echo $i; ?>" class="block p-3 rounded-xl bg-amber-50 hover:bg-amber-200 border-2 border-black shadow-[2px_2px_0px_#000] text-xs font-extrabold text-black transition-all active:translate-x-0.5 active:translate-y-0.5">
                                    <span class="bg-black text-white px-1.5 py-0.5 rounded text-[10px] mr-1.5"><?php echo sprintf('%02d', $i); ?></span>
                                    <?php echo htmlspecialchars($book['chname'.$i]); ?>
                                </a>
                            <?php endfor; ?>
                        </nav>

                        <div class="mt-8 pt-6 border-t-4 border-black">
                            <div class="bg-cyan-200 border-2 border-black shadow-[2px_2px_0px_#000] rounded-xl p-3 text-center">
                                <span class="text-[10px] font-black text-black uppercase tracking-wider block">Author</span>
                                <p class="text-xs font-black text-black mt-0.5"><?php echo htmlspecialchars($book['author']); ?></p>
                            </div>
                        </div>
                    </div>
                </aside>

                <!-- Chapter Content Reader -->
                <main class="lg:w-3/4">
                    <header class="mb-10 bg-white border-4 border-black rounded-2xl p-8 shadow-[8px_8px_0px_#000]">
                        <div class="bg-emerald-400 border-2 border-black h-4 w-28 mb-4 rounded-full shadow-[2px_2px_0px_#000]"></div>
                        <h2 class="text-3xl lg:text-5xl font-black text-black leading-tight mb-4 tracking-tight"><?php echo htmlspecialchars($book['title']); ?></h2>
                        <div class="flex flex-wrap gap-3">
                            <span class="bg-cyan-300 text-black border-2 border-black px-3 py-1 rounded-lg text-xs font-black uppercase shadow-[2px_2px_0px_#000]">
                                🏷️ <?php echo htmlspecialchars($book['category']); ?>
                            </span>
                            <span class="bg-amber-300 text-black border-2 border-black px-3 py-1 rounded-lg text-xs font-black uppercase shadow-[2px_2px_0px_#000]">
                                📅 Edition <?php echo htmlspecialchars($book['year']); ?>
                            </span>
                        </div>
                    </header>

                    <div class="space-y-12">
                        <?php for($i=1; $i<=6; $i++): ?>
                        <section id="ch<?php echo $i; ?>" class="scroll-mt-8">
                            <div class="flex items-center gap-3 mb-4">
                                <span class="text-lg font-black bg-emerald-400 text-black border-2 border-black shadow-[3px_3px_0px_#000] px-3 py-1 rounded-xl">
                                    * <?php echo sprintf('%02d', $i); ?>
                                </span>
                                <h3 class="text-xl lg:text-2xl font-black text-black uppercase tracking-tight bg-white border-2 border-black px-4 py-1 rounded-xl shadow-[3px_3px_0px_#000]">
                                    <?php echo htmlspecialchars($book['chname'.$i]); ?>
                                </h3>
                            </div>

                            <article class="bg-white rounded-2xl p-6 lg:p-8 border-4 border-black shadow-[6px_6px_0px_#000] relative overflow-hidden">
                                <p class="chapter-text text-base lg:text-lg text-black font-semibold leading-relaxed">
                                    <?php echo htmlspecialchars($book['chapter'.$i]); ?>
                                </p>
                            </article>
                        </section>
                        <?php endfor; ?>
                    </div>

                    <!-- Footer -->
                    <footer class="mt-16 pt-8 border-t-4 border-black flex flex-col sm:flex-row justify-between items-center gap-4 pb-8">
                        <a href="<?php echo htmlspecialchars($book['linkto']); ?>" class="bg-rose-400 hover:bg-rose-300 text-black text-xs font-black px-8 py-3.5 rounded-xl border-2 border-black uppercase tracking-wider shadow-[4px_4px_0px_#000] active:translate-x-0.5 active:translate-y-0.5 transition-all">
                            ← Return to Library
                        </a>
                        <p class="text-xs font-black text-black uppercase tracking-wider bg-white border-2 border-black px-4 py-2 rounded-xl shadow-[2px_2px_0px_#000]">
                            © 2026 Gemini AI College  
                        </p>
                    </footer>
                </main>

            </div>
        </div>

    </div>

</body>
</html>