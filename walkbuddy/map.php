<?php

include 'auth_check.php'; // Eto ang guard mo

// map.php - Admin Panel Live Tracking
$page = 'map'; 

include 'includes/header.php'; 
include 'includes/sidebar.php'; 
?>

<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

<style>
    /* --- MAP PAGE MIDNIGHT THEME --- */
    :root {
        --bg-dark: #0b0d0f;
        --card-blue: #1a2238;
        --accent-teal: #1abc9c;
        --accent-blue: #3498db;
        --text-dim: #94a3b8;
        --wb-danger: #e74c3c;
        --wb-success: #2ecc71;
        --sidebar-width: 250px;
        --header-height: 70px;
    }

    body { background-color: var(--bg-dark) !important; color: white; font-family: 'Poppins', sans-serif; }

    .admin-wrapper {
        
 margin-left: 250px; /* Sidebar width */
        margin-top: var(--header-height);
        padding: 25px;
    }

    .dashboard-header-section { margin-bottom: 20px; }
    .dashboard-header-section h2 { font-size: 1.5rem; margin: 0; font-weight: 700; color: #fff; }
    .dashboard-header-section p { color: var(--text-dim); font-size: 0.85rem; margin-top: 5px; }

    /* --- LAYOUT GRID --- */
    .map-grid {
        display: grid;
        grid-template-columns: 1fr 350px;
        gap: 20px;
        height: calc(100vh - 200px);
        min-height: 600px;
    }

    .map-card {
        background: var(--card-blue);
        border-radius: 15px;
        border: 1px solid rgba(255, 255, 255, 0.05);
        overflow: hidden;
        position: relative;
    }

    #map { height: 100%; width: 100%; z-index: 1; background: #0e1626; }

    /* --- TRACKING SIDEBAR --- */
    .tracking-sidebar {
        background: var(--card-blue);
        border-radius: 15px;
        border: 1px solid rgba(255, 255, 255, 0.05);
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    .sidebar-header {
        padding: 20px;
        background: rgba(0,0,0,0.2);
        border-bottom: 1px solid rgba(255,255,255,0.05);
    }

    .sidebar-header h5 { margin: 0; font-size: 1rem; color: var(--accent-teal); }

    #device-data-wrapper {
        flex: 1;
        overflow-y: auto;
        padding: 15px;
    }

    /* --- DEVICE CARDS --- */
    .device-wrapper {
        margin-bottom: 15px;
        background: rgba(0,0,0,0.2);
        border-radius: 12px;
        border: 1px solid rgba(255,255,255,0.03);
        transition: 0.3s;
    }

    .device-card {
        padding: 15px;
        cursor: pointer;
    }

    .device-card h2 {
        font-size: 0.95rem;
        margin: 0 0 8px 0;
        display: flex;
        align-items: center;
        color: #fff;
    }

    .card-status-indicator {
        font-size: 0.7rem;
        padding: 3px 8px;
        border-radius: 4px;
        text-transform: uppercase;
        font-weight: 700;
    }

    .status-active { background: rgba(46, 204, 113, 0.15); color: var(--wb-success); }
    .status-inactive { background: rgba(231, 76, 60, 0.15); color: var(--wb-danger); }

    .card-selected {
        border: 1px solid var(--accent-teal);
        background: rgba(26, 188, 156, 0.05);
    }

    .card-actions {
        padding: 0 15px 15px 15px;
    }

    .btn-details {
        width: 100%;
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.1);
        color: var(--text-dim);
        padding: 8px;
        border-radius: 6px;
        font-size: 0.75rem;
        cursor: pointer;
        transition: 0.3s;
    }

    .btn-details:hover { background: var(--accent-blue); color: white; }

    /* Custom Scrollbar */
    #device-data-wrapper::-webkit-scrollbar { width: 5px; }
    #device-data-wrapper::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }

 /* --- CUSTOM SWEETALERT MIDNIGHT DESIGN --- */
.midnight-modal {
    background: var(--card-blue) !important;
    border: 1px solid rgba(255, 255, 255, 0.1) !important;
    border-radius: 20px !important;
    color: white !important;
}

.midnight-title {
    color: var(--accent-teal) !important;
    font-size: 1.4rem !important;
    font-weight: 700 !important;
}

.midnight-content {
    color: var(--text-dim) !important;
}

