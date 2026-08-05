<?php
session_start();
$student_id = $_SESSION['user_id']    ?? 9990;
$first_name = $_SESSION['first_name'] ?? 'Administrator';
$last_name  = $_SESSION['last_name']  ?? '';
$email      = $_SESSION['email']      ?? '';

require_once __DIR__ . '/../../db-connect.php';
//require_once __DIR__ . '/../db-connect.php';
date_default_timezone_set('America/Los_Angeles');

$message = '';
$message_type = '';

try {
    // 1. Ensure initial row with ID=1 exists in parm_count
    $check_stmt = $pdo->prepare("SELECT * FROM `parm_count` WHERE `id` = 1 LIMIT 1");
    $check_stmt->execute();
    $parm_data = $check_stmt->fetch();

    if (!$parm_data) {
        $init_stmt = $pdo->prepare("INSERT INTO `parm_count` (`id`, `interview_cnt`, `exam_cnt`, `cnt3`, `cnt4`, `cnt5`) VALUES (1, 0, 0, 0, 0, 0)");
        $init_stmt->execute();

        // Fetch freshly created record
        $check_stmt->execute();
        $parm_data = $check_stmt->fetch();
    }

    // 2. Handle POST Update Request
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_params'])) {
        $interview_cnt = filter_input(INPUT_POST, 'interview_cnt', FILTER_VALIDATE_INT) ?? 0;
        $exam_cnt      = filter_input(INPUT_POST, 'exam_cnt', FILTER_VALIDATE_INT) ?? 0;
        $cnt3          = filter_input(INPUT_POST, 'cnt3', FILTER_VALIDATE_INT) ?? 0;
        $cnt4          = filter_input(INPUT_POST, 'cnt4', FILTER_VALIDATE_INT) ?? 0;
        $cnt5          = filter_input(INPUT_POST, 'cnt5', FILTER_VALIDATE_INT) ?? 0;

        $update_sql = "UPDATE `parm_count` 
                       SET `interview_cnt` = :interview_cnt, 
                           `exam_cnt`      = :exam_cnt, 
                           `cnt3`          = :cnt3, 
                           `cnt4`          = :cnt4, 
                           `cnt5`          = :cnt5 
                       WHERE `id` = 1";

        $update_stmt = $pdo->prepare($update_sql);
        $update_stmt->execute([
            ':interview_cnt' => $interview_cnt,
            ':exam_cnt'      => $exam_cnt,
            ':cnt3'          => $cnt3,
            ':cnt4'          => $cnt4,
            ':cnt5'          => $cnt5,
        ]);

        $message = "Parameters updated successfully!";
        $message_type = "success";

        // Refresh record for rendering
        $check_stmt->execute();
        $parm_data = $check_stmt->fetch();
    }
} catch (PDOException $e) {
    $message = "Database action failed: " . $e->getMessage();
    $message_type = "error";
}

// Display labels mapped to database columns
$parameter_labels = [
    'interview_cnt' => 'Interview Count',
    'exam_cnt'      => 'Exam Count',
    'cnt3'          => 'Parameter 3 (cnt3)',
    'cnt4'          => 'Parameter 4 (cnt4)',
    'cnt5'          => 'Parameter 5 (cnt5)',
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parameter Settings | Green Frame</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-emerald-50/50 min-h-screen flex items-center justify-center p-4">

    <!-- Main Container Framed with Green -->
    <div class="w-full max-w-xl bg-white rounded-2xl shadow-xl border-4 border-emerald-500 overflow-hidden">
        
        <!-- Header Bar -->
        <div class="bg-emerald-600 text-white px-6 py-4 flex justify-between items-center">
            <div>
                <h1 class="text-xl font-bold">Parameter Counter Management</h1>
                <p class="text-xs text-emerald-100 mt-0.5">Welcome, <?= htmlspecialchars($first_name . ' ' . $last_name) ?></p>
            </div>
            <span class="text-xs bg-emerald-700 px-3 py-1 rounded-full text-emerald-100 font-mono">
                ID: <?= htmlspecialchars($student_id) ?>
            </span>
        </div>

        <!-- Alert Notification -->
        <?php if (!empty($message)): ?>
            <div id="alert-msg" class="mx-6 mt-4 p-3 rounded-lg text-sm font-medium transition-all <?= $message_type === 'success' ? 'bg-emerald-100 text-emerald-800 border border-emerald-300' : 'bg-red-100 text-red-800 border border-red-300' ?>">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <!-- Form Body -->
        <form action="parm-update.php" method="POST" class="p-6 space-y-4">
            
            <div class="space-y-3">
                <?php foreach ($parameter_labels as $column_key => $label_name): ?>
                    <div class="flex items-center justify-between p-3 bg-emerald-50/60 rounded-lg border border-emerald-100 hover:border-emerald-300 transition">
                        <label for="<?= $column_key ?>" class="text-sm font-semibold text-gray-700">
                            <?= htmlspecialchars($label_name) ?>
                        </label>
                        <input 
                            type="number" 
                            id="<?= $column_key ?>" 
                            name="<?= $column_key ?>" 
                            value="<?= htmlspecialchars($parm_data[$column_key] ?? 0) ?>" 
                            min="0" 
                            required 
                            class="w-28 text-center px-3 py-1.5 border border-emerald-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 font-medium text-gray-800 shadow-sm"
                        >
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-between gap-4 pt-4 border-t border-gray-100">
                <!-- Home Button -->
                <a href="index.php" class="w-1/2 text-center py-2.5 px-4 rounded-xl border border-emerald-600 text-emerald-700 font-semibold hover:bg-emerald-50 transition active:scale-95 shadow-sm">
                    &larr; Home (index)
                </a>

                <!-- Update Button -->
                <button type="submit" name="update_params" class="w-1/2 py-2.5 px-4 rounded-xl bg-emerald-600 text-white font-semibold hover:bg-emerald-700 transition active:scale-95 shadow-md shadow-emerald-200">
                    Update Parameters
                </button>
            </div>

        </form>

    </div>

    <!-- JavaScript at the Bottom -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Auto-hide alert messages after 3.5 seconds
            const alertMsg = document.getElementById('alert-msg');
            if (alertMsg) {
                setTimeout(() => {
                    alertMsg.style.opacity = '0';
                    alertMsg.style.transition = 'opacity 0.5s ease';
                    setTimeout(() => alertMsg.remove(), 500);
                }, 3500);
            }
        });
    </script>
</body>
</html>