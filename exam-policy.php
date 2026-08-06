<?php
//Exam Policy & Session Grounding exam-policy.php
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
// Security Gate: Ensure the scholar is grounded in the session
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>GAC | Official Exam Policy</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background-color: #f0fdf4; font-family: 'Inter', sans-serif; }
        .policy-frame { border: 2px solid #166534; border-radius: 1.5rem; background: white; }
        .logic-highlight { color: #166534; font-weight: 800; }
    </style>
</head>
<body class="p-4 md:p-12">

    <div class="max-w-4xl mx-auto policy-frame p-8 md:p-12 shadow-xl">
        
        <div class="flex justify-between items-start border-b border-green-100 pb-6 mb-8">
            <div>
                <h1 class="text-2xl font-black text-green-900 uppercase tracking-tighter">GAC Exam Policy</h1>
                <p id="studentIdentity" class="text-red-600 font-mono text-sm font-bold">Initializing Identity...</p>
            </div>
            <div class="text-right">
                <p class="text-[10px] text-slate-400 font-mono">DOC_VER: 2026.03.29</p>
                <p class="text-[10px] text-slate-400 font-mono">SESSION_TS: <?php echo date("Y-m-d H:i:s"); ?></p>
            </div>
        </div>

        <div class="space-y-8 text-slate-700 leading-relaxed">
            
            <section>
                <h2 class="text-lg font-bold text-green-800 mb-3 underline decoration-green-300">1. Assessment Methodology</h2>
                <p class="text-sm">
                   Instead of relying on a handful of questions, the test gives you random 50 to 100 (T/F) quizzes from a massive bank of 400+ quizzes. This acts like casting a wide net in the ocean; it allows the system to check how much student actually learned about the entire subject, rather than just a few specific topics.
                </p>
            </section>

            <section class="bg-green-50 p-6 rounded-xl border border-green-100">
                <h2 class="text-lg font-bold text-green-800 mb-3">2. Negative Marking Protocol</h2>
                <p class="text-sm mb-4">
                    To offset the 50% guessing factor, GAC employs a <span class="logic-highlight">Negative Marking</span> system.
                </p>
                <ul class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs font-mono">
                    <li class="bg-white p-3 rounded border border-green-200">
                        <span class="text-emerald-600 font-bold">+1.0 Point</span> per Correct Answer
                    </li>
                    <li class="bg-white p-3 rounded border border-red-200">
                        <span class="text-red-600 font-bold">-0.5 Point</span> per Incorrect Answer
                    </li>
                </ul>
                <p class="text-[10px] mt-4 italic text-slate-500">
                    Note: It is statistically safer to leave a question blank than to guess blindly.
                </p>
            </section>

            <section>
                <h2 class="text-lg font-bold text-green-800 mb-3">3. Learning by Quiz</h2>
                <p class="text-lg font-bold text-blue-600">
                    Learning by Daily Study and Exam Method (DSEM): <span class="logic-highlight">Every study session and quiz at the NotebookLM Class Desk will give strength of brain muscle. Repeated feedback enhances long-term retention compared to passive studying..
					
                </p>
            </section>

            <div class="mt-12 pt-8 border-t border-green-100">
                <h3 class="text-center font-black text-slate-900 mb-6 uppercase tracking-widest text-xs">Sample of Exam Selection List</h3>
                <form action="exam.php" method="POST" class="space-y-6">
                    <div class="flex flex-col md:flex-row justify-around items-center bg-slate-50 p-6 rounded-2xl">
                        <div class="space-y-2">
                            <span class="block text-[10px] font-bold text-slate-400">COURSE AM110</span>
                            <label class="inline-flex items-center mr-4">
                                <input type="radio" name="exam_type" value="mid_110" class="text-green-600"> 
                                <span class="ml-2 text-sm font-bold">Midterm</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="exam_type" value="final_110" class="text-green-600"> 
                                <span class="ml-2 text-sm font-bold">Final</span>
                            </label>
                        </div>
                        <div class="h-10 w-px bg-slate-200 hidden md:block"></div>
                        <div class="space-y-2">
                            <span class="block text-[10px] font-bold text-slate-400">COURSE AM111</span>
                            <label class="inline-flex items-center mr-4">
                                <input type="radio" name="exam_type" value="mid_111" class="text-green-600"> 
                                <span class="ml-2 text-sm font-bold">Midterm</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="exam_type" value="final_111" class="text-green-600"> 
                                <span class="ml-2 text-sm font-bold">Final</span>
                            </label>
                        </div>
                    </div>
                    
                    <!--button type="submit" class="w-full bg-green-700 hover:bg-green-800 text-white font-black py-4 rounded-xl shadow-lg transition-all transform hover:scale-[1.01] active:scale-95 uppercase tracking-widest">
                        Enter Exam Hall
                    </button-->
					<a href="campus.php"> 
					 <button type="submit" class="w-full bg-green-600 hover:bg-green-800 text-white font-black py-4 rounded-xl shadow-lg transition-all transform hover:scale-[1.01] active:scale-95 uppercase tracking-widest">
                        Back to Campus
					</a>
                    </button>			  
                
                </form>
            </div>

        </div>
    </div>

    <script>
    async function groundStudentIdentity() {
        try {
            const response = await fetch('get_student_session.php');
            const student = await response.json();
            if (student.status === "Authorized") {
                const identityDisplay = document.getElementById('studentIdentity');
                if (identityDisplay) {
                    identityDisplay.innerText = `Scholar: ${student.first_name} ${student.last_name} (ID: ${student.id})`;
                }
                console.log("GAC Campus: Identity Grounded", student);
            } else {
                window.location.href = "login.php";
            }
        } catch (error) {
            console.error("Logic Error: Identity Bridge Failed", error);
        }
    }
    window.onload = groundStudentIdentity;
    </script>
</body>
</html>
