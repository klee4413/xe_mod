<?php
// [TIMESTAMP: 2026-04-01] - AIGC Refund Request Portal refund.php
session_start();
$host = 'localhost'; $db = 'ai-hi-work'; $user = 'root'; $pass = ''; 
require_once 'db_connect_local.php';
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) { die("Database Connection Fault."); }

$student_id = $_SESSION['user_id'] ?? 9999; 
$success_msg = "";
$error_msg = "";

// 1. SURGICAL DATA RETRIEVAL: Pulling from Bursar Payment History
// We assume a 'payments' table exists where course fees were settled
$pay_stmt = $pdo->prepare("SELECT receipt_no, course_code, amount FROM payments WHERE student_id = ? AND status = 'PAID'");
$pay_stmt->execute([$student_id]);
$payments = $pay_stmt->fetchAll(PDO::FETCH_ASSOC);

// 2. PROCESSING THE REFUND REQUEST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_refund'])) {
    $course_data = explode('|', $_POST['payment_select']); // Format: receipt_no|course_id|amount
    $reason = $_POST['reason'];
    $neural_audit = isset($_POST['neural_audit']);

    if (!$neural_audit) {
        $error_msg = "LOGIC GATE BLOCKED: You must accept the Neural Audit to proceed.";
    } else {
        $insert = $pdo->prepare("INSERT INTO refund_table (student_id, course_id, receipt_no, amount, reason) VALUES (?, ?, ?, ?, ?)");
        $insert->execute([$student_id, $course_data[1], $course_data[0], $course_data[2], $reason]);
        $success_msg = "Refund request grounded. AIGC Administration will review your technical friction logs within 48 hours.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>AIGC | Refund Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background-color: #F9FAFB; font-family: 'Inter', sans-serif; }
        .gac-card { border-top: 5px solid #BC4A3C; } /* AIGC Brick Color */
    </style>
</head>
<body class="p-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-2xl shadow-2xl p-10 gac-card">
            <header class="mb-8 border-b pb-6">
                <h1 class="text-2xl font-black text-slate-900 uppercase tracking-tighter">AIGC Refund Request Process</h1>
                <p class="text-xs text-slate-500 mt-1 font-bold">Verification Status: <span class="text-emerald-600">Verified Student (ID: <?php echo $student_id; ?>)</span></p>
            </header>

            <?php if($success_msg): ?>
                <div class="mb-6 p-4 bg-emerald-50 text-emerald-700 rounded-xl border border-emerald-200 font-bold italic"><?php echo $success_msg; ?></div>
            <?php endif; ?>

            <?php if($error_msg): ?>
                <div class="mb-6 p-4 bg-red-50 text-red-600 rounded-xl border border-red-200 font-bold italic"><?php echo $error_msg; ?></div>
            <?php endif; ?>

            <form method="POST" class="space-y-6">
                <div>
                    <label class="block text-[10px] font-black uppercase text-slate-400 mb-2">Select Payment Record</label>
                    <select name="payment_select" class="w-full p-4 bg-slate-50 border border-slate-200 rounded-xl font-bold text-sm focus:ring-2 focus:ring-red-500 outline-none" required>
                        <option value="">-- Choose Course Receipt --</option>
                        <?php foreach($payments as $p): ?>
                            <option value="<?php echo "{$p['receipt_no']}|{$p['course_code']}|{$p['amount']}"; ?>">
                                Receipt: <?php echo $p['receipt_no']; ?> - <?php echo $p['course_code']; ?> ($<?php echo $p['amount']; ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] font-black uppercase text-slate-400 mb-2">Reason for Request</label>
                    <select name="reason" class="w-full p-4 bg-slate-50 border border-slate-200 rounded-xl font-bold text-sm" required>
                        <option value="System Latency/Technical Friction">System Latency/Technical Friction</option>
                        <option value="Change in Professional Direction">Change in Professional Direction</option>
                        <option value="Financial Restructuring">Financial Restructuring</option>
                        <option value="Other">Other (Manual Spark Required)</option>
                    </select>
                </div>

                <div class="p-6 bg-slate-900 rounded-xl border border-slate-800">
                    <label class="flex items-start cursor-pointer">
                        <input type="checkbox" name="neural_audit" class="mt-1 mr-4 w-5 h-5 accent-red-500" required>
                        <span class="text-[11px] text-slate-300 leading-relaxed font-medium italic">
                            <strong class="text-white block mb-1 uppercase tracking-widest">The Neural Audit Check</strong>
                            "I understand that upon refund, my access to the 18-volume syllabus and active NotebookLM research hubs for this course will be revoked, and my progress data will be archived."
                        </span>
                    </label>
                </div>

                <div class="pt-4">
                    <button type="submit" name="submit_refund" class="w-full bg-[#BC4A3C] hover:bg-red-800 text-white font-black py-4 rounded-xl shadow-xl transition-all uppercase tracking-widest text-sm">
                        Submit Refund Request
                    </button>
                </div>
            </form>
        </div>

        <div class="mt-8 text-center">
            <a href="campus.php" class="text-slate-400 text-[10px] font-bold uppercase hover:text-slate-900 tracking-widest">Return to Campus Dashboard</a>
        </div>
		<center><h1>Gemini AI College (AIGC) Refund Policy</h1></center>
<br>
   <h2>1. The 21-Day "Trial Period with 10 NotebookLM study sessions" Guarantee</h2>
    <p>AIGC offers a <b>three-week (21-day) risk-free evaluation period with 10 study sessions record at least</b>. We believe in the "Manual Spark" so strongly that we allow scholars to experience the full Foundry environment before finalizing their investment. The "Trial Period" to 21 days, we position AIGC as a student-centric, high-integrity institution.</p>

    <ul>
        <li><b>Eligibility</b>: A full refund is available if requested within <b>21 days</b> of the original payment date.</li>
        <li><b>The Constraint</b>: To prevent "Credential Poaching," refunds are <b>void</b> if a student has already earned a <b>Course Certificate</b> or completed a <b>Final Term Exam</b> within that 21-day window. Once the "Neural Seal" (Certification) is issued, the value has been delivered.</li>
    </ul>
<br>
    <h2>2. Subscription & Certificate Models</h2>
    <ul>
        <li><b>Monthly Subscriptions</b>: Cancellations stop future billing immediately. Refunds for the current month are eligible within the first <b>7 days</b> of the billing cycle, provided no new certificates were earned.</li>
        <li><b>AI Foundation Course</b>: Given the <b>$999 Celebration Discount</b>, refunds are eligible within 14 days of enrollment, minus a $50 "Administrative Logic Fee."</li>
    </ul>
<br>
    <h2>3. Non-Refundable Items</h2>
    <ul>
        <li><b>AIGC Career Store Physical Goods</b>: Due to the "Just-in-Time" dropshipping nature of the AIGC Store, physical items are subject to exchange only for manufacturing defects.</li>
        <li><b>Completed Credit Hours</b>: Once a credit hour is transcripted to the <b>Arizona Board</b>, it is considered a permanent "Data Node" and non-refundable.</li>
    </ul>
<br>
    
	 <p><b>Compliance of the Arizona Private Postsecondary Education Board (AZPPSEB)</b> requires a "Clear and Equitable" refund policy (A.R.S. § 32-3021). By adopting the 7-10 business day processing window <b>Strategic Advantage</b>: Most "AI Schools" have no refund policy.  By codifying this, we demonstrate that AIGC is a Permanent Academic Fixture, rather than a transient automation service.
</p>
	</div>
</body>
</html>