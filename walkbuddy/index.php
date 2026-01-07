<?php
session_start();

// DITO IPAPASOK ANG LOGIN LOGIC
$loginStatus = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Simpleng validation (Sa totoong system, dapat itong i-check sa Database)
    if ($username === 'admin' && $password === 'admin') {
        $_SESSION['admin_logged_in'] = true;
        $loginStatus = "success";
    } else {
        $loginStatus = "error";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - WalkBuddy High-Tech</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
        :root {
            --primary-yellow: #f1c40f; 
            --bg-dark: #0f1113; 
            --card-bg: rgba(30, 33, 36, 0.85); 
            --text-light: #ffffff;
            --text-gray: #aeb6bf;
            --glow: 0 0 25px rgba(241, 196, 15, 0.3);
        }

        body {
            font-family: 'Poppins', sans-serif;
            margin: 0; padding: 0;
            display: flex; justify-content: center; align-items: center;
            min-height: 100vh;
            background: var(--bg-dark);
            overflow: hidden;
            color: var(--text-light);
        }

        /* Animated Background Circles */
        .circles-area {
            background: radial-gradient(circle at center, #1a2a4e 0%, #0f1113 100%);
            width: 100%; height: 100vh;
            position: absolute; z-index: -1;
        }

        .circles li {
            position: absolute; display: block; list-style: none;
            width: 20px; height: 20px;
            background: rgba(241, 196, 15, 0.1);
            border: 1px solid rgba(241, 196, 15, 0.3);
            animation: animate 25s linear infinite;
            bottom: -150px;
        }

        .circles li:nth-child(1){ left: 25%; width: 80px; height: 80px; animation-delay: 0s; }
        .circles li:nth-child(2){ left: 10%; width: 20px; height: 20px; animation-delay: 2s; animation-duration: 12s; }
        .circles li:nth-child(3){ left: 70%; width: 20px; height: 20px; animation-delay: 4s; }
        .circles li:nth-child(4){ left: 40%; width: 60px; height: 60px; animation-delay: 0s; animation-duration: 18s; }
        .circles li:nth-child(5){ left: 65%; width: 20px; height: 20px; animation-delay: 0s; }

        @keyframes animate {
            0% { transform: translateY(0) rotate(0deg); opacity: 1; border-radius: 4px; }
            100% { transform: translateY(-1000px) rotate(720deg); opacity: 0; border-radius: 50%; }
        }

        .login-container {
            background-color: var(--card-bg);
            padding: 40px;
            border-radius: 25px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.6);
            width: 100%;
            max-width: 400px;
            text-align: center;
            border-top: 2px solid rgba(241, 196, 15, 0.5);
            border-left: 1px solid rgba(255,255,255,0.1);
            position: relative;
            z-index: 1;
            backdrop-filter: blur(15px);
            transition: all 0.3s ease;
        }

        .login-container:hover {
            box-shadow: var(--glow);
            transform: translateY(-8px);
        }

        .brand-icon {
            background: var(--primary-yellow);
            width: 70px; height: 70px;
            margin: -75px auto 20px;
            border-radius: 20px;
            display: flex; align-items: center; justify-content: center;
            font-size: 2.2rem; color: #000;
            box-shadow: 0 10px 20px rgba(241, 196, 15, 0.3);
            transform: rotate(-10deg);
            transition: 0.5s;
        }

        .login-title {
            color: var(--primary-yellow);
            margin: 10px 0 5px;
            font-weight: 700; font-size: 1.8rem;
            letter-spacing: 2px;
        }

        .sys-info {
            font-size: 0.7rem; color: var(--text-gray);
            letter-spacing: 3px; margin-bottom: 30px; display: block;
        }

        .input-group { margin-bottom: 25px; text-align: left; }
        
        .input-label {
            display: flex; align-items: center; gap: 8px;
            margin-bottom: 10px; color: var(--primary-yellow);
            font-size: 0.75rem; font-weight: 600; letter-spacing: 1px;
        }

        .input-wrapper { position: relative; }

        .input-wrapper input {
            width: 100%;
            padding: 15px 15px 15px 50px;
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 15px;
            box-sizing: border-box;
            background: rgba(0, 0, 0, 0.3);
            color: white; font-size: 1rem;
            transition: all 0.3s;
        }

        .input-wrapper input:focus {
            border-color: var(--primary-yellow);
            outline: none;
            background: rgba(241, 196, 15, 0.05);
        }

        .input-icon {
            position: absolute; left: 18px; top: 50%;
            transform: translateY(-50%); color: var(--text-gray);
        }

        .password-toggle {
            position: absolute; right: 15px; top: 50%;
            transform: translateY(-50%); cursor: pointer;
            color: var(--text-gray); transition: 0.3s;
        }

        .login-button {
            width: 100%; padding: 16px;
            background: var(--primary-yellow);
            color: #000; border: none;
            border-radius: 15px; font-size: 1rem;
            font-weight: 700; cursor: pointer;
            transition: all 0.3s; margin-top: 10px;
            text-transform: uppercase; letter-spacing: 2px;
            display: flex; align-items: center; justify-content: center; gap: 10px;
        }

        .login-button:hover {
            background: #fff;
            box-shadow: 0 0 30px var(--primary-yellow);
        }

        /* SWEETALERT CUSTOM STYLES */
        .cyber-swal-popup { background: #121417 !important; border: 1px solid var(--primary-yellow) !important; border-radius: 20px !important; }
        .cyber-swal-title { color: var(--primary-yellow) !important; font-size: 1.2rem !important; text-transform: uppercase; }
        .cyber-confirm-btn { background: var(--primary-yellow) !important; color: #000 !important; font-weight: 700 !important; border-radius: 10px !important; padding: 10px 30px !important; border: none !important; cursor: pointer; }
    </style>
</head>
<body>

    <div class="circles-area">
        <ul class="circles">
            <li></li><li></li><li></li><li></li><li></li>
            <li></li><li></li><li></li><li></li><li></li>
        </ul>
    </div>

    <div class="login-container">
        <div class="brand-icon">
            <i class="fas fa-walking"></i> 
        </div>

        <h2 class="login-title">WalkBuddy</h2>
        <span class="sys-info"><i class="fas fa-shield-halved"></i> COMMAND CENTER ACCESS</span>
        
        <form method="POST" action=""> 
            <div class="input-group">
                <label class="input-label"><i class="fas fa-id-badge"></i> ADMIN IDENTIFICATION</label>
                <div class="input-wrapper">
                    <input type="text" name="username" placeholder="Username" required autocomplete="off">
                    <i class="fas fa-user-shield input-icon"></i>
                </div>
            </div>

            <div class="input-group">
                <label class="input-label"><i class="fas fa-key"></i> ACCESS KEY</label>
                <div class="input-wrapper"> 
                    <input type="password" name="password" id="password" placeholder="••••••••" required>
                    <i class="fas fa-fingerprint input-icon"></i>
                    <span class="password-toggle" id="togglePassword">
                        <i class="fas fa-eye"></i>
                    </span> 
                </div>
            </div>
            
            <button type="submit" class="login-button">
                Initialize Login <i class="fas fa-arrow-right"></i>
            </button>
        </form>

<div class="footer-note" id="quote-container" style="margin-top:20px;">
    <i class="fas fa-terminal"></i> <span id="dynamic-quote">INITIALIZING SYSTEM...</span>
</div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>

      const quotes = [
    "EMPOWERING EVERY STEP BEYOND THE DARKNESS.",
    "GUIDING VISION THROUGH INNOVATION.",
    "TECHNOLOGY THAT SEES WHAT EYES CANNOT.",
    "WALK WITH CONFIDENCE, GUIDED BY INTELLIGENCE.",
    "BREAKING BARRIERS, ONE STEP AT A TIME.",
    "YOUR SAFETY IS OUR VISION.",
    "WALKBUDDY: REDEFINING INDEPENDENCE FOR ALL."
];

function updateQuote() {
    const quoteElement = document.getElementById('dynamic-quote');
    const randomQuote = quotes[Math.floor(Math.random() * quotes.length)];
    
    // Simpleng fade effect
    quoteElement.style.opacity = 0;
    
    setTimeout(() => {
        quoteElement.innerText = randomQuote;
        quoteElement.style.opacity = 1;
    }, 500);
}

// Magpapalit ng quote bawat 10 segundo
setInterval(updateQuote, 9000);
// Tawagin agad para may laman na sa start
updateQuote();
        // Password Toggle Logic
        const togglePassword = document.querySelector('#togglePassword');
        const passwordField = document.querySelector('#password');

        togglePassword.addEventListener('click', function () {
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);
            this.querySelector('i').classList.toggle('fa-eye');
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });

        // PHP TO JS SWEETALERT HANDLER
        <?php if ($loginStatus === "success"): ?>
            Swal.fire({
                title: 'ACCESS GRANTED',
                text: 'Authentication successful. Synchronizing dashboard...',
                icon: 'success',
                iconColor: '#f1c40f',
                buttonsStyling: false,
                allowOutsideClick: false,
                customClass: {
                    popup: 'cyber-swal-popup',
                    title: 'cyber-swal-title',
                    confirmButton: 'cyber-confirm-btn'
                },
                confirmButtonText: 'ENTER'
            }).then(() => { 
                window.location.href = 'dashboard.php'; 
            });
        <?php elseif ($loginStatus === "error"): ?>
            Swal.fire({
                title: 'AUTH FAILED',
                text: 'Invalid access key. Please verify credentials.',
                icon: 'error',
                iconColor: '#e74c3c',
                buttonsStyling: false,
                customClass: {
                    popup: 'cyber-swal-popup',
                    title: 'cyber-swal-title',
                    confirmButton: 'cyber-confirm-btn'
                },
                confirmButtonText: 'RETRY'
            });
        <?php endif; ?>
    </script>
</body>
</html>