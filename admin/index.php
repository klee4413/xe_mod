<?php
session_start();
require_once __DIR__ . '/../../db-connect.php'; //  PDO connection adlogin.php to index.php to admin

// 1. SET THE NEURAL CLOCK TO PST
date_default_timezone_set('America/Los_Angeles');
$current_time = date('Y-m-d H:i:s');

$message = "";

// 2. DATA CAPTURE (POST HANDSHAKE)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // A. SETUP LOGIC (Creates the Admin if they don't exist)
    if (isset($_POST['setup'])) {
        try {
            $hashed_pass = password_hash($password, PASSWORD_DEFAULT);
            
            // SOVEREIGN IDENTITY PARSING
            $email_parts = explode('@', $email);
            $raw_name = $email_parts[0];
            $name_segments = preg_split('/[._-]/', $raw_name);
            $parsed_name = ucfirst($name_segments[0]);
            
            $stmt = $pdo->prepare("INSERT INTO admin_login (user_name, email, password, created_at) VALUES (?, ?, ?, ?)");
            $stmt->execute([$parsed_name, $email, $hashed_pass, $current_time]);
            $message = "Admin Account is set. You may now login.";
        } catch (PDOException $e) {
            $message = "Setup Dissonance: Account may already exist.";
        }
    }

    // B. LOGIN LOGIC
    if (isset($_POST['login'])) {
        $stmt = $pdo->prepare("SELECT * FROM admin_login WHERE email = ?");
        $stmt->execute([$email]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['password'])) {
            // IDENTITY PARSING FOR SESSION
            $email_parts = explode('@', $admin['email']);
            $raw_name = $email_parts[0];
            $name_segments = preg_split('/[._-]/', $raw_name);
            $user_name = ucfirst($name_segments[0]);

            // UPDATE LAST LOGIN AT IN THE FOUNDRY
            $update_stmt = $pdo->prepare("UPDATE admin_login SET last_login_at = ? WHERE id = ?");
            $update_stmt->execute([$current_time, $admin['id']]);

            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_email'] = $admin['email'];
            $_SESSION['admin_name'] = $user_name;

            header("Location: admin-offices.php");
            exit();
        } else {
            $message = "Identity Dissonance: Invalid Credentials.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32.png">
    <title>AIGC | Admin Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#F0FFF4] h-screen flex items-center justify-center p-6">

    <div class="w-full max-w-md bg-white rounded-[2.5rem] shadow-2xl p-10 border border-green-100">
        
        <div class="text-center mb-8">
            <h1 class="text-xl font-black text-green-900 uppercase tracking-tight">
                AIGC Administration Offices
            </h1>
            <p class="text-[10px] font-mono text-slate-500 mt-2">
                SYSTEM TIME: <?php echo date('Y-m-d H:i'); ?> PST
            </p>
        </div>

        <form method="POST" class="space-y-6">
            <div class="space-y-4">
                <input type="email" name="email" required placeholder="Enter Admin Email" 
                       class="w-full p-4 bg-gray-50 border-2 border-green-200 rounded-2xl focus:border-green-500 outline-none transition-all">
                
                <input type="password" name="password" required placeholder="Enter Security Password" 
                       class="w-full p-4 bg-gray-50 border-2 border-green-200 rounded-2xl focus:border-green-500 outline-none transition-all">
            </div>

            <div class="grid grid-cols-2 gap-4 pt-4">
                <button type="submit" name="setup" 
                        class="bg-white border-2 border-green-600 text-green-700 font-bold py-4 rounded-2xl hover:bg-green-50 transition-all uppercase tracking-widest text-xs">
                    1. Setup
                </button>
                <button type="submit" name="login" 
                        class="bg-green-700 text-white font-bold py-4 rounded-2xl hover:bg-green-800 transition-all shadow-lg uppercase tracking-widest text-xs">
                    2. Login
                </button>
            </div>
        </form>

        <?php if($message): ?>
            <p class="mt-6 text-center font-bold text-red-600 text-sm"><?php echo $message; ?></p>
        <?php endif; ?>

    </div>

</body>
</html>