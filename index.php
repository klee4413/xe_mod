<!DOCTYPE html  >
<html lang="en">
<head>
    <link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> AI Gemini College |Landing Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Custom animation for the mobile menu */
        .animate-fade-in-down {
            animation: fadeInDown 0.3s ease-out;
        }
        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="bg-gray-50">
<header class="sticky top-0 z-50 w-full bg-[#BC4A3C] shadow-xl border-b border-white/10">
  <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center h-20">
            <div class="flex-shrink-0">
        <div class="bg-white p-1 rounded-lg shadow-inner">
            <img class="h-10 w-auto sm:h-12 rounded" 
                 src="images/agc-logo.png" 
                 alt="AIGC Logo"
                 onerror="this.src='https://ui-avatars.com/api/?name=AIGC&background=fff&color=A54B40'">
        </div>
      </div>

      <div class="absolute left-1/2 transform -translate-x-1/2 hidden lg:block">
        <h1 class="text-white text-xl md:text-2xl font-black tracking-[0.2em] uppercase whitespace-nowrap drop-shadow-md">
          AI Gemini College
        </h1>
      </div>

      <div class="hidden md:flex items-center space-x-6">
        <a href="signup.php" class="text-white border-b-2 border-transparent hover:border-white/60 px-1 py-2 text-sm font-bold transition-all">SIGN UP</a>
		 <a href="login.php" class="text-white border-b-2 border-transparent hover:border-white/60 px-1 py-2 text-sm font-bold transition-all">LOGIN</a>
        <!--a href="login.php" class="text-white hover:text-gray-200 px-1 py-2 text-sm font-bold transition-all">LOGIN</a-->
        <!--a href="campus.php" class="bg-[#40E0FF] hover:bg-cyan-300 text-[#0B0D10] px-8 py-2.5 rounded-full text-sm font-black shadow-lg hover:scale-105 transition-all active:scale-95">
           CLASS
        </a-->
      </div>

      <div class="md:hidden flex items-center">
        <button id="mobile-menu-button" class="text-white p-2 hover:bg-white/10 rounded-lg transition-colors">
          <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
          </svg>
        </button>
      </div>
    </div>

    <div id="mobile-menu" class="hidden md:hidden pb-6 space-y-4 px-2">
      <h2 class="text-white/70 text-center text-xs font-bold uppercase tracking-widest pt-2">Portal Access</h2>
      <a href="signup.php" class="block text-white text-center py-3 bg-white/10 rounded-xl font-bold border border-white/20">SIGN UP</a>
	    <a href="login.php" class="block text-white text-center py-3 bg-white/10 rounded-xl font-bold border border-white/20">LOGIN</a>
      <!--a href="login.php" class="block text-white text-center py-3 font-bold">LOGIN</a-->
      <!--a href="class.php" class="block bg-[#40E0FF] text-[#0B0D10] text-center py-4 rounded-xl font-black shadow-lg">GO TO CLASS</a-->
    </div>
  </nav>
</header>

<script>
  // Mobile Menu Logic
  const btn = document.getElementById('mobile-menu-button');
  const menu = document.getElementById('mobile-menu');

  btn.addEventListener('click', () => {
    menu.classList.toggle('hidden');
    menu.classList.toggle('animate-fade-in-down');
  });
