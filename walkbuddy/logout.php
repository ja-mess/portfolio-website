<?php
session_start();

// Burahin ang lahat ng session variables
$_SESSION = array();

// Sirain ang session cookie kung mayroon man
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time()-42000, '/');
}

// Tuluyan nang sirain ang session
session_destroy();

// I-redirect pabalik sa login page
header("Location: index.php");
exit;
?>