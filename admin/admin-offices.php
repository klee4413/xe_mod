<?php
// 1. Database Connection admin-offices.php
session_start();
require_once __DIR__ . '/../../db-connect.php';

$admin_id = $_SESSION['user_id']    ?? 0;
$first_name = $_SESSION['first_name'] ?? 'Scholar';
$last_name  = $_SESSION['admin_name']  ?? $_SESSION['last_name'] ?? '';
$email      = $_SESSION['email']      ?? '';

if (!isset($pdo)) {
    die("Connection failed: Database object not found.");
}

try {
    // SELECT rooms excluding header, sorted by room_group
    $rooms_query = "SELECT id, room_name, description, linkto, button_color 
                    FROM campus_table 
                    WHERE room_name <> 'header' AND status = 'admin' 
                    ORDER BY room_group ASC";
    $stmt = $pdo->query($rooms_query);
    $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database Logic Fault: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32.png">
    <link rel="shortcut icon" href="images/favicon-32.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AIGC Central Campus | Directory</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: #065f46; /* Emerald Dark Outer Frame Canvas */
            margin: 0; 
            padding: 16px; 
            min-height: 100vh;
            box-sizing: border-box;
        }

        /* Outer Frame Container */
        .container-frame { 
            max-width: 1280px; 
            margin: auto; 
            background: #fffbeb; /* Warm Amber Canvas */
            padding: 24px; 
            border: 8px solid #059669; /* Thick Green Frame */
            border-radius: 20px; 
            box-shadow: 12px 12px 0px #000000; 
        }

        /* Neo Brutalist Cards */
        .neo-card {
            background-color: #ffffff;
            border: 3px solid #000000;
            border-radius: 12px;
            box-shadow: 6px 6px 0px #000000;
            transition: transform 0.1s ease, box-shadow 0.1s ease;
        }

        .neo-card:hover {
            transform: translate(-2px, -2px);
            box-shadow: 8px 8px 0px #000000;
        }

        .hidden-description { display: none; }

        /* Neo Buttons */
        .neo-btn {
            border: 2px solid #000000;
            box-shadow: 3px 3px 0px #000000;
            transition: all 0.1s ease;
        }
        .neo-btn:active {
            transform: translate(2px, 2px);
            box-shadow: 1px 1px 0px #000000;
        }
    </style>
</head>
<body>

