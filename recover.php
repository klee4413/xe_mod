<?php
 
session_start();
$isLocal = in_array($_SERVER['HTTP_HOST'] ?? '', ['localhost', '127.0.0.1']) 
        || str_contains($_SERVER['HTTP_HOST'] ?? '', 'localhost:');

if ($isLocal) {
    require_once 'db-connect.php';
} else {
    require_once __DIR__ . '/../db-connect.php';
}
$error = "";
$msg = "";
$user_info = "";
$confirmed = false;

// ACTION 1: CONFIRM USER (Phase 1)
if (isset($_POST['action']) && $_POST['action'] == 'confirm') {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $passcode = $_POST['passcode'];

    $stmt = $pdo->prepare("SELECT id, first_name, last_name, signup_date FROM sign_up WHERE email = ? AND passcode = ?");
    $stmt->execute([$email, $passcode]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $_SESSION['recover_user_id'] = $user['id'];
        $user_info = "ID: " . $user['id'] . "\nNAME: " . $user['first_name'] . " " . $user['last_name'] . "\nSIGN UP DATE: " . $user['signup_date'] . "\nCONFIRMED: Proceed to New Password.";
        $confirmed = true;
    } else {
        $user_info = "NO RECORD: Re-Sign up required.";
        $confirmed = false;
    }
}

// ACTION 2: SAVE NEW PASSWORD (Phase 2)
if (isset($_POST['action']) && $_POST['action'] == 'save' && isset($_SESSION['recover_user_id'])) {
    $new_password = $_POST['new_password'];
    $hash = password_hash($new_password, PASSWORD_BCRYPT);
    
    $stmt = $pdo->prepare("UPDATE sign_up SET password_hash = ? WHERE id = ?");
    if ($stmt->execute([$hash, $_SESSION['recover_user_id']])) {
        $msg = "Success: Password Reset Complete.";
        unset($_SESSION['recover_user_id']); // Logic Lock
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>GAC | User Confirmation and Password Reset</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .admin-input { border: 1px solid #cbd5e1; border-radius: 8px; padding: 12px; width: 100%; margin-bottom: 10px; }
        .pink-box { background: #fdf2f8; border: 1px dashed #ec4899; color: #831843; font-family: monospace; padding: 15px; border-radius: 8px; min-height: 100px; white-space: pre-wrap; margin-bottom: 15px; }
        .btn-gac { padding: 10px 24px; border-radius: 4px; font-weight: bold; color: white; transition: 0.3s; }
        .btn-confirm { background-color: #2563eb; }
        .btn-save { background-color: #2563eb; }
        .btn-save:disabled { background-color: #94a3b8; cursor: not-allowed; }
        .btn-exit { background-color: #000; }
        .btn-reset { border: 1px solid #ef4444; color: #ef4444; }
    </style>
</head>
<body class="bg-slate-50 flex items-center justify-center min-h-screen p-4">

    <div class="bg-white p-10 rounded-xl shadow-lg w-full max-w-2xl border border-slate-200">
	<div class="h-10 w-14 bg-[#BC4A3C] rounded mx-auto mb-2 flex items-center justify-center text-white font-bold">AGC</div>
        <h1 class="text-3xl font-bold text-center text-blue-500 mb-8 font-serif">Password Reset by Data Verification<br>Protected by Advanced Encryption</h1>
               <?php if($msg): ?>
            <div class="bg-green-100 text-green-800 p-3 rounded mb-4 text-center font-bold"><?php echo $msg; ?></div>
        <?php endif; ?>

        <form method="POST" id="recoverForm">
            <input type="email" name="email" placeholder="Enter email" class="admin-input" value="<?php echo @$_POST['email']; ?>" required>
            <input type="text" name="passcode" placeholder="Enter Passcode" class="admin-input" required>
            
            <div class="flex justify-end mb-4">
                <button type="submit" name="action" value="confirm" class="btn-gac btn-confirm">1.Confirm</button>
            </div>

            <div class="pink-box"><?php echo $user_info; ?></div>

            <div class="relative mb-6">
                <input type="password" name="new_password" id="newPass" placeholder="Enter new password" class="admin-input">
                <button type="button" onclick="togglePass()" class="absolute right-4 top-3 text-slate-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                </button>
            </div>

            <div class="flex justify-between items-center">
                <button type="submit" name="action" value="save" class="btn-gac btn-save" <?php echo !$confirmed ? 'disabled' : ''; ?>>2.Save</button>
                <div class="space-x-2">
                    <button type="reset" class="btn-gac btn-reset">Reset Entries</button>
                    <a href="login.php" class="btn-gac btn-exit">3.Go to Login</a>
                </div>
            </div>
        </form>
    </div>

    <script>
        function togglePass() {
            const p = document.getElementById('newPass');
            p.type = p.type === "password" ? "text" : "password";
        }
    </script>
</body>
</html>
