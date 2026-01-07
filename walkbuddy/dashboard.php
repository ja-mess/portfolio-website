<?php
include 'auth_check.php'; // Eto ang guard mo
// dashboard.php
$page = 'dashboard';
include 'includes/header.php'; 
include 'includes/sidebar.php'; 

?>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

<style>
    :root {
        --accent-teal: #1abc9c;
        --wb-yellow: #f1c40f;
        --wb-dark-bg: #0b0d0f;
        --wb-card-bg: #1a2238;
        --wb-text-gray: #aeb6bf;
        --wb-red: #e74c3c;
        --header-height: 75px;
        --sidebar-width: 260px; /* Siguraduhing tugma ito sa sidebar mo */
    }

    body { 
        background-color: var(--wb-dark-bg) !important; 
        font-family: 'Poppins', sans-serif; 
        color: white; 
        margin: 0;
    }

    /* FIX: Main Layout Container */
    .admin-wrapper {
        margin-top: 75px;
        margin-left: var(--sidebar-width);
        padding: 25px;
        min-height: 100vh;
        transition: all 0.3s ease;
    }

    .summary-section { margin-bottom: 30px; }
    .summary-section h2 { font-weight: 700; color: #fff; margin: 0; }

    /* KPI Grid - Responsive */
    .kpi-container { 
        display: grid; 
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); 
        gap: 20px; 
        margin-bottom: 35px; 
    }

    .kpi-card { 
        background: var(--wb-card-bg); 
        padding: 25px; 
        border-radius: 18px; 
        border: 1px solid rgba(255, 255, 255, 0.05); 
        display: flex; 
        justify-content: space-between; 
        align-items: center;
        box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        transition: 0.3s;
    }

    .kpi-card:hover { transform: translateY(-5px); border-color: var(--accent-teal); }
    .kpi-value { font-size: 2rem; font-weight: 700; color: #fff; display: block; }
    .kpi-label { font-size: 0.75rem; color: var(--wb-text-gray); text-transform: uppercase; letter-spacing: 1px; }

    /* FEED AND SHORTCUTS GRID */
    .dashboard-grid { 
        display: grid; 
        grid-template-columns: 1.5fr 1fr; /* Mas malapad ang feed */
        gap: 25px; 
    }

    .alert-feed-container { 
        background: var(--wb-card-bg); 
        border-radius: 20px; 
        padding: 25px; 
        border: 1px solid rgba(255, 255, 255, 0.05);
        max-height: 600px;
        display: flex;
        flex-direction: column;
    }

    .feed-items { 
        overflow-y: auto; 
        padding-right: 10px;
        flex-grow: 1;
    }

    /* Feed Item Styling */
    .feed-item { 
        background: rgba(0,0,0,0.2); 
        padding: 15px; 
        border-radius: 12px; 
        margin-bottom: 15px; 
        border-left: 4px solid var(--accent-teal);
        transition: 0.2s;
        cursor: pointer;
    }
    .feed-item:hover { background: rgba(255,255,255,0.05); }
    .feed-item.emergency-type { border-left-color: var(--wb-red); background: rgba(231, 76, 60, 0.05); }

    /* SHORTCUT CARDS */
    .shortcut-card {
        background: var(--wb-card-bg);
        border: 1px solid rgba(255, 255, 255, 0.05);
        padding: 20px;
        border-radius: 15px;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 15px;
        text-decoration: none;
        color: white;
        transition: 0.3s;
    }
    .shortcut-card:hover { 
        background: rgba(26, 188, 156, 0.1); 
        border-color: var(--accent-teal);
        padding-left: 25px;
    }
    .shortcut-card i { font-size: 1.5rem; color: var(--accent-teal); }

    .pulse-red { width: 10px; height: 10px; background: var(--wb-red); border-radius: 50%; display: inline-block; animation: blink 1s infinite; margin-right: 8px; }
    @keyframes blink { 0% { opacity: 1; } 50% { opacity: 0.3; } 100% { opacity: 1; } }

    /* Mobile Responsive */
    @media (max-width: 1100px) {
        .admin-wrapper { margin-left: 0; padding: 20px; padding-top: 80px; }
        .dashboard-grid { grid-template-columns: 1fr; }
    }
</style>

<div class="admin-wrapper">
    <div class="summary-section">
        <h2><i class="fas fa-terminal" style="color: var(--accent-teal);"></i> Command Center</h2>
        <p style="color: var(--wb-text-gray); font-size: 0.9rem;">Real-time Telemetry & Security Stream</p>
    </div>

    <div class="kpi-container">
        <div class="kpi-card">
            <div><span class="kpi-value" id="totalUsers">0</span><span class="kpi-label">Active Users</span></div>
            <i class="fas fa-users" style="opacity:0.3; font-size:2rem;"></i>
        </div>
        <div class="kpi-card" style="border-right: 4px solid var(--wb-red);">
            <div><span class="kpi-value" id="totalEmergency" style="color: var(--wb-red);">0</span><span class="kpi-label">Active SOS</span></div>
            <i class="fas fa-exclamation-triangle" style="color: var(--wb-red); font-size:2rem;"></i>
        </div>
      <div class="kpi-card">
    <div>
        <span class="kpi-value" id="totalNotifications">0</span>
        <span class="kpi-label">Notifications</span>
    </div>
    <i class="fas fa-bell" style="opacity:0.3; font-size:2rem;"></i>
</div>
        <div class="kpi-card">
            <div><span class="kpi-value" id="totalDevices">0</span><span class="kpi-label">Tracking Units</span></div>
            <i class="fas fa-map-marker-alt" style="opacity:0.3; font-size:2rem;"></i>
        </div>
    </div>

    <div class="dashboard-grid">
        <div class="alert-feed-container">
            <h3 style="font-size: 0.9rem; margin-bottom: 20px; color: var(--wb-text-gray);">
                <i class="fas fa-satellite-dish" style="color: var(--accent-teal);"></i> LIVE SURVEILLANCE FEED
            </h3>
            <div class="feed-items" id="liveAlertFeed">
                </div>
        </div>

        <div class="shortcuts-column">
            <h3 style="font-size: 0.8rem; color: var(--wb-text-gray); margin-bottom: 15px; letter-spacing: 1px;">QUICK ACCESS</h3>
            
            <a href="map.php" class="shortcut-card">
                <i class="fas fa-map-marked-alt"></i>
                <div>
                    <strong style="display:block;">Tactical Map</strong>
                    <small style="color:var(--wb-text-gray);">Live GPS Monitoring</small>
                </div>
            </a>

            <a href="notification.php" class="shortcut-card">
                <i class="fas fa-bullhorn"></i>
                <div>
                    <strong style="display:block;">System Logs</strong>
                    <small style="color:var(--wb-text-gray);">View all notifications</small>
                </div>
            </a>

            <div style="padding: 20px; background: rgba(26, 188, 156, 0.05); border-radius: 15px; border: 1px dashed var(--accent-teal); margin-top: 20px;">
                <p style="font-size: 0.75rem; color: var(--accent-teal); font-weight: bold; margin-bottom: 5px;">SURVEILLANCE STATUS</p>
                <span style="font-size: 0.8rem; color: #2ecc71;"><i class="fas fa-check-circle"></i> Encryption Active</span>
            </div>
        </div>
    </div>
</div>

<script src="https://www.gstatic.com/firebasejs/9.6.10/firebase-app-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/9.6.10/firebase-firestore-compat.js"></script>

<script>
    const USER_FIREBASE_CONFIG = {
        apiKey: "AIzaSyC9zVfgdIYDBLKjCG4w1eqGkBYtChqFYEI",
        authDomain: "walkbuddy-system.firebaseapp.com",
        projectId: "walkbuddy-system",
        storageBucket: "walkbuddy-system.firebasestorage.app",
        messagingSenderId: "270646846701",
        appId: "1:270646846701:web:d70d456e9eb987a33ed8e4"
    };

    if (!firebase.apps.length) firebase.initializeApp(USER_FIREBASE_CONFIG);
    const db = firebase.firestore();

    function parseDate(field) {
        if (!field) return new Date();
        if (typeof field.toDate === 'function') return field.toDate();
        return new Date(field);
    }

    let feedData = new Map();

    function syncDashboard() {
        // Counters
        db.collection("users").onSnapshot(s => document.getElementById('totalUsers').textContent = s.size);
        db.collection("map").onSnapshot(s => document.getElementById('totalDevices').textContent = s.size);
        
        // --- NOTIFICATION & EMERGENCY COUNTERS ---
        let systemCount = 0;
        let emergencyCount = 0;

        const updateNotifUI = () => {
            // Total Notifications (Sum of all logs)
            document.getElementById('totalNotifications').textContent = systemCount + emergencyCount;
        };

        // Bilangin ang Emergency SOS (Active only - yung acknowledged ay false)
        db.collection("emergency_alerts").where("acknowledged", "==", false).onSnapshot(s => {
            document.getElementById('totalEmergency').textContent = s.size;
        });

        // Bilangin ang lahat para sa Notification Badge
        db.collection("alerts").doc("device1").collection("system_alert").onSnapshot(s => {
            systemCount = s.size;
            updateNotifUI();
        });
        db.collection("emergency_alerts").onSnapshot(s => {
            emergencyCount = s.size;
            updateNotifUI();
        });

        // --- FEED LISTENERS ---

        // Listen for regular alerts
        db.collection("alerts").doc("device1").collection("system_alert").orderBy('timestamp', 'desc').limit(10)
        .onSnapshot(snap => {
            snap.forEach(doc => feedData.set(doc.id, { ...doc.data(), id: doc.id, category: 'SYSTEM' }));
            renderFeed();
        });

        // Listen for emergency (Lahat kinukuha pero i-fi-filter sa renderFeed)
        db.collection("emergency_alerts").orderBy('timestamp', 'desc').limit(10)
        .onSnapshot(snap => {
            snap.forEach(doc => feedData.set(doc.id, { ...doc.data(), id: doc.id, category: 'EMERGENCY' }));
            renderFeed();
        });
    }

    function renderFeed() {
        const container = document.getElementById('liveAlertFeed');
        
        // FILTER: Kunin lang ang mga logs na HINDI pa acknowledged kung ito ay EMERGENCY
        const filteredData = Array.from(feedData.values()).filter(item => {
            if (item.category === 'EMERGENCY' && item.acknowledged === true) {
                return false; // Itago na
            }
            return true; // Ipakita ang System Alerts at Unacknowledged SOS
        });

        // SORT: Ayusin ayon sa pinakabago
        const sorted = filteredData.sort((a, b) => parseDate(b.timestamp) - parseDate(a.timestamp));
        
        container.innerHTML = "";

        if (sorted.length === 0) {
            container.innerHTML = "<p style='color:var(--wb-text-gray); font-size:0.8rem; text-align:center; margin-top:20px;'>No active alerts in the feed.</p>";
            return;
        }

        sorted.slice(0, 8).forEach(item => {
            const date = parseDate(item.timestamp);
            const timeStr = date.toLocaleTimeString('en-PH', { hour:'2-digit', minute:'2-digit' });
            const isSOS = item.category === 'EMERGENCY';

            const div = document.createElement('div');
            div.className = `feed-item ${isSOS ? 'emergency-type' : ''}`;
            div.onclick = () => {
                localStorage.setItem('autoOpenLogId', item.id);
                window.location.href = 'notification.php';
            };
            
            div.innerHTML = `
                <h4 style="margin:0; font-size:0.9rem; color:${isSOS ? 'var(--wb-red)' : 'var(--accent-teal)'};">
                    ${isSOS ? '<span class="pulse-red"></span>SOS: ' : '<i class="fas fa-info-circle"></i> '}${item.title || 'SYSTEM ALERT'}
                </h4>
                <p style="margin:5px 0; font-size:0.8rem; color:#fff; opacity:0.9;">${item.message}</p>
                <div style="display:flex; justify-content:space-between; margin-top:10px; opacity:0.6; font-size:0.75rem;">
                    <span><i class="far fa-clock"></i> ${timeStr}</span>
                    <span style="font-family:monospace;">ID: ${item.deviceId || 'SYS-01'}</span>
                </div>
            `;
            container.appendChild(div);
        });
    }

    window.onload = syncDashboard;
</script>

<?php include 'includes/footer.php'; ?>