<?php
session_start();
require_once __DIR__ . '/../../db-connect.php';

// 1. TEMPORAL GROUNDING (PST)
date_default_timezone_set('America/Los_Angeles');

// 2. IDENTITY EXTRACTION FROM SESSION
$admin_email = $_SESSION['admin_email'] ?? 'admin@aigeminicollege.org';
$email_parts = explode('@', $admin_email);
$raw_name = $email_parts[0];

$name_segments = preg_split('/[._-]/', $raw_name);
$user_name = ucfirst($name_segments[0]);
if (isset($name_segments[1])) {
    $user_name .= ' ' . ucfirst($name_segments[1]);
}

$operator_id = $user_name;
$message = "";

// 3. DATA CAPTURE: SAVE NEW LOG OR APPEND COMMENT
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['save_session'])) {
        $subject = trim($_POST['subject'] ?? '');
        $description = trim($_POST['description'] ?? '');
        
        if (!empty($subject)) {
            try {
                $stmt = $pdo->prepare("INSERT INTO adaily_log (name, subject, description) VALUES (?, ?, ?)");
                $stmt->execute([$user_name, $subject, $description]);
                $message = "Session Grounded Successfully.";
            } catch (PDOException $e) {
                $message = "Log Dissonance: " . $e->getMessage();
            }
        }
    }

    if (isset($_POST['append_comment'])) {
        $log_id = $_POST['log_id'];
        $pst_now = date('Y-m-d H:i');
        $new_comment = "\n[Msg: $pst_now by $operator_id]: " . $_POST['comment_text'];
        
        try {
            $stmt = $pdo->prepare("UPDATE adaily_log SET description = CONCAT(description, ?) WHERE no = ?");
            $stmt->execute([$new_comment, $log_id]);
            $message = "Progress Comment Appended.";
        } catch (PDOException $e) {
            $message = "Update Dissonance: " . $e->getMessage();
        }
    }
}

