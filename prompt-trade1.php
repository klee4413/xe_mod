<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deep Confluence: Algorithmic Trading Framework</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&family=JetBrains+Mono:wght@400;700&display=swap');
        
        :root {
            --slate-950: #020617;
            --emerald-500: #10b981;
            --gold: #fbbf24;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--slate-950);
            color: #f1f5f9;
            overflow: hidden;
        }

        .mono { font-family: 'JetBrains Mono', monospace; }

        .slide {
            height: 100vh;
            width: 100vw;
            display: none;
            padding: 6rem 4rem 4rem 4rem; /* Adjusted top padding for header */
            flex-direction: column;
            justify-content: center;
            background: radial-gradient(circle at 0% 0%, rgba(16, 185, 129, 0.03) 0%, transparent 50%),
                        radial-gradient(circle at 100% 100%, rgba(59, 130, 246, 0.03) 0%, transparent 50%);
        }

        .slide.active { display: flex; animation: slideIn 0.5s ease-out; }

        @keyframes slideIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .code-block {
            background: rgba(15, 23, 42, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-left: 4px solid var(--emerald-500);
        }

        .nav-btn {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.2s;
        }

        .nav-btn:hover { background: rgba(16, 185, 129, 0.1); border-color: var(--emerald-500); }

        .chart-container {
            position: relative;
            width: 100%;
            height: 380px;
        }

        .indicator-tag {
            font-size: 0.7rem;
            font-weight: 800;
            padding: 2px 8px;
            border-radius: 4px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .top-nav {
            background: rgba(2, 6, 23, 0.8);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }
    </style>
</head>
<body class="antialiased">

    <!-- NEW: Top Fixed Header with Display Button -->
    <header class="fixed top-0 left-0 right-0 z-[100] top-nav h-16 px-8 flex items-center justify-between">
        <div class="flex items-center gap-4">
            <!--div class="w-8 h-8 bg-emerald-500 rounded flex items-center justify-center text-slate-950 font-black italic">Σ</div-->
			<div class="w-8 h-8 bg-emerald-500 rounded flex items-center justify-center text-slate-950 font-black italic">AIGC</div>
            <div>
                <!--p class="text-[10px] font-black text-emerald-500 uppercase tracking-widest leading-none">Deep Confluence</p-->
				 <p class="text-[10px] font-black text-emerald-500 uppercase tracking-widest leading-none">AI GEMINI COLLEGE</p>
                <p class="text-xs text-slate-400 font-bold">Trading Algorithm v2.4</p>
            </div>
        </div>

        <div class="flex items-center gap-6">
            <div class="hidden md:flex items-center gap-3">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Logic Engine: Active</span>
            </div>
             
			
			 <a href="campus.php" target="_blank" style="text-decoration: none;">
             <button class="bg-emerald-600 hover:bg-emerald-700 text-slate-950 px-10 py-4 rounded-full font-black uppercase tracking-widest text-xs transition transform hover:scale-105 active:scale-95 shadow-xl shadow-emerald-500/20">
             <i class="fa-solid fa-wand-magic-sparkles"></i> Back to Campus
             </button>
             </a>
			
			
			
        </div>
    </header>

    <!-- Slide 1: Introduction -->
    <div class="slide active" id="slide-1">
        <div class="max-w-4xl mx-auto text-center">
            <span class="indicator-tag bg-emerald-500/20 text-emerald-400 mb-6 inline-block">Methodology Overview</span>
            <h1 class="text-6xl font-black tracking-tighter mb-6">DEEP <span class="text-emerald-500">CONFLUENCE</span></h1>
            <p class="text-xl text-slate-400 leading-relaxed mb-12">
                Why use one indicator when you can engineer a stack? The algorithm combines <span class="text-white font-bold">EMA (Trend)</span>, <span class="text-white font-bold">VWAP (Value)</span>, and <span class="text-white font-bold">MACD (Momentum)</span> to eliminate market noise and capture high-probability entries.
            </p>
            <div class="grid grid-cols-3 gap-6 text-left">
                <div class="p-6 code-block rounded-2xl">
                    <h4 class="font-bold text-emerald-500 mb-2">EMA</h4>
                    <p class="text-xs text-slate-500 italic">Structural Integrity</p>
                </div>
                <div class="p-6 code-block rounded-2xl">
                    <h4 class="font-bold text-blue-500 mb-2">VWAP</h4>
                    <p class="text-xs text-slate-500 italic">Institutional Value</p>
                </div>
                <div class="p-6 code-block rounded-2xl">
                    <h4 class="font-bold text-purple-500 mb-2">MACD</h4>
                    <p class="text-xs text-slate-500 italic">Momentum Velocity</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Slide 2: EMA (50) - The Compass -->
    <div class="slide" id="slide-2">
        <div class="max-w-5xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div>
                <h2 class="text-4xl font-black mb-6 italic text-emerald-500">The Compass</h2>
                <h3 class="text-2xl font-bold mb-4 italic text-slate-300">Variable: $ema / $p_ema</h3>
                <p class="text-slate-400 mb-8">
                    The 50-period Exponential Moving Average acts as our primary directional filter. We don't guess the trend; we calculate its slope.
                </p>
                <div class="p-6 code-block rounded-xl mono text-sm text-emerald-400">
                    $trendUp = $ema > $p_ema;
                </div>
                <p class="mt-6 text-sm text-slate-500 leading-relaxed">
                    By comparing current EMA to its previous state, the algorithm confirms structural strength. If the slope is negative, all long signals are discarded.
                </p>
            </div>
            <div class="bg-slate-900/50 p-8 rounded-3xl border border-white/5 text-center">
                <div class="w-full h-1 bg-slate-800 relative mb-24">
                    <div class="absolute -top-12 left-0 text-[10px] text-slate-500 uppercase font-bold">Past</div>
                    <div class="absolute -top-12 right-0 text-[10px] text-slate-500 uppercase font-bold">Present</div>
                    <div class="h-1 bg-emerald-500 absolute left-0 top-0 transition-all duration-1000" style="width: 100%; transform: rotate(-15deg); transform-origin: left;"></div>
                </div>
                <p class="text-xs font-bold text-emerald-500 uppercase tracking-widest">Slope: Positive (Trend Up)</p>
            </div>
        </div>
    </div>

    <!-- Slide 3: VWAP - Institutional Value -->
    <div class="slide" id="slide-3">
        <div class="max-w-5xl mx-auto text-center">
            <h2 class="text-4xl font-black mb-6 text-blue-400">Institutional Value</h2>
            <h3 class="text-2xl font-bold mb-10 italic text-slate-300">Variable: $vwap</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                <div class="text-left">
                    <p class="text-slate-400 mb-6">
                        VWAP represents the true "Fair Value" of an asset weighted by volume. The algorithm uses this to ensure we aren't buying extended prices.
                    </p>
                    <div class="p-6 code-block rounded-xl mono text-sm text-blue-400 border-blue-500">
                        $aboveValue = $price > $vwap;
                    </div>
                </div>
                <div class="p-10 bg-blue-500/5 rounded-[3rem] border border-blue-500/20">
                    <div class="space-y-4">
                        <div class="flex justify-between items-center text-xs font-bold uppercase tracking-widest text-slate-500 border-b border-white/5 pb-2">
                            <span>Status</span>
                            <span>Logic Check</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-blue-400">Above VWAP</span>
                            <span class="text-emerald-500 font-bold">✓ Institutional Support</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-rose-400">Below VWAP</span>
                            <span class="text-rose-500 font-bold">✗ Distribution Zone</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Slide 4: MACD - The Gatekeeper -->
    <div class="slide" id="slide-4">
        <div class="max-w-4xl mx-auto">
            <h2 class="text-4xl font-black mb-6 text-purple-400">The Gatekeeper</h2>
            <h3 class="text-2xl font-bold mb-8 italic text-slate-300">Variable: $macd_f / $macd_s</h3>
            <p class="text-slate-400 mb-10 leading-relaxed text-lg">
                Trend and Value define the environment; MACD defines the <span class="text-white font-bold italic">Timing</span>. By tracking the Fast (12) and Slow (26) lines, we identify the exact moment momentum shifts in our favor.
            </p>
            <div class="bg-slate-900 rounded-3xl p-8 border border-purple-500/20">
                <div class="flex items-center gap-8">
                    <div class="flex-1 p-6 bg-slate-950 rounded-2xl border border-white/5">
                        <p class="text-[10px] uppercase font-bold text-slate-500 mb-4 tracking-widest">Code Logic</p>
                        <p class="mono text-purple-400">$macdBullish = $macd_f > $macd_s;</p>
                    </div>
                    <div class="w-24 h-24 rounded-full border-4 border-dashed border-purple-500/50 flex items-center justify-center animate-pulse">
                        <span class="text-2xl">⚡</span>
                    </div>
                    <div class="flex-1 p-6 bg-slate-950 rounded-2xl border border-white/5">
                        <p class="text-[10px] uppercase font-bold text-slate-500 mb-4 tracking-widest">Trade Status</p>
                        <p class="font-black text-emerald-500">MOMENTUM GO</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Slide 5: RSI - The Exhaustion Metric -->
    <div class="slide" id="slide-5">
        <div class="max-w-5xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div class="order-2 lg:order-1">
                <div class="h-64 flex flex-col justify-between border-l-2 border-slate-800 pl-8">
                    <div class="text-rose-500 font-black">75 — OVERBOUGHT (SELL)</div>
                    <div class="h-px bg-slate-800 w-full relative">
                        <div class="absolute -top-3 left-4 bg-slate-950 px-2 text-[10px] text-slate-500">NEUTRAL ZONE</div>
                    </div>
                    <div class="text-emerald-500 font-black">25 — OVERSOLD (BUY)</div>
                </div>
            </div>
            <div class="order-1 lg:order-2">
                <h2 class="text-4xl font-black mb-6 text-orange-400">The Exhaustion Metric</h2>
                <h3 class="text-2xl font-bold mb-6 italic text-slate-300">Variable: $rsi</h3>
                <p class="text-slate-400 mb-8 italic">
                    "The algorithm's safety valve."
                </p>
                <div class="space-y-4">
                    <div class="p-4 code-block rounded-xl text-xs mono text-rose-400 border-rose-500">
                        elseif ($rsi > 75) { return 'SELL'; }
                    </div>
                    <div class="p-4 code-block rounded-xl text-xs mono text-emerald-400">
                        elseif ($rsi < 25) { return 'BUY'; }
                    </div>
                </div>
                <p class="mt-8 text-sm text-slate-500">
                    Regardless of other signals, extreme RSI overrides suggest a reversal is imminent. This protects capital from chasing overextended moves.
                </p>
            </div>
        </div>
    </div>

    <!-- Slide 6: Algorithm Reasoning (BUY) -->
    <div class="slide" id="slide-6">
        <div class="max-w-4xl mx-auto">
            <h2 class="text-4xl font-black mb-8 text-center uppercase tracking-tighter">Stackable Confirmation <span class="text-emerald-500">(BUY)</span></h2>
            <div class="space-y-4">
                <div class="flex gap-4 items-center p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl">
                    <div class="w-8 h-8 rounded-full bg-emerald-500 flex items-center justify-center font-bold">1</div>
                    <p class="mono text-sm">price > ema <span class="text-slate-500 ml-4">// Holding above structure</span></p>
                </div>
                <div class="flex gap-4 items-center p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl">
                    <div class="w-8 h-8 rounded-full bg-emerald-500 flex items-center justify-center font-bold">2</div>
                    <p class="mono text-sm">trendUp <span class="text-slate-500 ml-4">// EMA is ascending</span></p>
                </div>
                <div class="flex gap-4 items-center p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl">
                    <div class="w-8 h-8 rounded-full bg-emerald-500 flex items-center justify-center font-bold">3</div>
                    <p class="mono text-sm">low <= (ema * 1.002) <span class="text-slate-500 ml-4">// Valid Pullback detected</span></p>
                </div>
                <div class="flex gap-4 items-center p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl">
                    <div class="w-8 h-8 rounded-full bg-emerald-500 flex items-center justify-center font-bold">4</div>
                    <p class="mono text-sm">macdBullish <span class="text-slate-500 ml-4">// Positive Momentum Crossover</span></p>
                </div>
                <div class="flex gap-4 items-center p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl">
                    <div class="w-8 h-8 rounded-full bg-emerald-500 flex items-center justify-center font-bold">5</div>
                    <p class="mono text-sm">aboveValue <span class="text-slate-500 ml-4">// Institutional Price Support</span></p>
                </div>
            </div>
            <div class="mt-8 text-center">
                <p class="text-emerald-500 font-black text-2xl animate-pulse">CONFLUENCE ACHIEVED: BUY SIGNAL</p>
            </div>
        </div>
    </div>

    <!-- Slide 7: Expected Analysis Result -->
    <div class="slide" id="slide-7">
        <div class="max-w-6xl mx-auto">
            <div class="flex justify-between items-end mb-8">
                <div>
                    <h2 class="text-4xl font-black mb-2">High Probability Result</h2>
                    <p class="text-slate-400 italic">Visualizing the logic execution</p>
                </div>
                <div class="bg-emerald-600 text-white px-8 py-3 rounded-2xl font-black text-xl shadow-lg shadow-emerald-500/20">
                    RESULT: BUY
                </div>
            </div>
            <div class="bg-slate-900 border border-white/5 rounded-[3rem] p-10 relative">
                <div class="chart-container">
                    <canvas id="tradeChart"></canvas>
                </div>
                <div class="absolute top-1/2 left-2/3 -translate-y-1/2 p-6 bg-emerald-500 rounded-3xl shadow-2xl text-white">
                    <p class="text-xs font-black uppercase mb-1">Entry Trigger</p>
                    <p class="font-bold">Deep Confluence</p>
                    <p class="text-[10px] opacity-70">EMA+MACD+VWAP Aligned</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Slide 8: Risk Management -->
    <div class="slide" id="slide-8">
        <div class="max-w-4xl mx-auto text-center">
            <div class="w-24 h-24 bg-rose-500/20 rounded-full flex items-center justify-center mx-auto mb-8 text-4xl">🛡️</div>
            <h2 class="text-4xl font-black mb-6">Risk Management</h2>
            <p class="text-xl text-slate-400 leading-relaxed mb-8">
                An algorithm is only as good as its protection. Deep Confluence requires strict position sizing. Never risk more than <span class="text-white font-bold">1-2%</span> of total equity on any single confluence trigger.
            </p>
            <div class="p-8 bg-slate-900 rounded-3xl border border-rose-500/20 inline-block text-left">
                <ul class="space-y-4 text-sm font-medium">
                    <li class="flex gap-4">
                        <span class="text-rose-500">•</span>
                        <span>Stop Loss anchored below EMA 50 structure.</span>
                    </li>
                    <li class="flex gap-4">
                        <span class="text-rose-500">•</span>
                        <span>Trailing Stop activated once 1:1 RR is met.</span>
                    </li>
                    <li class="flex gap-4">
                        <span class="text-rose-500">•</span>
                        <span>Hard Exit on RSI extreme overbought (>75).</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Slide 9: Patience in Confluence -->
    <div class="slide" id="slide-9">
        <div class="max-w-4xl mx-auto text-center">
            <h2 class="text-4xl font-black mb-8">Patience in Confluence</h2>
            <div class="relative py-20">
                <div class="absolute inset-0 flex items-center justify-center opacity-10">
                    <div class="w-64 h-64 border-2 border-emerald-500 rounded-full animate-ping"></div>
                </div>
                <p class="text-3xl font-bold italic text-emerald-400 mb-8 relative z-10">
                    "The algorithm returns HOLD for a reason."
                </p>
            </div>
            <p class="text-slate-500 leading-relaxed max-w-2xl mx-auto italic">
                Wait for the boxes to check. Chasing half-signals results in equity drawdown. True wealth is built on the entries where all five pillars align perfectly.
            </p>
        </div>
    </div>

    <!-- Slide 10: Optimization -->
    <div class="slide" id="slide-10">
        <div class="max-w-5xl mx-auto text-center">
            <h2 class="text-5xl font-black mb-6 uppercase tracking-tighter">Continuous Optimization</h2>
            <p class="text-slate-400 mb-12">The market is a dynamic engine; your variables must evolve.</p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-left mb-16">
                <div class="p-8 bg-white/5 rounded-3xl border border-white/5">
                    <h4 class="font-bold text-lg mb-2">Backtest Rigor</h4>
                    <p class="text-sm text-slate-500">Simulate confluence logic over 5 years of historical data to verify alpha stability.</p>
                </div>
                <div class="p-8 bg-white/5 rounded-3xl border border-white/5">
                    <h4 class="font-bold text-lg mb-2">Execution Speed</h4>
                    <p class="text-sm text-slate-500">Optimize PHP runtime for zero-latency execution on high-frequency candle closures.</p>
                </div>
            </div>
           <div class="pt-8 border-t border-white/5 flex flex-col items-center">
    
	         <a href="campus.php" target="_blank" style="text-decoration: none;">
             <button class="bg-emerald-600 hover:bg-emerald-700 text-slate-950 px-10 py-4 rounded-full font-black uppercase tracking-widest text-xs transition transform hover:scale-105 active:scale-95 shadow-xl shadow-emerald-500/20">
             <i class="fa-solid fa-wand-magic-sparkles"></i> Back to Campus
             </button>
             </a>
             <p class="text-[10px] text-slate-800 mt-4 uppercase font-bold tracking-[0.3em]">Institutional Grade Output Ready</p>
          </div>
        </div>
    </div>

    <!-- Navigation Overlay -->
    <div class="fixed bottom-12 left-0 right-0 z-50">
        <div class="max-w-7xl mx-auto px-12 flex justify-between items-center">
            <div class="text-[10px] font-bold text-slate-600 tracking-widest uppercase">
                Slide <span id="current-slide-num">1</span> / 10
            </div>
            <div class="flex gap-4">
                <button onclick="prevSlide()" class="nav-btn p-3 rounded-xl">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"></polyline></svg>
                </button>
                <button onclick="nextSlide()" class="nav-btn p-3 rounded-xl">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Progress Bar -->
    <div class="fixed bottom-0 left-0 h-1 bg-emerald-500 transition-all duration-300" id="progress-bar" style="width: 10%"></div>

    <script>
        let currentSlide = 1;
        const totalSlides = 10;
        let chartInstance = null;

        function updateSlides() {
            document.querySelectorAll('.slide').forEach((s, i) => {
                s.classList.toggle('active', i + 1 === currentSlide);
            });
            document.getElementById('current-slide-num').innerText = currentSlide;
            document.getElementById('progress-bar').style.width = (currentSlide / totalSlides) * 100 + '%';
            
            // Re-render chart if on slide 7
            if(currentSlide === 7) {
                setTimeout(renderTradeChart, 100);
            }
        }

        function nextSlide() {
            if (currentSlide < totalSlides) {
                currentSlide++;
                updateSlides();
            }
        }

        function prevSlide() {
            if (currentSlide > 1) {
                currentSlide--;
                updateSlides();
            }
        }

        // Keyboard Navigation
        document.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowRight' || e.key === ' ') nextSlide();
            if (e.key === 'ArrowLeft') prevSlide();
        });

        // Hypothetical Trade Chart Rendering
        function renderTradeChart() {
            const ctx = document.getElementById('tradeChart').getContext('2d');
            if (chartInstance) chartInstance.destroy();

            const data = [100, 102, 101, 105, 104, 103, 107, 108, 106, 110, 115, 114, 118];
            const ema = [98, 99, 100, 101, 102, 103, 104, 105, 106, 107, 108, 109, 110];
            const vwap = [101, 101, 101, 101, 101, 101, 101, 101, 101, 101, 101, 101, 101];

            chartInstance = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['', '', '', '', '', '', 'Trigger Point', '', '', '', '', '', ''],
                    datasets: [
                        {
                            label: 'Price Action',
                            data: data,
                            borderColor: '#ffffff',
                            borderWidth: 3,
                            tension: 0.4,
                            pointRadius: (ctx) => ctx.dataIndex === 6 ? 10 : 0,
                            pointBackgroundColor: '#10b981',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2
                        },
                        {
                            label: 'EMA 50',
                            data: ema,
                            borderColor: '#10b981',
                            borderWidth: 2,
                            borderDash: [5, 5],
                            tension: 0
                        },
                        {
                            label: 'VWAP',
                            data: vwap,
                            borderColor: '#3b82f6',
                            borderWidth: 2,
                            tension: 0
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: { duration: 1500, easing: 'easeInOutQuart' },
                    scales: {
                        y: { display: false },
                        x: { display: true, ticks: { color: '#475569' }, grid: { display: false } }
                    },
                    plugins: {
                        legend: { display: false }
                    }
                }
            });
        }

        window.onload = () => {
            console.log("Algo Strategy Portal Loaded.");
        };
    </script>
</body>
</html>