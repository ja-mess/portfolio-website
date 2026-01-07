<?php
include 'auth_check.php'; 
$page = 'about'; 

include 'includes/header.php'; 
include 'includes/sidebar.php'; 
?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div id="canvas-container"></div>

<div class="admin-wrapper cinematic-mode">
    <div class="dashboard-header-section">
        <h2 class="cyber-title"><i class="fas fa-terminal"></i> SYSTEM CORE TERMINAL</h2>
        <p class="sys-subtitle">Advanced Command Center for Visually Impaired Assistance</p>
    </div>

    <div class="about-grid">
        <div class="about-card mission-card glass-effect">
            <div class="brand-display">
                <i class="fas fa-walking pulse-icon"></i>
                <h3>WalkBuddy</h3>
                <span>Visionary Mobility Assistance</span>
            </div>
            
            <p class="mission-text">
                <strong>WalkBuddy</strong> is a hardware and software ecosystem designed to serve as a guide for the visually impaired. It integrates real-time ultrasonic sensing and GPS cloud monitoring to ensure safer independent navigation.
            </p>
            
            <div class="feature-tags">
                <div class="tag"><i class="fas fa-microchip"></i> IoT Integrated</div>
                <div class="tag"><i class="fas fa-cloud"></i> Cloud Sync</div>
                <div class="tag"><i class="fas fa-shield-alt"></i> SOS Ready</div>
            </div>
        </div>

        <div class="about-card specs-card glass-effect">
            <h4 class="section-label">ADMIN CAPABILITIES</h4>
            
            <ul class="capability-list">
                <li><i class="fas fa-map-marked-alt"></i> <strong>Live Geospatial Tracking</strong> - Real-time monitoring of every node using the Leaflet API.</li>
                <li><i class="fas fa-users-cog"></i> <strong>Node Management</strong> - Full control over user profiles, activation, and authentication logs.</li>
                <li><i class="fas fa-comments"></i> <strong>Neural Messaging</strong> - Direct communication link between the Command Center and Guardians.</li>
                <li><i class="fas fa-history"></i> <strong>System Logs</strong> - Detailed audit trail of all SOS signals and system activities.</li>
            </ul>
        </div>

        <div class="about-card status-card glass-effect">
            <h4 class="section-label">SYSTEM CORE</h4>
            <div class="status-row">
                <span>Core Engine</span>
                <span class="badge">PHP 8.2 / Firebase</span>
            </div>
            <div class="status-row">
                <span>Security Level</span>
                <span class="badge-green">SHA-256 ENCRYPTED</span>
            </div>
            <div class="status-row">
                <span>Database Status</span>
                <span class="text-dim">Connected (Real-time)</span>
            </div>
            
            <button class="update-btn" onclick="simulateUpdate()">
                <i class="fas fa-sync-alt"></i> RUN SYSTEM DIAGNOSTIC
            </button>
        </div>
    </div>
</div>

