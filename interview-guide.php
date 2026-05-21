<?php
// [TIMESTAMP: 2026-03-30] - GAC Interview Identity Grounding interview-guide.php
// CRITICAL: session_start MUST be at the very top
session_start();
// Optional: Redirect back to signup if someone tries to access this page directly without a session
if (!isset($_SESSION['user_id'])) {
    header("Location: signup.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- interview-guide.html-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AGC | Admission Interview Protocol</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --brick: #BC4A3C;
			--green: #30C89F;
            --google-gray: #DADCE0;
            --soft-bg: #F8F9FA;
        }
        body { background-color: var(--soft-bg); font-family: 'Roboto', 'Inter', sans-serif; }
        .admin-card { background: white; border: 1px solid var(--google-gray); border-radius: 8px; }
        .logic-gate { border-bottom: 1px solid #f1f3f4; padding-bottom: 2rem; margin-bottom: 2rem; }
        .logic-gate:last-child { border-bottom: none; }
        .btn-brick { background-color: var(--brick); transition: all 0.2s; }
        .btn-brick:hover { background-color: #A03D32; transform: translateY(-1px); box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .btn-green { background-color: var(--green); transition: all 0.2s; }
        .btn-green:hover { background-color: #A03D32; transform: translateY(-1px); box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
		.pulse-slow { animation: pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite; }
        @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: .6; } }
    </style>
</head>
<body class="min-h-screen flex flex-col py-12 px-4">

    <div class="max-w-[800px] w-full mx-auto text-center mb-8">
        <!--img src="https://gac-media-storage.s3.amazonaws.com/gac-logo.png" class="h-12 mx-auto mb-4" alt="AGC Logo"-->
        <!--h1 class="text-xs font-black tracking-[0.4em] text-gray-400 uppercase">Official Notice</h1-->
        <!--h2 class="text-3xl font-normal text-gray-900 mt-2">AGC Admissions Interview Protocol</h2-->
		<div class="h-10 w-14 bg-[#BC4A3C] rounded mx-auto mb-2 flex items-center justify-center text-white font-bold">AGC</div>
	   <h2 class="text-3xl font-normal text-gray-900 mt-2" id="displayName">
            AGC Admissions Interview Protocol
			 <span class="text-red-600 text-xl md:text-2xl ml-4 font-mono">
            Guest ID:<?php echo $_SESSION['user_id']; ?>       
			<!--?php echo $_SESSION['first_name'] . " " . $_SESSION['last_name']; ?-->
			<?php echo " " . $_SESSION['last_name']; ?>
        </span>
	</h2>
        <p class="text-xs font-mono text-slate-400 mt-2" id="displayTime"></p>			
    </div>
    <div class="admin-card max-w-[800px] w-full mx-auto p-10 shadow-sm">        
        <div class="bg-red-50 border-l-4 border-[#BC4A3C] p-6 mb-10">
            <p class="text-gray-800 font-medium leading-relaxed">			
                Welcome to the AI Gemini College (AGC) Admission Interview. 
                Please read the following guidance carefully before initializing your session. 
                The college admissions interview is a collaborative dialogue between the interviewer and interviewee, designed to provide mutual insight.  While the interviewer seeks to assess the applicant’s fit, character, and genuine interest in the institution, the interviewee uses the opportunity to demonstrate their knowledge of the college, passion for their intended major, and personal characteristics.
            </p>
        </div>
        <div class="space-y-8">            
            <div class="logic-gate">
                <h3 class="text-lg font-bold text-[#BC4A3C] mb-3 uppercase tracking-tight">1. No Fear, Just Facts</h3>
                <p class="text-gray-600 text-sm leading-relaxed">
                    Do not be afraid or nervous. This is a documented, written-response selection interview. Unlike traditional verbal interviews where "charisma" can mask a lack of logic, our system values your <strong>Authenticity Over Perfection</strong>. You have the time to express your true self without the pressure of face-to-face scrutiny.
                </p>
            </div>

            <div class="logic-gate">
                <h3 class="text-lg font-bold text-[#BC4A3C] mb-3 uppercase tracking-tight">2. Read and Think Deeply</h3>
                <p class="text-gray-600 text-sm leading-relaxed">
                    Read every statement with surgical precision. Think deeply about how your education and experiences align with the AGC education mission before selecting any checkbox. Remember: Your daily thinking or feeling about your life at present time of your selections generate the idea toward to AGC to evaluate your fit for our "Zero-Failure Goal".
                </p>
            </div>
            <div class="logic-gate">
                <h3 class="text-lg font-bold text-[#BC4A3C] mb-3 uppercase tracking-tight">3. The "Second Interview Appeal" Clause</h3>
                <p class="text-gray-600 text-sm leading-relaxed">
                    If you are dissatisfied with your performance for any reason (education, experience, technical issues, health, or external environment), you are entitled to a <strong>Second Chance</strong>. Send a formal email to the Dean to request a re-interview. At AGC, we believe in Resilience—your ability to identify a failure and seek a correction is a positive trait we value.
                </p>
            </div>

            <div class="logic-gate">
                <h3 class="text-lg font-bold text-[#BC4A3C] mb-3 uppercase tracking-tight">4. Final Admission Notice</h3>
                <p class="text-gray-600 text-sm leading-relaxed">
                    Upon clicking the "Finish" button, you will be presented with a Summary Result of your responses. This summary is for your transparency and is not a final decision. The final <strong>Acceptance Decision</strong> will be audited by the Dean’s office and sent via email.
                </p>
            </div>
            <div class="logic-gate">
                <h3 class="text-lg font-bold text-[#BC4A3C] mb-3 uppercase tracking-tight">5.Interview Administration Fee</h3>
                <p class="text-gray-600 text-sm leading-relaxed mb-4 vertical-align: top">
                    A standard processing fee covers the computational analysis cost of processing your interview responses.
                </p>
                <div class="inline-block bg-[#D6331F] text-white px-4 py-2 rounded text-xs font-black tracking-widest">
                  Opening Special --> Free Interview  PROCESSING FEE: $15.00
                </div>
            </div>

            <!--div class="border-2 border-dashed border-[#BC4A3C]/30 p-6 rounded-xl bg-gray-50">
                <p class="text-[#BC4A3C] text-sm italic font-medium">
                    <strong>6. The Integrity Guardrail:</strong> "Any attempt to use external AI to generate these responses is a violation of the AGC Ethics Token. We are looking for Your 10% Manual Spark, not a machine's imitation."
                </p>
            </div-->
        </div>

        <div class="vertical-align: top mt-12 pt-8 border-t border-gray-100 flex flex-col md:flex-row justify-between items-center gap-6">
		<!--a href="your-page.html" onclick="window.open(this.href, '_blank', 'width=' + (screen.width * 0.8) + ',height=' + (screen.height * 0.8) + ',left=' + (screen.width * 0.1) / 2 + ',top=' + (screen.height * 0.1) / 2); return false;">
  Open Page
</a>   
            <a href="https://davidsarahlee.myshopify.com/products/admission-interview-fee" 
               target="_top"
               class="text-gray-500 hover:text-[#BC4A3C] text-sm font-bold flex items-center group transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                PAY INTERVIEW FEE ($15)
            </a-->
<div class="flex flex-col sm:flex-row items-center justify-center space-y-4 sm:space-y-0 sm:space-x-6 mt-12 pt-8 border-t border-gray-100">
    
    <!--a href="https://davidsarahlee.myshopify.com/products/admission-interview-fee" 
       onclick="window.open(this.href, '_blank', 'width=' + (screen.width * 0.8) + ',height=' + (screen.height * 0.8) + ',left=' + (screen.width * 0.1) / 2 + ',top=' + (screen.height * 0.1) / 2); return false;"
       class="w-full sm:w-auto flex items-center justify-center px-8 py-4 bg-[#BC4A3C] hover:bg-[#A03D32] text-white text-lg font-black rounded-xl shadow-xl transform hover:scale-105 transition-all group">
        <svg class="w-6 h-6 mr-3 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
        </svg>
        PAY $0
    </a-->

    <a href="index.php" 
       class="w-full sm:w-auto flex items-center justify-center px-8 py-4 bg-[#0B0D10] hover:bg-gray-800 text-white text-lg font-black rounded-xl shadow-lg transition-all">
        <svg class="w-6 h-6 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
        QUIT
    </a>

<!--/div-->
            <a href="interview.html" class="btn-green text-white px-10 py-4 rounded font-black uppercase tracking-widest text-sm shadow-lg">
                Start Interview 
            </a>
        </div>
<!--div>
        <!--div class="mt-10 text-right opacity-40 text-[10px] font-mono uppercase tracking-tighter">
            Certified by the Office of the Dean<br>
            AI Gemini College | 2026
        </div>
    </div-->

    <!--div class="mt-8 text-center text-gray-400 text-xs flex items-center justify-center space-x-2">
        <span class="pulse-slow text-green-500">●</span>
        <span>Zero Time Pressure Protocol Engaged</span>
    </div-->
</div>
  <script>
    async function groundIntervieweeIdentity() {
        try {
            // Fetching data from the Identity Agent (json4interview.php)
            const response = await fetch('json4interview.php');
            const data = await response.json();
            
            if (data.first_name) {
                const timestamp = new Date().toLocaleString();
                // Injecting First and Last Name into the Protocol Title
                document.getElementById('displayName').innerText = 
                    `AGC Admissions Interview Protocol: for ${data.first_name} ${data.last_name}`;
                
                document.getElementById('displayTime').innerText = `SESSION_TS: ${timestamp}`;
                
                console.log("AGC Integration: Identity Grounded", data);
            } else {
                console.warn("AGC Logic Notice: No active interviewee session found.");
            }
        } catch (error) {
            console.error("AGC Logic Error: Identification Bridge Failed", error);
        }
    }

    // Trigger on load
    window.onload = groundIntervieweeIdentity;
    </script>
</body>
</html>