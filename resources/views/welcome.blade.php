<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Msaraty Core | Mobile & Desktop Interface</title>
    <style>
        /* --- التصميم العام --- */
        :root {
            --neon-cyan: #00f3ff;
            --neon-purple: #bc13fe;
            --bg-dark: #020617;
            --glass: rgba(10, 15, 30, 0.85);
        }

        body, html {
            margin: 0; padding: 0;
            width: 100%; min-height: 100vh;
            background-color: var(--bg-dark);
            color: white;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        #particle-canvas {
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            z-index: 1;
        }

        .overlay {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: radial-gradient(circle, transparent 20%, var(--bg-dark) 90%);
            z-index: 2;
            pointer-events: none;
        }

        /* --- تصميم الروبوت المطور --- */
        .robot-head {
            position: relative;
            width: 120px; height: 120px;
            margin-bottom: 20px;
            background: linear-gradient(145deg, #1e293b, #0f172a);
            border: 3px solid var(--neon-cyan);
            border-radius: 30px;
            box-shadow: 0 0 20px rgba(0, 243, 255, 0.4);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 10;
            transition: transform 0.1s ease-out;
            flex-shrink: 0;
        }

        .eyes-container {
            display: flex;
            justify-content: space-around;
            width: 100%;
        }

        .eye-socket {
            width: 30px; height: 30px;
            background: #000;
            border-radius: 50%;
            position: relative;
            border: 2px solid rgba(0, 243, 255, 0.3);
            overflow: hidden;
        }

        .pupil {
            width: 15px; height: 15px;
            background: radial-gradient(circle, #fff 10%, var(--neon-cyan) 80%);
            border-radius: 50%;
            position: absolute;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            box-shadow: 0 0 10px var(--neon-cyan);
        }

        .robot-nose {
            width: 10px; height: 15px;
            background: rgba(0, 243, 255, 0.2);
            border: 1px solid var(--neon-cyan);
            border-radius: 3px;
            margin-top: 8px;
        }

        .robot-mouth {
            width: 40px; height: 6px;
            background: #000;
            border-radius: 10px;
            margin-top: 10px;
            position: relative;
            overflow: hidden;
        }

        .mouth-line {
            width: 100%; height: 2px;
            background: var(--neon-cyan);
            position: absolute;
            top: 2px;
            animation: talk 1.5s infinite ease-in-out;
        }

        @keyframes talk {
            0%, 100% { transform: scaleX(0.4); opacity: 0.5; }
            50% { transform: scaleX(0.9); opacity: 1; }
        }

        /* --- لوحة البيانات المتجاوبة --- */
        .dashboard-card {
            background: var(--glass);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(0, 243, 255, 0.3);
            border-radius: 25px;
            width: 90%;
            max-width: 800px;
            padding: 25px;
            box-shadow: 0 0 40px rgba(0,0,0,0.7);
            z-index: 10;
            margin: 20px auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .title {
            font-size: 1.5rem;
            font-weight: bold;
            background: linear-gradient(to left, var(--neon-cyan), var(--neon-purple));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .card-content {
            display: grid;
            grid-template-columns: 1fr 1fr; /* عمودين في الكمبيوتر */
            gap: 15px;
        }

        /* تعديلات الجوال */
        @media (max-width: 600px) {
            .card-content {
                grid-template-columns: 1fr; /* عمود واحد في الجوال */
            }
            .title {
                font-size: 1.2rem;
            }
            .dashboard-card {
                padding: 15px;
            }
            .robot-head {
                width: 100px; height: 100px;
            }
        }

        .sub-card {
            background: rgba(255,255,255,0.05);
            border-radius: 15px;
            padding: 15px;
            border: 1px solid rgba(255,255,255,0.05);
        }

        .sub-card h3 { margin: 0 0 8px 0; color: var(--neon-cyan); font-size: 0.8rem; text-transform: uppercase; }

        .member-tag {
            display: inline-block;
            background: rgba(188, 19, 254, 0.15);
            padding: 4px 10px;
            border-radius: 6px;
            margin: 3px;
            font-size: 0.8rem;
            border: 1px solid var(--neon-purple);
        }

        .footer {
            margin-top: 25px;
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
            font-size: 0.75rem;
            opacity: 0.7;
        }

        @media (max-width: 480px) {
            .footer {
                flex-direction: column;
                gap: 10px;
                text-align: center;
            }
        }

        .host-badge {
            background: var(--neon-cyan);
            color: var(--bg-dark);
            padding: 3px 10px;
            border-radius: 5px;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <canvas id="particle-canvas"></canvas>
    <div class="overlay"></div>

    <div style="display: flex; flex-direction: column; align-items: center; z-index: 10; width: 100%; padding: 10px; box-sizing: border-box;">
        
        <div class="robot-head" id="robot">
            <div class="eyes-container">
                <div class="eye-socket"><div class="pupil"></div></div>
                <div class="eye-socket"><div class="pupil"></div></div>
            </div>
            <div class="robot-nose"></div>
            <div class="robot-mouth"><div class="mouth-line"></div></div>
        </div>

        <div class="dashboard-card">
            <div class="header">
                <div class="title">MSARATY CORE</div>
                <div style="font-family: monospace; color: var(--neon-cyan); font-size: 0.8rem;">V2.5_STABLE</div>
            </div>

            <div class="card-content">
                <div class="sub-card" style="grid-column: span 1; grid-column-end: -1; grid-column-start: 1;">
                    <h3>فكرة المشروع / Project</h3>
                    <p style="margin: 0; line-height: 1.5; font-size: 0.9rem;">
                        نظام ذكي لإدارة النقل الجامعي يربط الطلاب بالباصات والمسارات بشكل منظم لتحسين كفاءة الرحلات.
                    </p>
                </div>

                <div class="sub-card">
                    <h3>الفريق / The Team</h3>
                    <div class="member-tag">OSAMA WEBRAN</div>
                    <div class="member-tag">RYAN OSAMA</div>
                    <div class="member-tag">SABER BAFADEL</div>
                    <div class="member-tag">JOMAAN BIN SALMAN</div>
                </div>

                <div class="sub-card">
                    <h3>النظام / Info</h3>
                    <p style="font-size: 0.8rem; margin: 0; line-height: 1.6;">
                        • المحرك: <span style="color:var(--neon-purple)">Laravel</span><br>
                        • الاستضافة: <span style="color:var(--neon-cyan)">Cloud Secured</span><br>
                        • الوقت: <span id="clock">00:00:00</span>
                    </p>
                </div>
            </div>

            <div class="footer">
                <div>© 2026 مساراتي</div>
                <div>الاستضافة: <span class="host-badge">ABDUL SALAM AMIN</span></div>
            </div>
        </div>
    </div>

    <script>
        const canvas = document.getElementById('particle-canvas');
        const ctx = canvas.getContext('2d');
        let particlesArray = [];
        let numberOfParticles = (window.innerWidth < 600) ? 50 : 100;

        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;

        class Particle {
            constructor() {
                this.x = Math.random() * canvas.width;
                this.y = Math.random() * canvas.height;
                this.size = Math.random() * 2 + 1;
                this.speedX = (Math.random() - 0.5) * 1.2;
                this.speedY = (Math.random() - 0.5) * 1.2;
            }
            update() {
                this.x += this.speedX; this.y += this.speedY;
                if (this.x > canvas.width || this.x < 0) this.speedX *= -1;
                if (this.y > canvas.height || this.y < 0) this.speedY *= -1;
            }
            draw() {
                ctx.fillStyle = '#00f3ff';
                ctx.beginPath(); ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2); ctx.fill();
            }
        }

        function init() {
            particlesArray = [];
            for (let i = 0; i < numberOfParticles; i++) { particlesArray.push(new Particle()); }
        }

        function animate() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            for (let i = 0; i < particlesArray.length; i++) {
                particlesArray[i].update();
                particlesArray[i].draw();
                for (let j = i; j < particlesArray.length; j++) {
                    const dx = particlesArray[i].x - particlesArray[j].x;
                    const dy = particlesArray[i].y - particlesArray[j].y;
                    const distance = Math.sqrt(dx * dx + dy * dy);
                    const limit = (window.innerWidth < 600) ? 80 : 120;
                    if (distance < limit) {
                        ctx.strokeStyle = `rgba(0, 243, 255, ${1 - distance/limit})`;
                        ctx.lineWidth = 0.5; ctx.beginPath();
                        ctx.moveTo(particlesArray[i].x, particlesArray[i].y);
                        ctx.lineTo(particlesArray[j].x, particlesArray[j].y); ctx.stroke();
                    }
                }
            }
            requestAnimationFrame(animate);
        }

        window.addEventListener('resize', () => { 
            canvas.width = window.innerWidth; canvas.height = window.innerHeight; 
            numberOfParticles = (window.innerWidth < 600) ? 50 : 100;
            init(); 
        });
        
        init(); animate();

        // دعم الماوس واللمس
        function handleMove(clientX, clientY) {
            const xAxis = (window.innerWidth / 2 - clientX) / 20;
            const yAxis = (window.innerHeight / 2 - clientY) / 20;
            const robot = document.getElementById('robot');
            robot.style.transform = `rotateY(${-xAxis}deg) rotateX(${yAxis}deg)`;

            document.querySelectorAll('.pupil').forEach(p => {
                const rect = p.parentElement.getBoundingClientRect();
                const x = ((clientX - rect.left) / rect.width) * 100;
                const y = ((clientY - rect.top) / rect.height) * 100;
                p.style.left = `${Math.min(Math.max(x, 20), 80)}%`;
                p.style.top = `${Math.min(Math.max(y, 20), 80)}%`;
            });
        }

        document.addEventListener('mousemove', (e) => handleMove(e.clientX, e.clientY));
        document.addEventListener('touchmove', (e) => {
            handleMove(e.touches[0].clientX, e.touches[0].clientY);
        });

        setInterval(() => { document.getElementById('clock').innerText = new Date().toLocaleTimeString(); }, 1000);
    </script>
</body>
</html>