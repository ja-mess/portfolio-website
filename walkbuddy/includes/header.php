<?php
// PHP Security Check (Para magamit sa lahat ng pages)
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500;700&family=Quicksand:wght@300;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="assets/cyber-swal.css">
</head>

<style>
    :root {
        --wb-hd-bg: rgba(11, 13, 15, 0.95); 
        --wb-hd-yellow: #f1c40f;
        --wb-hd-border: rgba(241, 196, 15, 0.15);
        --wb-hd-text: #aeb6bf;
    }

    #WB_CORE_HEADER_2024 {
        position: fixed;
        top: 0;
        right: 0;
        width: calc(100% - 260px); 
        height: 75px;
        background: var(--wb-hd-bg);
        backdrop-filter: blur(15px);
        border-bottom: 1px solid var(--wb-hd-border);
        z-index: 999;
        display: flex;
        align-items: center;
        transition: all 0.3s ease;
        font-family: 'Quicksand', sans-serif;
    }

    .wb-hd-inner-wrapper {
        display: flex;
        justify-content: flex-start; /* Pinagtabi ang toggle at status box */
        align-items: center;
        width: 100%;
        padding: 0 25px;
    }

    /* --- MOBILE TOGGLE NA NASA LOOB NG HEADER --- */
    .wb-mobile-toggle-header {
        display: none; /* Tago sa desktop */
        background: var(--wb-hd-yellow);
        border: none;
        padding: 10px 12px;
        border-radius: 8px;
        cursor: pointer;
        color: #0b0d0f;
        font-size: 1.1rem;
        margin-right: 15px; /* Space sa pagitan ng button at orasan */
        box-shadow: 0 0 15px rgba(241, 196, 15, 0.3);
    }

    .wb-hd-status-box {
        display: flex;
        flex-direction: column;
        min-width: fit-content;
    }

    .wb-hd-welcome {
        color: white;
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        opacity: 0.6;
    }

    .wb-hd-system-time {
        color: var(--wb-hd-yellow);
        font-family: 'Orbitron', sans-serif;
        font-size: 0.85rem;
        font-weight: 700;
    }

    .wb-hd-actions {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-left: auto; /* Itutulak ang profile sa dulo ng kanan */
    }

    .wb-hd-search-container {
        position: relative;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 8px;
        padding: 6px 12px;
        display: flex;
        align-items: center;
        width: 200px; 
        transition: 0.3s;
    }

    .wb-hd-search-input {
        background: transparent;
        border: none;
        color: white;
        outline: none;
        width: 100%;
        font-size: 0.8rem;
    }

    .wb-hd-profile-trigger {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .wb-hd-user-details {
        text-align: right;
    }

    .wb-hd-name {
        display: block;
        color: white;
        font-size: 0.85rem;
        font-weight: 700;
    }

    .wb-hd-role {
        font-size: 0.6rem;
        color: var(--wb-hd-yellow);
        text-transform: uppercase;
    }

    .wb-hd-avatar-glow {
        width: 38px;
        height: 38px;
        background: #000;
        border: 1px solid var(--wb-hd-yellow);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--wb-hd-yellow);
    }

    @media (max-width: 992px) {
        #WB_CORE_HEADER_2024 {
            width: 100%; 
        }
        .wb-mobile-toggle-header {
            display: flex; /* Labas ang button sa mobile */
            align-items: center;
            justify-content: center;
        }
        .wb-hd-search-container {
            width: 150px;
        }
    }

    @media (max-width: 768px) {
        .wb-hd-search-container {
            display: none; 
        }
        .wb-hd-welcome {
            display: none; 
        }
    }
</style>

<header id="WB_CORE_HEADER_2024">
    <div class="wb-hd-inner-wrapper">
        <button class="wb-mobile-toggle-header" id="wbHeaderToggleBtn">
            <i class="fas fa-bars"></i>
        </button>

        <div class="wb-hd-status-box">
            <span class="wb-hd-welcome">System Operator</span>
            <span class="wb-hd-system-time" id="wb-live-clock">00:00:00 AM</span>
        </div>

        <div class="wb-hd-actions">
           

            <div class="wb-hd-profile-trigger"> 
                <div class="wb-hd-user-details">
                    <span class="wb-hd-name">Walk Buddy Admin</span>
                    <span class="wb-hd-role">Super Admin</span>
                </div>
                <div class="wb-hd-avatar-glow">
                    <i class="fas fa-user-shield"></i>
                </div>
            </div>
        </div>
    </div>
</header>

<script>
    function updateWBCoreTime() {
        const now = new Date();
        const timeStr = now.toLocaleTimeString('en-US', { 
            hour: '2-digit', 
            minute: '2-digit', 
            second: '2-digit',
            hour12: true 
        });
        document.getElementById('wb-live-clock').textContent = timeStr;
    }
    setInterval(updateWBCoreTime, 1000);
    updateWBCoreTime();
</script>