<?php

include 'auth_check.php'; // Eto ang guard mo

// messages.php - Admin Panel Messages Page
$page = 'messages';
include 'includes/header.php'; 
include 'includes/sidebar.php'; 
?>

<link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500;700&family=Quicksand:wght@300;500;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    :root {
        --bg-dark: #0b0d0f;
        --card-blue: #1a2238;
        --accent-teal: #1abc9c;
        --text-dim: #94a3b8;
        --header-height: 75px;
        --sidebar-width: 260px;
    }

    /* --- RESET & LAYOUT --- */
    body {
        background-color: var(--bg-dark) !important;
        margin: 0;
        height: 100vh;
        overflow: hidden; /* Prevent body scroll */
        font-family: 'Quicksand', sans-serif;
    }

    .wb-messages-wrapper {
        margin-top: var(--header-height);
        margin-left: var(--sidebar-width);
        height: calc(100vh - var(--header-height));
        display: flex;
        flex-direction: column;
        padding: 20px;
        box-sizing: border-box;
        transition: all 0.3s ease;
    }

    .wb-page-title { flex-shrink: 0; margin-bottom: 15px; }
    .wb-page-title h2 { color: #fff; margin: 0; font-size: 1.4rem; font-weight: 700; }
    .wb-page-title p { color: var(--text-dim); margin: 5px 0 0 0; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; }

    /* --- MAIN CONTENT GRID --- */
    .wb-msg-grid {
        display: flex;
        gap: 20px;
        flex: 1;
        min-height: 0; /* Critical for inner scrolling */
    }

    /* --- LEFT SIDEBAR (Threads) --- */
    .wb-sidebar-col {
        width: 350px;
        display: flex;
        flex-direction: column;
        gap: 15px;
        flex-shrink: 0;
    }

    .wb-glass-card {
        background: var(--card-blue);
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 12px;
        display: flex;
        flex-direction: column;
        min-height: 0;
        flex: 1;
    }

    .wb-card-header {
        padding: 12px 15px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: rgba(0,0,0,0.2);
    }

    .wb-card-header h5 { margin: 0; font-size: 0.75rem; color: var(--accent-teal); font-family: 'Orbitron'; }

    .message-list-container {
        overflow-y: auto;
        flex: 1;
    }

    .msg-item {
        padding: 15px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.02);
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        transition: 0.2s;
    }

    .msg-item:hover { background: rgba(255, 255, 255, 0.05); }

    /* --- RIGHT CHAT AREA --- */
    #chatContainer {
        flex: 1;
        background: var(--card-blue);
        border-radius: 15px;
        border: 1px solid rgba(255, 255, 255, 0.05);
        display: flex;
        flex-direction: column;
        min-width: 0;
        position: relative;
    }

    .chat-header {
        padding: 15px 20px;
        background: rgba(0,0,0,0.3);
        border-bottom: 1px solid rgba(255,255,255,0.05);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .chat-body {
        flex: 1;
        overflow-y: auto;
        padding: 20px;
        display: flex;
        flex-direction: column;
        gap: 10px;
        background: rgba(0,0,0,0.1);
    }

    .chat-footer {
        padding: 15px;
        background: rgba(0,0,0,0.3);
        display: flex;
        gap: 10px;
    }

    .chat-footer input {
        flex: 1;
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.1);
        padding: 12px;
        border-radius: 8px;
        color: white;
        outline: none;
    }

    .chat-footer button {
        background: var(--accent-teal);
        border: none;
        padding: 0 20px;
        border-radius: 8px;
        font-weight: bold;
        cursor: pointer;
    }

    /* --- BUBBLES --- */
    .bubble { max-width: 80%; padding: 10px 15px; border-radius: 15px; font-size: 0.85rem; line-height: 1.4; }
    .bubble.user { align-self: flex-start; background: #2c3e50; color: white; border-bottom-left-radius: 2px; }
    .bubble.admin { align-self: flex-end; background: var(--accent-teal); color: #0b0d0f; border-bottom-right-radius: 2px; }

    .chat-placeholder {
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        opacity: 0.2;
    }

    /* --- RESPONSIVE MOBILE --- */
    .mobile-back-btn { display: none; }

    @media (max-width: 992px) {
        .wb-messages-wrapper { margin-left: 0; padding: 10px; }
        
        .wb-sidebar-col { width: 100%; }

        #chatContainer {
            display: none;
            position: fixed;
            top: var(--header-height);
            left: 0;
            width: 100%;
            height: calc(100vh - var(--header-height));
            z-index: 1000;
        }

        #chatContainer.mobile-active { display: flex; }
        .mobile-back-btn { display: block; background: none; border: none; color: white; font-size: 1.2rem; margin-right: 15px; }
    }

    /* Scrollbar Style */
    ::-webkit-scrollbar { width: 4px; }
    ::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
</style>

<div class="wb-messages-wrapper">
    <div class="wb-page-title">
        <h2><i class="fas fa-satellite" style="color: var(--accent-teal);"></i> Communication Center</h2>
        <p><i class="fas fa-satellite-dish"></i> Secure Admin Terminal Active</p>
    </div>

    <div class="wb-msg-grid">
        <div class="wb-sidebar-col">
            <div class="wb-glass-card">
                <div class="wb-card-header">
                    <h5>THREADS</h5>
                    <div style="display:flex; gap:5px;">
                        <button id="btn-newest" class="sort-btn active" onclick="handleSort('date_desc')" style="font-size: 10px; background: none; border: 1px solid var(--accent-teal); color: white; cursor: pointer; border-radius: 4px;">NEW</button>
                    </div>
                </div>
                <div class="existing-messages-list message-list-container"></div>
            </div>

            <div class="wb-glass-card" style="flex: 0.6;">
                <div class="wb-card-header"><h5>GUARDIANS</h5></div>
                <div class="guardian-list message-list-container"></div>
            </div>
        </div>

        <div id="chatContainer">
            <div class="chat-placeholder">
                <i class="fas fa-terminal fa-4x"></i>
                <p style="margin-top:15px; font-family: 'Orbitron'; letter-spacing: 2px;">SELECT NODE TO START LINK</p>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

<script src="https://www.gstatic.com/firebasejs/9.6.10/firebase-app-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/9.6.10/firebase-firestore-compat.js"></script>

<script>
    // ✅ FIREBASE CONFIG
    const firebaseConfig = {
        apiKey: "AIzaSyC9zVfgdIYDBLKjCG4w1eqGkBYtChqFYEI",
        authDomain: "walkbuddy-system.firebaseapp.com",
        projectId: "walkbuddy-system",
        storageBucket: "walkbuddy-system.firebasestorage.app",
        messagingSenderId: "270646846701",
        appId: "1:270646846701:web:d70d456e9eb987a33ed8e4"
    };

    firebase.initializeApp(firebaseConfig);
    const db = firebase.firestore();
    const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3000 });

    let currentSenderId = null;
    let messageSortDirection = 'date_desc';

    // Helper: Dates
    function getDate(ts) { return (ts && ts.toDate) ? ts.toDate() : new Date(ts || 0); }
    function formatTime(ts) {
        let date = getDate(ts);
        return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    }

    // 1. Load Messages
    function loadMessages() {
        const container = document.querySelector(".existing-messages-list");
        db.collection("messages").onSnapshot(snapshot => {
            let grouped = {};
            snapshot.forEach(doc => {
                const msg = doc.data();
                if (!grouped[msg.userId] || getDate(msg.sentAt) > getDate(grouped[msg.userId].latestMessage.sentAt)) {
                    grouped[msg.userId] = {
                        senderId: msg.userId,
                        senderName: msg.senderUsername || "Unknown Node",
                        latestMessage: msg
                    };
                }
            });
            renderList(Object.values(grouped), container, 'messages');
        });
    }

    // 2. Load Guardians
    function loadGuardians() {
        const container = document.querySelector(".guardian-list");
        db.collection("users").get().then(snapshot => {
            let list = [];
            snapshot.forEach(doc => {
                const data = doc.data();
                if (data.guardian?.username) {
                    list.push({
                        senderId: doc.id,
                        senderName: data.guardian.username,
                        latestMessage: { message: "Available", sentAt: new Date(0) }
                    });
                }
            });
            renderList(list, container, 'guardians');
        });
    }

    function renderList(data, container, type) {
        if(!container) return;
        container.innerHTML = "";
        data.forEach(chat => {
            const div = document.createElement("div");
            div.className = "msg-item";
            div.innerHTML = `
                <div style="flex:1" onclick='openChat(${JSON.stringify(chat)})'>
                    <div style="font-weight:700; font-size:0.9rem; color:#fff;">${chat.senderName}</div>
                    <div style="font-size:0.75rem; color:var(--text-dim); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; width:200px;">${chat.latestMessage.message || ''}</div>
                </div>
                <div style="text-align:right">
                    <div style="font-size:9px; color:var(--accent-teal)">${formatTime(chat.latestMessage.sentAt)}</div>
                    ${type === 'messages' ? `<i class="fas fa-trash-alt" style="color:#e74c3c; font-size:10px; opacity:0.5;" onclick="deleteThread(event, '${chat.senderId}')"></i>` : ''}
                </div>
            `;
            container.appendChild(div);
        });
    }

    // 3. Chat Logic
    function openChat(chat) {
        currentSenderId = chat.senderId;
        const container = document.getElementById("chatContainer");
        container.classList.add("mobile-active");
        
        container.innerHTML = `
            <div class="chat-header">
                <div style="display:flex; align-items:center;">
                    <button class="mobile-back-btn" onclick="closeChat()">
                        <i class="fas fa-arrow-left"></i>
                    </button>
                    <div>
                        <div style="color:white; font-weight:700; font-size:0.9rem;">${chat.senderName}</div>
                        <div style="font-size:10px; color:var(--accent-teal)">SECURE ENCRYPTED LINK</div>
                    </div>
                </div>
                <i class="fas fa-sync-alt" onclick="location.reload()" style="color:var(--text-dim); cursor:pointer;"></i>
            </div>
            <div class="chat-body" id="chatBody"></div>
            <div class="chat-footer">
                <input type="text" id="replyInp" placeholder="Enter transmission..." onkeydown="if(event.key === 'Enter') sendReply()">
                <button onclick="sendReply()">SEND</button>
            </div>
        `;
        syncChat(chat.senderId);
    }

    function syncChat(userId) {
        const body = document.getElementById("chatBody");
        db.collection("messages").where("userId", "==", userId).onSnapshot(mSnap => {
            db.collection("replies").where("replyTo", "==", userId).onSnapshot(rSnap => {
                let msgs = [];
                mSnap.forEach(d => msgs.push({ type: "user", text: d.data().message, ts: d.data().sentAt }));
                rSnap.forEach(d => msgs.push({ type: "admin", text: d.data().reply, ts: d.data().sentAt }));
                msgs.sort((a, b) => getDate(a.ts) - getDate(b.ts));
                
                body.innerHTML = "";
                msgs.forEach(m => {
                    const d = document.createElement("div");
                    d.className = `bubble ${m.type}`;
                    d.innerHTML = `<div>${m.text}</div><div style="font-size:8px; opacity:0.5; margin-top:4px; text-align:right;">${formatTime(m.ts)}</div>`;
                    body.appendChild(d);
                });
                body.scrollTop = body.scrollHeight;
            });
        });
    }

    function sendReply() {
        const inp = document.getElementById("replyInp");
        if (!inp.value.trim() || !currentSenderId) return;

        db.collection("replies").add({
            replyTo: currentSenderId,
            reply: inp.value.trim(),
            sentAt: new Date().toISOString(),
            isAdmin: true
        }).then(() => {
            inp.value = "";
            body.scrollTop = body.scrollHeight;
        });
    }

    function closeChat() { document.getElementById("chatContainer").classList.remove("mobile-active"); }

async function deleteThread(event, userId) {
    event.stopPropagation();
    
    const result = await Swal.fire({
        title: 'TERMINATE THREAD?',
        text: "This will wipe all communication logs for this node.",
        icon: 'warning',
        showCancelButton: true,
        
        // DITO IA-APPLY ANG MGA CLASSES MULA SA CSS MO
        customClass: {
            popup: 'cyber-swal-popup',
            title: 'cyber-swal-title',
            htmlContainer: 'cyber-swal-content',
            confirmButton: 'cyber-confirm-btn',
            cancelButton: 'cyber-cancel-btn'
        },
        
        // Siguraduhin na false ito para hindi ma-override ng default styles ang custom buttons mo
        buttonsStyling: false, 
        
        confirmButtonText: 'CONFIRM WIPE',
        cancelButtonText: 'ABORT'
    });

    if (result.isConfirmed) {
        // Iyong delete logic...
        Toast.fire({ 
            icon: 'success', 
            title: 'LOGS WIPED',
            customClass: {
                popup: 'cyber-swal-popup',
                title: 'cyber-swal-title'
            }
        });
    }
}

    window.onload = () => { loadMessages(); loadGuardians(); };
</script>

