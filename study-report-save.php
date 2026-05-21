<?php
// [TIMESTAMP: 2026-04-07] - GAC STUDY PERFORMANCE RECORDING (Confirmation View)
session_start();
//require_once 'db_connect_local.php';
require_once 'db_connect.php';
// Initialize variables for the UI
$show_confirmation = false;
$s_id = $_SESSION['scholar_id'] ?? 'GAC-UNKNOWN';
$f_name = $_SESSION['first_name'] ?? 'Alpha';
$l_name = $_SESSION['last_name'] ?? 'Scholar';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Capture Form Data
    $s_id    = $_POST['student_id'];
    $c_id    = $_POST['class_id'];
    $subject = $_POST['subject'] ?? 'AI General';
    $status  = $_POST['status'];
    $q_score = (int)$_POST['quiz_score'];
    $comment = substr($_POST['comment'], 0, 50); 

    try {
        // 2. Surgical Insertion into study_reports 
        // (Keeping your logic exactly as it was)
        $sql = "INSERT INTO study_reports 
                (student_id, last_name, class_id, subject, study_status, quiz_score, comments) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$s_id, $l_name, $c_id, $subject, $status, $q_score, $comment]);

        // 3. Instead of Redirecting, we trigger the Confirmation UI
        $show_confirmation = true;

    } catch (PDOException $e) {
        die("Data Grounding Fault: " . $e->getMessage());
    }
}

// If someone tries to access this page directly without POST, redirect them Home
if (!$show_confirmation) {
    header("Location: index.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="refresh" content="15;url=campus.php">
    <meta charset="UTF-8">
    <title>GAC | Report Grounded</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .gac-green { background-color: #059669; }
        .gac-dark { background-color: #064e3b; }
    </style>
</head>
<body class="bg-[#F0F4F0] min-h-screen flex items-center justify-center p-4 font-sans">

    <div class="bg-white w-full max-w-xl rounded-[3rem] shadow-2xl overflow-hidden border-4 border-emerald-500">
        
        <div class="p-10 text-center">
            <div class="w-20 h-20 gac-green rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>

            <h1 class="text-[10px] font-black uppercase tracking-[0.4em] text-emerald-600 mb-2">AI Gemini College</h1>
            <h2 class="text-2xl font-black text-gray-900 uppercase italic mb-8">Thank You For Your Study Report</h2>
            
            <div class="bg-gray-50 border border-gray-200 rounded-2xl py-6 px-4 mb-10">
                <p class="text-[9px] font-black text-gray-400 uppercase mb-1">Scholar Identity</p>
                <p class="text-xl font-bold text-gray-800"><?php echo htmlspecialchars($f_name . " " . $l_name); ?></p>
                <p class="text-sm font-mono text-emerald-600 font-bold"><?php echo htmlspecialchars($s_id); ?></p>
            </div>

            <div class="grid grid-cols-1 gap-4">
                <a href="campus.php" class="gac-green text-white font-black uppercase text-xs tracking-widest py-5 rounded-2xl shadow-xl transition-all hover:scale-[1.02]">
                    Return to Campus
                </a>
                <a href="notebook-button.php" class="bg-gray-800 text-white font-black uppercase text-xs tracking-widest py-5 rounded-2xl shadow-xl transition-all hover:bg-black hover:scale-[1.02]">
                    Back to Study 
                </a>
            </div>
        </div>

        <div class="bg-gray-100 py-4 text-center">AI Gemini Augmented
            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">AI Gemini  College</p>
        </div>
    </div>

</body>
</html>