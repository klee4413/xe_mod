<?php
// =========================================================================
// GAC COGNITIVE FOUNDRY — PLATFORM INTERFACE : interview-guide.php
// PURPOSE: ADMISSION INTERVIEW PROTOCOL & POLICY DOCUMENT INTERFACE
// UPDATE: UPGRADED TO RESILIENT HIGH-CONTRAST NEUBRUTALISM FRAMEWORK
// =========================================================================
session_start();

// GSAAC DEMO SESSION HYDRATION LEDGER
$student_id = $_SESSION['user_id']    ?? 9990;
$first_name = $_SESSION['first_name'] ?? 'Scholar';
$last_name  = $_SESSION['last_name']  ?? '';
$email      = $_SESSION['email']  ?? '';
 

date_default_timezone_set('America/Los_Angeles');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>AIGC | Admission Interview Protocol</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        body { 
            background-color: #bdf2bd; 
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; 
        }
        /* Neubrutalist Core Design Tokens */
        .brutal-card {
            background: #ffffff;
            border: 4px solid #0f172a;
            box-shadow: 8px 8px 0px 0px #0f172a;
        }
        .brutal-banner {
            background: #fef08a; /* Soft warning yellow */
            border: 3px solid #0f172a;
            box-shadow: 4px 4px 0px 0px #0f172a;
        }
        .brutal-gate {
            border-bottom: 3px dashed #0f172a;
            padding-bottom: 1.5rem;
            margin-bottom: 1.5rem;
        }
        .brutal-gate:last-child {
            border-bottom: none;
            padding-bottom: 0;
            margin-bottom: 0;
        }
        .brutal-btn-dark {
            background-color: #0f172a;
            color: #ffffff;
            border: 3px solid #0f172a;
            box-shadow: 4px 4px 0px 0px #1e3a8a;
            transition: all 0.15s ease-in-out;
        }
        .brutal-btn-dark:hover {
            transform: translate(-2px, -2px);
            box-shadow: 6px 6px 0px 0px #1e3a8a;
        }
        .brutal-btn-dark:active {
            transform: translate(2px, 2px);
            box-shadow: 2px 2px 0px 0px #1e3a8a;
        }
        .brutal-btn-green {
            background-color: #22c55e;
            color: #ffffff;
            border: 3px solid #0f172a;
            box-shadow: 4px 4px 0px 0px #0f172a;
            transition: all 0.15s ease-in-out;
        }
        .brutal-btn-green:hover {
            transform: translate(-2px, -2px);
            box-shadow: 6px 6px 0px 0px #0f172a;
        }
        .brutal-btn-green:active {
            transform: translate(2px, 2px);
            box-shadow: 2px 2px 0px 0px #0f172a;
        }
        .brutal-badge {
            background-color: #f43f5e; /* Rose-500 */
            color: white;
            border: 2px solid #0f172a;
            box-shadow: 2px 2px 0px 0px #0f172a;
        }
    </style>