.modal-detail-container {
    padding: 10px 0;
}

.modal-detail-field {
    background: rgba(0,0,0,0.2);
    padding: 12px 15px;
    border-radius: 10px;
    margin-bottom: 8px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border: 1px solid rgba(255,255,255,0.03);
}

.modal-detail-field strong {
    color: var(--accent-blue);
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.modal-detail-field span {
    color: #fff;
    font-weight: 500;
}

.modal-address-box {
    background: rgba(26, 188, 156, 0.1);
    border-left: 4px solid var(--accent-teal);
    padding: 15px;
    border-radius: 8px;
    text-align: left;
    margin-top: 15px;
}

.modal-address-box small {
    display: block;
    color: var(--accent-teal);
    font-weight: 700;
    margin-bottom: 5px;
    font-size: 0.75rem;
}

.modal-address-box p {
    margin: 0;
    font-size: 0.85rem;
    color: #fff;
    line-height: 1.5;
}
    @media (max-width: 1024px) {
        .admin-wrapper { margin-left: 0; }
        .map-grid { grid-template-columns: 1fr; height: auto; }
        #map { height: 400px; }
    }

    /* Pulse Effect para sa Active Marker */
.leaflet-marker-icon {
    filter: drop-shadow(0 0 5px rgba(52, 152, 219, 0.5));
}

@keyframes pulse {
    0% { transform: scale(0.95); opacity: 0.7; }
    70% { transform: scale(1.1); opacity: 0.3; }
    100% { transform: scale(0.95); opacity: 0; }
}

.pulse-marker::after {
    content: '';
    display: block;
    width: 35px;
    height: 35px;
    background: #3498db;
    border-radius: 50%;
    animation: pulse 2s infinite;
    position: absolute;
    top: 0;
    left: 0;
}

/* Customizing the Leaflet Layer Control to match your Midnight Theme */
.leaflet-control-layers {
    background: var(--card-blue) !important;
    color: white !important;
    border: 1px solid rgba(255, 255, 255, 0.1) !important;
    border-radius: 12px !important;
    padding: 10px !important;
    font-family: 'Poppins', sans-serif;
    box-shadow: 0 4px 15px rgba(0,0,0,0.5) !important;
}

.leaflet-control-layers-list {
    margin-bottom: 0;
}

.leaflet-control-layers label {
    margin-bottom: 5px;
    cursor: pointer;
}

.leaflet-control-layers-base input {
    margin-right: 8px;
}
</style>

<div class="admin-wrapper">
    <main class="content-area">
        <div class="dashboard-header-section">
            <h2><i class="fas fa-satellite-dish" style="color: var(--accent-teal);"></i> Live Tracking Map</h2>
            <p>Real-time geospatial monitoring of active WalkBuddy devices.</p>
        </div>

        <div class="map-grid">
            <div class="map-card">
                <div id="map"></div>
            </div>

            <div class="tracking-sidebar">
                <div class="sidebar-header">
                    <h5><i class="fas fa-microchip"></i> Active Nodes</h5>
                </div>
                <div id="device-data-wrapper">
                    <div class="loading-message" style="text-align: center; padding: 40px; color: var(--text-dim);">
                        <i class="fas fa-circle-notch fa-spin"></i>
                        <p style="margin-top:10px; font-size: 0.8rem;">Syncing with Satellite...</p>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://www.gstatic.com/firebasejs/9.6.1/firebase-app-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/9.6.1/firebase-firestore-compat.js"></script>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
    // --- FIREBASE CONFIGURATION ---
    const FIREBASE_CONFIG = {
        apiKey: "AIzaSyC9zVfgdIYDBLKjCG4w1eqGkBYtChqFYEI",
        authDomain: "walkbuddy-system.firebaseapp.com",
        projectId: "walkbuddy-system",
        storageBucket: "walkbuddy-system.firebasestorage.app",
        messagingSenderId: "270646846701",
        appId: "1:270646846701:web:d70d456e9eb987a33ed8e4"
    };
    
    const PROXY_URL = 'get_address.php'; 
    firebase.initializeApp(FIREBASE_CONFIG);
    const db = firebase.firestore();
    
    // --- LEAFLET INITIALIZATION ---
    // Gagamit tayo ng "Dark Mode" tiles para sa mapa
    // --- MAP LAYER OPTIONS (SWITCHER) ---
// Midnight Dark Style
const darkMap = L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
    subdomains: 'abcd',
    maxZoom: 20
});

