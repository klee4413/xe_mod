<?php
// [TIMESTAMP: 2026-03-01] - AIGC ACTIVE ENROLLMENT FOUNDRY course-select-active.php
session_start();
$isLocal = in_array($_SERVER['HTTP_HOST'] ?? '', ['localhost', '127.0.0.1']) 
        || str_contains($_SERVER['HTTP_HOST'] ?? '', 'localhost:');

if ($isLocal) {
    require_once 'db-connect.php';
} else {
    require_once __DIR__ . '/../db-connect.php';
}

$student_id = $_SESSION['user_id']    ?? 0;
$first_name = $_SESSION['first_name'] ?? 'Scholar';
$last_name  = $_SESSION['last_name']  ?? '';
$email      = $_SESSION['email']      ?? '';
$date       = date("Y-m-d");
// [TIMESTAMP: 2026-04-01] - AIGC Enrollment Session Bridge
// Logic: Catch the selection via AJAX and ground it in the session
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax_save'])) {
    $_SESSION['pending_enrollment'] = $_POST['selected_courses'] ?? [];
    echo json_encode(["status" => "grounded"]);
    exit;
}
// ... rest of your existing db_connect and fetch code ...
require_once 'db_connect.php'; 
try {
    // SELECT ONLY UNLOCKED CLASSES AS REQUESTED
    $stmt = $pdo->prepare("SELECT * FROM classes WHERE status = 'UNLOCKED' ORDER BY class_id ASC");
    $stmt->execute();
    $active_classes = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Database Connection Fault: " . $e->getMessage());
}
//  ID:<?php echo $_SESSION['user_id']; ?--> <span class="text-blue-600 text-xl md:text-2xl ml-4 font-mono">
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AIGC | Active Course Selection</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .gac-border { border: 6px solid #059669; }
        .gac-green { background-color: #059669; }
        #enroll-popup { display: none; background: rgba(0,0,0,0.8); backdrop-filter: blur(8px); }
        /* Large touch-friendly checkboxes for mobile */
        input[type="checkbox"] { width: 1.6rem; height: 1.6rem; accent-color: #059669; cursor: pointer; }
    </style>
</head>
<body class="bg-[#F9FAFB] min-h-screen p-2 md:p-10 font-sans">

    <div class="max-w-6xl mx-auto bg-white gac-border rounded-[3rem] overflow-hidden shadow-2xl relative">
        
        <header class="p-8 md:p-12 bg-white border-b border-gray-100 flex flex-col md:flex-row justify-between items-center gap-6">
            <div>
                <h1 class="text-[10px] font-black uppercase tracking-[0.4em] text-green-900 mb-2">AIGC Course Enrollment</h1>
                <h2 class="text-4xl font-black text-gray-900 tracking-tighter">AIGC Active Course Selection to Register</h2>
                <p class="text-[20px] text-red-800 mt-2">12 Credit Hours (Full Time) Enrollment Allowed for Simultaneous Study.</p>
            </div>
            <div class="bg-green-50 px-8 py-4 rounded-2xl border border-green-100 text-center">
                <p class="text-[9px] font-bold text-green-600 uppercase tracking-widest">Student Account</p>
                <!--p class="font-black text-gray-800"><?php echo htmlspecialchars($student_id); ?></p-->
				  ID:<span class="text-blue-600 text-xl md:text-2xl ml-4 font-mono"><?php echo $student_id; ?> -  <?php echo $_SESSION['first_name'] . " " . $_SESSION['last_name']; ?>
                 </span>
            </div>
        </header>

        <form id="active-enroll-form">
            <div class="divide-y divide-gray-100">
                <?php foreach ($active_classes as $c): ?>
                    <div class="p-8 md:p-12 flex items-start gap-8 hover:bg-green-50/30 transition-all group">
                        <div class="pt-2">
                            <input type="checkbox" name="selected_courses[]" 
                                   value="<?php echo htmlspecialchars($c['class_name']); ?>" 
                                   data-id="<?php echo htmlspecialchars($c['class_id']); ?>"
                                   class="rounded-lg border-gray-300">
                        </div>

                        <div class="flex-1">
                            <div class="flex flex-wrap items-center gap-3 mb-4">
                                <span class="bg-gray-900 text-white text-[10px] font-black px-3 py-1 rounded-md uppercase tracking-widest">
                                    <?php echo $c['class_id']; ?>
                                </span>
                                <span class="text-[10px] font-black text-green-700 uppercase">Tier <?php echo $c['tier']; ?></span>
                                <span class="text-[10px] font-bold text-black-500 uppercase"><?php echo $c['credit_hour']; ?> Credits</span>
                            </div>

                            <h3 class="text-2xl font-black text-gray-900 mb-4 group-hover:text-green-700 transition-colors">
                                <?php echo htmlspecialchars($c['class_name']); ?>
                            </h3>

                            <div class="bg-gray-50 p-8 rounded-[2rem] border border-gray-100 shadow-inner">
                                <h4 class="text-[9px] font-black text-gray-400 uppercase mb-3 tracking-widest">Full Academic Syllabus</h4>
                                <p class="text-sm text-gray-600 leading-relaxed whitespace-pre-line"><?php echo htmlspecialchars($c['syllabus']); ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="p-10 bg-gray-50 border-t border-gray-100 text-center sticky bottom-0 z-40">
                <button type="button" onclick="openEnrollPopup()" 
                        class="gac-green text-white px-24 py-5 rounded-2xl font-black uppercase text-sm tracking-[0.2em] shadow-2xl hover:bg-green-700 transition-all transform hover:scale-105 active:scale-95">
                     Process Selected Courses
                </button>
            </div>
        </form>
    </div>
	   <div class="rounded-md shadow">
              <a href="campus.php" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-black rounded-md text-white bg-[#BC4A3C] hover:bg-red-800 md:py-4 md:text-lg md:px-10 transition-all transform hover:scale-105">
               Back to Campus
              </a>
            </div>

    <div id="enroll-popup" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="bg-white w-full max-w-lg rounded-[3rem] p-10 shadow-2xl border-t-[12px] border-green-600 text-center">
            
            <h3 class="text-2xl font-black text-gray-900 mb-2">Enrollment Summary</h3>
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-8">Confirming Your Course Selection</p>

            <ul id="summary-list" class="space-y-3 mb-10 max-h-60 overflow-y-auto px-4">
                </ul>

            <div class="flex flex-col gap-4">
                <a href="course-regit2.php" 
                   class="w-full gac-green text-white py-5 rounded-2xl font-black uppercase text-xs tracking-[0.2em] shadow-xl hover:bg-green-700">
                    Proceed to Course Registration..
                </a>
                <button onclick="closeEnrollPopup()" class="text-[10px] text-gray-400 font-bold uppercase hover:text-red-500 tracking-widest">
                    Go Back & Modify
                </button>
            </div>
        </div>
    </div>

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

    // 2. Surgical Dispatch: Send to Session
    try {
        await fetch('course-select-active.php', { method: 'POST', body: formData });
        
        // 3. Update Visual Summary
        boxes.forEach(box => {
            const li = document.createElement('li');
            li.className = "flex justify-between items-center bg-green-50 p-4 rounded-xl border border-green-100";
            li.innerHTML = `
                <span class="text-xs font-bold text-gray-800">${box.value}</span>
                <span class="text-[10px] font-black text-green-600 bg-white px-2 py-1 rounded shadow-sm">${box.getAttribute('data-id')}</span>
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