</head>
<body class="min-h-screen p-4 md:p-8 flex flex-col justify-between items-center">

    <header class="w-full max-w-2xl mx-auto text-center mb-8 space-y-2">
        <h1 class="text-2xl md:text-3xl font-black text-slate-900 uppercase tracking-tight" id="displayName">
            AIGC Admissions Interview Protocol
        </h1>
        
        <div class="inline-flex items-center gap-2 brutal-badge px-4 py-1.5 rounded-xl font-mono text-xs font-black uppercase">
            <i class="fa-solid fa-user-shield"></i>
            ID: <?php echo $student_id; ?> &bull; <?php echo $first_name . " " . $last_name; ?>
        </div>
        
        <p class="text-xs font-mono font-bold text-emerald-950 uppercase tracking-wider" id="displayTime"></p>         
    </header>

    <main class="w-full max-w-2xl mx-auto brutal-card rounded-2xl p-6 md:p-8 space-y-6">
        
        <div class="brutal-banner p-4 md:p-5 rounded-xl flex items-start gap-3">
            <div class="bg-yellow-400 text-slate-900 p-2 rounded-lg border-2 border-slate-900 shrink-0 shadow">
                <i class="fa-solid fa-triangle-exclamation text-base"></i>
            </div>
            <p class="text-slate-900 font-bold text-xs md:text-sm leading-relaxed">         
                Welcome to the Gemini AI College (AIGC) Admission Interview. Please read the following guidance carefully before initializing your session. The college admissions interview is a collaborative dialogue between the interviewer and interviewee, designed to provide mutual insight.
            </p>
        </div>

        <div class="space-y-5">            
            
            <div class="brutal-gate">
                <h3 class="text-sm font-black text-rose-600 mb-1.5 uppercase tracking-wider flex items-center gap-2">
                    <span class="bg-rose-100 text-rose-700 px-2 py-0.5 border border-rose-600 rounded text-[10px] font-mono">01</span>
                    No Fear, Just Facts
                </h3>
                <p class="text-slate-700 text-xs leading-relaxed font-medium text-justify">
                    Do not be afraid or nervous. This is a documented, written-response selection interview. Unlike traditional verbal interviews where "charisma" can mask a lack of logic, our system values your <strong>Authenticity Over Perfection</strong>. You have the time to express your true self without the pressure of face-to-face scrutiny.
                </p>
            </div>

            <div class="brutal-gate">
                <h3 class="text-sm font-black text-rose-600 mb-1.5 uppercase tracking-wider flex items-center gap-2">
                    <span class="bg-rose-100 text-rose-700 px-2 py-0.5 border border-rose-600 rounded text-[10px] font-mono">02</span>
                    Read and Think Deeply
                </h3>
                <p class="text-slate-700 text-xs leading-relaxed font-medium text-justify">
                    Read every statement with surgical precision. Think deeply about how your education and experiences align with the AIGC education mission before selecting any checkbox. Remember: Your daily thinking or feeling about your life at present time of your selections generate the idea toward to AIGC to evaluate your fit for our "Zero-Failure" environment.
                </p>
            </div>

            <div class="brutal-gate">
                <h3 class="text-sm font-black text-rose-600 mb-1.5 uppercase tracking-wider flex items-center gap-2">
                    <span class="bg-rose-100 text-rose-700 px-2 py-0.5 border border-rose-600 rounded text-[10px] font-mono">03</span>
                    The "Second Interview Appeal" Clause
                </h3>
                <p class="text-slate-700 text-xs leading-relaxed font-medium text-justify">
                    If you are dissatisfied with your performance for any reason (education, experience, technical issues, health, or external environment), you are entitled to a <strong>Second Chance</strong>. Send a formal email to the Dean to request a re-interview. At AIGC, we believe in Resilience—your ability to identify a failure and seek a correction is a positive trait we value.
                </p>
            </div>

            <div class="brutal-gate">
                <h3 class="text-sm font-black text-rose-600 mb-1.5 uppercase tracking-wider flex items-center gap-2">
                    <span class="bg-rose-100 text-rose-700 px-2 py-0.5 border border-rose-600 rounded text-[10px] font-mono">04</span>
                    Final Admission Notice
                </h3>
                <p class="text-slate-700 text-xs leading-relaxed font-medium text-justify">
                    Upon clicking the "Finish" button, you will be presented with a Summary Result of your responses. This summary is for your transparency and is not a final decision. The final <strong>Acceptance Decision</strong> will be audited by the Dean’s office and sent via email.
                </p>
            </div>

            <div class="brutal-gate">
                <h3 class="text-sm font-black text-rose-600 mb-1.5 uppercase tracking-wider flex items-center gap-2">
                    <span class="bg-rose-100 text-rose-700 px-2 py-0.5 border border-rose-600 rounded text-[10px] font-mono">05</span>
                    Interview Administration Fee $15
                </h3>
                <p class="text-slate-700 text-xs leading-relaxed font-medium text-justify">
                    A standard processing fee covers the computational analysis cost of processing your interview responses.<br>
					Pay fee at Bursar's Office, https://store.aigeminicollege.org/products/admission-interview-fee
                </p>
            </div>
        </div>

        <div class="pt-6 border-t-4 border-slate-900 flex flex-col sm:flex-row items-center justify-between gap-4">
            
            <a href="index.php" class="w-full sm:w-auto h-12 px-6 brutal-btn-dark rounded-xl text-xs font-black uppercase tracking-wider flex items-center justify-center gap-2">
                <i class="fa-solid fa-xmark"></i> Quit Interview
            </a>

            <a href="interview.php" class="w-full sm:w-auto h-12 px-8 brutal-btn-green rounded-xl text-xs font-black uppercase tracking-widest flex items-center justify-center gap-2">
                Start Interview <i class="fa-solid fa-chevron-right"></i>
            </a>
        </div>
    </main>

    <footer class="w-full max-w-2xl mx-auto mt-6 text-center text-[10px] font-mono font-bold text-emerald-950 uppercase tracking-widest">
        &copy; 2026 AI GEMINI COLLEGE &copy; DEPLOYMENT SPEC: Interview Guide
    </footer>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Re-render live standard local time configurations
            const timestamp = new Date().toLocaleString('en-US', { timeZone: 'America/Los_Angeles' });
            document.getElementById('displayTime').innerText = `Date/Time: ${timestamp} PST`;
            console.log("AIGC Neubrutalist Identity Bridge: Active Session Engaged.");
        });
    </script>
</body>
</html>