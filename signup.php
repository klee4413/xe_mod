<?php
// =========================================================================
// GAC COGNITIVE FOUNDRY — REGISTRATION GATEWAY : signup.php modified 6/9/2026 Tues
// PURPOSE: SANITIZED, ROBUST SIGNUP CONTROLLER FOR ADMISSIONS PIPELINE
// =========================================================================
session_start();
require_once __DIR__ . '/../db-connect.php'; // Corrected file pointer referencing standard hyphen convention
date_default_timezone_set('America/Los_Angeles');

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. SURGICAL SANITIZATION (Standard ASCII space validation)
    $first_name = filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_SPECIAL_CHARS);
    $last_name  = filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_SPECIAL_CHARS);
    $email      = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    
    $password   = isset($_POST['password']) ? $_POST['password'] : '';
    $passcode   = isset($_POST['passcode']) ? $_POST['passcode'] : '';
    $education  = filter_input(INPUT_POST, 'education', FILTER_SANITIZE_SPECIAL_CHARS);    
    
    // 2. MOBILE-FRIENDLY PHONE NUMBER NORMALIZATION
    $phone_raw  = isset($_POST['phone_no']) ? $_POST['phone_no'] : '';
    $phone_no   = preg_replace('/[^0-9]/', '', $phone_raw); // Strip all parentheses, hyphens, spaces, and signs
    
    $password_hash = password_hash($password, PASSWORD_BCRYPT);
    
    try {
        // Enforce basic parameters checks before database interaction
        if (strlen($phone_no) !== 10) {
            throw new Exception("Please enter a valid 10-digit phone number.");
        }

        $stmt = $pdo->prepare("INSERT INTO sign_up (first_name, last_name, email, password_hash, passcode, phone_no, education) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $result = $stmt->execute([$first_name, $last_name, $email, $password_hash, $passcode, $phone_no, $education]);

        if ($result) {
            // 3. SECURE SESSION REGENERATION
            session_unset();       // Clear old user states
            session_destroy();     // Destroy old session file
            session_start();       // Begin clean session state
            session_regenerate_id(true);

            $new_id = $pdo->lastInsertId();

            // 4. ESTABLISH SESSION IDENTITY
            $_SESSION['user_id'] = $new_id;
            $_SESSION['first_name'] = $first_name;
            $_SESSION['last_name'] = $last_name;
            $_SESSION['email'] = $email;            
            
            $interviewee_data = [
                "id" => $new_id,
                "first_name" => $first_name,
                "last_name" => $last_name,
                "email" => $email,
                "status" => "interviewee_pending"
            ];
            $_SESSION['interviewee_json'] = json_encode($interviewee_data);
            
            header("Location: interview-guide.php");
            exit();
        }
    } catch (PDOException $e) {
        // Code 23000 = Duplicate Entry (Email already exists)
        if ($e->getCode() == 23000) {
            $error = "This email is in our records. Please sign in instead.";
        } else {
            // Include actual system diagnostic details for administrators during debugging
            $error = "System Error: " . htmlspecialchars($e->getMessage());
        }
    } catch (Exception $e) {
        $error = htmlspecialchars($e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GAC | Create Account</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root { --brick: #BC4A3C; --google-gray: #DADCE0; }
        body { background-color: #F8F9FA; font-family: 'Roboto', sans-serif; }
        .admin-card { border: 1px solid var(--google-gray); border-radius: 8px; background: white; }
        .admin-input { border: 1px solid var(--google-gray); border-radius: 4px; padding: 13px 15px; width: 100%; transition: border 0.2s; }
        .admin-input:focus { border: 2px solid var(--brick); outline: none; }
        .admin-btn { background-color: var(--brick); color: white; padding: 10px 24px; border-radius: 4px; font-weight: 500; }
    </style>
</head>
<body class="min-h-screen flex flex-col items-center justify-center p-4">

    <div class="admin-card w-full max-w-[500px] p-10 shadow-sm">
        <div class="text-center mb-8">
            <div class="h-10 w-14 bg-[#BC4A3C] rounded mx-auto mb-2 flex items-center justify-center text-white font-bold">AGC</div>
            <h1 class="text-2xl font-normal text-[#202124]">Create AI Gemini College Account</h1>
            <p class="text-[#91352A] mt-2 text-sm">Protected by Advanced Encryption</p>
        </div>

        <?php if($error): ?>
            <div class="bg-red-50 text-[#BC4A3C] p-3 rounded mb-6 text-sm font-bold text-center border border-red-100">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="signup.php" class="space-y-4" autocomplete="off">
            <div class="flex space-x-4">
                <input type="text" name="first_name" placeholder="First name" class="admin-input" required autocomplete="off">
                <input type="text" name="last_name" placeholder="Last name" class="admin-input" required autocomplete="off">
            </div>
            
            <input type="email" name="email" placeholder="Email address" class="admin-input" required autocomplete="off">
            
            <div class="relative">
                <input type="password" name="password" id="passwordField" placeholder="Password" class="admin-input" required minlength="8" autocomplete="new-password">
                <button type="button" onclick="togglePassword()" class="absolute right-4 top-4 text-gray-400">
                    <svg id="eyeIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </button>
            </div>
            
            <input type="password" name="passcode" placeholder="Passcode(6-10 numbers & letters)" class="admin-input" required minlength="8" autocomplete="off">
            <input type="tel" name="phone_no" placeholder="Phone number (10 digits)" class="admin-input" required maxlength="14" autocomplete="off">
            <input type="text" name="education" placeholder="Education - GED,AA,BS,or Other" class="admin-input" required minlength="2" autocomplete="off">

            <div class="flex items-center space-x-2 py-4">
                <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"/>
                </svg>
                <span class="text-xs text-gray-500 font-medium italic">Sign Up Information Protection Active: Protected by Advanced Encryption</span>
            </div>

            <div class="flex justify-between items-center mt-8">
                <button type="submit" class="admin-btn">Create Account</button>
                <div class="flex space-x-4">
                    <a href="index.php" class="text-[#3177EA] font-bold text-sm hover:underline">Home</a>
                    <a href="signup.php" class="text-[#BC4A3C] font-bold text-sm hover:underline">Clear</a>
                </div>
            </div>
        </form>
    </div>
    <script>
        function togglePassword() {
            const pwd = document.getElementById('passwordField');
            const icon = document.getElementById('eyeIcon');
            if (pwd.type === "password") {
                pwd.type = "text";
                icon.style.color = "#BC4A3C";
            } else {
                pwd.type = "password";
                icon.style.color = "#9CA3AF";
            }
        }
    </script>
</body>
</html>