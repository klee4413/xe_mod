<?php
// [TIMESTAMP: 2026-04-03] - GAC Session Identity Sandbox testsession.php
session_start();

$msg = "";

// 2. LOGIC GATE: CREATE SESSION
if (isset($_POST['create'])) {
    // We use ID 1001/1002 to match your established test samples
    $_SESSION['user_id']    = '1002'; 
    $_SESSION['first_name'] = 'John';
    $_SESSION['last_name']  = 'Doe';
    $_SESSION['email']      = 'johndoe@gmail.com';
    
    // Logic: Harmonize message with the actual variable set
    $msg = "Identity Grounded: Scholar {$_SESSION['user_id']} ({$_SESSION['first_name']} {$_SESSION['last_name']}) is now active.";
}

// 3. LOGIC GATE: REMOVE SESSION
if (isset($_POST['remove'])) {
    session_unset();
    session_destroy();
    // Restart session immediately to allow the UI to show the 'NULL' state
    session_start();
    $msg = "Session Purified: Identity cleared.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>GAC | Session Logic Test</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .gac-accent { color: #30C89F; }
        .gac-bg-accent { background-color: #30C89F; }
    </style>
</head>
<body class="bg-slate-900 text-slate-100 min-h-screen flex items-center justify-center p-6">

    <div class="max-w-md w-full bg-slate-800 rounded-[2rem] shadow-2xl border border-slate-700 p-10">
        <header class="text-center mb-8">
            <div class="inline-block bg-emerald-500/10 text-emerald-400 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest mb-2">Sandbox Environment</div>
            <h1 class="text-2xl font-black uppercase tracking-tighter gac-accent italic">Session Foundry</h1>
        </header>
        
        <div class="bg-slate-950 rounded-2xl p-6 mb-8 font-mono text-[11px] space-y-3 border border-slate-700 shadow-inner">
            <div class="flex justify-between border-b border-slate-800 pb-2">
                <span class="text-slate-500 font-bold uppercase">Variable</span>
                <span class="text-slate-500 font-bold uppercase">Value</span>
            </div>
            <p class="flex justify-between"><span>USER_ID:</span> <span class="text-emerald-400"><?php echo $_SESSION['user_id'] ?? 'NULL'; ?></span></p>
            <p class="flex justify-between"><span>FIRST_NAME:</span> <span class="text-emerald-400"><?php echo $_SESSION['first_name'] ?? 'NULL'; ?></span></p>
            <p class="flex justify-between"><span>LAST_NAME:</span> <span class="text-emerald-400"><?php echo $_SESSION['last_name'] ?? 'NULL'; ?></span></p>
            <p class="flex justify-between"><span>EMAIL:</span> <span class="text-emerald-400"><?php echo $_SESSION['email'] ?? 'NULL'; ?></span></p>
        </div>

        <?php if($msg): ?>
            <div class="mb-8 p-4 bg-emerald-900/20 border border-emerald-500/30 text-emerald-400 text-xs rounded-xl font-bold italic text-center">
                <?php echo $msg; ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-4">
            <button type="submit" name="create" class="w-full gac-bg-bg-accent bg-[#30C89F] text-slate-900 font-black py-4 rounded-xl hover:brightness-110 transition-all uppercase tracking-widest text-xs shadow-lg">
                Ground Identity (John Doe)
            </button>
            
            <button type="submit" name="remove" class="w-full bg-rose-600/10 text-rose-500 border border-rose-600/30 font-black py-4 rounded-xl hover:bg-rose-600 hover:text-white transition-all uppercase tracking-widest text-xs">
                Purify Session
            </button>
        </form>

        <div class="mt-10 pt-6 border-t border-slate-700 grid grid-cols-2 gap-4">
            <a href="exam-select-now.php" class="text-[9px] text-center font-black text-slate-500 uppercase tracking-widest hover:text-emerald-400 transition-colors">Exam Hall</a>
            <a href="course-regit.php" class="text-[9px] text-center font-black text-slate-500 uppercase tracking-widest hover:text-emerald-400 transition-colors">Transcript</a>
        </div>
    </div>

</body>
</html>