// Satellite View Style
const satelliteMap = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
    attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EBP, and the GIS User Community'
});

// Light/Standard Style
const standardMap = L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
    attribution: '&copy; OpenStreetMap &copy; CARTO'
});

// --- INITIALIZE MAP ---
const map = L.map('map', {
    center: [14.5995, 120.9842],
    zoom: 12,
    layers: [darkMap] // Default na naka-Dark Mode
});

// --- LAYER CONTROL SWITCHER ---
const baseMaps = {
    "<span style='color: #1abc9c; font-weight: bold;'>Midnight Dark</span>": darkMap,
    "Satellite View": satelliteMap,
    "Standard Map": standardMap
};

// Idadagdag ang button/control sa kanang itaas ng mapa
L.control.layers(baseMaps, null, { position: 'topright', collapsed: false }).addTo(map);
// CartoDB Voyager - Malinis, modern, at sakto ang contrast sa kulay
// CartoDB Dark Matter - Dark base pero sobrang linaw ng kalsada (High Contrast)
// Palitan ang URL sa loob ng L.tileLayer
L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
    // ... config
}).addTo(map);

    const markers = {};
    const polylines = {}; 
    const startMarkers = {}; 
    let allDeviceData = {}; 
    let selectedDeviceId = null; 

    // Custom Icons
// Icon para sa Live Position (Blue Pin)
const deviceIcon = L.icon({
    iconUrl: 'https://cdn-icons-png.flaticon.com/512/2776/2776067.png', 
    iconSize: [38, 38],
    iconAnchor: [19, 38],
    popupAnchor: [0, -38]
});

// Icon para sa Start Location (Green Flag)
const startIcon = L.icon({ 
    iconUrl: 'https://cdn-icons-png.flaticon.com/512/1053/1053916.png',
    iconSize: [30, 30],
    iconAnchor: [5, 30],
    popupAnchor: [10, -30]
});

    // Haversine
    function calculateDistance(lat1, lon1, lat2, lon2) {
        const R = 6371e3; 
        const p1 = lat1 * Math.PI/180;
        const p2 = lat2 * Math.PI/180;
        const dp = (lat2-lat1) * Math.PI/180;
        const dl = (lon2-lon1) * Math.PI/180;
        const a = Math.sin(dp/2)**2 + Math.cos(p1)*Math.cos(p2)*Math.sin(dl/2)**2;
        return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    }

    // Realtime Listener
    db.collection('map').onSnapshot(snapshot => {
        const container = document.getElementById('device-data-wrapper');
        let html = '';
        let bounds = L.latLngBounds();

        snapshot.forEach(doc => {
            const id = doc.id;
            const data = doc.data();
            const lat = parseFloat(data.latitude);
            const lng = parseFloat(data.longitude);
            const status = data.status || 'Offline';
            const time = data.timestamp?.toDate ? data.timestamp.toDate().toLocaleString() : 'N/A';

            allDeviceData[id] = { ...data, latitude: lat, longitude: lng, timestamp: time };

            if (!isNaN(lat) && !isNaN(lng)) {
                const pos = [lat, lng];
                if (markers[id]) {
                    markers[id].setLatLng(pos).setPopupContent(`<b>${id}</b><br>${status}`);
                } else {
                    markers[id] = L.marker(pos, {icon: deviceIcon}).addTo(map).bindPopup(`<b>${id}</b>`);
                }
                bounds.extend(pos);
            }

            const isFixed = status.toLowerCase().includes('fixed');
            html += `
                <div class="device-wrapper ${id === selectedDeviceId ? 'card-selected' : ''}">
                    <div class="device-card" onclick="handleDeviceCardClick('${id}')">
                        <h2><i class="fas fa-microchip" style="color: ${isFixed ? 'var(--wb-success)' : 'var(--wb-danger)'}"></i> ${id}</h2>
                        <span class="card-status-indicator ${isFixed ? 'status-active' : 'status-inactive'}">${status}</span>
                    </div>
                    <div class="card-actions">
                        <button class="btn-details" onclick="showDeviceDetails('${id}')"><i class="fas fa-info-circle"></i> View Details</button>
                    </div>
                </div>`;
        });

        container.innerHTML = html || '<p style="text-align:center">No active devices.</p>';
        if (Object.keys(markers).length > 0 && !selectedDeviceId) map.fitBounds(bounds, {padding:[50,50]});
    });

    async function handleDeviceCardClick(id) {
        selectedDeviceId = id;
        const data = allDeviceData[id];
        
        // Remove old traces
        Object.values(polylines).forEach(p => map.removeLayer(p));
        Object.values(startMarkers).forEach(s => map.removeLayer(s));

        if (data && !isNaN(data.latitude)) {
            const history = await getDeviceHistory(id);
            const points = history.map(p => [p.latitude, p.longitude]);
            points.push([data.latitude, data.longitude]);

            if (points.length > 1) {
                polylines[id] = L.polyline(points, {color: '#3498db', weight: 4, dashArray: '5, 10'}).addTo(map);
                startMarkers[id] = L.marker(points[0], {icon: startIcon}).addTo(map).bindPopup('Session Start');
                map.fitBounds(polylines[id].getBounds(), {padding: [50, 50]});
            } else {
                map.setView([data.latitude, data.longitude], 16);
            }
            markers[id].openPopup();
        }
    }

    async function getDeviceHistory(id) {
        const snap = await db.collection('map').doc(id).collection('history').orderBy('timestamp', 'asc').get();
        return snap.docs.map(doc => ({
            latitude: parseFloat(doc.data().latitude),
            longitude: parseFloat(doc.data().longitude)
        })).filter(p => !isNaN(p.latitude));
    }

