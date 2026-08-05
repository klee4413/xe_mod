<?php
// 1. Database Connection campus-course.php
session_start();
require_once __DIR__ . '/../db-connect.php';
//require_once 'db-connect.php';
$student_id = $_SESSION['user_id']    ?? 0;
$first_name = $_SESSION['first_name'] ?? 'Scholar';
$last_name  = $_SESSION['last_name']  ?? '';
$email      = $_SESSION['email']      ?? '';
 
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Select course rooms: IDs 38, 37, 41
$rooms_query = "SELECT id, room_name, description, linkto, button_color FROM campus_table WHERE id IN (38, 37, 41) AND status = 'active' ORDER BY id ASC";
$rooms_result = $conn->query($rooms_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32.png">
    <link rel="shortcut icon" href="images/favicon-32.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AIGC Central Campus | Programming Lab</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .card-transition { transition: all 0.3s ease; }
        .hidden-description { display: none; }
    </style>
</head>
<body class="bg-orange-600 min-h-screen p-2 md:p-6 font-sans text-gray-800">

    <!-- Orange Framed Container -->
    <div class="max-w-6xl mx-auto bg-white rounded-2xl shadow-2xl overflow-hidden border-2 border-orange-400">
        
        <!-- Header Section -->
        <header class="bg-white pt-6 pb-4 px-6 border-b border-orange-100">
            <div class="text-center mb-4">
                <h1 class="text-2xl md:text-3xl font-extrabold text-orange-900 inline-flex flex-wrap items-center justify-center gap-2">
                    <span>AIGC Course & Review</span>
                    <span id="studentIdentity" class="text-orange-600 text-lg md:text-xl font-mono ml-2">
                        ID: <?= htmlspecialchars($student_id) ?> - <?= htmlspecialchars(strtoupper($first_name . " " . $last_name)) ?>
                    </span>
                </h1>
            </div>

            <!-- 2400x800 Banner Image -->
            <div class="w-full h-48 md:h-64 lg:h-72 rounded-xl overflow-hidden shadow-inner border border-orange-100 bg-orange-50">
                <img 
                    src="images/campus-course.jpg" 
                    alt="AIGC Programming Lab Banner" 
                    class="w-full h-full object-cover object-center"
                    onerror="this.src='https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&w=2400&q=80'"
                >
            </div>
        </header>

        <!-- Main Content Area -->
        <main class="p-6 md:p-8 bg-amber-50/30">
            
            <!-- Directory Header & Action -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">        
                <h2 class="text-lg font-bold text-orange-900 tracking-wider uppercase flex items-center gap-2">
                    Course Selection & Registration
                </h2>        
                <a href="campus.php" 
                   class="bg-orange-600 hover:bg-orange-700 text-white font-bold py-2 px-5 rounded-xl transition-all duration-300 shadow-sm hover:shadow flex items-center gap-2 text-xs uppercase tracking-wider">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Campus
                </a>
            </div>

            <!-- Course Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                <?php while($row = $rooms_result->fetch_assoc()): ?>
                    <div class="bg-orange-50/70 rounded-xl border border-orange-100 p-5 card-transition hover:shadow-md relative flex flex-col justify-between">
                        
                        <!-- Expand/Collapse Button -->
                        <button onclick="toggleDesc(<?= $row['id']; ?>)" 
                                class="absolute top-4 right-4 text-orange-400 hover:text-orange-700 transition-colors p-1">
                            <i id="icon-<?= $row['id']; ?>" class="fa-solid fa-chevron-down text-sm"></i>
                        </button>

                        <div>
                            <!-- Room Title -->
                            <div class="flex items-start gap-2.5 mb-3 pr-6">
                                <span class="text-orange-600 text-base mt-0.5"><i class="fa-solid fa-door-open"></i></span>
                                <h3 class="font-bold text-gray-900 text-base leading-snug">  
                                    <span class="text-xs font-normal text-orange-500 mr-1"><?= $row['id']; ?>.</span> 
                                    <?= htmlspecialchars($row['room_name']); ?> 
                                </h3>
                            </div>

                            <!-- Expandable Description -->
                            <div id="desc-<?= $row['id']; ?>" class="hidden-description mb-4 bg-white/80 p-3 rounded-lg border border-orange-100">
                                <p class="text-xs text-gray-700 leading-relaxed">
                                    <?= htmlspecialchars($row['description']); ?>
                                </p>
                            </div>
                        </div>

                        <!-- Link Button -->
                        <div class="mt-3 pt-2">
                            <a href="<?= htmlspecialchars($row['linkto']); ?>" target="_blank" rel="noopener noreferrer"   
                               style="background-color: #<?= ltrim($row['button_color'], '#'); ?>;"                       
                               class="inline-block text-white px-4 py-1.5 rounded-lg text-xs font-semibold hover:brightness-90 transition-all shadow-sm">
                               Link to
                            </a>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </main>

        <!-- Footer -->
        <footer class="text-center py-6 text-gray-400 text-xs border-t border-orange-50 bg-white">
            &copy; 2026 AI Gemini College. All rights reserved.
        </footer>

    </div>

    <!-- Client-Side JavaScript -->
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
                        identityDisplay.innerText = `ID: ${student.id} - ${student.first_name.toUpperCase()} ${student.last_name.toUpperCase()}`;
                    }
                    console.log("GAC Campus Course: Identity Grounded", student);
                } else {
                    window.location.href = "login.php";
                }
            } catch (error) {
                console.error("Logic Error: Identity Bridge Failed", error);
            }
        }

        window.onload = groundStudentIdentity;
    </script>
</body>
</html>