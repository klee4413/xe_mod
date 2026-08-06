<?php
// [TIMESTAMP: 2026-04-04] - AIGC AI Research Hub notebook-button.php
 
$student_name = $_SESSION['first_name'] ?? 'Scholar';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AIGC | AI Study Desk</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .glass-morphism {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex items-center justify-center p-6">

    <div class="max-w-2xl w-full text-center">
        <header class="mb-10">
            <div class="inline-block bg-blue-500/20 text-blue-400 px-4 py-1 rounded-full text-[10px] font-black uppercase tracking-[0.2em] mb-4">
                Ai Assited Advanced Learning Desk
            </div>
            <h1 class="text-4xl font-black tracking-tighter uppercase mb-2">AIGC NoteBookLM Study & Research Desk</h1>
            <p class="text-slate-400 text-sm">Welcome, <span class="text-blue-400 font-bold"><?php echo htmlspecialchars($student_name); ?></span>. Use the NotebookLM desk to launch your AI study assistant.</p>
        </header>

        <div class="glass-morphism rounded-[2.5rem] p-12 shadow-2xl">
            <div class="mb-8">
                <div class="w-16 h-16 bg-blue-600 rounded-2xl mx-auto flex items-center justify-center mb-6 shadow-lg shadow-blue-600/20">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        AI<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                    </svg>
                </div>
                <h2 class="text-xl font-bold mb-2">***Do not forget to upload your pdf textbook first.</h2>
                <p class="text-slate-500 text-xs uppercase tracking-widest font-semibold">External Neural Link</p>
            </div>

            <div class="flex flex-col items-center gap-6">
                <button onclick="openNotebookLM()" class="group relative w-full bg-blue-600 hover:bg-blue-500 text-white px-8 py-5 rounded-2xl font-black shadow-xl shadow-blue-900/20 transition-all transform hover:-translate-y-1 active:scale-95 uppercase tracking-widest text-sm">
                    1. Go to AIGC Study Desk (NotebookLM)
                    <span class="absolute right-6 opacity-0 group-hover:opacity-100 transition-opacity">→</span>
                </button>
                   <!--button onclick="study-report.php" class="group relative w-full bg-yellow-600 hover:bg-blue-500 text-white px-9 py-5 rounded-2xl font-black shadow-xl shadow-green-900/20 transition-all transform hover:-translate-y-1 active:scale-95 uppercase tracking-widest text-sm">
                  ←  2. After study session, Do your study report
                    <span class="absolute right-6 opacity-0 group-hover:opacity-100 transition-opacity">→</span>
                </button-->
                <button onclick="window.location.href='study-report.php'" 
        class="group relative w-full bg-yellow-600 hover:bg-blue-500 text-white px-9 py-5 rounded-2xl font-black shadow-xl shadow-green-900/20 transition-all transform hover:-translate-y-1 active:scale-95 uppercase tracking-widest text-sm">
    ← 2. After study session, Do your study report
    <span class="absolute right-6 opacity-0 group-hover:opacity-100 transition-opacity">→</span>
</button>
				<!--a href="campus.php" class="text-xs font-bold text-slate-500 hover:text-blue-400 transition-colors uppercase tracking-widest flex items-center gap-2">
                    <span>←</span> Go back to Campus 
                </a-->
            </div>
        </div>

        <footer class="mt-12 opacity-30">
            <p class="text-[19px] uppercase tracking-[0.5em]">AI Gemini College &copy; 2026</p>
        </footer>
    </div>

    <script>
    function openNotebookLM() {
        // REPLACE THIS with your shared NotebookLM public link
        const nlmUrl = "https://notebooklm.google.com/"; 
        
        // POP-UP CONFIGURATION: Optimal for research side-by-side
        const width = 1400;
        const height = 800;
        const left = (window.screen.width / 2) - (width / 2);
        const top = (window.screen.height / 2) - (height / 2);
        
        const features = `menubar=no,location=no,resizable=yes,scrollbars=yes,status=no,width=${width},height=${height},top=${top},left=${left}`;
        
        window.open(nlmUrl, "GAC_Research_Hub", features);
    }
    </script>

</body>
</html>
