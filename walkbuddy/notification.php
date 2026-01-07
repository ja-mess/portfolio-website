<?php

include 'auth_check.php'; // Eto ang guard mo

// notification.php - Universal System Activity Logs
$page = 'notifications';

include 'includes/header.php'; 
include 'includes/sidebar.php'; 
?>

<link rel="stylesheet" href="assets/css/cyber-swal.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    :root {
        --bg-dark: #0b0d0f;
        --card-blue: #1a2238;
        --accent-teal: #1abc9c;
        --accent-blue: #3498db;
        --accent-red: #e74c3c;
        --accent-yellow: #f1c40f;
        --text-dim: #94a3b8;
        --header-height: 75px;
        --sidebar-width: 260px;
    }

    body { background-color: var(--bg-dark) !important; font-family: 'Poppins', sans-serif; margin: 0; color: white; }

    .admin-wrapper {
        margin-left: var(--sidebar-width);
        margin-top: var(--header-height);
        padding: 25px;
        transition: all 0.3s ease;
    }

    .dashboard-header-section { margin-bottom: 30px; }
    .dashboard-header-section h2 { font-size: 1.5rem; font-weight: 700; margin: 0; color: #fff; }

    .log-container {
        background: var(--card-blue);
        border-radius: 15px;
        border: 1px solid rgba(255, 255, 255, 0.05);
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    }

    .table-responsive { width: 100%; overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; min-width: 900px; }
    th { text-align: left; padding: 18px 20px; background: rgba(0, 0, 0, 0.2); color: var(--accent-teal); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; }
    td { padding: 15px 20px; border-bottom: 1px solid rgba(255, 255, 255, 0.03); font-size: 0.85rem; vertical-align: middle; }

    /* BADGES */
    .type-badge { padding: 4px 10px; border-radius: 5px; font-size: 0.7rem; font-weight: bold; text-transform: uppercase; }
    .bg-reg { background: rgba(26, 188, 156, 0.2); color: var(--accent-teal); }
    .bg-edit { background: rgba(52, 152, 219, 0.2); color: var(--accent-blue); }
    .bg-pass { background: rgba(241, 196, 15, 0.2); color: var(--accent-yellow); }
    .bg-alert { background: rgba(231, 76, 60, 0.2); color: var(--accent-red); }
    .bg-emergency { background: #e74c3c; color: white; animation: pulse-red 1.5s infinite; }
    
    @keyframes pulse-red { 0% { box-shadow: 0 0 0 0 rgba(231, 76, 60, 0.7); } 70% { box-shadow: 0 0 0 10px rgba(231, 76, 60, 0); } 100% { box-shadow: 0 0 0 0 rgba(231, 76, 60, 0); } }

    .view-btn { background: rgba(255,255,255,0.05); border: none; color: white; width: 35px; height: 35px; border-radius: 8px; cursor: pointer; transition: 0.3s; margin-right: 5px; }
    .view-btn:hover { background: var(--accent-teal); color: black; }
    .del-btn { background: rgba(231, 76, 60, 0.1); border: none; color: var(--accent-red); width: 35px; height: 35px; border-radius: 8px; cursor: pointer; transition: 0.3s; }
    .del-btn:hover { background: var(--accent-red); color: white; }

    /* FIXED & SCROLLABLE MODAL */
    .modal { display: none; position: fixed; z-index: 2000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.85); backdrop-filter: blur(10px); }
    
    .modal-content { 
        background: var(--card-blue); 
        margin: 5% auto; 
        padding: 0; /* Changed to 0 para sa internal padding control */
        border-radius: 15px; 
        width: 90%; 
        max-width: 550px; 
        border: 1px solid rgba(255,255,255,0.1); 
        max-height: 80vh; /* Para hindi lumagpas sa screen */
        display: flex;
        flex-direction: column;
        overflow: hidden; /* Pinipigilan ang content na lumabas sa border-radius */
    }

    .modal-header {
        padding: 20px 25px;
        border-bottom: 1px solid rgba(255,255,255,0.05);
        background: rgba(0,0,0,0.2);
    }

    #modalBody { 
        padding: 20px 25px;
        overflow-y: auto; /* Dito papasok ang scrollbar */
        flex: 1; /* Kakainin nito ang space sa gitna */
    }

    .modal-footer {
        padding: 15px 25px;
        border-top: 1px solid rgba(255,255,255,0.05);
        background: rgba(0,0,0,0.1);
    }
    
    .detail-item { background: rgba(0,0,0,0.2); padding: 12px; border-radius: 8px; margin-bottom: 10px; border-left: 3px solid var(--accent-teal); }
    .detail-item label { display: block; font-size: 0.65rem; color: var(--accent-teal); text-transform: uppercase; margin-bottom: 3px; font-weight: bold; }
    .detail-item p { margin: 0; word-break: break-all; font-size: 0.9rem; color: #fff; }

    /* Custom Scrollbar para sa Modal Body */
    #modalBody::-webkit-scrollbar { width: 6px; }
    #modalBody::-webkit-scrollbar-thumb { background: rgba(26, 188, 156, 0.3); border-radius: 10px; }
    #modalBody::-webkit-scrollbar-thumb:hover { background: var(--accent-teal); }

    /* Cyber SWAL Styling */
    .cyber-swal-popup { background: #0b0d0f !important; border: 1px solid #f1c40f !important; color: #fff !important; }
    .cyber-swal-title { color: #f1c40f !important; font-family: 'Orbitron', sans-serif !important; }

    @media (max-width: 1100px) { .admin-wrapper { margin-left: 0; padding: 15px; } }
</style>

<div class="admin-wrapper">
    <div class="dashboard-header-section">
        <h2><i class="fas fa-microchip" style="color: var(--accent-teal);"></i> System Activity Terminal</h2>
        <p style="color: var(--text-dim); font-size: 0.85rem;">Emergency alerts are prioritized at the top.</p>
    </div>

    <div class="log-container">
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>TIMESTAMP (PH)</th>
                        <th>CATEGORY</th>
                        <th>ACTIVITY / MESSAGE</th>
                        <th>TARGET / DEVICE</th>
                        <th>ACTION</th>
                    </tr>
                </thead>
                <tbody id="universal-logs-body"></tbody>
            </table>
        </div>
    </div>
</div>

<div id="universalModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="modalTitle" style="margin:0; color:var(--accent-teal); font-size: 1.2rem;">Log Details</h2>
        </div>
        
        <div id="modalBody">
            </div>

        <div class="modal-footer">
            <button class="view-btn" style="width:100%; height:45px; margin:0;" onclick="closeModal()">CLOSE TERMINAL</button>
        </div>
    </div>
</div>

<script src="https://www.gstatic.com/firebasejs/9.6.10/firebase-app-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/9.6.10/firebase-firestore-compat.js"></script>

<script>
    const firebaseConfig = {
        apiKey: "AIzaSyC9zVfgdIYDBLKjCG4w1eqGkBYtChqFYEI",
        authDomain: "walkbuddy-system.firebaseapp.com",
        projectId: "walkbuddy-system",
        storageBucket: "walkbuddy-system.firebasestorage.app",
        messagingSenderId: "270646846701",
        appId: "1:270646846701:web:d70d456e9eb987a33ed8e4"
    };

    if (!firebase.apps.length) firebase.initializeApp(firebaseConfig);
    const db = firebase.firestore();

    let allLogsMap = new Map();

    function startUniversalListener() {
        const paths = [
            { ref: db.collection('emergency_alerts'), type: 'EMERGENCY', collectionName: 'emergency_alerts' }, 
            { ref: db.collection('notifications').doc('account_created').collection('events'), type: 'REGISTRATION', collectionName: 'notifications/account_created/events' },
            { ref: db.collection('notifications').doc('password_change').collection('events'), type: 'PASSWORD', collectionName: 'notifications/password_change/events' },
            { ref: db.collection('user_edits'), type: 'EDIT', collectionName: 'user_edits' },
            { ref: db.collection('alerts').doc('device1').collection('system_alert'), type: 'ALERT', collectionName: 'alerts/device1/system_alert' }
        ];

        paths.forEach(pathObj => {
            pathObj.ref.onSnapshot(snapshot => {
                snapshot.docChanges().forEach(change => {
                    const data = change.doc.data();
                    const id = change.doc.id;
                    
                    if (change.type === "removed") {
                        allLogsMap.delete(id);
                        renderTable();
                    } else if (change.type === "added" || change.type === "modified") {
                        let finalDate = new Date();
                        if (data.timestamp?.toDate) finalDate = data.timestamp.toDate();
                        else if (data.created_at?.toDate) finalDate = data.created_at.toDate();
                        else if (data.edited_at?.toDate) finalDate = data.edited_at.toDate();
                        else if (typeof data.timestamp === 'string') finalDate = new Date(data.timestamp);
                        
                        allLogsMap.set(id, {
                            id: id,
                            category: pathObj.type,
                            collectionPath: pathObj.collectionName,
                            timestamp: finalDate,
                            displayTime: data.timestamp_ph || finalDate.toLocaleString('en-PH'),
                            data: data,
                            isEmergency: pathObj.type === 'EMERGENCY'
                        });
                        renderTable();
                    }
                });
            });
        });
    }

    function renderTable() {
        const container = document.getElementById('universal-logs-body');
        const visibleLogs = Array.from(allLogsMap.values()).filter(log => {
            if (log.category === 'EMERGENCY' && log.data.acknowledged === true) return false;
            return true;
        });

        const sortedLogs = visibleLogs.sort((a, b) => {
            if (a.isEmergency !== b.isEmergency) return a.isEmergency ? -1 : 1; 
            return b.timestamp - a.timestamp;
        });
        
        container.innerHTML = "";
        if (sortedLogs.length === 0) {
            container.innerHTML = "<tr><td colspan='5' style='text-align:center; padding:20px; color:var(--text-dim);'>No active logs or alerts found.</td></tr>";
            return;
        }

        sortedLogs.forEach(log => {
            let message = "", target = "", badgeClass = "";
            switch(log.category) {
                case 'EMERGENCY':
                    message = `🚨 ${log.data.title}: ${log.data.message}`;
                    target = `GPS: ${log.data.latitude}, ${log.data.longitude}`;
                    badgeClass = "bg-emergency"; break;
                case 'REGISTRATION':
                    message = log.data.message || `New User: ${log.data.full_name}`;
                    target = `Finger ID: ${log.data.finger_id}`;
                    badgeClass = "bg-reg"; break;
                case 'EDIT':
                    message = `Update: ${log.data.old_full_name} ➔ ${log.data.new_full_name}`;
                    target = log.data.guardian_email;
                    badgeClass = "bg-edit"; break;
                case 'PASSWORD':
                    message = log.data.message;
                    target = log.data.user_email;
                    badgeClass = "bg-pass"; break;
                case 'ALERT':
                    message = `⚠️ ${log.data.title || 'Alert'}: ${log.data.message}`;
                    target = log.data.deviceId || "System";
                    badgeClass = "bg-alert"; break;
            }

            const row = document.createElement('tr');
            if(log.isEmergency) row.style.background = "rgba(231, 76, 60, 0.05)"; 
            
            row.innerHTML = `
                <td><small style="color:var(--accent-teal); font-weight:bold;">${log.displayTime}</small></td>
                <td><span class="type-badge ${badgeClass}">${log.category}</span></td>
                <td>${message}</td>
                <td><code style="color:var(--accent-blue)">${target}</code></td>
                <td>
                    <button class="view-btn" onclick="openDetails('${log.id}')" title="View"><i class="fas fa-eye"></i></button>
                    <button class="del-btn" onclick="deleteSpecificLog('${log.id}')" title="Delete"><i class="fas fa-trash-alt"></i></button>
                </td>
            `;
            container.appendChild(row);
        });
    }

    async function deleteSpecificLog(id) {
        const log = allLogsMap.get(id);
        if(!log) return;

        const result = await Swal.fire({
            title: 'PURGE RECORD?',
            text: "This specific log will be permanently removed from the database.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'DELETE',
            cancelButtonText: 'ABORT',
            buttonsStyling: false,
            customClass: {
                popup: 'cyber-swal-popup',
                title: 'cyber-swal-title',
                htmlContainer: 'cyber-swal-content',
                confirmButton: 'cyber-confirm-btn',
                cancelButton: 'cyber-cancel-btn'
            }
        });

        if (result.isConfirmed) {
            try {
                await db.collection(log.collectionPath).doc(id).delete();
                Swal.fire({
                    title: 'SUCCESS',
                    text: 'Record purged.',
                    icon: 'success',
                    timer: 1500,
                    showConfirmButton: false,
                    customClass: { popup: 'cyber-swal-popup', title: 'cyber-swal-title' }
                });
            } catch (error) { console.error(error); }
        }
    }

    function openDetails(id) {
        const log = allLogsMap.get(id);
        if(!log) return;
        const body = document.getElementById('modalBody');
        let detailsHtml = "";
        
        for (const [key, value] of Object.entries(log.data)) {
            let displayVal = value;
            if (value && typeof value.toDate === 'function') displayVal = value.toDate().toLocaleString('en-PH');
            if (typeof value !== 'object' || (value && value.toDate)) {
                detailsHtml += `<div class="detail-item">
                    <label>${key.replace(/_/g, ' ')}</label>
                    <p>${displayVal}</p>
                </div>`;
            }
        }
        body.innerHTML = detailsHtml;
        document.getElementById('universalModal').style.display = 'block';
        // Reset scroll position to top whenever opened
        body.scrollTop = 0;
    }

    function closeModal() { document.getElementById('universalModal').style.display = 'none'; }
    
    // Close modal when clicking outside
    window.onclick = function(event) {
        let modal = document.getElementById('universalModal');
        if (event.target == modal) closeModal();
    }

    window.onload = startUniversalListener;
</script>

<?php include 'includes/footer.php'; ?>