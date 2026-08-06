<?php
// [TIMESTAMP: 2026-04-02] - GAC CLASS GUIDE: Commitment Pattern Interface ------  class-guide.php in webbook table 2
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
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) { die("Database Connection Fault."); }

// 1. SURGICAL DATA RETRIEVAL: Targeting ID 2
$stmt = $pdo->prepare("SELECT * FROM webbooks WHERE id = 8 LIMIT 1");// place to change for the next webpage
$stmt->execute();
$book = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$book) { die("GAC Logic Alert: Tuition Payment Guide (ID 2) not grounded in repository."); }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>GAC Guide | <?php echo htmlspecialchars($book['title']); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .commitment-gradient { background: linear-gradient(135deg, #148145 0%, #1e293b 100%); }
        .hero-overlay { background: linear-gradient(to bottom, rgba(15, 23, 42, 0.4), rgba(15, 23, 42, 0.9)); }
        article { transition: transform 0.3s ease; }
        article:hover { transform: translateY(-5px); }
    </style>
</head>
<body class="bg-slate-50 font-sans leading-relaxed">

    <header class="relative h-[60vh] flex items-center justify-center text-center overflow-hidden">
        <!--img src="<?php echo $book['hero']; ?>" class="absolute inset-0 w-full h-full object-cover" alt="Hero"-->
        <div class="absolute inset-0 hero-overlay"></div>
        
        <div class="relative z-10 px-6 max-w-4xl">
            <span class="bg-emerald-500 text-white px-4 py-1 rounded-full text-[10px] font-black uppercase tracking-[0.2em] mb-4 inline-block">
			 <div class="text-right">
               <span class="text-red-600 text-xl md:text-2xl ml-4 font-mono">
            ID:<?php echo $_SESSION['user_id']; ?>       
			<?php echo $_SESSION['first_name'] . " " . $_SESSION['last_name']; ?>
        </span>
            </div>
			</span>
            <h1 class="text-4xl md:text-6xl font-black text-white uppercase tracking-tighter leading-none mb-6">
                <?php echo $book['title']; ?>
            </h1>
            <!--p class="text-emerald-400 font-mono text-sm uppercase tracking-widest">Mastery Velocity: 100% Guaranteed</p-->
			     <a href="<?php echo htmlspecialchars($book['linkto2']); ?>" 
   class="inline-block bg-[#F5F5DC] hover:bg-[#EEDC82] text-[#CC0000] font-bold px-12 py-6 rounded-[2rem] uppercase tracking-[0.2em] text-xl transition-all border-2 border-[#CC0000]/20 shadow-xl scale-110">
    Go to Bursar's Office
</a>
        </div>
    </header>

    <div class="max-w-7xl mx-auto px-6 py-16 flex flex-col lg:flex-row gap-12">
        
        <nav class="lg:w-1/4">
            <!--div class="sticky top-24 bg-white rounded-3xl p-8 border border-slate-200 shadow-sm">
                <h3 class="text-[10px] font-black text-slate-400 uppercase mb-6 tracking-widest">Curriculum Path</h3>
                <ul class="space-y-4">
                    <?php for($i=1; $i<=6; $i++): if(empty($book['chname'.$i])) continue; ?>
                    <li>
                        <a href="#section-<?php echo $i; ?>" class="group flex items-center gap-3 text-sm font-bold text-slate-500 hover:text-emerald-600 transition-all">
                            <span class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center group-hover:bg-emerald-100 text-[10px]"><?php echo $i; ?></span>
                            <?php echo $book['chname'.$i]; ?>
                        </a>
                    </li>
                    <?php endfor; ?>
                </ul>
            </div-->
        </nav>

        <main class="lg:w-3/4 space-y-16">
            <?php for($i=1; $i<=6; $i++): if(empty($book['chname'.$i])) continue; ?>
            <section id="section-<?php echo $i; ?>" class="scroll-mt-28">
                <article class="bg-white rounded-3xl p-10 border border-slate-200 shadow-xl relative overflow-hidden">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="h-1 w-12 bg-emerald-500 rounded-full"></div>
                        <h2 class="text-2xl font-black text-slate-900 uppercase italic"><?php echo $book['chname'.$i]; ?></h2>
                    </div>
                    <div class="text-slate-600 text-lg leading-relaxed">
                        <?php echo nl2br(htmlspecialchars($book['chapter'.$i])); ?>
                    </div>
                </article>
            </section>
            <?php endfor; ?>

            <footer class="commitment-gradient rounded-[3rem] p-12 text-center text-white shadow-2xl border border-white/10">
                <h3 class="text-3xl font-black mb-6 tracking-tighter">STUDENT FEE PAYMENT REPORT</h3>
                <p class="text-slate-300 text-lg mb-10 max-w-2xl mx-auto italic">
                    "<?php echo $book['footer']; ?>"
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="<?php echo $book['linkto2']; ?>" class="bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black px-12 py-4 rounded-2xl uppercase tracking-widest text-sm transition-all shadow-lg">
                        Go to Bursar's Office 
                    </a>
                    <a href="<?php echo $book['linkto']; ?>" class="bg-green-700 hover:bg-slate-600 text-white font-black px-12 py-4 rounded-2xl uppercase tracking-widest text-sm transition-all border border-white/10">
                        To Campus
                    </a>
                </div>
            </footer>
        </main>
    </div>

</body>
</html>
