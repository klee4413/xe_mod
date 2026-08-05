<?php
// [TIMESTAMP: 2026-03-01] - AIGC ACTIVE ENROLLMENT FOUNDRY course-select-active.php
session_start();
require_once __DIR__ . '/../db-connect.php';

$student_id = $_SESSION['user_id'] ?? null;
$first_name = $_SESSION['first_name'] ?? '';
$last_name  = $_SESSION['last_name'] ?? '';
$email      = $_SESSION['email'] ?? '';	

// [TIMESTAMP: 2026-04-01] - AIGC Enrollment Session Bridge
// Logic: Catch the selection via AJAX and ground it in the session
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax_save'])) {
    $_SESSION['pending_enrollment'] = $_POST['selected_courses'] ?? [];
    echo json_encode(["status" => "grounded"]);
    exit;
}

try {
    // SELECT ONLY UNLOCKED CLASSES AS REQUESTED
    $stmt = $pdo->prepare("SELECT * FROM classes WHERE status = 'UNLOCKED' ORDER BY class_id ASC");
    $stmt->execute();
    $active_classes = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Database Connection Fault: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AIGC | Active Course Selection</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        #enroll-popup { 
            display: none; 
            background: rgba(0,0,0,0.75); 
            backdrop-filter: blur(4px); 
        }
        /* Touch-friendly neo-brutalist checkboxes */
        input[type="checkbox"] { 
            width: 1.75rem; 
            height: 1.75rem; 
            accent-color: #059669; 
            cursor: pointer; 
            border: 2px solid #000;
        }
        .course-card {
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }
        .course-card:hover {
            transform: translate(-2px, -2px);
            box-shadow: 7px 7px 0px #000;
        }
    </style>
