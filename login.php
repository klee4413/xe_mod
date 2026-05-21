<?php
// [TIMESTAMP: 2026-03-17 01:35:00] - AGC Scholar Login Logic    login.php
//$host = 'localhost'; $db = 'ai-hi-work'; $user = 'root'; $pass = ''; 
//try {$pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
//} catch (PDOException $e) { //die("Database Connection Fault: " . $e->getMessage());}
session_start();
include 'db_connect.php';
$error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email_input = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password_input = $_POST['password']; // Raw input for verification

    // 1. SURGICAL QUERY: Retrieve ID, Names, and the Hashed Password
    $stmt = $pdo->prepare("SELECT id, first_name, last_name, email, password_hash FROM sign_up WHERE email = ?");
    $stmt->execute([$email_input]);
    $user_data = $stmt->fetch(PDO::FETCH_ASSOC);

    // 2. VERIFICATION GATE (10/90 Logic)
    if ($user_data && password_verify($password_input, $user_data['password_hash'])) {
        // SUCCESS: Capture JSON Login Data
        $_SESSION['user_id'] = $user_data['id'];
        $_SESSION['first_name'] = $user_data['first_name'];
        $_SESSION['last_name'] = $user_data['last_name'];
        $_SESSION['email'] = $user_data['email'];
        
        // Log activity to DB
        $update = $pdo->prepare("UPDATE sign_up SET last_login = NOW() WHERE id = ?");
        $update->execute([$user_data['id']]);

        header("Location: campus.php"); 
        exit();
    } else {
        $error = "Invalid Login Credentials. Check Logic Integrity.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AGC | Student Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root { --brick: #BC4A3C; --google-gray: #DADCE0; }
        body { background-color: #F8F9FA; font-family: 'Roboto', sans-serif; }
        .admin-card { border: 1px solid var(--google-gray); border-radius: 8px; background: white; }
        .admin-input { border: 1px solid var(--google-gray); border-radius: 4px; padding: 13px 15px; width: 100%; transition: border 0.2s; }
        .admin-input:focus { border: 2px solid var(--brick); outline: none; }
        .admin-btn { background-color: var(--brick); color: white; padding: 10px 24px; border-radius: 4px; font-weight: 500; transition: 0.3s; }
        .admin-btn:hover { opacity: 0.9; transform: scale(1.02); }
    </style>
</head>
<body class="min-h-screen flex flex-col items-center justify-center p-4">
    <div class="admin-card w-full max-w-[500px] p-10 shadow-sm">
        <div class="text-center mb-8">
            <div class="h-10 w-14 bg-[#BC4A3C] rounded mx-auto mb-2 flex items-center justify-center text-white font-bold">AGC</div>
            <h1 class="text-2xl font-normal text-[#202124]">AI Gemini College Login</h1>
            <p class="text-[#91352A] mt-2 text-sm">Protected by Advanced Encryption</p>
        </div>

        <?php if($error): ?>
            <div class="bg-red-50 text-[#BC4A3C] p-3 rounded mb-6 text-sm font-bold text-center border border-red-100">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="login.php" class="space-y-4">
            <input type="email" name="email" placeholder="Email address" class="admin-input" required>
            
            <div class="relative">
                <input type="password" name="password" id="passwordField" placeholder="Password" class="admin-input" required>
                <button type="button" onclick="togglePassword()" class="absolute right-4 top-4 text-gray-400 hover:text-gray-600">
                    <svg id="eyeIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </button>
            </div>

            <div class="flex items-center space-x-2 py-2">
                <div class="w-4 h-4 rounded-full bg-green-100 flex items-center justify-center">
                    <div class="w-2 h-2 bg-green-600 rounded-full"></div>
                </div>
                <span class="text-[11px] text-gray-500 font-medium italic">Data Entry Protection Active</span>
            </div>

            <div class="flex justify-between items-center mt-8">
                <div class="flex flex-col space-y-1">
                    <a href="signup.php" class="text-[#3177EA] font-bold text-sm hover:underline">Create Account</a>
                    <a href="recover.php" class="text-[#BC4A3C] font-bold text-xs hover:underline">Forgot Password?</a>
                </div>
                <button type="submit" class="admin-btn">Login to Class</button>
            </div>
        </form>
    </div>

    <script>
        // Password Visibility Logic
        function togglePassword() {
            const pwd = document.getElementById('passwordField');
            const icon = document.getElementById('eyeIcon');
            if (pwd.type === "password") {
                pwd.type = "text";
                icon.style.color = "#BC4A3C"; // AGC Brick color when visible
            } else {
                pwd.type = "password";
                icon.style.color = "#9CA3AF";
            }
        }
    </script>

</body>
</html>