async function showDeviceDetails(id) {
    const data = allDeviceData[id];
    
    // Loading State with Midnight Style
    Swal.fire({ 
        title: 'Syncing Data...', 
        background: '#1a2238',
        color: '#fff',
        didOpen: () => Swal.showLoading() 
    });

    const history = await getDeviceHistory(id);
    const dist = (calculateTotalDistance(history, data.latitude, data.longitude) / 1000).toFixed(2);
    const addr = await reverseGeocode(data.latitude, data.longitude);

    Swal.fire({
        customClass: {
            popup: 'midnight-modal',
            title: 'midnight-title',
            htmlContainer: 'midnight-content'
        },
        title: `<i class="fas fa-satellite"></i> Device Terminal: ${id}`,
        html: `
            <div class="modal-detail-container">
                <div class="modal-detail-field">
                    <strong>Signal Status</strong> 
                    <span style="color: ${data.status.toLowerCase().includes('fixed') ? '#2ecc71' : '#e74c3c'}">
                        <i class="fas fa-circle" style="font-size: 8px;"></i> ${data.status}
                    </span>
                </div>
                <div class="modal-detail-field">
                    <strong>Tracked Distance</strong> 
                    <span>${dist} km</span>
                </div>
                <div class="modal-detail-field">
                    <strong>Telemetry</strong> 
                    <span>${data.latitude.toFixed(5)}, ${data.longitude.toFixed(5)}</span>
                </div>
                <div class="modal-detail-field">
                    <strong>Last Ping</strong> 
                    <span>${data.timestamp}</span>
                </div>
                
                <div class="modal-address-box">
                    <small><i class="fas fa-map-marker-alt"></i> GEO-LOCATION</small>
                    <p>${addr}</p>
                </div>
            </div>
        `,
        confirmButtonText: 'Close Terminal',
        confirmButtonColor: '#3498db',
        buttonsStyling: true,
        showClass: { popup: 'animate__animated animate__fadeInDown' },
        hideClass: { popup: 'animate__animated animate__fadeOutUp' }
    });
}

    function calculateTotalDistance(pts, curLat, curLon) {
        let d = 0;
        const all = [...pts, {latitude: curLat, longitude: curLon}];
        for(let i=1; i<all.length; i++) d += calculateDistance(all[i-1].latitude, all[i-1].longitude, all[i].latitude, all[i].longitude);
        return d;
    }

    async function reverseGeocode(lat, lng) {
        try {
            const res = await fetch(`${PROXY_URL}?lat=${lat}&lon=${lng}`);
            const d = await res.json();
            return d.display_name || "Address unknown";
        } catch { return "Service unavailable"; }
    }
</script>

<?php include 'includes/footer.php'; ?>