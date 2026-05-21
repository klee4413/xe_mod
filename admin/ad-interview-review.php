<?php
// [TIMESTAMP: 2026-03-30] - GAC Interview Decision & Notification Console
session_start();
// Security Gate: Ensure only Admin/Dean can access
// if ($_SESSION['role'] !== 'admin') { die("Unauthorized."); }

//$host = 'localhost'; $db = 'ai-hi-work'; $user = 'root'; $pass = ''; 
require_once 'db-connect.php';
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) { die("Database Connection Fault."); }

// HANDLE DECISION & EMAIL TRIGGER
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['process_decision'])) {
    $sid = $_POST['student_id'];
    $decision = $_POST['decision'];
    $email = $_POST['student_email'];
    $name = $_POST['student_name'];

    // 1. Update Database
    $update = $pdo->prepare("UPDATE interview_table SET decision = ? WHERE id = ?");
    $update->execute([$decision, $sid]);

    // 2. Prepare Email Notification Logic (Surgical SMTP)
    $subject = "GAC Admissions Decision: " . ($decision == 'Y' ? "Accepted" : "Update");
    $message = ($decision == 'Y') 
        ? "Congratulations $name! You have been accepted to Gemini AI College." 
        : "Dear $name, thank you for your interest. We are unable to offer admission at this time.";
    
    // Note: In XAMPP, mail() requires sendmail configuration. 
    // For production, use PHPMailer with an API like SendGrid or Gmail SMTP.
    $headers = "From: admissions@geminiaicollege.org";
    $mail_sent = mail($email, $subject, $message, $headers);

    $msg = "Decision '$decision' saved for $name. " . ($mail_sent ? "Email dispatched." : "Email failed (Check SMTP).");
}

// Fetch Pending Reviews
$stmt = $pdo->query("SELECT * FROM interview_table ORDER BY date DESC");
$reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>GAC Admin | Interview Review</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 p-8">
    <div class="max-w-6xl mx-auto">
        <header class="mb-10">
            <h1 class="text-3xl font-black text-slate-900">Admissions Review Board</h1>
            <?php if(isset($msg)) echo "<p class='text-emerald-600 font-bold mt-2'>$msg</p>"; ?>
        </header>

        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-800 text-white text-[10px] uppercase tracking-widest">
                    <tr>
                        <th class="p-6">Scholar Info</th>
                        <th class="p-6">Selected (Grit)</th>
                        <th class="p-6">Unselected</th>
                        <th class="p-6">Final Decision</th>
						<th class="p-6">AUTO</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php foreach($reviews as $r): ?>
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="p-6">
                            <div class="font-bold text-slate-900"><?php echo $r['first_name']." ".$r['last_name']; ?></div>
                            <div class="text-xs text-slate-400">ID: <?php echo $r['id']; ?> | <?php echo $r['email']; ?></div>
                        </td>
                        <td class="p-6">
                            <span class="text-emerald-600 font-black">Score: <?php echo $r['sel_rank']; ?></span>
                            <div class="text-[10px] text-slate-400">Total Attributes: <?php echo $r['sel_total']; ?></div>
                        </td>
                        <td class="p-6">
                            <span class="text-rose-600 font-black">Score: <?php echo $r['unsel_rank']; ?></span>
                            <div class="text-[10px] text-slate-400">Total Attributes: <?php echo $r['unsel_total']; ?></div>
                        </td>
                        <td class="p-6">
                            <form method="POST" class="flex items-center space-x-4">
                                <input type="hidden" name="student_id" value="<?php echo $r['id']; ?>">
                                <input type="hidden" name="student_email" value="<?php echo $r['email']; ?>">
                                <input type="hidden" name="student_name" value="<?php echo $r['first_name']; ?>">
                                
                                <label class="flex items-center text-xs font-bold">
                                    <input type="radio" name="decision" value="Y" <?php if($r['decision'] == 'Y') echo 'checked'; ?> class="mr-1"> Y
                                </label>
                                <label class="flex items-center text-xs font-bold">
                                    <input type="radio" name="decision" value="N" <?php if($r['decision'] == 'N') echo 'checked'; ?> class="mr-1"> N
                                </label>
                                
                                <button type="submit" name="process_decision" class="bg-slate-900 text-white text-[10px] font-bold px-4 py-2 rounded hover:bg-black uppercase">
                                    Process & Email
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
	<button type="button" onclick="toHome()" 
                        class="gac-green text-blue px-20 py-5 rounded-2xl font-black uppercase text-sm tracking-widest shadow-xl hover:scale-105 transition-all">
                    To Home
                </button>
 
</body>

<footer class="p-20 text-center text-blue-400 font-bold uppercase tracking-widest">
        &copy; AI Gemini College 2026
		<script>function toHome() {window.location.href = 'admin-offices.php';}</script>
</html>