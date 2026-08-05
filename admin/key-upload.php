<?php
// key-upload.php in admin.aigeminicollege.org /var/www/admin  
require_once __DIR__ . '/../../db-connect.php'; 
$message = "";
$message_class = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['gemini_key'])) {
    $plain_key = trim($_POST['gemini_key']);
    
    if (empty($plain_key)) {
        $message = "Error: Key cannot be empty.";
        $message_class = "error";
    } else {      
        $target_file = '/var/www/db-connect.php';
        
        if (file_exists($target_file)) {
            // 1. Read existing content of db-connect.php
            $current_content = file_get_contents($target_file);
            
            // 2. Remove any pre-existing GEMINI_KEY definitions to prevent duplicate crashes
            $clean_content = preg_replace("/define\s*\(\s*['\"]GEMINI_KEY['\"]\s*,\s*['\"].*?['\"]\s*\)\s*;\s*\n?/i", "", $current_content);
            
            // 3. Clean up trailing closing PHP tags if present so append sits correctly
            $clean_content = rtrim($clean_content);
            if (substr($clean_content, -2) === '?>') {
                $clean_content = substr($clean_content, 0, -2);
            }
            
            // 4. Construct append injection vector string
            $append_data = "\n\n// Automatically updated key configuration segment\n";
            $append_data .= "define('GEMINI_KEY', '" . addslashes($plain_key) . "');\n";
            $append_data .= "?>";
            
            // 5. Commit updated structural code back to the system
            if (file_put_contents($target_file, $clean_content . $append_data, LOCK_EX) !== false) {
                $message = "API Key successfully compiled into db-connect.php storage.";
                $message_class = "success";
            } else {
                $message = "System Error: Write permission denied on /var/www/db-connect.php.";
                $message_class = "error";
            }
        } else {
            $message = "System Error: Base target /var/www/db-connect.php file not found.";
            $message_class = "error";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin-Key Encryption | Neo-Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght=500;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #BEF9AC;
        }
        /* NEOBRUTALISM UI SPECIFIC CORE ACCENTS */
        .neo-box {
            border: 4px solid #000000;
            box-shadow: 8px 8px 0px 0px #000000;
            transition: all 0.15s ease-in-out;
        }
        .neo-btn {
            border: 4px solid #000000;
            box-shadow: 4px 4px 0px 0px #000000;
            transition: all 0.1s ease-in-out;
        }
        .neo-btn:hover {
            transform: translate(-2px, -2px);
            box-shadow: 6px 6px 0px 0px #000000;
        }
        .neo-btn:active {
            transform: translate(4px, 4px);
            box-shadow: 0px 0px 0px 0px #000000;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">

<div class="w-full max-w-xl bg-white p-8 md:p-12 rounded-none neo-box relative overflow-hidden">
    <div class="absolute top-0 left-0 right-0 bg-black h-3"></div>
    
    <header class="mb-8 text-left">
        <h1 class="text-3xl md:text-4xl font-extrabold uppercase tracking-tight text-black mb-2">
            Key Injection Panel
        </h1>
        <p class="text-xs font-bold uppercase tracking-wider text-slate-500">
            AIGC Security Infrastructure Control Node
        </p>
    </header>
    
    <form method="POST" action="" class="space-y-6">
        <div>
            <label for="gemini_key" class="block text-sm font-extrabold uppercase tracking-wider text-black mb-2">
                Encryption Hash Input
            </label>
            <div class="relative flex items-center">
                <input 
                    type="password" 
                    id="gemini_key" 
                    name="gemini_key" 
                    class="w-full bg-[#f8fafc] border-4 border-black p-4 text-sm font-bold tracking-widest text-black focus:outline-none focus:bg-white placeholder-slate-400" 
                    required 
                    placeholder="••••••••••••••••••••••••••••••••"
                >
                <button 
                    type="button" 
                    class="absolute right-4 text-xl font-bold p-1 hover:scale-110 active:scale-95 transition-transform" 
                    onclick="toggleVisibility()"
                    aria-label="Toggle Key Visibility"
                >
                    👁
                </button>
            </div>
        </div>
        
        <button type="submit" class="w-full bg-[#ffde4d] text-black font-extrabold uppercase tracking-widest text-sm py-4 rounded-none neo-btn">
            Compile Key to Target Vector
        </button>
    </form>
    
    <div class="mt-8 pt-6 border-t-4 border-black flex flex-col sm:flex-row items-center justify-between gap-4">
        <a href="admin.php" class="inline-block bg-[#f1f5f9] px-4 py-2 text-xs font-extrabold uppercase tracking-wider text-black neo-btn">
            ← Return to Base Admin
        </a>
        
        <?php if (!empty($message)): ?>
            <div class="text-xs font-extrabold uppercase tracking-wide px-3 py-2 border-2 border-black <?php echo ($message_class === 'success') ? 'bg-emerald-300' : 'bg-rose-300'; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
function toggleVisibility() {
    const input = document.getElementById('gemini_key');
    if (input.type === 'password') {
        input.type = 'text';
    } else {
        input.type = 'password';
    }
}
</script>

</body>
</html>