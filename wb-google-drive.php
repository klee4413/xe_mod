<?php
// [TIMESTAMP: 2026-04-01] - AIGC WEBBOOK  
session_start();
require_once 'db-connect.php';
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) { die("Database Connection Fault."); }

// 1. SURGICAL DATA RETRIEVAL: Pulling the specific Google Drive volume
$stmt = $pdo->prepare("SELECT * FROM webbooks WHERE title LIKE '%Google Drive%' LIMIT 1");
$stmt->execute();
$book = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$book) { die("GAC Logic Alert: Webbook volume not found in repository."); }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>GAC Library | <?php echo $book['title']; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;700;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; scroll-behavior: smooth; }
        .glass-nav { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(10px); }
        .chapter-card:hover { border-left-color: #3b82f6; }
        .sticky-sidebar { height: calc(100vh - 100px); top: 80px; }
    </style>
</head>
<body class="bg-slate-50 text-slate-900">

    <nav class="glass-nav sticky top-0 z-50 border-b border-slate-200 px-8 py-4 flex justify-between items-center">
        <div class="flex items-center gap-3">
            <div class="bg-blue-600 text-white p-2 rounded-lg font-black text-xs">GAC</div>
            <h1 class="font-black uppercase tracking-tighter text-slate-800">Research Foundry</h1>
        </div>
        <div class="flex gap-6 text-[10px] font-black uppercase tracking-widest text-slate-400">
            <a href="campus.php" class="hover:text-blue-600 transition-all">Campus</a>
            <a href="library.php" class="hover:text-blue-600 transition-all">Library Archive</a>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-8 py-12 flex flex-col lg:flex-row gap-12">
        
        <aside class="lg:w-1/4 sticky-sidebar sticky hidden lg:block">
            <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-200">
                <h3 class="text-[10px] font-black uppercase text-slate-400 mb-6 tracking-widest text-center">Volume Chapters</h3>
                <nav class="space-y-2">
                    <?php for($i=1; $i<=6; $i++): ?>
                        <a href="#ch<?php echo $i; ?>" class="block p-3 rounded-xl hover:bg-blue-50 text-xs font-bold text-slate-500 hover:text-blue-600 transition-all border-l-4 border-transparent">
                            <?php echo $i . ". " . $book['chname'.$i]; ?>
                        </a>
                    <?php endfor; ?>
                </nav>
                <div class="mt-10 pt-6 border-t border-slate-100">
                    <div class="text-center">
                        <span class="text-[9px] font-black text-slate-300 uppercase">Author</span>
                        <p class="text-xs font-bold text-slate-800"><?php echo $book['author']; ?></p>
                    </div>
                </div>
            </div>
        </aside>

        <main class="lg:w-3/4">
            <header class="mb-16">
                <div class="bg-blue-600 h-2 w-24 mb-6 rounded-full"></div>
                <h2 class="text-5xl font-black text-slate-900 leading-tight mb-4"><?php echo $book['title']; ?></h2>
                <div class="flex gap-4">
                    <span class="bg-blue-100 text-blue-600 px-3 py-1 rounded-full text-[10px] font-black uppercase"><?php echo $book['category']; ?></span>
                    <span class="bg-slate-200 text-slate-600 px-3 py-1 rounded-full text-[10px] font-black uppercase">Edition <?php echo $book['year']; ?></span>
                </div>
            </header>

            <div class="space-y-24">
                <?php for($i=1; $i<=6; $i++): ?>
                <section id="ch<?php echo $i; ?>" class="scroll-mt-32">
                    <div class="flex items-center gap-4 mb-6">
                        <span class="text-4xl font-black text-slate-200"><?php echo sprintf('%02d', $i); ?></span>
                        <h3 class="text-2xl font-black text-slate-800 uppercase tracking-tight"><?php echo $book['chname'.$i]; ?></h3>
                    </div>
                    <article class="bg-white rounded-3xl p-10 shadow-xl border border-slate-100 relative overflow-hidden">
                        <div class="absolute top-0 right-0 p-8 opacity-5">
                            <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24"><path d="M13 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V9l-7-7z"/></svg>
                        </div>
                        
                        <p class="text-lg text-slate-600 leading-relaxed first-letter:text-5xl first-letter:font-black first-letter:text-blue-600 first-letter:mr-3 first-letter:float-left">
                            <?php echo $book['chapter'.$i]; ?>
                        </p>
                    </article>
                </section>
                <?php endfor; ?>
            </div>

            <footer class="mt-24 pt-12 border-t border-slate-200 flex justify-between items-center">
                <a href="<?php echo $book['linkto']; ?>" class="bg-slate-900 text-white text-[10px] font-black px-10 py-4 rounded-full uppercase tracking-widest shadow-lg hover:bg-blue-600 transition-all">
                    Return to Library
                </a>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest italic">
                    © 2026 Gemini AI College  
                </p>
            </footer>
        </main>
    </div>

</body>
</html>