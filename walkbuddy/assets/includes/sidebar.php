<?php 
$active_page = $page ?? 'dashboard'; 
?>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&family=Orbitron:wght@700&display=swap" rel="stylesheet">

<aside class="wb-sidebar-container" id="wbSidebar">
    <canvas id="sidebarCanvas"></canvas>

    <div class="wb-data-stream">
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
    </div>

    <div class="wb-sidebar-brand">
        <div class="wb-brand-icon">
            <i class="fas fa-microchip"></i>
        </div>
        <div class="wb-brand-text">
            <h3>WalkBuddy</h3>
            <span>SYSTEM ADMIN</span>
        </div>
    </div>

    <nav class="wb-sidebar-nav">
        <ul class="wb-nav-list">
            <li class="wb-nav-item <?php echo ($active_page === 'dashboard') ? 'wb-active' : ''; ?>">
                <a href="dashboard.php" class="wb-nav-link">
                    <span class="wb-icon-box"><i class="fas fa-layer-group"></i></span>
                    <span class="wb-nav-text">Dashboard</span>
                </a>
            </li>
            <li class="wb-nav-item <?php echo ($active_page === 'messages') ? 'wb-active' : ''; ?>">
                <a href="messages.php" class="wb-nav-link">
                    <span class="wb-icon-box"><i class="fas fa-satellite"></i></span>
                    <span class="wb-nav-text">Messages</span>
                </a>
            </li>
            <li class="wb-nav-item <?php echo ($active_page === 'notifications') ? 'wb-active' : ''; ?>">
                <a href="notification.php" class="wb-nav-link">
                    <span class="wb-icon-box"><i class="fas fa-bolt"></i></span>
                    <span class="wb-nav-text">Notifications</span>
                </a>
            </li>
            <li class="wb-nav-item <?php echo ($active_page === 'active') ? 'wb-active' : ''; ?>">
                <a href="active_user.php" class="wb-nav-link">
                    <span class="wb-icon-box"><i class="fas fa-broadcast-tower"></i></span>
                    <span class="wb-nav-text">Active Users</span>
                </a>
            </li>
            <li class="wb-nav-item <?php echo ($active_page === 'map') ? 'wb-active' : ''; ?>">
                <a href="map.php" class="wb-nav-link">
                    <span class="wb-icon-box"><i class="fas fa-crosshairs"></i></span>
                    <span class="wb-nav-text">Live Radar</span>
                </a>
            </li>
            <li class="wb-nav-item <?php echo ($active_page === 'users') ? 'wb-active' : ''; ?>">
                <a href="users.php" class="wb-nav-link">
                    <span class="wb-icon-box"><i class="fas fa-user-shield"></i></span>
                    <span class="wb-nav-text">Manage Users</span>
                </a>
            </li>
        </ul>
    </nav>

    <div class="wb-sidebar-footer">
        <button id="wb_logout_trigger_unique" class="wb-logout-btn">
            <i class="fas fa-power-off"></i>
            <span>INITIALIZE LOGOUT</span>
        </button>
    </div>
</aside>

