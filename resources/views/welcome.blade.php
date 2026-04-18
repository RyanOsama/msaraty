<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masarat API | Neural Network Interface</title>
    <style>
        /* --- التصميم العام --- */
        :root {
            --neon-cyan: #00f3ff;
            --neon-purple: #bc13fe;
            --bg-dark: #020617;
            --glass: rgba(10, 15, 30, 0.7);
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

        /* --- 1. الخلفية المتحركة (الشبكة العصبية) --- */
        #particle-canvas {
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            z-index: 1; /* خلف كل شيء */
        }

        /* طبقة تظليل فوق الخلفية لزيادة التباين */
        .overlay {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: radial-gradient(circle, transparent 20%, var(--bg-dark) 90%);
            z-index: 2;
            pointer-events: none;
        }

        /* --- 2. الروبوت التقني --- */
        .robot-head {
            position: relative;
            width: 110px; height: 110px;
            margin-bottom: 20px;
            background: var(--glass);
            border: 2px solid var(--neon-cyan);
            border-radius: 20px;
            box-shadow: 0 0 30px rgba(0, 243, 255, 0.3);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 10;
            transition: transform 0.1s ease;
        }

        .eye {
            width: 14px; height: 14px;
            background: var(--neon-cyan);
            border-radius: 50%;
            margin: 0 12px;
            box-shadow: 0 0 20px var(--neon-cyan);
            position: relative;
        }

        .pupil {
            width: 5px; height: 5px;
            background: white;
            border-radius: 50%;
            position: absolute;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
        }

        /* --- 3. لوحة البيانات --- */
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
            position: relative;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            padding-bottom: 15px;
            margin-bottom: 25px;
        }

        .title {
            font-size: 2rem;
            font-weight: bold;
            background: linear-gradient(to left, var(--neon-cyan), var(--neon-purple));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .card-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .sub-card {
            background: rgba(255,255,255,0.05);
            border-radius: 15px;
            padding: 20px;
            border: 1px solid rgba(255,255,255,0.05);
        }

        .sub-card h3 {
            margin: 0 0 10px 0;
            color: var(--neon-cyan);
            font-size: 0.9rem;
            text-transform: uppercase;
        }

        .member-tag {
            display: inline-block;
            background: rgba(188, 19, 254, 0.2);
            padding: 5px 12px;
            border-radius: 8px;
            margin: 4px;
            font-size: 0.85rem;
            border: 1px solid var(--neon-purple);
        }

        .footer {
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.8rem;
            opacity: 0.6;
        }

        .host-badge {
            background: var(--neon-cyan);
            color: var(--bg-dark);
            padding: 4px 12px;
            border-radius: 5px;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <canvas id="particle-canvas"></canvas>
    <div class="overlay"></div>

    <div style="display: flex; flex-direction: column; align-items: center; z-index: 10; width: 100%;">
        
        <div class="robot-head" id="robot">
            <div class="eye"><div class="pupil"></div></div>
            <div class="eye"><div class="pupil"></div></div>
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
يهدف إلى تحسين كفاءة النقل وتقليل الفوضى وتسهيل متابعة الرحلات                    </p>
                </div>

                <div class="sub-card">
                    <h3>فريق التطوير / The Team</h3>
                    <div class="member-tag"> OSAMA WEBRAN </div>
                    <div class="member-tag">RYAN OSAMA</div>
                    <div class="member-tag">SABER BAFADEL  </div>
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
                <div>© 2026 جميع الحقوق محفوظة لـ مسارات</div>
                <div>صاحب الاستضافة: <span class="host-badge">ABDUL SALAM AMIN</span></div>
            </div>
        </div>
    </div>

    <script>
        // --- كود خلفية الجسيمات (Particles) ---
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
                this.x += this.speedX;
                this.y += this.speedY;
                if (this.x > canvas.width || this.x < 0) this.speedX *= -1;
                if (this.y > canvas.height || this.y < 0) this.speedY *= -1;
            }
            draw() {
                ctx.fillStyle = '#00f3ff';
                ctx.beginPath();
                ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
                ctx.fill();
            }
        }

        function init() {
            particlesArray = [];
            for (let i = 0; i < numberOfParticles; i++) {
                particlesArray.push(new Particle());
            }
        }

        function animate() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            for (let i = 0; i < particlesArray.length; i++) {
                particlesArray[i].update();
                particlesArray[i].draw();
                
                // رسم الخطوط بين الجسيمات القريبة
                for (let j = i; j < particlesArray.length; j++) {
                    const dx = particlesArray[i].x - particlesArray[j].x;
                    const dy = particlesArray[i].y - particlesArray[j].y;
                    const distance = Math.sqrt(dx * dx + dy * dy);
                    if (distance < 150) {
                        ctx.strokeStyle = `rgba(0, 243, 255, ${1 - distance/150})`;
                        ctx.lineWidth = 0.5;
                        ctx.beginPath();
                        ctx.moveTo(particlesArray[i].x, particlesArray[i].y);
                        ctx.lineTo(particlesArray[j].x, particlesArray[j].y);
                        ctx.stroke();
                    }
                }
            }
            requestAnimationFrame(animate);
        }

        window.addEventListener('resize', () => {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
            init();
        });

        init();
        animate();

        // --- حركة الروبوت والعيون ---
        const robot = document.getElementById('robot');
        const pupils = document.querySelectorAll('.pupil');

        document.addEventListener('mousemove', (e) => {
            const xAxis = (window.innerWidth / 2 - e.clientX) / 25;
            const yAxis = (window.innerHeight / 2 - e.clientY) / 25;
            robot.style.transform = `rotateY(${-xAxis}deg) rotateX(${yAxis}deg)`;

            pupils.forEach(p => {
                const rect = p.parentElement.getBoundingClientRect();
                const x = (e.clientX - rect.left) / rect.width * 100;
                const y = (e.clientY - rect.top) / rect.height * 100;
                p.style.left = `${Math.min(Math.max(x, 15), 85)}%`;
                p.style.top = `${Math.min(Math.max(y, 15), 85)}%`;
            });
        });

        // --- تحديث الساعة ---
        setInterval(() => {
            document.getElementById('clock').innerText = new Date().toLocaleTimeString();
        }, 1000);
    </script>
</body>
</html>