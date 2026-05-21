<?php
// [TIMESTAMP: 2026-04-01] - GAC BURSAR'S OFFICE: Checkout & Ledger Portal
session_start();

$host = 'localhost'; $db = 'ai-hi-work'; $user = 'root'; $pass = ''; 
require_once 'db_connect_local.php';
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) { die("Database Connection Fault."); }

$student_id = $_SESSION['user_id'] ?? 9999;
$last_name  = $_SESSION['last_name'] ?? 'Guest';
$success_msg = "";

// 1. CALCULATE OUTSTANDING TUITION
// We check course_regit for courses that do NOT have a matching 'PAID' record in the payments table
$query = "
    SELECT r.course_code, c.credit_hour, (c.credit_hour * 99) as cost 
    FROM course_regit r
    JOIN classes c ON r.course_code = c.class_id
    LEFT JOIN payments p ON r.course_code = p.course_code AND r.student_id = p.student_id AND p.status = 'PAID'
    WHERE r.student_id = ? AND p.receipt_no IS NULL
";
$stmt = $pdo->prepare($query);
$stmt->execute([$student_id]);
$unpaid_courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total_due = array_sum(array_column($unpaid_courses, 'cost'));

// 2. PROCESSING PAYMENT (GAC Checkout Logic)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pay_now']) && $total_due > 0) {
    try {
        $pdo->beginTransaction();
        
        foreach ($unpaid_courses as $item) {
            // Generate a Unique GAC Receipt ID
            $receipt_no = "GAC-" . date("Y") . "-" . strtoupper(bin2hex(random_bytes(3)));
            
            $insert = $pdo->prepare("INSERT INTO payments (receipt_no, student_id, course_code, amount, status) VALUES (?, ?, ?, ?, 'PENDING')");
            $insert->execute([$receipt_no, $student_id, $item['course_code'], $item['cost']]);
        }
        
        $pdo->commit();
        $success_msg = "Transaction Grounded. Check your email for your official GAC receipts.";
        $total_due = 0; // Clear UI total
        $unpaid_courses = []; // Clear UI list
    } catch (Exception $e) {
        $pdo->rollBack();
        die("Financial Logic Error: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>GAC | Bursar's Office & Refund Policy</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background-color: #f3f4f6; }
        .policy-box { max-height: 200px; overflow-y: auto; scrollbar-width: thin; }
    </style>
</head>
<body class="p-6 md:p-12">

<div class="max-w-5xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8 mb-8">
        <h1 class="text-xl font-black text-slate-900 mb-4 uppercase tracking-tight italic border-b-2 border-emerald-500 pb-2">I. AI  Gemini College (GAC) Tuition Payment and Refund Policy</h1>
        
        <div class="policy-box text-sm text-slate-600 space-y-4 pr-4">
            <section>
                <h2 class="font-black text-slate-800 uppercase text-xs">1. The 21-Day "Trial Period" Guarantee</h2>
                <p>GAC offers a <b>three-week (21-day) risk-free evaluation period</b>. Eligibility: A full refund is available if requested within 21 days. <b>The Constraint:</b> Refunds are void if a student has earned a Course Certificate or completed a Mid Term Exam.</p>
            </section>

            <section>
                <h2 class="font-black text-slate-800 uppercase text-xs">2. Subscription & Certificate Models</h2>
                <p>Monthly Subscriptions: Refunds eligible within 7 days. AI Foundation Course: Refunds within 14 days, minus a $50 "Administrative Logic Fee."</p>
            </section>

            <section>
                <h2 class="font-black text-slate-800 uppercase text-xs">3. Non-Refundable Items</h2>
                <p>Career Store physical goods are exchange only. Once a credit hour is transcripted to the <b>Arizona Board</b>, it is a permanent "Data Node" and non-refundable.</p>
            </section>

            <p class="text-[10px] uppercase font-bold text-slate-400">Compliance: Arizona Private Postsecondary Education Board (AZPPSEB) A.R.S. § 32-3021.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2">
            <div class="bg-slate-900 rounded-3xl p-8 shadow-2xl text-white">
                <h2 class="text-2xl font-black mb-6 uppercase">Tuition Amount Record</h2>
                
                <?php if($success_msg): ?>
                    <div class="mb-6 p-4 bg-emerald-500/20 border border-emerald-500 text-emerald-400 rounded-xl font-bold italic"><?php echo $success_msg; ?></div>
                <?php endif; ?>

                <div class="space-y-4">
                    <?php if(empty($unpaid_courses)): ?>
                        <p class="text-slate-500 italic">No outstanding course fees. Your ledger is balanced.</p>
                    <?php else: ?>
                        <?php foreach($unpaid_courses as $course): ?>
                        <div class="flex justify-between items-center bg-slate-800 p-5 rounded-2xl border border-slate-700">
                            <div>
                                <span class="block text-lg font-black"><?php echo $course['course_code']; ?></span>
                                <span class="text-[15px] uppercase text-slate-500 font-bold"><?php echo $course['credit_hour']; ?> Credit Hours @ $99/hr</span>
                            </div>
                            <span class="text-xl font-mono font-black text-emerald-400">$<?php echo number_format($course['cost'], 2); ?></span>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="bg-white rounded-3xl p-8 shadow-xl border border-slate-200 sticky top-12">
                <!--h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-2 text-center"> Total</h3>
                <div class="text-5xl font-black text-slate-900 text-center mb-8 font-mono tracking-tighter">
                    $<?php echo number_format($total_due, 2); ?>
                </div-->

                <!--form method="POST"-->
                    <!--button type="submit" name="pay_now" <?php echo ($total_due <= 0) ? 'disabled' : ''; ?> 
                        class="w-full bg-emerald-600 hover:bg-emerald-700 disabled:bg-slate-200 text-white font-black py-5 rounded-2xl shadow-lg transition-all uppercase tracking-widest text-sm mb-4">
                        Process Course Registration
                    </button-->
                <!--/form-->

                <!--p class="text-[10px] text-slate-400 text-center leading-relaxed">
                    By clicking "Process Payment," you acknowledge the <br><strong>GAC 21-Day Neural Seal Policy</strong>.
                </p-->

                <div class="mt-8 pt-6 border-t border-slate-100 flex flex-col gap-3 text-center">
                    <a href="https://store.aigeminicollege.org/collections/educational-services" class="text-[20px] font-black text-slate-400 uppercase hover:text-emerald-600 tracking-widest">1. Go to Bursar's Office to Pay</a>
                    <a href="refund.php" class="text-[20px] font-black text-slate-400 uppercase hover:text-red-500 tracking-widest">2. Request Refund</a>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>