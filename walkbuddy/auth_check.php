<?php
session_start();

// I-check kung ang session variable ay HINDI naka-set
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    // Kung hindi pa naka-login, i-redirect sa login page
    header("Location: index.php");
    exit;
}
?>