</script>
<section class="relative bg-white overflow-hidden border-b border-gray-200">
  <div class="max-w-7xl mx-auto">
    <div class="relative z-10 pb-8 bg-white sm:pb-16 md:pb-20 lg:max-w-2xl lg:w-full lg:pb-28 xl:pb-32">
      
      <svg class="hidden lg:block absolute right-0 inset-y-0 h-full w-48 text-white transform translate-x-1/2" fill="currentColor" viewBox="0 0 100 100" preserveAspectRatio="none" aria-hidden="true">
        <polygon points="50,0 100,0 50,100 0,100" />
      </svg>

      <main class="mt-10 mx-auto max-w-7xl px-4 sm:mt-12 sm:px-6 md:mt-16 lg:mt-20 lg:px-8 xl:mt-28">
        <div class="sm:text-center lg:text-left">
          <h1 class="text-sm font-bold text-[#0B0D10] tracking-wide uppercase italic">
            Easy & Friendly AI Assisted Socratic Study & Learning
          </h1>
         
 <h1 class="text-4xl tracking-tight font-extrabold text-gray-900 sm:text-5xl md:text-6xl">
             <span class="block text-[#10862E] xl:inline">AI GEMINI COLLEGE</span>
			 <span class="block xl:inline text-[#0B0D10]">AI Professional Certificate Program</span>
			 <span class="block text-[#BC4A3C] xl:inline">Course Completion Certificate</span><br>
			<span class="block xl:inline text-[#0B0D10]"> to AA or BS Degree</span>
            
          </h1>
          <p class="mt-3 text-base text-gray-700 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0 font-medium">
            *Accelerate Your Future! Master at Your Own Pace.<br>
			<span class="text-gray-900 font-bold italic">*No set class time, No required login time.</span><br>
            <span class="text-gray-900 font-bold italic">*Smarter & Easier Learning by AI Assisted Study</span><br>
			 <span class="text-gray-900 font-bold italic">*Low Cost - Free Application, Interview and Textbook.</span>
          </p>
          
          <div class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start">
            <div class="rounded-md shadow">
              <a href="signup.php" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-black rounded-md text-white bg-[#BC4A3C] hover:bg-red-800 md:py-4 md:text-lg md:px-10 transition-all transform hover:scale-105">
                SIGN UP NOW
              </a>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>
  
  <div class="lg:absolute lg:inset-y-0 lg:right-0 lg:w-1/2">
    <div class="min-h-[400px] w-full bg-[#0B0D10] sm:h-72 md:h-96 lg:w-full lg:h-full flex items-center justify-center text-white p-4 sm:p-12">
        
        <div class="border-4 border-[#BC4A3C] p-6 sm:p-8 rounded-xl bg-gray-900/50 backdrop-blur-sm shadow-2xl max-w-sm sm:max-w-none">
            
            <h3 class="text-lg sm:text-2xl font-black mb-4 text-[#40E0FF] uppercase tracking-tighter italic leading-tight">
                Privacy and Security: Student keeps individual study data by login private data vault meets FERPA 2.0 requirements by ensuring student privacy and security.
            </h3>
          
            <div class="mt-6 flex items-center space-x-2 text-sm font-mono text-white-700">
                <span class="h-2 w-2 rounded-full bg-green-600 animate-pulse"></span>
                <span>https://aigeminicollege.org <br>System Status: Sovereign AIGC Active</span>
            </div>
        </div>
    </div>
</div>
</section>

<section class="bg-gray-50 py-12 border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10">
            <h2 class="text-3xl font-black text-gray-900 uppercase tracking-widest">The AIGC Architect's Blueprint</h2>
            <div class="h-1 w-20 bg-[#BC4A3C] mx-auto mt-2"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-[#BC4A3C]">
                <h4 class="font-black text-gray-900 mb-2">1. You Lead, AI Follows</h4>
                <p class="text-sm text-gray-600 italic">"Never ask AI to think for you. You give the orders to AI."</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-[#BC4A3C]">
                <h4 class="font-black text-gray-900 mb-2">2. Flexible Choice</h4>
                <p class="text-sm text-gray-600 italic">"Start a class or certificate course toward a degree destination."</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-[#BC4A3C]">
                <h4 class="font-black text-gray-900 mb-2">3. Logic Over Magic</h4>
                <p class="text-sm text-gray-600 italic">"Mistakes are Faulty Logic, leading to unwanted hallucinations."</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-[#BC4A3C]">
                <h4 class="font-black text-gray-900 mb-2">4. Protect Flow State</h4>
                <p class="text-sm text-gray-600 italic">"Zero Time Pressure to finish. Quality over speed."</p>
            </div>
        </div>
    </div>
</section>
<section class="py-20 bg-white">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
    <h2 class="text-3xl font-black text-gray-900 uppercase tracking-widest mb-2">AIGC Departments</h2>
    <div class="h-1 w-20 bg-[#BC4A3C] mx-auto mb-12"></div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
      <div class="group bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100 hover:scale-105 transition-transform">
        <div class="bg-blue-600 py-4 text-white font-black text-sm uppercase">Computer & AI Science<br>On going</div>
        <ul class="p-6 space-y-2 text-left text-sm text-gray-600">
          <li>• AI Ethics & Faulty Logic</li>
          <li>• Programming AI Assisted</li>
          <li>• Web Development</li>
          <li>• Database Management</li>
        </ul>
      </div>

      <div class="group bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100 hover:scale-105 transition-transform">
        <div class="bg-purple-600 py-4 text-white font-black text-sm uppercase">Data & AI Science<br> Coming soon</div>
        <ul class="p-6 space-y-2 text-left text-sm text-gray-600">
          <li>• Data Structures</li>
          <li>• AI Deep Learning</li>
          <li>• Probability</li>
          <li>• Statisics</li>
        </ul>
      </div>

      <div class="group bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100 hover:scale-105 transition-transform">
        <div class="bg-green-600 py-4 text-white font-black text-sm uppercase">Management & AI Science<br> Coming soon</div>
        <ul class="p-6 space-y-2 text-left text-sm text-gray-600">
          <li>• Business Analytics</li>
          <li>• Project Management</li>
          <li>• Financial Models</li>
          <li>• Marketing AI</li>
        </ul>
      </div>

      <div class="group bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100 hover:scale-105 transition-transform">
        <div class="bg-orange-500 py-4 text-white font-black text-sm uppercase">Law & AI Science<br> Coming soon</div>
        <ul class="p-6 space-y-2 text-left text-sm text-gray-600">
          <li>• Constitutional Law</li>
          <li>• AI Assisted Law Research</li>
          <li>• AI & Legal Ethics</li>
          <li>• Intellectual Property</li>
        </ul>
      </div>
    </div>
  </div>
</section>

<section class="py-16 bg-gray-50 border-y border-gray-200">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-3xl shadow-2xl overflow-hidden border-2 border-[#40E0FF]/30 flex flex-col lg:flex-row">
      
      <div class="lg:w-1/3 bg-[#0B0D10] p-10 flex flex-col items-center justify-center text-center border-r border-gray-800">
        <div class="w-32 h-32 mb-6 bg-[#BC4A3C] rounded-full flex items-center justify-center shadow-[0_0_30px_rgba(188,74,60,0.4)]">
          <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-8.06 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946 8.06 3.42 3.42 0 010 4.438 3.42 3.42 0 00-1.946 8.06 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-8.06 3.42 3.42 0 010-4.438z" />
          </svg>
        </div>
        <h3 class="text-white text-2xl font-black uppercase tracking-tighter italic">AI Foundation Professional Certification<br> $990 Opening Special</h3>
        <!--p class="text-[#40E0FF] font-bold text-sm mt-2">Professional Certification</p-->
      </div>

      <div class="lg:w-2/3 p-10">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
          <div>
            <h2 class="text-3xl font-black text-gray-800 uppercase tracking-tight">AIGC AI Professional Certificate</h2>
            <p class="text-[#BC4A3C] font-bold uppercase text-xs tracking-[0.1em] mt-1">1.AI Ethics 2.Google Drive 3.Prompt 4.NotebookLM</p>
          </div>
          <div class="mt-4 md:mt-0 bg-gray-100 px-6 py-2 rounded-full border border-gray-200">
            <span class="text-gray-900 font-black">12 UNITS</span>
          </div>
        </div>
		<!------------------------------------------------------------------------------->
		 <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
          <div>
            <h2 class="text-3xl font-black text-gray-800 uppercase tracking-tight">AIGC Auto Machenic Certificate</h2>
            <p class="text-[#BC4A3C] font-bold uppercase text-xs tracking-[0.1em] mt-1">1.Automotive Fundamentals 2.Basic Electrical System 3.Braking System</p>
          </div>
          <div class="mt-4 md:mt-0 bg-gray-100 px-6 py-2 rounded-full border border-gray-200">
            <span class="text-gray-900 font-black">9 UNITS</span>
          </div>
        </div>
		<!------------------------------------------------------------------------------->

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div class="space-y-4">
            <div class="flex items-start">
              <div class="h-6 w-6 rounded bg-[#BC4A3C]/10 flex items-center justify-center mt-1 mr-3 shrink-0">
                <span class="text-[#BC4A3C] font-bold text-xs">01</span>
              </div>
              <p class="text-gray-700 text-sm font-medium"><span class="font-bold">Core Essential Fundamental Courses </span></p>
            </div>
            <div class="flex items-start">
              <div class="h-6 w-6 rounded bg-[#BC4A3C]/10 flex items-center justify-center mt-1 mr-3 shrink-0">
                <span class="text-[#BC4A3C] font-bold text-xs">02</span>
              </div>
              <p class="text-gray-700 text-sm font-medium"><span class="font-bold">Fully Transferable:</span> All units apply directly toward AIGC degree programs.</p>
            </div>
          </div>

          <div class="space-y-4">
            <div class="flex items-start">
              <div class="h-6 w-6 rounded bg-green-100 flex items-center justify-center mt-1 mr-3 shrink-0">
                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
              </div>
              <p class="text-gray-800 text-sm font-small">No Degree or Diploma Required for Admission. No Application and Free  Online Interview.</p>
            </div> 
            <div class="flex items-start">
              <div class="h-6 w-6 rounded bg-green-100 flex items-center justify-center mt-1 mr-3 shrink-0">
                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
              </div>
              <p class="text-gray-800 text-sm font-small">Self-Paced AI Assisited Learning with Quiz, Video Overview, Chat Dialog, Images and more.</p>
            </div>
          </div>
        </div>

        <div class="mt-8 pt-6 border-t border-gray-100 flex items-center justify-between">
            <!--p class="text-sm text-gray-500 italic">"The fastest path to proficiency by AI assistance."</p-->
            <a href="wb-prompt1.php" class="text-[#BC4A3C] font-black text-sm uppercase hover:underline">*More on AI Learning Courses→</a>
        <!--/div-->
		 <!--div class="mt-8 pt-6 border-t border-gray-100 flex items-center justify-between"-->
            <!--p class="text-xs text-gray-500 italic">"The shortest path to AI proficiency for the Sovereign Scholar."</p-->
            <a href="auto-course1.html" class="text-[#BC4A3C] font-black text-sm uppercase hover:underline">*More on Automotive courses →</a>
        </div>
      </div>

    </div>
  </div>
</section>

<section class="py-20 bg-gray-900 text-white">
  <div class="max-w-4xl mx-auto px-6 text-center">
    <h2 class="text-4xl font-black mb-8">Welcome to <span class="text-[#40E0FF]">AI GEMINI COLLEGE</span></h2>
    <p class="text-xl text-gray-300 leading-relaxed mb-6">
      We are standing at the threshold of a new era in human history—the Age of Artificial Intelligence. At AIGC, we don't just teach the future; we prove AI and  build with it.
    </p>
    <p class="text-lg text-gray-400 italic">
      "AIGC curriculum and textbook is uniquely designed by HI and by AI-assisted learning, ensuring AIGC students are not just consumers of technology, but its masters."
    </p>
    <div class="mt-10">.
      <p class="text-[#40E0FF]">The President, Keun Lee, B.S. in Business & Economics, Yonsei Univ., Korea. <br> M.S. in Computer Science and M.S. in Statistics, Oklahoma State University, Stillwater, Ok. U.S.A.</p>
    </div>
  </div>
</section>
<section class="py-24 bg-white">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    
    <div class="text-center mb-16">
      <h2 class="text-sm font-black text-[#BC4A3C] tracking-[0.3em] uppercase mb-2">The Process</h2>
      <h3 class="text-4xl font-extrabold text-[#0B0D10] tracking-tight">Your Journey to AI Mastery</h3>
      <div class="h-1.5 w-24 bg-[#BC4A3C] mx-auto mt-4 rounded-full"></div>
    </div>

    <div class="relative">
      <div class="hidden lg:block absolute top-12 left-0 w-full h-0.5 bg-gray-100 z-0"></div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 relative z-10">
        
        <div class="flex flex-col items-center text-center group">
          <div class="w-24 h-24 rounded-2xl bg-white border-2 border-gray-100 shadow-xl flex items-center justify-center mb-6 group-hover:border-[#BC4A3C] transition-all duration-500 transform group-hover:-translate-y-2">
            <span class="text-3xl font-black text-gray-200 group-hover:text-[#BC4A3C] transition-colors">01</span>
          </div>
          <h4 class="text-xl font-bold text-gray-900 mb-2">Quick Register</h4>
          <p class="text-gray-500 text-sm leading-relaxed px-4">
            Verify your email and create your student profile in under 60 seconds.
          </p>
        </div>

        <div class="flex flex-col items-center text-center group">
          <div class="w-24 h-24 rounded-2xl bg-white border-2 border-gray-100 shadow-xl flex items-center justify-center mb-6 group-hover:border-[#40E0FF] transition-all duration-500 transform group-hover:-translate-y-2">
            <span class="text-3xl font-black text-gray-200 group-hover:text-[#40E0FF] transition-colors">02</span>
          </div>
          <h4 class="text-xl font-bold text-gray-900 mb-2">Speedy Assessment</h4>
          <p class="text-gray-500 text-sm leading-relaxed px-4">
            Complete a short online interview to align your goals with our AI assisted education.
          </p>
        </div>

        <div class="flex flex-col items-center text-center group">
          <div class="w-24 h-24 rounded-2xl bg-white border-2 border-gray-100 shadow-xl flex items-center justify-center mb-6 group-hover:border-[#BC4A3C] transition-all duration-500 transform group-hover:-translate-y-2">
            <span class="text-3xl font-black text-gray-200 group-hover:text-[#BC4A3C] transition-colors">03</span>
          </div>
          <h4 class="text-xl font-bold text-gray-900 mb-2">Instant Acceptance</h4>
          <p class="text-gray-500 text-sm leading-relaxed px-4">
            Receive your digital admission letter and student ID in 48 hours via the AIGC Dean email reply.
          </p>
        </div>

        <div class="flex flex-col items-center text-center group">
          <div class="w-24 h-24 rounded-2xl bg-[#0B0D10] shadow-2xl flex items-center justify-center mb-6 border-2 border-[#40E0FF] animate-pulse">
            <svg class="w-10 h-10 text-[#40E0FF]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
            </svg>
          </div>
          <h4 class="text-xl font-bold text-gray-900 mb-2">Begin Learning</h4>
          <p class="text-gray-500 text-sm leading-relaxed px-4">
            Access your AIGC published free textbook and start your AI assisted easy and pleasant learning journey.
          </p>
        </div>

      </div>
    </div>

    <!--div class="mt-20 p-8 bg-gray-50 rounded-2xl border-l-4 border-[#BC4A3C] max-w-3xl mx-auto">
      <h5 class="font-black text-gray-900 uppercase text-xs tracking-widest mb-2">Sovereign Notice</h5>
      <p class="text-sm text-gray-600 italic">
        "Admission is open to all who possess the drive to lead. No prior degree or diploma is required for our Certificate or Associate tracks. Your logic is your credential."
      </p>
    </div-->

  </div>
</section>
<!--section class="py-24 bg-gray-50">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    
    <div class="flex flex-col md:flex-row md:items-end md:justify-between mb-12">
      <div class="max-w-2xl">
        <h2 class="text-sm font-black text-[#BC4A3C] tracking-[0.3em] uppercase mb-2">The Curriculum</h2>
        <h3 class="text-4xl font-extrabold text-[#0B0D10] tracking-tight">Mastering the 10/90 Rule</h3>
        <p class="mt-4 text-gray-600 font-medium italic">"Proprietary textbooks designed to transform how you think, not just how you prompt."</p>
      </div>
      <div class="mt-6 md:mt-0">
        <a href="https://store.geminiaicollege.org" class="inline-flex items-center text-[#BC4A3C] font-black hover:underline group">
          VIEW ALL 18 BOOKS 
          <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
        </a>
      </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
      
      <a href="https://store.geminiaicollege.org" class="group block">
        <div class="relative bg-white rounded-xl shadow-lg p-4 transition-all duration-500 group-hover:-translate-y-4 group-hover:shadow-2xl border-b-4 border-transparent group-hover:border-[#BC4A3C]">
          <div class="aspect-[3/4] overflow-hidden rounded-lg bg-gray-200 mb-6">
            <img src="https://your-new-bucket.s3.amazonaws.com/book-ethics.jpg" 
                 alt="AI Ethics & Faulty Logic" 
                 class="w-full h-full object-cover"
                 onerror="this.src='https://placehold.co/400x600/0B0D10/40E0FF?text=AI+Ethics'">
          </div>
          <h4 class="text-lg font-black text-gray-900 leading-tight">AI Ethics & Faulty Logic</h4>
          <p class="text-xs text-[#BC4A3C] font-bold mt-2 uppercase tracking-widest">Core Requirement</p>
        </div>
      </a>

      <a href="https://store.geminiaicollege.org" class="group block">
        <div class="relative bg-white rounded-xl shadow-lg p-4 transition-all duration-500 group-hover:-translate-y-4 group-hover:shadow-2xl border-b-4 border-transparent group-hover:border-[#40E0FF]">
          <div class="aspect-[3/4] overflow-hidden rounded-lg bg-gray-200 mb-6">
            <img src="https://your-new-bucket.s3.amazonaws.com/book-notebooklm.jpg" 
                 alt="Smart Learn with NotebookLM" 
                 class="w-full h-full object-cover"
                 onerror="this.src='https://placehold.co/400x600/0B0D10/40E0FF?text=NotebookLM'">
          </div>
          <h4 class="text-lg font-black text-gray-900 leading-tight">Smart Learn with NotebookLM</h4>
          <p class="text-xs text-[#BC4A3C] font-bold mt-2 uppercase tracking-widest">Lab Guide</p>
        </div>
      </a>

      <a href="https://store.geminiaicollege.org" class="group block">
        <div class="relative bg-white rounded-xl shadow-lg p-4 transition-all duration-500 group-hover:-translate-y-4 group-hover:shadow-2xl border-b-4 border-transparent group-hover:border-[#BC4A3C]">
          <div class="aspect-[3/4] overflow-hidden rounded-lg bg-gray-200 mb-6">
            <img src="https://your-new-bucket.s3.amazonaws.com/book-prompting.jpg" 
                 alt="Prompt to Talk to AI" 
                 class="w-full h-full object-cover"
                 onerror="this.src='https://placehold.co/400x600/0B0D10/40E0FF?text=AI+Prompting'">
          </div>
          <h4 class="text-lg font-black text-gray-900 leading-tight">Prompt to Talk to AI</h4>
          <p class="text-xs text-[#BC4A3C] font-bold mt-2 uppercase tracking-widest">Fundamental Mastery</p>
        </div>
      </a>

      <a href="https://store.geminiaicollege.org" class="group block">
        <div class="relative bg-[#0B0D10] rounded-xl shadow-lg p-4 transition-all duration-500 group-hover:-translate-y-4 group-hover:shadow-2xl border-b-4 border-[#40E0FF]">
          <div class="aspect-[3/4] overflow-hidden rounded-lg bg-gray-800 mb-6 flex items-center justify-center">
            <svg class="w-16 h-16 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
          </div>
          <h4 class="text-lg font-black text-white leading-tight">Other Books</h4>
          <p class="text-xs text-[#40E0FF] font-bold mt-2 uppercase tracking-widest">Library Access</p>
        </div>
      </a>

    </div>
  </div>
</section-->
   <!-- Logic Tier II: Infrastructure -->
    <section id="infrastructure" class="py-24 px-6 bg-slate-900 text-white overflow-hidden relative">
        <div class="max-w-7xl mx-auto relative z-10">
            <div class="mb-20">
                <!--h2 class="text-xs font-black text-emerald-400 uppercase tracking-[0.3em] mb-4">Logic Tier II</h2-->
                <h3 class="text-4xl font-extrabold tracking-tight">Institutional Infrastructure</h3>
                <p class="text-slate-400 mt-4 max-w-4xl">AI technology is utilized on advanced Learning Management Systems including real-time 
Socratic study and learning, unlimited instant quiz practice, not for evaluation but for practical study review to enhance natural memorization, 
online comprehensive exams, and AI prompt and computer language lab to increase muscle memory of study with minimum instructor's supervision.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="space-y-8">
                    <div class="flex gap-6 items-start">
                        <div class="text-3xl font-black text-emerald-500 mono">01</div>
                        <div>
                            <h5 class="text-xl font-bold mb-2">Zero-Friction Admissions and Learning</h5>
                            <p class="text-slate-400 text-sm">No SAT, GMAT, or GRE. Potential is measured by Grit and Logic via a 10-minute online interview and review of simple education and career experience. AIGC welcomes everybody, young or old.</p>
                        </div>
                    </div>
                    <div class="flex gap-6 items-start">
                        <div class="text-3xl font-black text-emerald-500 mono">02</div>
                        <div>
                            <h5 class="text-xl font-bold mb-2">Hybrid Integration Model</h5>
                            <p class="text-slate-400 text-sm">Artificial Intelligence (AI) 
combines human cognitive (HI) strengths (context, empathy, ethical judgment) by AI's computational 
power (speed, data processing) to improve decision-making and efficient performance.</p>
                        </div>
                    </div>
                    <div class="flex gap-6 items-start">
                        <div class="text-3xl font-black text-emerald-500 mono">03</div>
                        <div>
                            <h5 class="text-xl font-bold mb-2">Knowledge Hub Architecture</h5>
                            <p class="text-slate-400 text-sm">Learning via Knowledge Vault: AIGC uses AI based semi-automated learning systems, 
referred to as a "Knowledge Vault," to search, gather, and verify facts from various knowledge database.</p>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-800/50 p-10 rounded-[3rem] border border-slate-700 backdrop-blur-sm">
                    <div class="text-center">
                        <p class="text-emerald-400 font-bold mb-2 uppercase tracking-widest text-[10px]">AI Foundation Certificate Course: Opening Special $990 for 12 Units</p>
                        <h4 class="text-3xl font-black mb-6">The Holy Trinity in AI Learning</h4>
                        <div class="space-y-3">
                            <div class="h-2 bg-slate-700 rounded-full w-full"></div>
                            <div class="h-2 bg-slate-700 rounded-full w-3/4 mx-auto"></div>
                            <div class="h-2 bg-slate-700 rounded-full w-1/2 mx-auto"></div>
                        </div>
                        <div class="mt-8 grid grid-cols-3 gap-4">
                            <div class="aspect-square bg-emerald-500/20 rounded-2xl flex items-center justify-center text-emerald-400 font-bold">Smart Learning<br>with<br>NotebookLM<br>in Google Drive</div>
                            <div class="aspect-square bg-blue-500/20 rounded-2xl flex items-center justify-center text-blue-400 font-bold">Verified Learning<br>from<br> AI Ethics & <br>Faulty Logic</div>
                            <div class="aspect-square bg-purple-500/20 rounded-2xl flex items-center justify-center text-purple-400 font-bold">Learn Prompt <br>for<br>Fluent Interface<br>with AI</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Decorative elements -->
        <div class="absolute top-0 right-0 w-96 h-96 bg-emerald-500/10 rounded-full blur-[120px]"></div>
    </section>

<section class="py-24 bg-[#0B0D10] text-white">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-16">
      <h2 class="text-[#40E0FF] font-black uppercase tracking-[0.3em] text-sm mb-4">Academic Specifications</h2>
      <h3 class="text-4xl font-extrabold tracking-tight">The Sovereign Learning Policy</h3>
      <div class="h-1 w-24 bg-[#BC4A3C] mx-auto mt-4"></div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
      <div class="p-8 border border-white/10 rounded-2xl bg-white/5 backdrop-blur-sm">
        <div class="text-[#BC4A3C] mb-4">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
        </div>
        <h4 class="text-xl font-bold mb-4">Lifelong Learning</h4>
        <p class="text-gray-400 text-sm leading-relaxed">
          We prioritize the quality of your "Flow State." Our <strong>Zero Time Pressure</strong> model ensures your brain truly grows through logic-based training, not rote memorization.
        </p>
      </div>

      <div class="p-8 border border-[#40E0FF]/30 rounded-2xl bg-white/5 backdrop-blur-sm shadow-[0_0_20px_rgba(64,224,255,0.1)]">
        <div class="text-[#40E0FF] mb-4">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <h4 class="text-xl font-bold mb-4">Failure is not an option</h4>
        <p class="text-gray-400 text-sm leading-relaxed mb-4">
          By utilizing AI-assisted refinement, failure is removed from the equation. Students are guided until mastery is achieved.
        </p>
        <div class="text-xs font-mono text-gray-500 bg-black/30 p-3 rounded">
            A: 90% | B: 80% | C: 70%
        </div>
      </div>

      <div class="p-8 border border-white/10 rounded-2xl bg-white/5 backdrop-blur-sm">
        <div class="text-[#BC4A3C] mb-4">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path></svg>
        </div>
        <h4 class="text-xl font-bold mb-4">Academic Units</h4>
        <ul class="text-gray-400 text-sm space-y-2">
          <li>• <span class="text-white">Associate:</span> 60 Units (20 Basic Core)</li>
          <li>• <span class="text-white">Bachelor's:</span> 120 Units (40 Total)</li>
          <li>• <span class="text-white">Certificates:</span> 12 Transferable Units</li>
        </ul>
      </div>
    </div>
  </div>
</section>

<footer class="bg-[#BC4A3C] text-white pt-16 pb-8">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
      <div class="col-span-1 md:col-span-2">
        <h4 class="text-2xl font-black tracking-tighter uppercase mb-4">Gemini AI College</h4>
        <p class="text-white/80 max-w-sm text-sm leading-relaxed italic">
          "The first truly AI-Assisted online education. We don't just teach the tools; we teach the logic that masters the tools. AIGC operates on a Decentralized Knowledge Vault. The student maintains individual private 'Knowledge Repository.' This architecture exceeds FERPA 2.0 requirements by ensuring that no sensitive student study material is stored on AIGC-controlled servers.""
        </p>
      </div>

      <div>
        <h5 class="font-black uppercase text-xs tracking-widest mb-4 opacity-80">Resources</h5>
        <ul class="space-y-2 text-sm">         
		  <li><a href="new-edu1.html" class="hover:underline">Education Policy</a></li>
          <li><a href="about-agc.php" class="hover:underline">About AIGC</a></li>
		   <li><a href="https://store.aigemincollege.org" class="hover:underline">AIGC Store</a></li>
		  <li><a href="privacy.php" class="hover:underline">Privacy</a></li>
          <li><a href="#" class="hover:underline">Terms of Use</a></li>
        </ul>
      </div>

      <div>
        <h5 class="font-black uppercase text-xs tracking-widest mb-4 opacity-80">Location</h5>
        <p class="text-sm">West Los Angeles Area (Temporary)</p>
        <p class="text-sm opacity-80 mt-1">Online Campus </p>
      </div>
    </div>

    <div class="border-t border-white/10 pt-8 flex flex-col md:flex-row justify-between items-center text-xs opacity-60">
      <p>© 2026 AI GEMINI COLLEGE. All Rights Reserved.</p>
      <div class="flex space-x-6 mt-4 md:mt-0">
        <a href="privacy.php" class="hover:text-white transition-colors">Privacy & Cookie Policy</a>
        <a href="tos.php" class="hover:text-white transition-colors">Terms of Service</a>
      </div>
    </div>
  </div>
</footer>


</body>
</html>
 