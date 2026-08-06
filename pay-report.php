<?php
// 1. SESSION & INFRASTRUCTURE GROUNDING pay-report.php
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

if ($student_id === 0) {
    header("Location: login.php");
    exit();
}

$message = "";

// 2. DATA CAPTURE (POST HANDSHAKE)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $receipt_no = $_POST['receipt_no'] ?? '';
    $total_paid = $_POST['total_amount_paid'] ?? 0;
    
    // Capture the four course inputs from your UI
    $courses = [
        $_POST['course1'] ?? '',
        $_POST['course2'] ?? '',
        $_POST['course3'] ?? '',
        $_POST['course4'] ?? ''
    ];

    // Calculate individual amount (Splitting total across active course entries)
    $active_courses = array_filter($courses); // Remove empty inputs
    $course_count = count($active_courses);
    $individual_amount = ($course_count > 0) ? ($total_paid / $course_count) : 0;

    try {
        $pdo->beginTransaction();
        
        $stmt = $pdo->prepare("INSERT INTO payments (receipt_no, student_id, course_code, amount, status) VALUES (?, ?, ?, ?, 'PAID')");

        foreach ($active_courses as $code) {
            $stmt->execute([$receipt_no, $student_id, $code, $individual_amount]);
        }

        $pdo->commit();
        $message = "Payment Report progressing. No Blank or Duplicated Receipt No. is Accepted.";
    } catch (Exception $e) {
        $pdo->rollBack();
        $message = "Transaction Dissonance: " . $e->getMessage();
		header("Refresh: 5; url=campus.php");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>GAC | Fee Payment Report</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 p-10">
    <div class="max-w-2xl mx-auto bg-white rounded-[2.5rem] shadow-2xl p-12">
        <h2 class="text-center font-black text-2xl text-slate-800 uppercase tracking-tight mb-8">
            AIGC Student Fee Payment Report to Administration
        </h2>

        <div class="bg-slate-50 rounded-3xl p-6 text-center mb-10 border border-slate-100">
            <p class="text-slate-400 font-mono text-sm"><?php echo date('Y-m-d H:i:s'); ?></p>
            <p class="text-2xl font-bold text-slate-800"><?php echo "$first_name $last_name"; ?></p>
            <p class="text-green-600 font-mono font-bold">ID: <?php echo $student_id; ?></p>
        </div>

        <form method="POST" class="space-y-6">
            <div class="grid grid-cols-2 gap-6">
                <input type="text" name="course1" placeholder="E.G. AIM101..." class="p-4 border-2 border-red-400 rounded-2xl">
                <input type="text" name="course2" placeholder="e.g. AIM102..." class="p-4 border-2 border-red-400 rounded-2xl">
                <input type="text" name="course3" placeholder="e.g. AIM104..." class="p-4 border-2 border-red-400 rounded-2xl">
                <input type="text" name="course4" placeholder="E.G. AIM103..." class="p-4 border-2 border-red-400 rounded-2xl">
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-400 uppercase">Receipt No.</label>
                    <input type="text" name="receipt_no" placeholder="10011" class="w-full p-4 bg-cyan-50 border-2 border-red-400 rounded-2xl">
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-400 uppercase">Total Amount Paid</label>
                    <input type="number" step="0.01" name="total_amount_paid" placeholder="999.00" class="w-full p-4 bg-cyan-50 border-2 border-red-400 rounded-2xl">
                </div>
            </div>

            <button type="submit" class="w-full bg-[#009669] hover:bg-[#057a5b] text-white font-black py-5 rounded-2xl uppercase tracking-widest transition-all">
                Finish Payment Report
            </button>
        </form>

        <?php if($message): ?>
            <p class="mt-4 text-center font-bold text-green-600"><?php echo $message; ?></p>
			
        <?php endif; ?>
		<?php if ($message): ?>
        <div class="mt-10 text-center border-t border-slate-100 pt-8">
            <p class="text-slate-400 text-xs uppercase tracking-widest mb-4">
                Institutional Redirect in <span id="countdown" class="font-bold text-red-500">5</span> seconds
            </p>
            <a href="campus.php" class="bg-slate-800 hover:bg-slate-700 text-white px-8 py-3 rounded-xl text-sm font-bold transition-all">
                Return to Campus Now
            </a>
        </div>

        <script>
            let seconds = 7;
            const display = document.getElementById('countdown');
            
            // The Neural Countdown Timer
            const timer = setInterval(() => {
                seconds--;
                display.innerText = seconds;
                if (seconds <= 0) {
                    clearInterval(timer);
                    window.location.href = 'campus.php';
                }
            }, 1000);
        </script>
        <?php endif; ?>
    </div>
</body>
</html>