</head>
<body class="bg-emerald-800 min-h-screen p-3 md:p-8 font-sans">

    <!-- Outer Thick Green Framed Container -->
    <div class="max-w-6xl mx-auto bg-amber-50 rounded-2xl border-8 border-emerald-600 shadow-[12px_12px_0px_#000] p-6 md:p-10 relative">
        
        <!-- Header Banner Section -->
        <header class="bg-emerald-300 border-4 border-black shadow-[6px_6px_0px_#000] rounded-xl p-6 md:p-8 mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div>
                <span class="bg-yellow-300 border-2 border-black px-3 py-1 rounded-md text-[10px] font-black uppercase tracking-widest text-black shadow-[2px_2px_0px_#000]">
                    AIGC Course Enrollment
                </span>
                <h1 class="text-3xl md:text-4xl font-black text-black tracking-tight uppercase mt-3">
                    Active Course Selection
                </h1>
                <p class="text-xs md:text-sm font-black text-rose-700 uppercase tracking-wide mt-1">
                    <i class="fa-solid fa-circle-info mr-1"></i> 12 Credit Hours (Full Time) Allowed for Simultaneous Study
                </p>
            </div>

            <!-- Student Badge -->
            <div class="bg-white px-6 py-3 rounded-xl border-3 border-black shadow-[4px_4px_0px_#000] text-center shrink-0">
                <p class="text-[10px] font-black text-emerald-800 uppercase tracking-widest">Student Account</p>
                <p class="font-mono font-bold text-base md:text-lg text-blue-700 mt-0.5">
                    ID: <?php echo htmlspecialchars($student_id); ?> - <?php echo htmlspecialchars($first_name . " " . $last_name); ?>
                </p>
            </div>
        </header>

        <!-- Course Selection Form -->
        <form id="active-enroll-form">
            <div class="space-y-6">
                <?php foreach ($active_classes as $c): ?>
                    <div class="course-card bg-white p-6 md:p-8 rounded-xl border-3 border-black shadow-[5px_5px_0px_#000] flex flex-col sm:flex-row items-start gap-6">
                        
                        <!-- Checkbox Container -->
                        <div class="pt-1 shrink-0 flex items-center justify-center bg-yellow-200 p-3 rounded-lg border-2 border-black shadow-[2px_2px_0px_#000]">
                            <input type="checkbox" name="selected_courses[]" 
                                   value="<?php echo htmlspecialchars($c['class_name']); ?>" 
                                   data-id="<?php echo htmlspecialchars($c['class_id']); ?>"
                                   class="rounded border-2 border-black">
                        </div>

                        <!-- Course Info Body -->
                        <div class="flex-1 w-full">
                            <div class="flex flex-wrap items-center gap-2 mb-3">
                                <span class="bg-black text-white text-xs font-mono font-black px-3 py-1 rounded border border-black shadow-[2px_2px_0px_#000]">
                                    <?php echo htmlspecialchars($c['class_id']); ?>
                                </span>
                                <span class="bg-cyan-300 text-black text-xs font-black px-2.5 py-0.5 rounded border border-black shadow-[2px_2px_0px_#000] uppercase">
                                    Tier <?php echo htmlspecialchars($c['tier']); ?>
                                </span>
                                <span class="bg-emerald-200 text-black text-xs font-bold px-2.5 py-0.5 rounded border border-black shadow-[2px_2px_0px_#000] uppercase">
                                    <?php echo htmlspecialchars($c['credit_hour']); ?> Credits
                                </span>
                            </div>

                            <h2 class="text-xl md:text-2xl font-black text-black mb-3">
                                <?php echo htmlspecialchars($c['class_name']); ?>
                            </h2>

                            <!-- Syllabus Box -->
                            <div class="bg-stone-50 p-5 rounded-lg border-2 border-black shadow-[3px_3px_0px_#000]">
                                <h3 class="text-[10px] font-black text-gray-700 uppercase mb-2 tracking-widest border-b border-black/20 pb-1">
                                    Full Academic Syllabus
                                </h3>
                                <p class="text-xs md:text-sm text-gray-800 leading-relaxed font-medium whitespace-pre-line">
                                    <?php echo htmlspecialchars($c['syllabus']); ?>
                                </p>
                            </div>
                        </div>

                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Sticky Process Button Footer -->
            <div class="p-6 bg-emerald-200 rounded-xl border-4 border-black shadow-[6px_6px_0px_#000] text-center sticky bottom-6 z-30 mt-8 flex flex-col sm:flex-row justify-between items-center gap-4">
                
                <a href="campus.php" 
                   class="w-full sm:w-auto bg-rose-400 hover:bg-rose-300 text-black font-black px-8 py-3.5 rounded-xl border-3 border-black shadow-[4px_4px_0px_#000] active:translate-x-0.5 active:translate-y-0.5 active:shadow-[2px_2px_0px_#000] transition-all text-xs uppercase tracking-wider flex items-center justify-center gap-2">
                    <i class="fa-solid fa-arrow-left"></i> Back to Campus
                </a>

                <button type="button" onclick="openEnrollPopup()" 
                        class="w-full sm:w-auto bg-emerald-400 hover:bg-emerald-300 text-black px-12 py-3.5 rounded-xl border-3 border-black font-black uppercase text-xs md:text-sm tracking-wider shadow-[4px_4px_0px_#000] active:translate-x-0.5 active:translate-y-0.5 active:shadow-[2px_2px_0px_#000] transition-all flex items-center justify-center gap-2">
                     <i class="fa-solid fa-check-double"></i> Process Selected Courses
                </button>

            </div>
        </form>

        <!-- Page Footer -->
        <footer class="mt-8 pt-4 border-t-4 border-black text-center font-bold text-xs text-black uppercase tracking-wider">
            &copy; 2026 AI Gemini College. Active Course Registration Gate.
        </footer>

    </div>

    <!-- Enrollment Summary Popup -->
    <div id="enroll-popup" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="bg-white w-full max-w-lg rounded-2xl p-8 border-4 border-black shadow-[10px_10px_0px_#000] text-center relative">
            
            <h3 class="text-2xl font-black text-black uppercase tracking-tight mb-1">
                Enrollment Summary
            </h3>
            <p class="text-xs font-bold text-gray-600 uppercase tracking-wider mb-6">
                Confirming Your Course Selection
            </p>

            <ul id="summary-list" class="space-y-3 mb-8 max-h-60 overflow-y-auto px-2">
                <!-- Dynamic List Injected via JS -->
            </ul>

            <div class="flex flex-col gap-3">
                <a href="course-regit2.php" 
                   class="w-full bg-emerald-400 hover:bg-emerald-300 text-black py-4 rounded-xl border-3 border-black font-black uppercase text-xs tracking-wider shadow-[4px_4px_0px_#000] active:translate-x-0.5 active:translate-y-0.5 active:shadow-[2px_2px_0px_#000] transition-all block">
                    Proceed to Course Registration <i class="fa-solid fa-arrow-right ml-1"></i>
                </a>
                
                <button onclick="closeEnrollPopup()" 
                        class="w-full bg-gray-200 hover:bg-gray-300 text-black py-3 rounded-xl border-2 border-black font-bold uppercase text-xs tracking-wider shadow-[2px_2px_0px_#000] transition-all">
                    Go Back & Modify
                </button>
            </div>

        </div>
    </div>

    <!-- JavaScript Handling Session Grounding & Popup Modal -->
    <script>
      async function openEnrollPopup() {
        const boxes = document.querySelectorAll('input[name="selected_courses[]"]:checked');
        const summary = document.getElementById('summary-list');
        summary.innerHTML = '';

        if (boxes.length === 0) {
            alert("AIGC Logic Alert: Please select at least one active course.");
            return;
        }

        // 1. Prepare Data for Session Grounding
        const formData = new FormData();
        formData.append('ajax_save', '1');
        boxes.forEach(box => {
            formData.append('selected_courses[]', box.dataset.id); // Save IDs (AIM100, etc.)
        });

        // 2. Dispatch: Send to Session
        try {
            await fetch('course-select-active.php', { method: 'POST', body: formData });
            
            // 3. Update Visual Summary in Neo-Brutalist Cards
            boxes.forEach(box => {
                const li = document.createElement('li');
                li.className = "flex justify-between items-center bg-amber-100 p-3.5 rounded-lg border-2 border-black shadow-[2px_2px_0px_#000]";
                li.innerHTML = `
                    <span class="text-xs font-black text-black">${box.value}</span>
                    <span class="text-[10px] font-mono font-black text-white bg-black px-2 py-0.5 rounded border border-black">${box.getAttribute('data-id')}</span>
                `;
                summary.appendChild(li);
            });

            document.getElementById('enroll-popup').style.display = 'flex';
        } catch (e) {
            alert("Session Grounding Error: Connection Lost.");
        }
    }

    function closeEnrollPopup() {
        document.getElementById('enroll-popup').style.display = 'none';
    }
    </script>
</body>
</html>