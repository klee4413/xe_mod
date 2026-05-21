<?php
// [TIMESTAMP: 2026-04-07] - study-report.php
session_start();
$student_id = $_SESSION['user_id'] ?? 'GAC-UNKNOWN';
$l_name = $_SESSION['last_name'] ?? 'Scholar';
$f_name = $_SESSION['first_name'] ?? 'Alpha';
//require_once 'db_connect.php';
//require_once 'db_connect_local.php';
// 1. IDENTITY RETRIEVAL
$time = date("Y-m-d H:i:s");
// 2. SESSION PERSISTENCE MANAGER
//function retrieveSession($pdo) {
 //   $stmt = $pdo->prepare("SELECT * FROM gacSessions WHERE session_id = ? AND expires_at > NOW()");
//    $stmt->execute([session_id()]);
//    return $stmt->fetch(PDO::FETCH_ASSOC);
//}
 //                                            $current_session = retrieveSession($pdo);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>GAC | Study Session Evaluation</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .red-gate { border: 3px solid #EF4444 !important; border-radius: 12px; }
        .gac-green { background-color: #059669; }
        .gac-dark { background-color: #064e3b; }
    </style>
</head>
<body class="bg-[#F0F4F0] min-h-screen flex flex-col items-center justify-center p-4 font-sans">

    <!--div class="w-full max-w-2xl flex justify-between mb-6">
        <a href="index.html" class="bg-green-800 text-white px-6 py-2 rounded-full text-[10px] font-white uppercase tracking-widest shadow-lg hover:bg-black transition-all">
            ← Home (Index)
        </a>
        <a href="campus.php" class="gac-dark text-white px-6 py-2 rounded-full text-[10px] font-black uppercase tracking-widest shadow-lg hover:opacity-90 transition-all">
            Go to Campus →
        </a>
    </div-->

    <div class="bg-white w-full max-w-2xl rounded-3xl shadow-2xl overflow-hidden">
        
        <div class="p-8 border-b border-gray-100 bg-white text-center">
		  <h2 class="text-xl font-black text-gray-800 uppercase bold mb-4">AIGC Student Study Report</h2>
            <h1 class="text-[12px] font-black uppercase tracking-[0.3em] text-red-600 mb-2">*AIGC requires 10+ study and Quiz session reports for each course*</h1>
          
            
            <div class="inline-block bg-gray-50 border border-gray-200 rounded-2xl px-8 py-4">
                <!--p class="text-[9px] font-black text-gray-400 uppercase">Verified Scholar</p-->
				 <p class="text-lg font-bold text-gray-800"><?php echo htmlspecialchars($time); ?></p>
                <p class="text-lg font-bold text-gray-800"><?php echo htmlspecialchars($f_name . " " . $l_name); ?></p>
                <p class="text-xs font-mono text-emerald-600 font-bold"><?php echo htmlspecialchars('ID: '.$student_id); ?></p>
            </div>
        </div>

        <form id="eval-form" action="study-report-save.php" method="POST" class="p-8 space-y-6">
            <input type="hidden" name="student_id" value="<?php echo htmlspecialchars($student_id); ?>">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Course Code</label>
                    <div class="red-gate">
                        <input type="text" name="class_id" required placeholder="e.g. AIM102, Cert1..." 
                               class="w-full p-4 text-sm font-bold outline-none bg-transparent uppercase">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Subject, Topic, Interested or course...</label>
                    <div class="red-gate">
                        <input type="text" name="subject" required placeholder="Prompt Logic, Cot" 
                               class="w-full p-4 text-sm outline-none bg-transparent">
                    </div>
                </div>

                <div class="space-y-2">
				
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Current Vibe</label>
                    <select name="status" class="w-full border-2 border-gray-100 rounded-xl p-4 text-sm outline-none font-medium">
                        <option value="happy">Happy 😊 (Confident)</option>
                        <option value="easy">Easy 🟢 (Fluent)</option>
                        <option value="hard">Hard 🔴 (Needs Review)</option>
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Self-Quiz Score</label>
                    <input type="number" name="quiz_score" required min="0" max="100" placeholder="0-100"
                           class="w-full border-2 border-gray-100 rounded-xl p-4 text-sm outline-none font-bold text-emerald-700">
                </div>
            </div>

            <!--iv class="space-y-2"-->
			<div class="red-gate">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Student Comment on Learings</label>
                <textarea name="comment" required maxlength="100" placeholder="Describe your manual spark today..." 
                          class="w-full border-2 border-gray-100 rounded-xl p-4 text-sm h-24 outline-none resize-none"></textarea>
            </div>

            <button type="submit" class="w-full gac-green text-white font-black uppercase text-xs tracking-widest py-5 rounded-2xl shadow-xl transition-all hover:scale-[1.01] active:scale-95">
               Finish Study Session Report
            </button>
        </form>
    </div>
<script>
    let sessionIsAlive = true;

    function sendHeartbeat() {
        fetch('heartbeat.php')
            .then(response => {
                if (!response.ok) throw new Error('Session Expired');
                return response.json();
            })
            .then(data => {
                sessionIsAlive = true;
                // Silent confirmation in the background logs
                console.log('Neural Link Active: ' + data.timestamp);
            })
            .catch(error => {
                sessionIsAlive = false;
                console.error('Handshake Lost: The session has dissipated.');
            });
    }

    // 1. Initiate pulse every 5 minutes (300,000ms)
    setInterval(sendHeartbeat, 300000);
    
    // 2. Initial pulse
    sendHeartbeat();

    // 3. THE "RE-ENTRY" CHECK
    // When the student finishes NotebookLM and clicks back on the page
    window.onfocus = function() {
        if (!sessionIsAlive) {
            // Only yell if the student has actually returned to the page
            alert("⚠️ SYSTEM ALERT: Your session expired during your study. Please RE-LOGIN in a new tab before clicking Finish to save your work!");
        }
    };
</script>
</body>

</html>