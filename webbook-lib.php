<?php
// GAC FOUNDRY: WebBook Library (Asynchronous Identity Mode)
$isLocal = in_array($_SERVER['HTTP_HOST'] ?? '', ['localhost', '127.0.0.1']) 
        || str_contains($_SERVER['HTTP_HOST'] ?? '', 'localhost:');

if ($isLocal) {
    require_once 'db-connect.php';
} else {
    require_once __DIR__ . '/../db-connect.php';
}

if (!isset($pdo)) {
    die("Connection failed: Database object not found.");
}

// 1. SELECT description for the Hero Header
$header_query = "SELECT description FROM webbook_lib WHERE book_name = 'header' LIMIT 1";
$header_stmt = $pdo->query($header_query);
$header_row = $header_stmt->fetch();
$hero_description = ($header_row) ? $header_row['description'] : "Welcome to AIGC Central Campus.";

// 2. SELECT rooms excluding header, sorted by group_order
$rooms_query = "SELECT id, book_name, description, linkto FROM webbook_lib WHERE book_name <> 'header' ORDER BY group_order ASC";
$rooms_result = $pdo->query($rooms_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AIGC Central Campus | WEBBOOK Directory</title>
    <link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .card-transition { transition: all 0.3s ease; }
        .hidden-description { display: none; }
    </style>
</head>
<body class="bg-sky-500 min-h-screen p-2 md:p-6 font-sans text-gray-800">

    <!-- Sky Blue Framed Container -->
    <div class="max-w-6xl mx-auto bg-white rounded-2xl shadow-2xl overflow-hidden border-2 border-sky-400">
        
        <!-- Header Section -->
        <header class="bg-white pt-6 pb-4 px-6 border-b border-sky-100">
            <div class="text-center mb-4">
                <h1 class="text-2xl md:text-3xl font-extrabold text-sky-900 inline-flex flex-wrap items-center justify-center gap-2">
                    <span>AIGC WebBook Library</span>
                    <span id="studentIdentity" class="text-sky-600 text-lg md:text-xl font-mono ml-2">
                        Connecting to Identity Bridge...
                    </span>
                </h1>
            </div>

            <!-- Header Banner Image (Placed ABOVE the description) -->
            <div class="w-full h-48 md:h-64 lg:h-72 rounded-xl overflow-hidden shadow-inner border border-sky-100 bg-sky-50 mb-4">
                <img 
                    src="images/webbook-lib.jpg" 
                    alt="AIGC WebBook Banner" 
                    class="w-full h-full object-cover object-center"
                    onerror="this.src='https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&w=2400&q=80'"
                >
            </div>

            <!-- Existing Dynamic Header Description -->
            <div class="text-center">
                <p class="text-sm md:text-base font-semibold leading-relaxed text-sky-800 max-w-4xl mx-auto bg-sky-50/60 p-3 rounded-lg border border-sky-100">
                    <?= htmlspecialchars($hero_description); ?>
                </p>
            </div>
        </header>

        <!-- Main Content Area -->
        <main class="p-6 md:p-8 bg-slate-50/60">
            
            <!-- Directory Header -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">        
                <h2 class="text-lg font-bold text-sky-900 tracking-wider uppercase flex items-center gap-2">
                    WebBook Directory
                </h2>        
            </div>

            <!-- Directory Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 mb-8">
                <?php while($row = $rooms_result->fetch()): ?>
                    <div class="bg-sky-50/70 rounded-xl border border-sky-100 p-5 card-transition hover:shadow-md relative flex flex-col justify-between">
                        
                        <!-- Expand/Collapse Button -->
                        <button onclick="toggleDesc(<?= $row['id']; ?>)" 
                                class="absolute top-4 right-4 text-sky-400 hover:text-sky-700 transition-colors p-1">
                            <i id="icon-<?= $row['id']; ?>" class="fa-solid fa-chevron-down text-sm"></i>
                        </button>

                        <div>
                            <!-- Book Title -->
                            <div class="flex items-start gap-2.5 mb-3 pr-6">
                                <span class="text-sky-600 text-base mt-0.5"><i class="fa-solid fa-book"></i></span>
                                <h3 class="font-bold text-gray-900 text-base leading-snug">  
                                    <span class="text-xs font-normal text-sky-500 mr-1"><?= $row['id']; ?>.</span> 
                                    <?= htmlspecialchars($row['book_name']); ?> 
                                </h3>
                            </div>

                            <!-- Expandable Description -->
                            <div id="desc-<?= $row['id']; ?>" class="hidden-description mb-4 bg-white/80 p-3 rounded-lg border border-sky-100">
                                <p class="text-xs text-gray-700 leading-relaxed">
                                    <?= htmlspecialchars($row['description']); ?>
                                </p>
                            </div>
                        </div>

                        <!-- Link Button -->
                        <div class="mt-3 pt-2">
                            <a href="<?= htmlspecialchars($row['linkto']); ?>" target="_blank" rel="noopener noreferrer"   
                               class="inline-block bg-sky-600 text-white px-4 py-1.5 rounded-lg text-xs font-semibold hover:bg-sky-700 transition-all shadow-sm">
                               Link to
                            </a>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>

            <!-- Bottom Navigation Action Button -->
            <div class="flex justify-center mt-6">
                <a href="campus.php" 
                   class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-6 rounded-xl transition-all duration-300 shadow-md hover:shadow-lg flex items-center gap-2 text-xs uppercase tracking-wider">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Campus
                </a>
            </div>

        </main>

        <!-- Footer -->
        <footer class="text-center py-6 text-gray-400 text-xs border-t border-sky-50 bg-white">
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

                const identityDisplay = document.getElementById('studentIdentity');
                if (student.status === "Authorized") {
                    identityDisplay.innerText = `Scholar: ${student.first_name} ${student.last_name} (ID: ${student.id})`;
                    console.log("AIGC Campus: Identity Grounded", student);
                } else {
                    window.location.href = "login.php";
                }
            } catch (error) {
                console.error("Logic Error: Identity Bridge Failed", error);
                document.getElementById('studentIdentity').innerText = "Identity Error";
            }
        }

        window.onload = groundStudentIdentity;
    </script>
</body>
</html>
