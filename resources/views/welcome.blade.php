<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Msaraty Core | Neural Network Interface</title>
    <style>
        /* --- التصميم العام --- */
        :root {
            --neon-cyan: #00f3ff;
            --neon-purple: #bc13fe;
            --bg-dark: #020617;
            --glass: rgba(10, 15, 30, 0.8);
        }

        body, html {
            margin: 0; padding: 0;
            width: 100%; height: 100%;
            background-color: var(--bg-dark);
            color: white;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow: hidden;
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
            width: 140px; height: 140px;
            margin-bottom: 20px;
            background: linear-gradient(145deg, #1e293b, #0f172a);
            border: 3px solid var(--neon-cyan);
            border-radius: 30px;
            box-shadow: 0 0 30px rgba(0, 243, 255, 0.4), inset 0 0 15px rgba(0, 243, 255, 0.2);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 10;
            transition: transform 0.1s ease-out;
            perspective: 1000px;
        }

        .eyes-container {
            display: flex;
            justify-content: space-around;
            width: 100%;
            margin-top: 10px;
        }

        .eye-socket {
            width: 35px; height: 35px;
            background: #000;
            border-radius: 50%;
            position: relative;
            border: 2px solid rgba(0, 243, 255, 0.3);
            overflow: hidden;
            box-shadow: inset 0 0 10px rgba(0, 243, 255, 0.5);
        }

        .pupil {
            width: 18px; height: 18px;
            background: radial-gradient(circle, #fff 10%, var(--neon-cyan) 80%);
            border-radius: 50%;
            position: absolute;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            box-shadow: 0 0 15px var(--neon-cyan);
        }

        /* إضافة خشم الروبوت */
        .robot-nose {
            width: 12px; height: 18px;
            background: rgba(0, 243, 255, 0.2);
            border: 1px solid var(--neon-cyan);
            border-radius: 4px;
            margin-top: 10px;
            box-shadow: 0 0 5px var(--neon-cyan);
        }

        /* إضافة فم الروبوت (شاشة ديجيتال) */
        .robot-mouth {
            width: 50px; height: 8px;
            background: #000;
            border-radius: 10px;
            margin-top: 12px;
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(0, 243, 255, 0.5);
        }

        .mouth-line {
            width: 100%; height: 2px;
            background: var(--neon-cyan);
            position: absolute;
            top: 3px;
            box-shadow: 0 0 10px var(--neon-cyan);
            animation: talk 1.5s infinite ease-in-out;
        }

        @keyframes talk {
            0%, 100% { transform: scaleX(0.4); opacity: 0.5; }
            50% { transform: scaleX(0.9); opacity: 1; }
        }

        /* --- لوحة البيانات --- */
        .dashboard-card {
            background: var(--glass);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(0, 243, 255, 0.3);
            border-radius: 25px;
            width: 90%;
            max-width: 800px;
            padding: 40px;
            box-shadow: 0 0 60px rgba(0,0,0,0.7);
            z-index: 10;
        }

        .header {
            display: flex; justify-content: space-between; align-items: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            padding-bottom: 15px; margin-bottom: 25px;
        }

        .title {
            font-size: 2rem; font-weight: bold;
            background: linear-gradient(to left, var(--neon-cyan), var(--neon-purple));
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        }

        .card-content { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }

        .sub-card {
            background: rgba(255,255,255,0.05); border-radius: 15px;
            padding: 20px; border: 1px solid rgba(255,255,255,0.05);
        }

        .sub-card h3 { margin: 0 0 10px 0; color: var(--neon-cyan); font-size: 0.9rem; text-transform: uppercase; }

        .member-tag {
            display: inline-block; background: rgba(188, 19, 254, 0.2);
            padding: 5px 12px; border-radius: 8px; margin: 4px;
            font-size: 0.85rem; border: 1px solid var(--neon-purple);
        }

        .footer {
            margin-top: 30px; display: flex; justify-content: space-between;
            align-items: center; font-size: 0.8rem; opacity: 0.6;
        }

        .host-badge { background: var(--neon-cyan); color: var(--bg-dark); padding: 4px 12px; border-radius: 5px; font-weight: bold; }
    </style>
</head>
<body>

    <canvas id="particle-canvas"></canvas>
    <div class="overlay"></div>

    <div style="display: flex; flex-direction: column; align-items: center; z-index: 10; width: 100%;">
        
        <div class="robot-head" id="robot">
            <div class="eyes-container">
                <div class="eye-socket"><div class="pupil"></div></div>
                <div class="eye-socket"><div class="pupil"></div></div>
            </div>
            <div class="robot-nose"></div>
            <div class="robot-mouth">
                <div class="mouth-line"></div>
            </div>
        </div>

        <div class="dashboard-card">
            <div class="header">
                <div class="title">MSARATY CORE</div>
                <div style="font-family: monospace; color: var(--neon-cyan);">V2.5.0_STABLE</div>
            </div>

            <div class="card-content">
                <div class="sub-card" style="grid-column: span 2;">
                    <h3>فكرة المشروع / Project Concept</h3>
                    <p style="margin: 0; line-height: 1.6;">
                        نظام ذكي لإدارة النقل الجامعي يربط الطلاب بالباصات والمسارات بشكل منظم.
                        يهدف إلى تحسين كفاءة النقل وتقليل الفوضى وتسهيل متابعة الرحلات بدقة عالية.
                    </p>
                </div>

                <div class="sub-card">
                    <h3>فريق التطوير / The Team</h3>
                    <div class="member-tag">OSAMA WEBRAN</div>
                    <div class="member-tag">RYAN OSAMA</div>
                    <div class="member-tag">SABER BAFADEL</div>
                    <div class="member-tag">JOMAAN BIN SALMAN</div>
                </div>

                <div class="sub-card">
                    <h3>حالة النظام / System Info</h3>
                    <p style="font-size: 0.85rem; margin: 0; line-height: 1.8;">
                        • المحرك: <span style="color:var(--neon-purple)">Laravel</span><br>
                        • الاستضافة: <span style="color:var(--neon-cyan)">Cloud Secured</span><br>
                        • التوقيت: <span id="clock">00:00:00</span>
                    </p>
                </div>
            </div>

            <div class="footer">
                <div>© 2026 جميع الحقوق محفوظة لـ مساراتي</div>
                <div>صاحب الاستضافة: <span class="host-badge">ABDUL SALAM AMIN</span></div>
            </div>
        </div>
    </div>

    <script>
        const canvas = document.getElementById('particle-canvas');
        const ctx = canvas.getContext('2d');
        let particlesArray = [];
        const numberOfParticles = 100;

        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;

        class Particle {
            constructor() {
                this.x = Math.random() * canvas.width;
                this.y = Math.random() * canvas.height;
                this.size = Math.random() * 2 + 1;
                this.speedX = (Math.random() - 0.5) * 1.5;
                this.speedY = (Math.random() - 0.5) * 1.5;
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
                    if (distance < 150) {
                        ctx.strokeStyle = `rgba(0, 243, 255, ${1 - distance/150})`;
                        ctx.lineWidth = 0.5; ctx.beginPath();
                        ctx.moveTo(particlesArray[i].x, particlesArray[i].y);
                        ctx.lineTo(particlesArray[j].x, particlesArray[j].y); ctx.stroke();
                    }
                }
            }
            requestAnimationFrame(animate);
        }

        window.addEventListener('resize', () => { canvas.width = window.innerWidth; canvas.height = window.innerHeight; init(); });
        init(); animate();

        // --- حركة الروبوت والعيون بدقة أكبر ---
        const robot = document.getElementById('robot');
        const pupils = document.querySelectorAll('.pupil');

        document.addEventListener('mousemove', (e) => {
            const xAxis = (window.innerWidth / 2 - e.clientX) / 20;
            const yAxis = (window.innerHeight / 2 - e.clientY) / 20;
            robot.style.transform = `rotateY(${-xAxis}deg) rotateX(${yAxis}deg)`;

            pupils.forEach(p => {
                const rect = p.parentElement.getBoundingClientRect();
                const x = ((e.clientX - rect.left) / rect.width) * 100;
                const y = ((e.clientY - rect.top) / rect.height) * 100;
                
                // تحديد حركة البؤبؤ داخل العين
                const moveX = Math.min(Math.max(x, 20), 80);
                const moveY = Math.min(Math.max(y, 20), 80);
                
                p.style.left = `${moveX}%`;
                p.style.top = `${moveY}%`;
            });
        });

        setInterval(() => { document.getElementById('clock').innerText = new Date().toLocaleTimeString(); }, 1000);
    </script>
</body>
</html>