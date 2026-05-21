<?php
// 1. Database Connection admin-offices.php
session_start();
require_once 'db-connect.php';
//require_once 'db-config.php';
$student_id = $_SESSION['user_id']    ?? 0;
$first_name = $_SESSION['first_name'] ?? '';
$last_name  = $_SESSION['admin_name']  ?? '';
$email      = $_SESSION['email']  ?? '';
 
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 2. SELECT description for the Hero Header
//$header_query = "SELECT description FROM campus_table WHERE room_name = 'header' LIMIT 1";
//$header_result = $conn->query($header_query);
//hero_description = ($header_result->num_rows > 0) ? $header_result->fetch_assoc()['description'] : "Welcome to GAC Central Campus.";
// CORRECT
//$hero_description = ($header_result->num_rows > 0) ? ...
// 4. SELECT rooms excluding header, sorted by group_order
//$rooms_query = "SELECT id, room_name, description, linkto FROM campus_table WHERE room_name <> 'header' ORDER BY group_order ASC LIMIT 24";
$rooms_query = "SELECT id, room_name, description, linkto, button_color FROM campus_table WHERE room_name <> 'header' and status = 'admin' ORDER BY room_group ASC";
$rooms_result = $conn->query($rooms_query);
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
    <style>
        .hero-gradient { background: linear-gradient(135deg, #f0fdf4 0%, #ffffff 100%); }
        .card-transition { transition: all 0.3s ease; }
        .hidden-description { display: none; }
    </style>
</head>
<body class="bg-gray-50 font-sans text-gray-800">

    <header class="hero-gradient border-b border-blue-100 py-12 px-6">
        <div class="max-w-5xl mx-auto text-center">
            <!--h1 class="text-3xl md:text-4xl font-bold text-green-800 mb-6">GAC Central Campus</h1-->
			 <h1 class="text-3xl md:text-4xl font-bold text-blue-800 mb-2">
             AI Gemini College Administration Offices
             <!--span class="text-green-600 text-xl md:text-2xl ml-4 font-mono"-->
            <!--?php echo " ID:". $student_id. " - ". $first_name . " " . $last_name; ?-->
			<span class="text-blue-600 text-xl md:text-2xl ml-4 font-mono">
    <?php 
        // We use the locally assigned variables, not the raw $_SESSION keys
        //echo " ID: " . htmlspecialchars($student_id) . " - " . htmlspecialchars($first_name) . " " . htmlspecialchars($last_name); 
		echo " ID: " . htmlspecialchars($student_id) . "/" . htmlspecialchars($last_name); 
    ?>
</span>
        </span>
    </h1>
            <!-- class="text-lg font-bold leading-relaxed text-green-700 max-w-4xl mx-auto">
                <?php echo htmlspecialchars($hero_description); ?>
            </p-->
        </div>
    </header>

    <main class="max-w-7xl mx-auto py-12 px-4">
        <!--h2 class="text-xl font-bold text-green-700 mb-8 tracking-widest uppercase">Campus Facility Directory</h2-->
		 
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">        
        <h2 class="text-xl font-bold text-blue-700 tracking-widest uppercase">Administration Facility Directory</h2>        
        <a href="logout.php" 
           class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-full transition-all duration-300 shadow-md hover:shadow-lg flex items-center gap-2 text-sm uppercase tracking-wider">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewviewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
            </svg>
            Logout
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        </div>
</main>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php while($row = $rooms_result->fetch_assoc()): ?>
                <div class="bg-blue-100 rounded-xl shadow-sm border border-black-100 p-6 card-transition hover:shadow-md relative">
                    
                    <button onclick="toggleDesc(<?php echo $row['id']; ?>)" 
                            class="absolute top-4 right-4 text-gray-400 hover:text-blue-600 transition-colors">
                        <i id="icon-<?php echo $row['id']; ?>" class="fa-solid fa-chevron-down"></i>
                    </button>

                    <div class="flex items-start gap-3 mb-4">
                        <span class="text-blue-600 text-xl"><i class="fa-solid fa-door-open"></i></span>
                        <!--h3 class="font-bold text-gray-900 text-lg">
                            <?php echo $row['id'] . ". " . htmlspecialchars($row['room_name']); ?>
                        </h3-->
<h3 class="font-bold text-gray-900 text-lg"> 
    <span class="text-xs font-normal text-gray-400"><?php echo $row['id']; ?>.</span> 
    <?php echo htmlspecialchars($row['room_name']); ?> 
</h3>

                    </div>

                    <div id="desc-<?php echo $row['id']; ?>" class="hidden-description mb-6">
                        <p class="text-sm text-gray-600 leading-relaxed">
                            <?php echo htmlspecialchars($row['description']); ?>
                        </p>
                    </div>

                    <div class="mt-auto">
                        <a href="<?php echo htmlspecialchars($row['linkto']); ?>" target="_blank" rel="noopener noreferrer"	
                         style="background-color: #<?php echo ltrim($row['button_color'], '#'); ?>;"						
                           
						 class="inline-block text-white px-4 py-2 rounded-lg text-sm font-medium hover:brightness-90 transition-all shadow-sm">
                           Link to
                        </a>
                    </div>
<!--------MODIFIED----------------------------------------------------------------------------------------------------------
<div class="mt-auto">
    <a href="<?php echo htmlspecialchars($row['linkto']); ?>" target="_blank" rel="noopener noreferrer"					
       style="background-color: #<?php echo ltrim($row['button_color'], '#'); ?>;"
       class="inline-block text-white px-4 py-2 rounded-lg text-sm font-medium hover:brightness-90 transition-all shadow-sm">
	          class="inline-block bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-700 transition-colors">
       Link to
    </a>
</div>
------------------------------------------------------------------------------------------------------------------->

</div>
            <?php endwhile; ?>
        </div>
    </main>

    <footer class="text-center py-10 text-gray-400 text-xs">
        &copy; 2026 AI Gemini  College. All rights reserved.
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
        const response = await fetch('get_student_session.php');
        const student = await response.json();

        if (student.status === "Authorized") {
            // Surgical UI Injection
            const identityDisplay = document.getElementById('studentIdentity');
            if (identityDisplay) {
                identityDisplay.innerText = `Scholar: ${student.first_name} ${student.last_name} (ID: ${student.id})`;
            }
            console.log("GAC Campus: Identity Grounded", student);
        } else {
            // Security Redirect if session is lost
            window.location.href = "login.php";
        }
    } catch (error) {
        console.error("Logic Error: Identity Bridge Failed", error);
    }
}

// Trigger on load
window.onload = groundStudentIdentity;
    </script>
	<script>function toHome() {window.location.href = 'admin-offices.php';}</script>
</body>
</html>