<?php
// AIGC FOUNDRY: Official Privacy & Data Sovereignty Protocol
require_once 'db-connect.php';
session_start();

$first_name = $_SESSION['first_name'] ?? 'Scholar';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>AIGC | Privacy Protocol</title>
    <link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --gac-navy: #000080; --gac-emerald: #ec4899; }
        body { background: #020617; color: #f8fafc; font-family: 'Inter', sans-serif; }
        .policy-card { background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.05); }
        .accent-line { width: 40px; height: 4px; background: var(--gac-emerald); border-radius: 2px; }
    </style>
</head>
<body class="min-h-screen flex flex-col">

    <header class="bg-[#000080] border-b-2 border-[#ec4899] py-6 px-8 flex justify-between items-center shadow-2xl">
        <div>
            <h1 class="text-2xl font-black italic uppercase tracking-tighter text-white">
                AIGC <span class="text-emerald-400">Privacy Protocol</span>
            </h1>
            <p class="text-[10px] uppercase tracking-widest text-blue-300">Data Sovereignty & Student Protection</p>
        </div>
        <a href="index.php" class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2 rounded-lg font-black uppercase text-xs transition-all">
            Return to Home
        </a>
    </header>

    <main class="max-w-4xl mx-auto py-16 px-6 flex-grow">
        
        <div class="mb-12">
            <h2 class="text-4xl font-black uppercase tracking-tight mb-4">Our Commitment to <br><span class="text-emerald-500">Neural Privacy.</span></h2>
            <div class="accent-line mb-6"></div>
            <p class="text-slate-400 leading-relaxed">
                At AI Gemini College (AIGC), we believe that your data is your intellectual property. Our systems are engineered to provide a secure "Foundry" environment where scholars can practice coding and AI interaction without fear of data exploitation.
            </p>
        </div>

        <div class="grid gap-8">
            
            <section class="policy-card p-8 rounded-2xl">
                <div class="flex items-center gap-4 mb-4">
                    <i class="fa-solid fa-shield-halved text-emerald-500 text-xl"></i>
                    <h3 class="text-xl font-bold uppercase tracking-wide">Data Collection</h3>
                </div>
                <p class="text-sm text-slate-400 leading-relaxed">
                    We collect minimal personal identifiers, including your **Student ID, Full Name, and Institutional Email**. This data is used strictly for academic record-keeping, session stabilization, and laboratory access via our <span class="text-blue-400 font-mono">API HOOK</span> infrastructure.
                </p>
            </section>

            <section class="policy-card p-8 rounded-2xl border-l-4 border-emerald-500">
                <div class="flex items-center gap-4 mb-4">
                    <i class="fa-solid fa-microchip text-emerald-500 text-xl"></i>
                    <h3 class="text-xl font-bold uppercase tracking-wide">AI Processing & Prompting</h3>
                </div>
                <p class="text-sm text-slate-400 leading-relaxed">
                    When utilizing our **Language Lab** or **Prompt Linter**, your inputs are processed through our secure Google AI Studio gateway. We do not use your practice prompts to train external models. Your "Neural Insights" remain private to your student profile.
                </p>
            </section>

            <section class="policy-card p-8 rounded-2xl">
                <div class="flex items-center gap-4 mb-4">
                    <i class="fa-solid fa-cookie-bite text-emerald-500 text-xl"></i>
                    <h3 class="text-xl font-bold uppercase tracking-wide">Cookie Protocol</h3>
                </div>
                <p class="text-sm text-slate-400 leading-relaxed">
                    AIGC utilizes **Essential Session Cookies**. These are strictly functional and allow you to transition between campus modules (e.g., from the Video Lab to the Exam Desk) without losing your authentication state. No third-party tracking cookies are permitted in the Foundry.
                </p>
            </section>

            <section class="policy-card p-8 rounded-2xl bg-emerald-900/10">
                <div class="flex items-center gap-4 mb-4">
                    <i class="fa-solid fa-lock text-emerald-500 text-xl"></i>
                    <h3 class="text-xl font-bold uppercase tracking-wide text-emerald-100">Zero-Sell Policy</h3>
                </div>
                <p class="text-sm text-slate-300 leading-relaxed">
                    **AIGC does not sell, trade, or rent student data to third parties.** Data shared with institutional partners is limited to academic accreditation requirements as per our Arizona permit status(Currently in the process of application)
                </p>
            </section>

        </div>

        <div class="mt-16 text-center">
            <p class="text-[10px] text-slate-600 uppercase tracking-[0.3em]">Last Updated: May 2026 | AIGC Administration</p>
        </div>
    </main>

    <footer class="py-10 border-t border-slate-900 text-center">
        <p class="text-slate-500 text-xs uppercase tracking-widest">&copy; 2026 AI Gemini College. All Rights Reserved.</p>
    </footer>

</body>
</html>