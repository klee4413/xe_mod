<?php
session_start();//Using webbook_lib
require_once 'db-connect.php';
//require_once 'db-config.php';
$student_id = $_SESSION['user_id']    ?? 0;
$first_name = $_SESSION['first_name'] ?? 'Scholar';
$last_name  = $_SESSION['last_name']  ?? '';
$email      = $_SESSION['email']  ?? '';

// 1. DATA ACQUISITION PROTOCOL
// Targeting ID 6 as requested (Reminders)
$target_id = 6;

try {
    $stmt = $pdo->prepare("SELECT * FROM webbook_lib WHERE id = :id AND status = 'active' LIMIT 1");
    $stmt->execute(['id' => $target_id]);
    $notice = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$notice) {
        // Fallback if record is missing or inactive
        $notice = [
            'book_name' => 'Notice Void',
            'description' => 'No active announcements found in the foundry for this sector.',
            'color' => 'gray'
        ];
    }
} catch (PDOException $e) {
    die("Foundry Access Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>AIGC NOTICE - <?php echo htmlspecialchars($notice['book_name']); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 min-h-screen p-6 font-sans">

    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-10">
            <h1 class="text-3xl font-black text-slate-800 tracking-tighter uppercase">
                AIGC <span class="text-green-600">Notice Board</span>
            </h1>
            <a href="campus.php" class="text-sm font-bold text-slate-500 hover:text-green-600 transition-colors uppercase tracking-widest">
                &larr; Back to Campus
            </a>
        </div>

        <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-slate-200">
            <div class="h-4 w-full" style="background-color: <?php echo $notice['color'] ?: '#16a34a'; ?>;"></div>
            
            <div class="p-10">
                <div class="flex items-center gap-3 mb-6">
                    <span class="px-3 py-1 bg-slate-100 text-slate-600 rounded-md text-xs font-bold uppercase tracking-widest">
                        Campus is <?php echo htmlspecialchars($notice['status'])."  now!"; ?>
                    </span>
                    <span class="text-slate-300">|</span>
                    <!--span class="text-slate-500 text-sm italic">ID: <?php echo $notice['id']; ?></span-->
                </div>

                <h2 class="text-5xl font-black text-slate-900 mb-8 leading-tight">
                    <?php echo htmlspecialchars($notice['book_name']); ?>
                </h2>

                <div class="prose prose-slate max-w-none mb-12">
                    <p class="text-xl text-slate-600 leading-relaxed whitespace-pre-line">
                        <?php echo htmlspecialchars($notice['description']); ?>
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <?php if (!empty($notice['linkto'])): ?>
                    <a href="<?php echo $notice['linkto']; ?>" target="_blank" 
                       class="flex items-center justify-center p-4 bg-green-600 hover:bg-green-700 text-white font-bold rounded-2xl transition-all shadow-lg hover:shadow-green-200">
                        To Campus &rarr;
                    </a>
                    <?php endif; ?>

                    <?php if (!empty($notice['linkto2'])): ?>
                    <a href="<?php echo $notice['linkto2']; ?>" target="_blank" 
                       class="flex items-center justify-center p-4 bg-slate-800 hover:bg-slate-900 text-white font-bold rounded-2xl transition-all shadow-lg">
                        Exit
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <p class="mt-8 text-center text-slate-400 text-xs uppercase tracking-widest font-medium">
            &copy; 2026 <?php echo htmlspecialchars($notice['institution'] ?: 'Gemini AI College'); ?> Foundry 
        </p>
    </div>

</body>
</html>