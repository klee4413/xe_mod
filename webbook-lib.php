<?php
// GAC FOUNDRY: WebBook Library (Asynchronous Identity Mode)
require_once 'db-connect.php'; 

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
        .hero-gradient { background: linear-gradient(135deg, #f0fdf4 0%, #ffffff 100%); }
        .card-transition { transition: all 0.3s ease; }
        .hidden-description { display: none; }
    </style>
</head>
<body class="bg-gray-50 font-sans text-gray-800">

    <header class="hero-gradient border-b border-green-100 py-12 px-6">
        <div class="max-w-5xl mx-auto text-center">
             <h1 class="text-3xl md:text-4xl font-bold text-blue-800 mb-2">
                 AIGC WebBook Library
                 <span id="studentIdentity" class="text-green-600 text-xl md:text-2xl ml-4 font-mono">
                     Connecting to Identity Bridge...
                 </span>
             </h1>
            <p class="text-lg font-bold leading-relaxed text-blue-700 max-w-4xl mx-auto">
                <?php echo htmlspecialchars($hero_description); ?>
            </p>
        </div>
    </header>

    <main class="max-w-7xl mx-auto py-12 px-4">
        <h2 class="text-xl font-bold text-blue-700 mb-8 tracking-widest uppercase">WebBook Directory</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php while($row = $rooms_result->fetch()): ?>
                <div class="bg-blue-100 rounded-xl shadow-sm border border-black-100 p-6 card-transition hover:shadow-md relative">
                    
                    <button onclick="toggleDesc(<?php echo $row['id']; ?>)" 
                            class="absolute top-4 right-4 text-gray-400 hover:text-green-600 transition-colors">
                        <i id="icon-<?php echo $row['id']; ?>" class="fa-solid fa-chevron-down"></i>
                    </button>

                    <div class="flex items-start gap-3 mb-4">
                        <span class="text-green-600 text-xl"><i class="fa-solid fa-door-open"></i></span>
                        <h3 class="font-bold text-gray-900 text-lg"> 
                            <span class="text-xs font-normal text-gray-400"><?php echo $row['id']; ?>.</span> 
                            <?php echo htmlspecialchars($row['book_name']); ?> 
                        </h3>
                    </div>

                    <div id="desc-<?php echo $row['id']; ?>" class="hidden-description mb-6">
                        <p class="text-sm text-gray-600 leading-relaxed">
                            <?php echo htmlspecialchars($row['description']); ?>
                        </p>
                    </div>

                    <div class="mt-auto">
                        <a href="<?php echo htmlspecialchars($row['linkto']); ?>" target="_blank" rel="noopener noreferrer"                    
                           class="inline-block bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-700 transition-colors">
                            Link to
                        </a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </main>

    <footer class="text-center py-10 text-gray-400 text-xs">
        &copy; 2026 Gemini AI College. All rights reserved.
    </footer>

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
                // Bridge call to the session-handler
                const response = await fetch('get_student_session.php');
                const student = await response.json();

                const identityDisplay = document.getElementById('studentIdentity');
                if (student.status === "Authorized") {
                    identityDisplay.innerText = `Scholar: ${student.first_name} ${student.last_name} (ID: ${student.id})`;
                    console.log("AIGC Campus: Identity Grounded", student);
                } else {
                    // Security Redirect if session is non-existent
                    window.location.href = "login.php";
                }
            } catch (error) {
                console.error("Logic Error: Identity Bridge Failed", error);
                document.getElementById('studentIdentity').innerText = "Identity Error";
            }
        }

        // Trigger on load
        window.onload = groundStudentIdentity;
    </script>
</body>
</html>