// 4. FETCH ALL RECORDS
$logs = $pdo->query("SELECT * FROM adaily_log ORDER BY no DESC")->fetchAll();
// 5. DATA MAINTENANCE: SUSTAIN SLIDING WINDOW (KEEP RECENT 15)
try {
    $sql = "DELETE FROM adaily_log 
            WHERE no NOT IN (
                SELECT no FROM (
                    SELECT no FROM adaily_log 
                    ORDER BY no DESC 
                    LIMIT 15
                ) AS recent_logs
            )";
    
    // Use exec() for DELETE operations as it returns the number of affected rows
    $deleted_count = $pdo->exec($sql);
    
    // Optional: log to your debug console if you wish
    // logDebug("Maintenance Complete: $deleted_count old records purged.");
} catch (PDOException $e) {
    // Silence maintenance errors or log them to a private file
    error_log("Maintenance Dissonance: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>AIGC | Progress and Review Records</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* High-Visibility Identity */
        .AIGC-thick-border { border: 3px solid #059669; }
        .AIGC-input-focus:focus { border: 3px solid #10B981; box-shadow: 0 0 10px rgba(16, 185, 129, 0.2); }
        .happy-gradient { background: linear-gradient(135deg, #059669 0%, #10B981 100%); }
        body { font-weight: 500; } /* Thicker overall letters */
    </style>
</head>
<body class="bg-emerald-50/30 p-8 font-sans text-slate-900">

    <div class="max-w-[1600px] mx-auto bg-white shadow-2xl rounded-[3rem] overflow-hidden border-4 border-green-100">
        <div class="flex justify-between items-center p-10 happy-gradient text-white">
            <div>
                <h1 class="text-4xl font-black tracking-tighter">AIGC COMMUNICATION ADMINISTRATION</h1>
                <p class="text-green-100 text-sm font-black uppercase tracking-widest mt-1">Daily Progress & Status Log</p>
            </div>
            <div class="text-right">
                <p class="text-xs font-black text-green-100 uppercase">Time (PST)</p>
                <p class="text-2xl font-mono font-bold"><?php echo date('Y-m-d H:i'); ?></p>
            </div>
        </div>

        <div class="p-10 bg-white border-b-4 border-green-50">
            <form method="POST" class="space-y-6">
                <div class="grid grid-cols-3 gap-8">
                    <div class="space-y-2">
                        <label class="text-xs font-black text-green-700 uppercase ml-2">Sequence Status</label>
                        <input type="text" disabled placeholder="AUTO-GENERATED ID" class="w-full p-4 bg-slate-100 border-2 border-slate-200 rounded-2xl font-bold text-slate-400">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-black text-green-700 uppercase ml-2">Administrator</label>
                        <input type="text" readonly value="<?php echo htmlspecialchars($user_name); ?>" class="w-full p-4 bg-green-50 border-3 border-green-200 rounded-2xl text-green-800 font-black">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-black text-green-700 uppercase ml-2">Subject</label>
                        <input type="text" name="subject" placeholder="Enter Subject Title" required class="border-4 border-black p-6 w-full p-4 bg-white    rounded-2xl AIGC-input-focus outline-none transition-all font-bold">
                    </div>
                </div>
                <!--textarea name="comment_text" placeholder="Add reflection..." 
                                     class="border-4 border-black p-6 text-sm rounded-2xl h-24 outline-none focus:border-black bg-white font-bold"></textarea-->

                <div class="space-y-2">
                    <label class="text-xs font-black text-green-700 uppercase ml-2">Description</label>
                    <textarea name="description" rows="5" required 
                              class="w-full p-5 bg-white border-4 border-black rounded-3xl AIGC-input-focus outline-none transition-all font-medium text-lg" 
                              placeholder="Your contents and work session results here..."></textarea>
                    
                    <div class="flex justify-start mt-2">
                        <a href="admin-offices.php" class="inline-flex items-center gap-2 px-6 py-3 bg-slate-800 text-white rounded-xl font-black text-xs uppercase tracking-widest hover:bg-black transition-all shadow-md">
                             To Home
                        </a>
                    </div>
                </div>
                
                <button type="submit" name="save_session" 
                        class="w-full happy-gradient hover:brightness-110 py-5 rounded-2xl font-black text-white transition-all shadow-xl shadow-green-200 uppercase tracking-[0.2em] text-md">
                    Click to Save
                </button>
            </form>
        </div>

        <div class="p-10">
            <div class="flex justify-between items-center mb-10">
                <h3 class="text-2xl font-black text-slate-800 uppercase tracking-tight border-l-8 border-green-500 pl-4">Communication Record</h3>
                <div class="flex gap-4">
                    <input type="text" placeholder="Filter logs..." class="border-4 border-black p-3 px-6 rounded-xl font-bold text-sm w-80 focus:border-green-500 outline-none">
                    <button class="bg-green-100 text-green-700 px-8 py-3 rounded-xl font-black text-sm hover:bg-green-200 transition-all uppercase tracking-widest">Search</button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-separate border-spacing-y-3">
                    <thead>
                        <tr class="text-green-800 text-xs font-black uppercase tracking-widest">
                            <th class="px-1 py-2">ID</th>
                            <th class="px-2 py-4">Admin</th>
                            <th class="px-3 py-4">Time</th>
                            <th class="px-6 py-4">Topic</th>
                            <th class="px-6 py-4">Contents</th>
                            <th class="px-6 py-4">Comment</th>
                        </tr>
                    </thead>
                    <tbody class="text-md">
                        <?php foreach ($logs as $log): ?>
                        <tr class="bg-white shadow-sm hover:shadow-md transition-all">
                            <td class="px-6 py-8 border-y-2 border-l-2 border-slate-100 rounded-l-3xl font-mono font-black text-slate-400"><?php echo $log['no']; ?></td>
                            <td class="px-6 py-8 border-y-2 border-slate-100 font-black text-slate-800"><?php echo htmlspecialchars($log['name']); ?></td>
                            <td class="px-6 py-8 border-y-2 border-slate-100 whitespace-nowrap text-xs font-bold text-slate-500"><?php echo date('M d, H:i', strtotime($log['date'])); ?></td>
                            <td class="px-6 py-8 border-y-2 border-slate-100 font-black text-green-600 uppercase italic"><?php echo htmlspecialchars($log['subject']); ?></td>
                            
                            <td class="px-12 py-8 border-y-2 border-slate-100 leading-relaxed font-medium min-w-[600px] text-slate-700">
                                <?php echo nl2br(htmlspecialchars($log['description'])); ?>
                            </td>
                            
                            <td class="px-6 py-8 border-y-2 border-r-2 border-slate-100 rounded-r-3xl bg-slate-50/80">
                                <form method="POST" class="flex flex-col gap-3">
                                    <input type="hidden" name="log_id" value="<?php echo $log['no']; ?>">
                                    <!--textarea name="comment_text" placeholder="Add reflection..." 
                                     class="border-5 border-red-200 p-6 text-sm rounded-2xl h-24 outline-none focus:border-black-700 bg-white font-bold"></textarea-->
											  
									<textarea name="comment_text" placeholder="Add reflection..." 
                                     class="border-4 border-black p-6 text-sm rounded-2xl h-24 outline-none focus:border-black bg-white font-bold"></textarea>

                                    <button type="submit" name="append_comment" class="text-green-600 font-black hover:text-green-800 transition-colors uppercase text-sm tracking-widest text-right">
                                        + ADD COMMENT
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="mt-12 text-center pb-20">
        <p class="text-slate-400 font-black text-[10px] uppercase tracking-[0.5em]">&copy AI Gemini College  2026</p>
    </div>

</body>
</html>