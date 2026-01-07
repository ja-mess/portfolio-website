<?php
// users.php - User Management Page
$page = 'active'; 

include 'includes/header.php'; 
include 'includes/sidebar.php'; 
?>

<style>
    /* --- USER MANAGEMENT MIDNIGHT DESIGN --- */
    :root {
        --bg-dark: #0b0d0f;
        --card-blue: #1a2238;
        --accent-teal: #1abc9c;
        --accent-blue: #3498db;
        --accent-red: #e74c3c;
        --text-dim: #94a3b8;
        --header-height: 70px;
        --sidebar-width: 260px;
    }

    body {
        background-color: var(--bg-dark) !important;
        font-family: 'Poppins', sans-serif;
        margin: 0;
    }

    .admin-wrapper {
        margin-left: 260px; 
        margin-top: var(--header-height);
        padding: 25px;
        min-height: calc(100vh - var(--header-height));
        color: white;
    }

    .dashboard-header-section { margin-bottom: 30px; }
    .dashboard-header-section h2 { font-size: 1.5rem; margin: 0; font-weight: 700; }
    .dashboard-header-section p { color: var(--text-dim); font-size: 0.85rem; margin-top: 15px; }

    /* --- FILTER BAR --- */
    .filter-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: var(--card-blue);
        padding: 20px 25px;
        border-radius: 15px;
        border: 1px solid rgba(255, 255, 255, 0.05);
        margin-bottom: 25px;
        flex-wrap: wrap;
        gap: 20px;
    }

    .sort-options { display: flex; align-items: center; gap: 10px; }
    .sort-label { font-size: 0.75rem; color: var(--text-dim); text-transform: uppercase; margin-right: 5px; }

    .sort-btn {
        background: rgba(255, 255, 255, 0.05);
        border: none;
        color: var(--text-dim);
        padding: 8px 15px;
        border-radius: 8px;
        cursor: pointer;
        font-size: 0.8rem;
        transition: 0.3s;
    }

    .sort-btn.active { background: var(--accent-teal); color: #000; font-weight: 600; }

    /* --- USERS GRID --- */
    .users-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 20px;
    }

    .user-card {
        background: var(--card-blue);
        border-radius: 15px;
        border: 1px solid rgba(255, 255, 255, 0.05);
        padding: 25px;
        transition: 0.3s;
        position: relative;
        overflow: hidden;
    }

    .user-card:hover {
        transform: translateY(-5px);
        border-color: var(--accent-teal);
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    }

    .user-card-header {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 20px;
    }

    .user-avatar {
        width: 50px;
        height: 50px;
        background: rgba(26, 188, 156, 0.1);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--accent-teal);
        font-size: 1.2rem;
    }

    .user-title h4 { margin: 0; font-size: 1rem; font-weight: 600; }
    .user-status-tag {
        font-size: 0.65rem;
        background: rgba(46, 204, 113, 0.1);
        color: #2ecc71;
        padding: 2px 8px;
        border-radius: 4px;
        text-transform: uppercase;
        font-weight: 700;
    }

    .user-card-body {
        background: rgba(0,0,0,0.15);
        border-radius: 10px;
        padding: 15px;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        font-size: 0.8rem;
    }

    .info-row .label { color: var(--text-dim); }
    .info-row .val { color: #fff; font-weight: 500; }

    /* --- ACTION BUTTONS --- */
    .btn-deactivate {
        width: 100%;
        margin-top: 15px;
        padding: 10px;
        border-radius: 8px;
        border: 1px solid rgba(231, 76, 60, 0.3);
        background: rgba(231, 76, 60, 0.1);
        color: var(--accent-red);
        font-size: 0.75rem;
        font-weight: 600;
        cursor: pointer;
        transition: 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn-deactivate:hover {
        background: var(--accent-red);
        color: white;
    }

    .user-card-footer {
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px solid rgba(255,255,255,0.05);
        font-size: 0.7rem;
        color: var(--text-dim);
        font-family: monospace;
    }

    .loading-state { text-align: center; padding: 100px; grid-column: 1 / -1; color: var(--text-dim); }
    .loading-state i { font-size: 2rem; color: var(--accent-teal); margin-bottom: 10px; }

    @media (max-width: 768px) {
        .admin-wrapper { margin-left: 0; }
        .filter-container { flex-direction: column; align-items: stretch; }
    }
</style>

<div class="admin-wrapper">
    <main class="content-area">
        <div class="dashboard-header-section">
            <h2><i class="fas fa-users-cog" style="color: var(--accent-teal);"></i> Active Users</h2>
            <p>Managing active telemetry nodes in the WalkBuddy system.</p>
        </div>

        <div class="filter-container">
            <div class="sort-options">
                <span class="sort-label">Order By:</span>
                <button class="sort-btn" data-sort-key="full-name" onclick="handleSort('full-name', 'string')">Name</button>
                <button class="sort-btn" data-sort-key="age" onclick="handleSort('age', 'number')">Age</button>
                <button class="sort-btn" data-sort-key="finger-id" onclick="handleSort('finger-id', 'number')">Finger ID</button>
                <button class="sort-btn active" data-sort-key="timestamp" onclick="handleSort('timestamp', 'date')">Recent</button>
            </div>
        </div>
        
        <div id="loadingIndicator" class="loading-state">
            <i class="fas fa-circle-notch fa-spin"></i>
            <p>Syncing User Records...</p>
        </div>

        <div id="dataGrid" class="users-grid"></div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script type="module">
    import { initializeApp } from "https://www.gstatic.com/firebasejs/9.6.10/firebase-app.js";
    import { getAuth, signInAnonymously, onAuthStateChanged } from "https://www.gstatic.com/firebasejs/9.6.10/firebase-auth.js";
    import { getFirestore, collection, onSnapshot, query, doc, updateDoc } from "https://www.gstatic.com/firebasejs/9.6.10/firebase-firestore.js";

    const USER_FIREBASE_CONFIG = {
        apiKey: "AIzaSyC9zVfgdIYDBLKjCG4w1eqGkBYtChqFYEI",
        authDomain: "walkbuddy-system.firebaseapp.com",
        projectId: "walkbuddy-system",
        storageBucket: "walkbuddy-system.firebasestorage.app",
        messagingSenderId: "270646846701",
        appId: "1:270646846701:web:d70d456e9eb987a33ed8e4"
    };

    const app = initializeApp(USER_FIREBASE_CONFIG);
    const auth = getAuth(app);
    const db = getFirestore(app);
    
    let isAuthReady = false;
    let currentSortKey = 'timestamp';
    let sortDirection = -1; 

    onAuthStateChanged(auth, async (user) => {
        if (!user) { try { await signInAnonymously(auth); } catch (e) { console.error(e); } }
        isAuthReady = true;
        fetchRealTimeData();
    });

    function fetchRealTimeData() {
        if (!isAuthReady) return;
        const q = query(collection(db, 'active'));
        onSnapshot(q, (snapshot) => {
            let dataArr = [];
            snapshot.forEach(doc => {
                const data = doc.data();
                // Tanging ang active=true lang ang isasama sa display
                if (data.active === true) dataArr.push({ id: doc.id, ...data });
            });
            document.getElementById('loadingIndicator').style.display = 'none';
            renderGrid(dataArr);
        });
    }

    function renderGrid(data) {
        const grid = document.getElementById('dataGrid');
        grid.innerHTML = '';
        if (data.length === 0) {
            grid.innerHTML = `<div class="loading-state" style="grid-column: 1/-1;">No active users in database.</div>`;
            return;
        }

        data.sort((a, b) => {
            let valA = a[currentSortKey]?.seconds || 0;
            let valB = b[currentSortKey]?.seconds || 0;
            return (valB - valA);
        });

        data.forEach(item => { 
            const joined = item.timestamp ? new Date(item.timestamp.seconds * 1000).toLocaleDateString() : 'N/A';
            const bday = item.birthday ? new Date(item.birthday.seconds * 1000).toLocaleDateString() : 'N/A';
            
            const card = document.createElement("div");
            card.className = "user-card";
            card.innerHTML = `
                <div class="user-card-header">
                    <div class="user-avatar"><i class="fas fa-user-circle"></i></div>
                    <div class="user-title">
                        <h4>${item.full_name || 'Anonymous User'}</h4>
                        <span class="user-status-tag">Status: Online</span>
                    </div>
                </div>
                <div class="user-card-body">
                    <div class="info-row"><span class="label">Finger ID</span><span class="val">#${item.finger_id || '0'}</span></div>
                    <div class="info-row"><span class="label">Age</span><span class="val">${item.age || '0'} y/o</span></div>
                    <div class="info-row"><span class="label">Birthday</span><span class="val">${bday}</span></div>
                    <div class="info-row"><span class="label">Sync Date</span><span class="val">${joined}</span></div>
                </div>
                
                <button class="btn-deactivate" onclick="handleDeactivate('${item.id}', '${item.full_name}')">
                    <i class="fas fa-user-slash"></i> DEACTIVATE USER
                </button>

                <div class="user-card-footer">REF: ${item.id.substring(0,15)}</div>
            `;

            card.setAttribute('data-full-name', item.full_name || '');
            card.setAttribute('data-age', item.age || 0);
            card.setAttribute('data-finger-id', item.finger_id || 0);
            card.setAttribute('data-timestamp', item.timestamp?.seconds || 0);
            grid.appendChild(card);
        });
    }

    // --- DEACTIVATE LOGIC ---
    window.handleDeactivate = async function(docId, userName) {
        const result = await Swal.fire({
            title: 'Confirm Deactivation?',
            text: `User ${userName} will be set to inactive and removed from this view.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e74c3c',
            cancelButtonColor: '#1a2238',
            confirmButtonText: 'Yes, Deactivate',
            background: '#1a2238',
            color: '#fff'
        });

        if (result.isConfirmed) {
            try {
                const userRef = doc(db, 'active', docId);
                await updateDoc(userRef, {
                    active: false
                });
                Swal.fire({
                    title: 'Success!',
                    text: 'User has been deactivated.',
                    icon: 'success',
                    background: '#1a2238',
                    color: '#fff'
                });
            } catch (error) {
                console.error("Error updating document: ", error);
                Swal.fire('Error', 'Failed to update status.', 'error');
            }
        }
    };

    window.handleSort = function(key, type) {
        const grid = document.getElementById('dataGrid');
        sortDirection = (currentSortKey === key) ? sortDirection * -1 : -1;
        currentSortKey = key;
        
        const cards = Array.from(grid.querySelectorAll('.user-card'));
        cards.sort((a, b) => {
            let valA = a.getAttribute(`data-${key}`);
            let valB = b.getAttribute(`data-${key}`);
            let res = (type === 'number' || type === 'date') ? parseFloat(valA) - parseFloat(valB) : valA.localeCompare(valB);
            return res * sortDirection;
        });

        document.querySelectorAll('.sort-btn').forEach(btn => btn.classList.toggle('active', btn.getAttribute('data-sort-key') === key));
        cards.forEach(card => grid.appendChild(card));
    };

</script>

<?php include 'includes/footer.php'; ?>