<div class="container-frame">

    <!-- Header Section -->
    <header class="bg-white border-4 border-black rounded-2xl p-6 shadow-[6px_6px_0px_#000] mb-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <div class="bg-emerald-400 text-black border-2 border-black shadow-[2px_2px_0px_#000] px-3 py-1 rounded-lg font-black text-xs uppercase tracking-wider inline-block mb-2">
                    AIGC Administration
                </div>
                <h1 class="text-2xl md:text-3xl font-black text-black uppercase tracking-tight">
                    AI Gemini College Administration Offices
                </h1>
            </div>
            
            <div class="bg-yellow-300 text-black border-2 border-black shadow-[3px_3px_0px_#000] px-4 py-2 rounded-xl font-black text-sm uppercase">
                ID: <?php echo htmlspecialchars($student_id) . " / " . htmlspecialchars($last_name); ?>
            </div>
        </div>
    </header>

    <main>
        <!-- Action Sub-header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4 bg-cyan-200 border-4 border-black p-4 rounded-2xl shadow-[6px_6px_0px_#000]">        
            <h2 class="text-lg font-black text-black tracking-wider uppercase">
                🏛️ Administration Facility Directory
            </h2>        
            <a href="logout.php" 
               class="neo-btn bg-rose-400 hover:bg-rose-300 text-black font-black py-2 px-6 rounded-xl flex items-center gap-2 text-xs uppercase tracking-wider">
                <i class="fa-solid fa-right-from-bracket"></i>
                Logout
            </a>
        </div>

        <!-- Facility Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php if (!empty($rooms)): ?>
                <?php foreach ($rooms as $row): ?>
                    <div class="neo-card p-6 flex flex-col justify-between relative bg-white">
                        
                        <!-- Toggle Button -->
                        <button onclick="toggleDesc(<?php echo $row['id']; ?>)" 
                                class="absolute top-4 right-4 bg-yellow-300 border-2 border-black rounded-lg w-8 h-8 flex items-center justify-center text-black shadow-[2px_2px_0px_#000] hover:bg-yellow-200 transition-colors">
                            <i id="icon-<?php echo $row['id']; ?>" class="fa-solid fa-chevron-down text-xs"></i>
                        </button>

                        <div>
                            <div class="flex items-center gap-3 mb-4">
                                <span class="bg-emerald-400 text-black border-2 border-black p-2 rounded-lg text-sm shadow-[2px_2px_0px_#000]">
                                    <i class="fa-solid fa-door-open"></i>
                                </span>
                                <h3 class="font-black text-black text-base uppercase"> 
                                    <span class="text-xs bg-black text-white px-1.5 py-0.5 rounded border border-black mr-1"><?php echo $row['id']; ?></span> 
                                    <?php echo htmlspecialchars($row['room_name']); ?> 
                                </h3>
                            </div>

                            <!-- Expandable Description -->
                            <div id="desc-<?php echo $row['id']; ?>" class="hidden-description mb-6 bg-emerald-50 border-2 border-black p-3 rounded-lg shadow-[2px_2px_0px_#000]">
                                <p class="text-xs font-bold text-black leading-relaxed">
                                    <?php echo htmlspecialchars($row['description']); ?>
                                </p>
                            </div>
                        </div>

                        <!-- Action Link Button -->
                        <div class="mt-4 pt-4 border-t-2 border-black">
                            <?php 
                                $btn_bg = !empty($row['button_color']) ? '#' . ltrim($row['button_color'], '#') : '#60a5fa'; 
                            ?>
                            <a href="<?php echo htmlspecialchars($row['linkto']); ?>" target="_blank" rel="noopener noreferrer"	
                               style="background-color: <?php echo $btn_bg; ?>;"						
                               class="neo-btn inline-block text-black px-5 py-2.5 rounded-xl text-xs font-black uppercase tracking-wider">
                               Link to Office →
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-span-3 bg-white border-4 border-black p-8 rounded-2xl shadow-[6px_6px_0px_#000] text-center font-black">
                    No administration offices found in facility directory.
                </div>
            <?php endif; ?>
        </div>
    </main>

    <!-- Footer -->
    <footer class="mt-12 pt-6 border-t-4 border-black text-center text-xs font-black text-black uppercase tracking-wider bg-white p-4 rounded-xl border-2 shadow-[4px_4px_0px_#000]">
        © 2026 AI Gemini College. All rights reserved.
    </footer>

</div>

<script>
function toggleDesc(id) {
    const desc = document.getElementById('desc-' + id);
    const icon = document.getElementById('icon-' + id);
    
    if (desc.style.display === "block") {
        desc.style.display = "none";
        icon.classList.replace('fa-chevron-up', 'fa-chevron-down');
    } else {
        desc.style.display = "block";
        icon.classList.replace('fa-chevron-down', 'fa-chevron-up');
    }
}

async function groundStudentIdentity() {
    try {
        const response = await fetch('get_student_session.php');
        const student = await response.json();

        if (student.status === "Authorized") {
            const identityDisplay = document.getElementById('studentIdentity');
            if (identityDisplay) {
                identityDisplay.innerText = `Scholar: ${student.first_name} ${student.last_name} (ID: ${student.id})`;
            }
            console.log("GAC Campus: Identity Grounded", student);
        } else {
            window.location.href = "login.php";
        }
    } catch (error) {
        console.error("Logic Error: Identity Bridge Failed", error);
    }
}

window.onload = groundStudentIdentity;

function toHome() {
    window.location.href = 'admin-offices.php';
}
</script>

</body>
</html>