<style>
    :root {
        --primary-yellow: #f1c40f;
        --bg-dark: #050505; 
        --card-blue: rgba(26, 34, 56, 0.6);
        --text-gray: #aeb6bf;
    }

    /* Cinematic Background */
    #canvas-container {
        background-color: var(--bg-dark);
        position: fixed;
        top: 0; left: 0;
        width: 100%; height: 100%;
        z-index: 0;
        pointer-events: none;
    }

    .admin-wrapper {
        position: relative;
        z-index: 1;
        margin-left: 260px;
        margin-top: 75px;
        padding: 30px;
        min-height: 100vh;
        background: transparent;
        /* Standard Font */
        font-family: 'Poppins', Arial, sans-serif;
    }

    /* Glassmorphism Effect */
    .glass-effect {
        background: var(--card-blue) !important;
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
        border: 1px solid rgba(241, 196, 15, 0.1) !important;
        box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.8);
    }

    .cyber-title {
        color: var(--primary-yellow);
        text-shadow: 0 0 15px rgba(241, 196, 15, 0.5);
        letter-spacing: 1px;
        font-weight: 700;
    }

    .sys-subtitle {
        color: var(--text-gray);
        font-size: 0.85rem;
        letter-spacing: 0.5px;
    }

    .about-grid {
        display: grid;
        grid-template-columns: 1.5fr 1fr;
        gap: 25px;
        margin-top: 20px;
    }

    .about-card {
        border-radius: 20px;
        padding: 30px;
        transition: transform 0.3s ease, border 0.3s ease;
    }

    .about-card:hover { 
        transform: translateY(-5px); 
        border-color: var(--primary-yellow) !important; 
    }

    .brand-display { text-align: center; margin-bottom: 25px; }
    
    .pulse-icon {
        font-size: 4rem;
        color: var(--primary-yellow);
        animation: pulse-glow 2s infinite;
    }

    @keyframes pulse-glow {
        0% { filter: drop-shadow(0 0 5px var(--primary-yellow)); opacity: 0.8; }
        50% { filter: drop-shadow(0 0 25px var(--primary-yellow)); opacity: 1; }
        100% { filter: drop-shadow(0 0 5px var(--primary-yellow)); opacity: 0.8; }
    }

    .brand-display h3 {
        font-size: 2.2rem;
        margin: 15px 0 5px;
        color: var(--primary-yellow);
        font-weight: 700;
    }

    .brand-display span {
        font-size: 0.75rem;
        letter-spacing: 2px;
        color: var(--text-gray);
        text-transform: uppercase;
    }

    .mission-text {
        color: #fff;
        line-height: 1.8;
        font-weight: 400;
        text-align: center;
        font-size: 0.95rem;
    }

    .section-label {
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--primary-yellow);
        margin-bottom: 20px;
        border-bottom: 1px solid rgba(241, 196, 15, 0.2);
        padding-bottom: 10px;
    }

    .feature-tags { display: flex; gap: 10px; justify-content: center; margin-top: 25px; }
    
    .tag {
        background: rgba(241, 196, 15, 0.1);
        padding: 6px 15px;
        border-radius: 50px;
        font-size: 0.7rem;
        color: var(--primary-yellow);
        border: 1px solid rgba(241, 196, 15, 0.3);
    }

    .capability-list { list-style: none; padding: 0; }
    
    .capability-list li {
        margin-bottom: 18px;
        font-size: 0.85rem;
        color: #e0e0e0;
        display: flex; gap: 12px; align-items: flex-start;
    }

    .capability-list i { color: var(--primary-yellow); margin-top: 4px; font-size: 1rem; }

    .status-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 15px;
        font-size: 0.85rem;
    }

    .badge { background: #34495e; padding: 3px 10px; border-radius: 5px; font-size: 0.7rem; }
    .badge-green { background: #27ae60; padding: 3px 10px; border-radius: 5px; font-size: 0.7rem; font-weight: bold; }
    .text-dim { color: var(--text-gray); }

    .update-btn {
        width: 100%; padding: 14px;
        background: transparent;
        border: 1px solid var(--primary-yellow);
        color: var(--primary-yellow);
        font-weight: 600;
        font-size: 0.75rem;
        cursor: pointer; transition: 0.3s;
        margin-top: 15px;
        text-transform: uppercase;
    }

    .update-btn:hover { 
        background: var(--primary-yellow); 
        color: #000; 
        box-shadow: 0 0 20px rgba(241, 196, 15, 0.4);
    }

    @media (max-width: 992px) {
        .admin-wrapper { margin-left: 0; }
        .about-grid { grid-template-columns: 1fr; }
    }
</style>

<script>
    // --- THREE.JS CINEMATIC SMART CANE ---
    const scene = new THREE.Scene();
    const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
    const renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });
    
    renderer.setSize(window.innerWidth, window.innerHeight);
    document.getElementById('canvas-container').appendChild(renderer.domElement);

    const smartCane = new THREE.Group();

    // 1. Main Shaft
    const shaftGeom = new THREE.CylinderGeometry(0.05, 0.05, 4, 32);
    const shaftMat = new THREE.MeshBasicMaterial({ 
        color: 0xffffff, 
        wireframe: true, 
        transparent: true, 
        opacity: 0.15 
    });
    const shaft = new THREE.Mesh(shaftGeom, shaftMat);
    smartCane.add(shaft);

    // 2. Handle
    const handleGeom = new THREE.TorusGeometry(0.3, 0.05, 16, 100, Math.PI);
    const handleMat = new THREE.MeshBasicMaterial({ color: 0xf1c40f, wireframe: true });
    const handle = new THREE.Mesh(handleGeom, handleMat);
    handle.position.y = 2;
    handle.position.x = 0.3;
    handle.rotation.z = Math.PI;
    smartCane.add(handle);

    // 3. Sensor Head
    const sensorGeom = new THREE.SphereGeometry(0.15, 16, 16);
    const sensorMat = new THREE.MeshBasicMaterial({ color: 0xf1c40f, wireframe: true });
    const sensor = new THREE.Mesh(sensorGeom, sensorMat);
    sensor.position.y = 1.5;
    smartCane.add(sensor);

    // 4. Tech Rings
    const ringGeom = new THREE.TorusGeometry(0.25, 0.01, 8, 50);
    const ringMat = new THREE.MeshBasicMaterial({ color: 0xf1c40f, transparent: true, opacity: 0.4 });
    const ring1 = new THREE.Mesh(ringGeom, ringMat);
    const ring2 = new THREE.Mesh(ringGeom, ringMat);
    ring1.position.y = 1.5;
    ring2.position.y = 1.5;
    smartCane.add(ring1);
    smartCane.add(ring2);

    scene.add(smartCane);

    // Ambient Digital Dust
    const particlesGeometry = new THREE.BufferGeometry();
    const posArray = new Float32Array(600 * 3);
    for(let i=0; i < 600 * 3; i++) { posArray[i] = (Math.random() - 0.5) * 15; }
    particlesGeometry.setAttribute('position', new THREE.BufferAttribute(posArray, 3));
    const particlesMaterial = new THREE.PointsMaterial({ size: 0.005, color: 0xf1c40f });
    const particlesMesh = new THREE.Points(particlesGeometry, particlesMaterial);
    scene.add(particlesMesh);

    camera.position.z = 5;

    function animate() {
        requestAnimationFrame(animate);
        
        smartCane.rotation.y += 0.007;
        smartCane.rotation.z = Math.sin(Date.now() * 0.001) * 0.05;

        ring1.rotation.x += 0.03;
        ring2.rotation.y += 0.03;

        smartCane.position.y = Math.sin(Date.now() * 0.001) * 0.15;
        particlesMesh.rotation.y += 0.0003;
        
        renderer.render(scene, camera);
    }
    animate();

    window.addEventListener('resize', () => {
        camera.aspect = window.innerWidth / window.innerHeight;
        camera.updateProjectionMatrix();
        renderer.setSize(window.innerWidth, window.innerHeight);
    });

    // --- DIAGNOSTIC SYSTEM ---
    function simulateUpdate() {
        Swal.fire({
            title: 'Initializing Core Diagnostic...',
            html: 'Checking Firebase connectivity and security handshake.',
            timer: 2500,
            timerProgressBar: true,
            background: '#1a2238',
            color: '#fff',
            didOpen: () => { Swal.showLoading(); },
        }).then(() => {
            Swal.fire({
                icon: 'success',
                title: 'SYSTEM OPTIMIZED',
                text: 'WalkBuddy Command Center is running at peak performance.',
                background: '#1a2238',
                color: '#fff',
                confirmButtonColor: '#f1c40f'
            });
        });
    }
</script>

<?php include 'includes/footer.php'; ?>