<style>
    :root {
        --primary-yellow: #f1c40f; 
        --bg-dark: #0b0d0f;
        --text-gray: #aeb6bf;
        --glow: 0 0 15px rgba(241, 196, 15, 0.4);
    }

    .wb-sidebar-container {
        width: 260px;
        height: 100vh;
        position: fixed;
        left: 0; top: 0;
        background: var(--bg-dark);
        display: flex;
        flex-direction: column;
        color: white;
        z-index: 1050; 
        border-right: 1px solid rgba(241, 196, 15, 0.1);
        font-family: 'Poppins', sans-serif;
        overflow: hidden;
        transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    #sidebarCanvas {
        position: absolute;
        top: 0; left: 0;
        width: 100%; height: 100%;
        z-index: 0; 
        opacity: 0.3;
        pointer-events: none; 
    }

    .wb-sidebar-brand {
        padding: 40px 25px;
        display: flex; align-items: center; gap: 15px;
        border-bottom: 1px solid rgba(255,255,255,0.03);
        position: relative;
        z-index: 2;
    }

    .wb-brand-icon { color: var(--primary-yellow); font-size: 1.8rem; filter: drop-shadow(var(--glow)); }
    .wb-brand-text h3 {
        font-family: 'Orbitron', sans-serif;
        margin: 0; font-size: 1.2rem; letter-spacing: 1px; color: var(--primary-yellow);
    }
    .wb-brand-text span { font-size: 0.7rem; color: var(--text-gray); letter-spacing: 2px; text-transform: uppercase; }

    .wb-sidebar-nav { flex: 1; padding: 30px 0; position: relative; z-index: 10; }
    .wb-nav-list { list-style: none; padding: 0; margin: 0; }
    .wb-nav-item { margin-bottom: 8px; position: relative; padding: 0 15px; }

    .wb-nav-link {
        display: flex; align-items: center;
        padding: 14px 18px;
        color: var(--text-gray);
        text-decoration: none;
        border-radius: 12px;
        transition: all 0.3s ease;
        font-weight: 400;
        font-size: 0.95rem;
        cursor: pointer;
    }

    .wb-icon-box { width: 25px; margin-right: 15px; font-size: 1.1rem; text-align: center; }

    .wb-nav-link:hover {
        color: var(--primary-yellow);
        background: rgba(241, 196, 15, 0.1);
        transform: translateX(5px);
    }

    .wb-active .wb-nav-link {
        background: rgba(241, 196, 15, 0.15);
        color: var(--primary-yellow);
        font-weight: 600;
    }

    .wb-sidebar-footer { 
        padding: 25px; 
        border-top: 1px solid rgba(255,255,255,0.03); 
        position: relative;
        z-index: 10;
    }

    .wb-logout-btn {
        width: 100%;
        display: flex; align-items: center; justify-content: center; gap: 10px;
        padding: 15px; color: #ff4d4d;
        border-radius: 12px;
        background: rgba(255, 77, 77, 0.05);
        border: 1px solid rgba(255, 77, 77, 0.1);
        transition: 0.3s;
        font-size: 0.8rem; font-weight: 700; letter-spacing: 1px;
        cursor: pointer;
    }

    .wb-logout-btn:hover {
        background: #ff4d4d; color: #fff;
        box-shadow: 0 5px 15px rgba(255, 77, 77, 0.3);
    }

    .wb-sidebar-overlay {
        display: none;
        position: fixed;
        top: 0; left: 0;
        width: 100vw; height: 100vh;
        background: rgba(0,0,0,0.7);
        backdrop-filter: blur(4px);
        z-index: 1040;
    }
    
    .wb-sidebar-overlay.active { display: block; }

    @media (max-width: 992px) {
        .wb-sidebar-container { transform: translateX(-100%); }
        .wb-sidebar-container.is-open { transform: translateX(0); }
    }

    .wb-data-stream { position: absolute; width: 100%; height: 100%; overflow: hidden; pointer-events: none; z-index: 1; }
    .particle {
        position: absolute; background: var(--primary-yellow);
        width: 1px; height: 10px; opacity: 0.2;
        bottom: -20px; animation: stream 5s infinite linear;
    }
    @keyframes stream {
        0% { transform: translateY(0); opacity: 0; }
        50% { opacity: 0.3; }
        100% { transform: translateY(-100vh); opacity: 0; }
    }
    
    /* Cyber SWAL Styling */
    .cyber-swal-popup { background: #0b0d0f !important; border: 1px solid #f1c40f !important; color: #fff !important; }
    .cyber-swal-title { color: #f1c40f !important; font-family: 'Orbitron', sans-serif !important; }
    .cyber-confirm-btn { background: #f1c40f !important; color: #000 !important; font-weight: bold !important; padding: 10px 25px !important; margin: 5px !important; border-radius: 5px !important; cursor: pointer; }
    .cyber-cancel-btn { background: #333 !important; color: #fff !important; padding: 10px 25px !important; margin: 5px !important; border-radius: 5px !important; cursor: pointer; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('wbSidebar');
    const toggleBtn = document.getElementById('wbHeaderToggleBtn'); 

    // 1. Overlay logic para sa Mobile
    let overlay = document.querySelector('.wb-sidebar-overlay');
    if(!overlay){
        overlay = document.createElement('div');
        overlay.className = 'wb-sidebar-overlay';
        document.body.appendChild(overlay);
    }

    // 2. Toggle button logic (Mobile open/close)
    if(toggleBtn) {
        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('is-open');
            overlay.classList.toggle('active');
            
            const icon = toggleBtn.querySelector('i');
            if (sidebar.classList.contains('is-open')) {
                icon.classList.replace('fa-bars', 'fa-times');
            } else {
                icon.classList.replace('fa-times', 'fa-bars');
            }
        });
    }

    // 3. Click sa overlay para mag-close (Mobile)
    overlay.addEventListener('click', () => {
        sidebar.classList.remove('is-open');
        overlay.classList.remove('active');
        if(toggleBtn) toggleBtn.querySelector('i').classList.replace('fa-times', 'fa-bars');
    });

    // 4. Logout Logic (Dito lang ang binago natin)
    const logoutBtn = document.getElementById('wb_logout_trigger_unique');
    if(logoutBtn) {
        logoutBtn.addEventListener('click', function() {
            Swal.fire({
                title: 'TERMINATE SESSION?',
                text: "Access keys will be revoked. You will be returned to the login portal.",
                icon: 'warning',
                iconColor: '#f1c40f',
                showCancelButton: true,
                confirmButtonText: 'CONFIRM LOGOUT',
                cancelButtonText: 'ABORT',
                buttonsStyling: false,
                customClass: {
                    popup: 'cyber-swal-popup',
                    title: 'cyber-swal-title',
                    confirmButton: 'cyber-confirm-btn',
                    cancelButton: 'cyber-cancel-btn'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Ito ang sigurado na gagana sa kahit anong page
                    window.location.href = 'logout.php';
                }
            });
        });
    }

    // --- CANVAS ANIMATION (Original) ---
    (function() {
        const canvas = document.getElementById('sidebarCanvas');
        if(!canvas) return;
        const ctx = canvas.getContext('2d');
        let squares = [];
        function init() {
            canvas.width = canvas.offsetWidth;
            canvas.height = canvas.offsetHeight;
            squares = [];
            for (let i = 0; i < 15; i++) {
                squares.push({
                    x: Math.random() * canvas.width,
                    y: Math.random() * canvas.height,
                    size: Math.random() * 25 + 5,
                    speed: Math.random() * 0.4 + 0.1,
                    opacity: Math.random() * 0.3
                });
            }
        }
        function draw() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            ctx.fillStyle = "#f1c40f";
            squares.forEach(sq => {
                ctx.globalAlpha = sq.opacity;
                ctx.fillRect(sq.x, sq.y, sq.size, sq.size);
                sq.y -= sq.speed;
                if (sq.y + sq.size < 0) sq.y = canvas.height;
            });
            requestAnimationFrame(draw);
        }
        window.addEventListener('resize', init);
        init(); draw();
    })();
});
</script>