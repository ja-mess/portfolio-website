<?php
include 'auth_check.php'; // Eto ang guard mo

// users.php - Admin Panel User Management
$page = 'users';
include 'includes/header.php'; 
include 'includes/sidebar.php'; 
?>

<style>
    /* --- SYSTEM VARIABLES --- */
    :root {
        --bg-dark: #0b0d0f;
        --card-blue: #1a2238;
        --accent-teal: #1abc9c;
        --accent-blue: #3498db;
        --text-dim: #94a3b8;
        --wb-danger: #e74c3c;
        --primary-yellow: #f1c40f;
        --sidebar-width: 260px;
        --header-height: 75px;
    }

    /* --- GLOBAL RESET --- */
    body { 
        background-color: var(--bg-dark) !important; 
        color: white; 
        font-family: 'Poppins', sans-serif; 
        margin: 0;
    }

    /* --- LAYOUT WRAPPER --- */
    .admin-wrapper {
        margin-left: var(--sidebar-width);
        padding: 25px;
        margin-top: var(--header-height);
        transition: all 0.3s ease;
    }

    /* --- HEADER --- */
    .content-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        flex-wrap: wrap;
        gap: 20px;
    }

    .content-header h2 { font-size: 1.5rem; font-weight: 700; margin: 0; color: #fff; }

    /* --- DATA CONTAINERS --- */
    .main-data-container {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 25px;
    }

    .data-column {
        background: var(--card-blue);
        border-radius: 15px;
        padding: 20px;
        border: 1px solid rgba(255,255,255,0.05);
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        min-width: 0;
    }

    .section-header {
        margin-bottom: 20px;
        border-bottom: 1px solid rgba(255,255,255,0.05);
        padding-bottom: 10px;
    }

    .section-header h3 { font-size: .9rem; color: var(--accent-teal); display: flex; align-items: center; gap: 10px; }

    /* --- TABLES --- */
    .table-responsive {
        width: 100%;
        overflow-x: auto;
    }

    .user-table { 
        width: 100%; 
        border-collapse: collapse; 
        font-size: 0.85rem; 
        min-width: 450px;
    }
    
    .user-table th { text-align: left; padding: 12px; color: var(--text-dim); border-bottom: 1px solid rgba(255,255,255,0.1); }
    .user-table td { padding: 15px 12px; border-bottom: 1px solid rgba(255,255,255,0.03); vertical-align: middle; }

    .badge-guardian {
        background: rgba(52, 152, 219, 0.15);
        color: var(--accent-blue);
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 0.75rem;
    }

    .action-cells button {
        background: rgba(255,255,255,0.05);
        border: none;
        color: white;
        width: 35px;
        height: 35px;
        border-radius: 6px;
        cursor: pointer;
        transition: 0.3s;
    }

    .update-user-btn:hover, .update-guardian-btn:hover { background: var(--accent-teal); }
    .delete-btn:hover { background: var(--wb-danger); }

    /* --- MODAL SYSTEM --- */
    .modal {
        display: none;
        position: fixed;
        z-index: 2000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.85); 
        backdrop-filter: blur(10px);
        overflow-y: auto;
    }

    .modal-content {
        background-color: var(--card-blue);
        margin: 5% auto;
        padding: 30px;
        border-radius: 15px;
        width: 90%;
        max-width: 500px;
        border: 1px solid rgba(255,255,255,0.1);
        position: relative;
        box-shadow: 0 25px 50px rgba(0,0,0,0.5);
    }

    .close-button { 
        position: absolute; 
        right: 20px; 
        top: 15px; 
        font-size: 24px; 
        cursor: pointer; 
        color: var(--text-dim); 
    }

    .modal-content h2 { margin-top: 0; font-size: 1.3rem; margin-bottom: 20px; color: #fff; }
    .modal-content label { display: block; margin-bottom: 8px; color: var(--text-dim); font-size: 0.8rem; }
    
    .modal-content input, .modal-content select {
        width: 100%;
        padding: 12px;
        margin-bottom: 20px;
        background: rgba(0,0,0,0.3);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 8px;
        color: white;
        box-sizing: border-box;
    }

    .btn-submit {
        width: 100%;
        padding: 14px;
        background: var(--accent-blue);
        border: none;
        color: white;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: 0.3s;
    }

    @media (max-width: 1100px) {
        .admin-wrapper { margin-left: 0; padding: 15px; }
        .main-data-container { grid-template-columns: 1fr; }
    }
</style>

<div class="admin-wrapper">
    <main class="content-area">
        <div class="content-header">
            <div>
                <h2><i class="fas fa-users-cog" style="color: var(--accent-teal);"></i> User Management</h2>
                <p style="color: var(--text-dim); margin-top: 5px;">Manage visually impaired users and their guardians.</p>
            </div>
            </div>

        <div class="main-data-container">
            <div class="data-column">
                <div class="section-header">
                    <h3><i class="fas fa-blind"></i> Visually Impaired Users</h3>
                </div>
                <div class="table-responsive">
                    <table class="user-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Address</th>
                                <th>Guardian</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="usersTbody"></tbody>
                    </table>
                </div>
            </div>

            <div class="data-column">
                <div class="section-header">
                    <h3><i class="fas fa-user-friends"></i> Guardians (Parents)</h3>
                </div>
                <div class="table-responsive">
                    <table class="user-table">
                        <thead>
                            <tr>
                                <th>Guardian</th>
                                <th>Email</th>
                                <th>Contact</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="guardiansTbody"></tbody>
                    </table>
                </div>
            </div>
        </div> 
    </main>
</div>

<div id="editUserModal" class="modal">
    <div class="modal-content">
        <span class="close-button">&times;</span>
        <h2><i class="fas fa-edit"></i> Edit Child Record</h2>
        <form id="editUserForm">
            <input type="hidden" id="editUserId">
            <label>Full Name</label>
            <input type="text" id="editFullname" required>
            <label>Address</label>
            <input type="text" id="editAddress">
            <button type="submit" class="btn-submit">💾 Update Record</button>
        </form>
    </div>
</div>

<div id="editGuardianModal" class="modal">
    <div class="modal-content">
        <span class="close-button">&times;</span>
        <h2><i class="fas fa-user-edit"></i> Edit Guardian</h2>
        <form id="editGuardianForm">
            <input type="hidden" id="editGuardianId">
            <label>Name</label>
            <input type="text" id="editGuardianName" required>
            <label>Email</label>
            <input type="email" id="editGuardianEmail" required>
            <label>Phone</label>
            <input type="text" id="editGuardianPhone">
            <button type="submit" class="btn-submit">💾 Update Profile</button>
        </form>
    </div>
</div>

<script src="https://www.gstatic.com/firebasejs/9.6.10/firebase-app-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/9.6.10/firebase-auth-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/9.6.10/firebase-firestore-compat.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    const firebaseConfig = {
        apiKey: "AIzaSyC9zVfgdIYDBLKjCG4w1eqGkBYtChqFYEI",
        authDomain: "walkbuddy-system.firebaseapp.com",
        projectId: "walkbuddy-system",
        storageBucket: "walkbuddy-system.firebasestorage.app",
        messagingSenderId: "270646846701",
        appId: "1:270646846701:web:d70d456e9eb987a33ed8e4"
    };

    if (!firebase.apps.length) { firebase.initializeApp(firebaseConfig); }
    const db = firebase.firestore();

    let userRecords = [];

    async function fetchUsers() {
        try {
            const snapshot = await db.collection("users").get();
            userRecords = [];
            const usersTbody = document.getElementById('usersTbody');
            const guardiansTbody = document.getElementById('guardiansTbody');

            usersTbody.innerHTML = '';
            guardiansTbody.innerHTML = '';

            snapshot.forEach(doc => {
                const data = doc.data();
                const id = doc.id;
                userRecords.push({ id, data });

                usersTbody.innerHTML += `
                    <tr>
                        <td><strong>${data.user?.full_name || 'N/A'}</strong></td>
                        <td style="color: #94a3b8">${data.user?.address || 'N/A'}</td>
                        <td><span class="badge-guardian">${data.guardian?.username || 'N/A'}</span></td>
                        <td class="action-cells">
                            <button class="update-user-btn" onclick="openEditUser('${id}')"><i class="fas fa-edit"></i></button>
                            <button class="delete-btn" onclick="deleteRecord('${id}')"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>`;

                guardiansTbody.innerHTML += `
                    <tr>
                        <td><strong>${data.guardian?.username || 'N/A'}</strong></td>
                        <td style="color: #94a3b8">${data.guardian?.email || 'N/A'}</td>
                        <td>${data.guardian?.phone || 'N/A'}</td>
                        <td class="action-cells">
                            <button class="update-guardian-btn" onclick="openEditGuardian('${id}')"><i class="fas fa-edit"></i></button>
                        </td>
                    </tr>`;
            });
        } catch (e) { console.error(e); }
    }

    // --- REUSABLE CYBER SUCCESS ALERT ---
    function showCyberSuccess(msg) {
        Swal.fire({
            title: 'PROTOCOL COMPLETE',
            text: msg,
            icon: 'success',
            customClass: {
                popup: 'cyber-swal-popup',
                title: 'cyber-swal-title',
                htmlContainer: 'cyber-swal-content',
                confirmButton: 'cyber-confirm-btn'
            }
        });
    }

    function openEditUser(id) {
        const rec = userRecords.find(r => r.id === id);
        document.getElementById('editUserId').value = id;
        document.getElementById('editFullname').value = rec.data.user.full_name;
        document.getElementById('editAddress').value = rec.data.user.address;
        document.getElementById('editUserModal').style.display = 'block';
    }

    document.getElementById('editUserForm').onsubmit = async (e) => {
        e.preventDefault();
        const id = document.getElementById('editUserId').value;
        await db.collection('users').doc(id).update({
            "user.full_name": document.getElementById('editFullname').value,
            "user.address": document.getElementById('editAddress').value
        });
        
        closeModals();
        showCyberSuccess('User profile has been synchronized with the cloud.');
        fetchUsers();
    };

    function openEditGuardian(id) {
        const rec = userRecords.find(r => r.id === id);
        document.getElementById('editGuardianId').value = id;
        document.getElementById('editGuardianName').value = rec.data.guardian.username;
        document.getElementById('editGuardianEmail').value = rec.data.guardian.email;
        document.getElementById('editGuardianPhone').value = rec.data.guardian.phone;
        document.getElementById('editGuardianModal').style.display = 'block';
    }

    document.getElementById('editGuardianForm').onsubmit = async (e) => {
        e.preventDefault();
        const id = document.getElementById('editGuardianId').value;
        await db.collection('users').doc(id).update({
            "guardian.username": document.getElementById('editGuardianName').value,
            "guardian.email": document.getElementById('editGuardianEmail').value,
            "guardian.phone": document.getElementById('editGuardianPhone').value
        });

        closeModals();
        showCyberSuccess('Guardian credentials have been updated.');
        fetchUsers();
    };

    async function deleteRecord(id) {
        const res = await Swal.fire({
            title: 'SYSTEM AUTHORIZATION',
            text: "Are you sure you want to purge this record? This action is irreversible.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'CONFIRM PURGE',
            cancelButtonText: 'ABORT',
            customClass: {
                popup: 'cyber-swal-popup',
                title: 'cyber-swal-title',
                htmlContainer: 'cyber-swal-content',
                confirmButton: 'cyber-confirm-btn',
                cancelButton: 'cyber-cancel-btn'
            }
        });

        if (res.isConfirmed) {
            try {
                await db.collection('users').doc(id).delete();
                showCyberSuccess('User record has been wiped from the database.');
                fetchUsers();
            } catch (err) {
                console.error(err);
            }
        }
    }

    function closeModals() {
        document.querySelectorAll('.modal').forEach(m => m.style.display = 'none');
    }

    document.querySelectorAll('.close-button').forEach(b => b.onclick = closeModals);
    window.onload = fetchUsers;
</script>

<?php include 'includes/footer.php'; ?>